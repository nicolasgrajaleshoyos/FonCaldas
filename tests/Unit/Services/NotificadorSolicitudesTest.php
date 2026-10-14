<?php

use App\Mail\NuevaSolicitudAdmin;
use App\Mail\SolicitudEstadoCambiado;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use App\Services\Solicitudes\NotificadorSolicitudes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function solicitudParaNotificar(): Solicitud
{
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);

    return Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);
}

test('aAdmins() notifica solo a los responsables activos del tipo de trámite', function () {
    Mail::fake();
    $solicitud = solicitudParaNotificar();
    $responsable = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $ajeno = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    app(NotificadorSolicitudes::class)->aAdmins($solicitud);

    Mail::assertSent(NuevaSolicitudAdmin::class, fn ($m) => $m->hasTo($responsable->email));
    Mail::assertNotSent(NuevaSolicitudAdmin::class, fn ($m) => $m->hasTo($ajeno->email));
});

test('un fallo de correo se registra en el log y no interrumpe el flujo', function () {
    $solicitud = solicitudParaNotificar();
    Mail::shouldReceive('to->send')->andThrow(new RuntimeException('smtp caído'));
    Log::shouldReceive('warning')->once()->with(Mockery::pattern('/No se pudo notificar \(asociado\)/'));

    expect(fn () => app(NotificadorSolicitudes::class)->alAsociado($solicitud))->not->toThrow(Throwable::class);
});
