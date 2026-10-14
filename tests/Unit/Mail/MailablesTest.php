<?php

use App\Mail\NuevaSolicitudAdmin;
use App\Mail\PqrsNotificacion;
use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('NuevaSolicitudAdmin se renderiza con el asunto correcto para creación y reasignación', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $solicitud = Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);

    $nueva = new NuevaSolicitudAdmin($solicitud);
    expect($nueva->envelope()->subject)->toBe("Nueva solicitud recibida: {$solicitud->codigo}")
        ->and($nueva->render())->toContain($solicitud->codigo);

    $reasignada = new NuevaSolicitudAdmin($solicitud, reasignada: true);
    expect($reasignada->envelope()->subject)->toBe("Solicitud asignada: {$solicitud->codigo}");
});

test('PqrsNotificacion se renderiza con el asunto y mensaje dados', function () {
    $pqrs = Pqrs::create([
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'x', 'descripcion' => 'x',
    ]);

    $mail = new PqrsNotificacion($pqrs, 'Asunto de prueba', 'Mensaje de prueba');

    expect($mail->envelope()->subject)->toBe('Asunto de prueba')
        ->and($mail->render())->toContain('Mensaje de prueba');
});
