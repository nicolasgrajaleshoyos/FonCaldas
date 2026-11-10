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

test('aAdmins() notifica solo a los administradores principales activos', function () {
    Mail::fake();
    $pqrs = pqrsParaNotificar();
    $principal = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $principalInactivo = User::factory()->create(['is_super_admin' => true, 'is_active' => false]);
    $funcionario = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    app(NotificadorPqrs::class)->aAdmins($pqrs);

    Mail::assertSent(PqrsNotificacion::class, fn ($m) => $m->hasTo($principal->email));
    Mail::assertNotSent(PqrsNotificacion::class, fn ($m) => $m->hasTo($principalInactivo->email));
    Mail::assertNotSent(PqrsNotificacion::class, fn ($m) => $m->hasTo($funcionario->email));
});

test('limita los correos al asociado por destinatario (10 por hora)', function () {
    Mail::fake();
    $pqrs = pqrsParaNotificar();

    foreach (range(1, 12) as $_) {
        app(NotificadorPqrs::class)->alAsociado($pqrs, 'asunto', 'mensaje');
    }

    Mail::assertSent(PqrsNotificacion::class, 10);
});

test('un fallo de correo al asociado se registra en el log y no interrumpe el flujo', function () {
    $pqrs = pqrsParaNotificar();
    Mail::shouldReceive('to->send')->andThrow(new RuntimeException('smtp caído'));
    Log::shouldReceive('warning')->once()->with(Mockery::pattern('/No se pudo notificar \(asociado\) la PQRS/'));

    expect(fn () => app(NotificadorPqrs::class)->alAsociado($pqrs, 'asunto', 'mensaje'))->not->toThrow(Throwable::class);
});
