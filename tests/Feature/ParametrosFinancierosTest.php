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

test('los simuladores públicos cargan y muestran la tasa vigente del producto', function () {
    $producto = productoDePrueba();
    ParametroFinanciero::create(datosTasa($producto) + ['nombre' => 'Tasa de interés', 'codigo' => 'tasa_interes', 'activo' => true]);

    $this->get('/simuladores/credito')->assertOk()->assertSee('Crédito Avances')->assertSee('data-tasa="1.350000"', false);
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
