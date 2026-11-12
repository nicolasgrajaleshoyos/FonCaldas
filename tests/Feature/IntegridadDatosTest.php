<?php

use App\Models\ParametroFinanciero;
use App\Models\Pqrs;
use App\Models\ProductoFinanciero;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('si otra radicación toma el mismo código justo antes de guardar, se genera otro y se guarda sin error', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);

    // Simula la carrera: "otra radicación" ya guardó FC-26-DUP001, y el generador (que ya revisó que estaba libre)
    // entrega justo ese código. El INSERT se rechaza y el sistema debe pedir otro código y reintentar.
    $robado = 'FC-26-DUP001';
    DB::table('solicitudes')->insert([
        'codigo' => $robado, 'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Otro', 'asociado_documento' => '9',
        'asociado_email' => 'otro@test.co', 'descripcion' => 'x', 'estado' => 'recibida', 'created_at' => now(), 'updated_at' => now(),
    ]);

    $modelo = new class extends Solicitud
    {
        public static array $cola = ['FC-26-DUP001', 'FC-26-NUEVO1'];

        public static function generarCodigo(): string
        {
            return array_shift(self::$cola);
        }
    };

    $solicitud = $modelo::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '1',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => 'recibida',
    ]);

    expect($solicitud->exists)->toBeTrue()
        ->and($solicitud->codigo)->toBe('FC-26-NUEVO1')
        ->and(Solicitud::where('codigo', $solicitud->codigo)->count())->toBe(1)
        ->and(Solicitud::count())->toBe(2);
});

test('un código puesto a mano que ya existe no se cambia en silencio: se rechaza', function () {
    Pqrs::create(['codigo' => 'PQ-26-FIJO01', 'tipo' => 'peticion', 'nombre' => 'A', 'documento' => '1', 'email' => 'a@test.co', 'asunto' => 'x', 'descripcion' => 'x']);

    expect(fn () => Pqrs::create(['codigo' => 'PQ-26-FIJO01', 'tipo' => 'peticion', 'nombre' => 'B', 'documento' => '2', 'email' => 'b@test.co', 'asunto' => 'x', 'descripcion' => 'x']))
        ->toThrow(QueryException::class);
});

test('un producto financiero con tasas no se puede borrar y toda tasa exige producto', function () {
    $producto = ProductoFinanciero::create(['nombre' => 'Ahorro', 'codigo' => 'ahorro-x', 'tipo' => 'ahorro']);
    ParametroFinanciero::create([
        'producto_financiero_id' => $producto->id, 'nombre' => 'Tasa de interés', 'codigo' => 'tasa_interes',
        'valor' => 1.2, 'fecha_inicio_vigencia' => now()->toDateString(),
    ]);

    expect(fn () => $producto->delete())->toThrow(QueryException::class)
        ->and(ProductoFinanciero::whereKey($producto->id)->exists())->toBeTrue();

    expect(fn () => ParametroFinanciero::create([
        'nombre' => 'Huérfana', 'codigo' => 'tasa_interes', 'valor' => 1, 'fecha_inicio_vigencia' => now()->toDateString(),
    ]))->toThrow(QueryException::class);

    // Un producto sin tasas sí se puede borrar.
    $vacio = ProductoFinanciero::create(['nombre' => 'Vacío', 'codigo' => 'vacio-x', 'tipo' => 'credito']);
    $vacio->delete();
    expect(ProductoFinanciero::whereKey($vacio->id)->exists())->toBeFalse();
});
