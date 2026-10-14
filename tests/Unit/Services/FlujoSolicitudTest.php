<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use App\Services\Solicitudes\FlujoSolicitud;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('verificarIdentidad() sobre una solicitud ya verificada no cambia el estado ni reenvía correo', function () {
    Mail::fake();
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $actor = User::factory()->create();
    $solicitud = Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_EN_REVISION,
    ]);

    app(FlujoSolicitud::class)->verificarIdentidad($solicitud, $actor);

    expect($solicitud->fresh()->estado)->toBe(Solicitud::ESTADO_EN_REVISION)
        ->and($solicitud->eventos()->where('accion', 'identidad_verificada')->exists())->toBeTrue();
    Mail::assertNothingSent();
});
