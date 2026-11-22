<?php

use App\Models\ParametroFinanciero;
use App\Models\ProductoFinanciero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function productoDePrueba(array $extra = []): ProductoFinanciero
{
    return ProductoFinanciero::create($extra + [
        'nombre' => 'Crédito Avances', 'codigo' => 'credito_avances', 'tipo' => 'credito', 'activo' => true,
    ]);
}

function datosTasa(ProductoFinanciero $producto, array $extra = []): array
{
    return $extra + [
        'producto_financiero_id' => $producto->id, 'valor' => '1.35', 'unidad' => '% mensual',
        'fecha_inicio_vigencia' => '2026-01-01', 'fecha_fin_vigencia' => null, 'descripcion' => 'Tasa de prueba',
    ];
}

test('el formulario de crédito entrega la tasa vigente y el de ahorro abre', function () {
    $this->freezeTime();
    $hoy = today();
    $producto = productoDePrueba();
    $atributosTasa = ['nombre' => 'Tasa de interés', 'codigo' => 'tasa_interes', 'activo' => true];
    ParametroFinanciero::create(datosTasa($producto, [
        'fecha_inicio_vigencia' => $hoy->toDateString(),
        'fecha_fin_vigencia' => $hoy->toDateString(),
    ]) + $atributosTasa);
    ParametroFinanciero::create(datosTasa($producto, [
        'valor' => '2.50',
        'fecha_inicio_vigencia' => $hoy->copy()->addDay()->toDateString(),
    ]) + $atributosTasa);

    $respuesta = $this->get('/simuladores/credito')->assertOk()->assertSee('Crédito Avances');
    $html = new DOMDocument;
    $html->loadHTML($respuesta->getContent(), LIBXML_NOERROR | LIBXML_NOWARNING);
    $opcion = (new DOMXPath($html))->query('//select[@id="producto_credito"]//option[@value="credito_avances"]')->item(0);
    expect($opcion)->toBeInstanceOf(DOMElement::class);

    $parametros = json_decode($opcion->getAttribute('data-parametros'), true, flags: JSON_THROW_ON_ERROR);
    expect($parametros['tasa_interes']['valor'] ?? null)->toBe(1.35)
        ->and($parametros['tasa_interes']['unidad'] ?? null)->toBe('% mensual');

    $this->get('/simuladores/ahorro')->assertOk();
});

test('el panel de parámetros financieros es solo para el administrador principal', function () {
    $this->get('/admin/parametros-financieros')->assertRedirect();

    $normal = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $this->actingAs($normal)->get('/admin/parametros-financieros')->assertForbidden();

    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $this->actingAs($super)->get('/admin/parametros-financieros')->assertOk();
});

test('el administrador registra una tasa, no se permiten vigencias superpuestas y se puede desactivar', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $producto = productoDePrueba();

    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($producto))->assertSessionHasNoErrors();
    expect(ParametroFinanciero::count())->toBe(1);

    // Misma vigencia abierta: se superpone con la anterior.
    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($producto, ['fecha_inicio_vigencia' => '2026-06-01']))
        ->assertSessionHasErrors('fecha_inicio_vigencia');
    expect(ParametroFinanciero::count())->toBe(1);

    $tasa = ParametroFinanciero::first();
    $this->actingAs($super)->patch("/admin/parametros-financieros/tasas/{$tasa->id}/desactivar")->assertRedirect();
    expect($tasa->fresh()->activo)->toBeFalse();

    // Con la anterior desactivada ya se puede registrar otra.
    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($producto, ['fecha_inicio_vigencia' => '2026-06-01']))
        ->assertSessionHasNoErrors();
    expect(ParametroFinanciero::count())->toBe(2);
});

test('la tasa se valida: no negativa y fecha final no anterior a la inicial', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $producto = productoDePrueba();

    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($producto, ['valor' => '-1']))->assertSessionHasErrors('valor');
    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($producto, ['fecha_fin_vigencia' => '2025-12-31']))->assertSessionHasErrors('fecha_fin_vigencia');
});

test('los simuladores rechazan plazos y montos fuera de los topes técnicos', function () {
    $this->post('/simuladores/ahorro', ['producto' => 'ahorro_ordinario', 'monto' => '1000', 'aporte_mensual' => '1000', 'plazo_meses' => 2000000000])
        ->assertSessionHasErrors('plazo_meses');
    $this->post('/simuladores/ahorro', ['producto' => 'ahorro_cdat', 'monto' => '999999999999999', 'plazo_dias' => 90])
        ->assertSessionHasErrors('monto');

    productoDePrueba(['simulable' => true]);
    $this->post('/simuladores/credito', ['producto' => 'credito_avances', 'monto' => '1000000', 'plazo' => 9000000000, 'dato_especifico' => '2000000'])
        ->assertSessionHasErrors('plazo');
});

test('la ruta de tasas no registra tasa mensual en productos que no la manejan', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $cdat = productoDePrueba(['nombre' => 'CDAT', 'codigo' => 'ahorro_cdat', 'tipo' => 'ahorro']);

    $this->actingAs($super)->post('/admin/parametros-financieros/tasas', datosTasa($cdat))
        ->assertSessionHasErrors('producto_financiero_id');
    expect(ParametroFinanciero::count())->toBe(0);
});

test('el formulario conserva el origen elegido tras un error de validación', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($super)->withSession(['_old_input' => ['origen' => 'pendiente']])
        ->get('/admin/parametros-financieros')
        ->assertSee('value="pendiente" selected', false)
        ->assertDontSee(')}>', false);
});

test('un parámetro protegido no se desactiva, elimina ni edita hasta habilitar su edición', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $producto = productoDePrueba();
    $tasa = ParametroFinanciero::create(datosTasa($producto) + [
        'nombre' => 'Tasa de interés', 'codigo' => 'tasa_interes', 'activo' => true,
        'tipo_dato' => 'decimal', 'categoria' => 'tasa', 'origen' => 'confirmado', 'editable_admin' => false,
    ]);

    $this->actingAs($super)->patch("/admin/parametros-financieros/tasas/{$tasa->id}/desactivar")->assertForbidden();
    $this->actingAs($super)->delete("/admin/parametros-financieros/parametros/{$tasa->id}")->assertForbidden();
    expect($tasa->fresh()->activo)->toBeTrue();

    $edicion = [
        'producto_financiero_id' => $producto->id, 'codigo' => 'tasa_interes', 'valor' => '1.50', 'unidad' => '% mensual',
        'origen' => 'confirmado', 'fecha_inicio_vigencia' => '2026-01-01',
    ];
    $this->actingAs($super)->put("/admin/parametros-financieros/tasas/{$tasa->id}", $edicion + ['editable_admin' => '1'])->assertForbidden();
    expect((float) $tasa->fresh()->valor)->toBe(1.35);

    $this->actingAs($super)->patch("/admin/parametros-financieros/tasas/{$tasa->id}/habilitar-edicion")->assertRedirect();
    $this->actingAs($super)->put("/admin/parametros-financieros/tasas/{$tasa->id}", $edicion + ['editable_admin' => '1'])->assertSessionHasNoErrors();
    expect((float) $tasa->fresh()->valor)->toBe(1.5)->and($tasa->fresh()->editable_admin)->toBeTrue();
});

test('el simulador de crédito mantiene los topes por saldo y por monto configurado', function () {
    $producto = productoDePrueba(['nombre' => 'Compra de cartera', 'codigo' => 'credito_compra_cartera', 'simulable' => true]);
    $base = ['nombre' => 'x', 'activo' => true, 'fecha_inicio_vigencia' => today()->subDay()->toDateString(), 'producto_financiero_id' => $producto->id];
    ParametroFinanciero::create($base + ['codigo' => 'tasa_interes', 'valor' => 1, 'unidad' => '% mensual']);
    ParametroFinanciero::create($base + ['codigo' => 'monto_minimo', 'valor' => 500000, 'unidad' => 'COP']);

    $this->post('/simuladores/credito', ['producto' => 'credito_compra_cartera', 'monto' => '2000000', 'plazo' => 12, 'dato_especifico' => '1500000'])
        ->assertSessionHasErrors(['monto' => 'El monto solicitado no puede superar el saldo de la obligación informado. El valor máximo permitido para esta simulación es $1.500.000.']);

    $this->post('/simuladores/credito', ['producto' => 'credito_compra_cartera', 'monto' => '100000', 'plazo' => 12, 'dato_especifico' => '1500000'])
        ->assertSessionHasErrors(['monto' => 'El monto solicitado es inferior al mínimo configurado de $500.000.']);

    $this->post('/simuladores/credito', ['producto' => 'credito_compra_cartera', 'monto' => '1000000', 'plazo' => 12, 'dato_especifico' => '1500000'])
        ->assertSessionHasNoErrors();
});

test('la capitalizacion diaria de ahorro ordinario se muestra como regla fija y no se puede editar', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $producto = ProductoFinanciero::create([
        'nombre' => 'Ahorro Ordinario',
        'codigo' => 'ahorro_ordinario',
        'tipo' => 'ahorro',
        'activo' => true,
    ]);
    $capitalizacion = ParametroFinanciero::create([
        'producto_financiero_id' => $producto->id,
        'nombre' => 'Capitalización',
        'codigo' => 'capitalizacion',
        'valor_texto' => 'diaria',
        'tipo_dato' => 'texto',
        'categoria' => 'capitalizacion',
        'origen' => 'referencia',
        'editable_admin' => true,
        'fecha_inicio_vigencia' => '2026-01-01',
        'activo' => true,
    ]);

    $this->actingAs($super)
        ->get("/admin/parametros-financieros/{$producto->id}/historial")
        ->assertOk()
        ->assertSee('Capitalización diaria')
        ->assertSee('Valor guardado anteriormente')
        ->assertSee('Solo lectura')
        ->assertDontSee('Habilitar edición');

    $this->actingAs($super)
        ->get("/admin/parametros-financieros/tasas/{$capitalizacion->id}/editar")
        ->assertForbidden();

    $this->actingAs($super)
        ->put("/admin/parametros-financieros/tasas/{$capitalizacion->id}", [
            'producto_financiero_id' => $producto->id,
            'codigo' => 'capitalizacion',
            'valor_texto' => 'mensual',
            'origen' => 'referencia',
            'fecha_inicio_vigencia' => '2026-01-01',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('codigo');

    $this->actingAs($super)
        ->patch("/admin/parametros-financieros/tasas/{$capitalizacion->id}/habilitar-edicion")
        ->assertForbidden();

    expect($capitalizacion->fresh()->valor_texto)->toBe('diaria')
        ->and(config('parametros_por_producto.ahorro_ordinario'))->not->toContain('capitalizacion');
});
