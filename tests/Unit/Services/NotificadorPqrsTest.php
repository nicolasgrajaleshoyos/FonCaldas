<?php

use App\Mail\PqrsNotificacion;
use App\Models\Pqrs;
use App\Models\User;
use App\Services\Pqrs\NotificadorPqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function pqrsParaNotificar(): Pqrs
{
    return Pqrs::create([
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'Demora', 'descripcion' => 'Detalle',
    ]);
}

test('aAdmins() notifica a todos los usuarios activos, sin importar el tipo de trámite', function () {
    Mail::fake();
    $pqrs = pqrsParaNotificar();
    $activo = User::factory()->create(['is_active' => true]);
    $inactivo = User::factory()->create(['is_active' => false]);

    app(NotificadorPqrs::class)->aAdmins($pqrs);

    Mail::assertSent(PqrsNotificacion::class, fn ($m) => $m->hasTo($activo->email));
    Mail::assertNotSent(PqrsNotificacion::class, fn ($m) => $m->hasTo($inactivo->email));
});

test('un fallo de correo al asociado se registra en el log y no interrumpe el flujo', function () {
    $pqrs = pqrsParaNotificar();
    Mail::shouldReceive('to->send')->andThrow(new RuntimeException('smtp caído'));
    Log::shouldReceive('warning')->once()->with(Mockery::pattern('/No se pudo notificar \(asociado\) la PQRS/'));

    expect(fn () => app(NotificadorPqrs::class)->alAsociado($pqrs, 'asunto', 'mensaje'))->not->toThrow(Throwable::class);
});
