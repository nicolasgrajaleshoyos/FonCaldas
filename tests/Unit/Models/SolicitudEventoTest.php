<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function crearSolicitudParaEventos(): Solicitud
{
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);

    return Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);
}

test('actor() identifica al funcionario cuando el evento tiene usuario', function () {
    $actor = User::factory()->create(['name' => 'Luisa']);
    $solicitud = crearSolicitudParaEventos();

    $evento = $solicitud->registrarEvento('solicitud_aprobada', userId: $actor->id);

    expect($evento->actor())->toBe('Luisa');
});

test('actor() es "Asociado" en acciones del asociado sin usuario', function () {
    $solicitud = crearSolicitudParaEventos();

    expect($solicitud->registrarEvento('solicitud_creada')->actor())->toBe('Asociado')
        ->and($solicitud->registrarEvento('documento_adjuntado')->actor())->toBe('Asociado');
});

test('actor() es "Sistema" para otras acciones sin usuario', function () {
    $solicitud = crearSolicitudParaEventos();

    expect($solicitud->registrarEvento('solicitud_asignada')->actor())->toBe('Sistema');
});
