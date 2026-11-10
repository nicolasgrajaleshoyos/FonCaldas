<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function crearSolicitud(TramiteTipo $tipo, array $extra = []): Solicitud
{
    return Solicitud::create($extra + [
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);
}

dataset('estados_abiertos', [
    [Solicitud::ESTADO_RECIBIDA, true],
    [Solicitud::ESTADO_EN_REVISION, true],
    [Solicitud::ESTADO_APROBADA, false],
    [Solicitud::ESTADO_RECHAZADA, false],
    [Solicitud::ESTADO_FINALIZADA, false],
]);

test('abierta() refleja si el estado sigue en trámite', function (string $estado, bool $esperado) {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);

    expect(crearSolicitud($tipo, ['estado' => $estado])->abierta())->toBe($esperado);
})->with('estados_abiertos');

test('estadoLabel() usa el catálogo y cae al valor crudo si no lo reconoce', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $solicitud = crearSolicitud($tipo);

    expect($solicitud->estadoLabel())->toBe('Recibida');

    $solicitud->forceFill(['estado' => 'desconocido'])->save();
    expect($solicitud->fresh()->estadoLabel())->toBe('desconocido');
});

test('scopeFiltrar combina estado, tipo de trámite y rango de fechas', function () {
    $propio = TramiteTipo::create(['nombre' => 'Propio', 'slug' => 'propio', 'activo' => true, 'es_certificacion' => false]);
    $otro = TramiteTipo::create(['nombre' => 'Otro', 'slug' => 'otro', 'activo' => true, 'es_certificacion' => false]);

    $vieja = crearSolicitud($propio, ['estado' => Solicitud::ESTADO_APROBADA]);
    $vieja->forceFill(['created_at' => now()->subDays(10)])->save();
    $reciente = crearSolicitud($propio, ['estado' => Solicitud::ESTADO_RECIBIDA]);
    $recienteOtroTipo = crearSolicitud($otro, ['estado' => Solicitud::ESTADO_RECIBIDA]);

    expect(Solicitud::filtrar(['estado' => Solicitud::ESTADO_RECIBIDA])->pluck('id')->sort()->values()->all())
        ->toBe(collect([$reciente->id, $recienteOtroTipo->id])->sort()->values()->all());
    expect(Solicitud::filtrar(['tramite_tipo_id' => $propio->id])->count())->toBe(2);
    expect(Solicitud::filtrar(['desde' => now()->subDays(1)->toDateString()])->pluck('id')->sort()->values()->all())
        ->toBe(collect([$reciente->id, $recienteOtroTipo->id])->sort()->values()->all());
    expect(Solicitud::filtrar(['hasta' => now()->subDays(5)->toDateString()])->pluck('id')->all())->toBe([$vieja->id]);
    expect(Solicitud::filtrar([])->count())->toBe(3);
});
