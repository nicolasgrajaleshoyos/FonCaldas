<?php

namespace App\Services\Pqrs;

use App\Mail\PqrsNotificacion;
use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class NotificadorPqrs
{
    // ponytail: envío síncrono; si el volumen crece, mover a cola (ShouldQueue).
    public function alAsociado(Pqrs $pqrs, string $asunto, string $mensaje): void
    {
        $this->enviar($pqrs, $pqrs->email, $asunto, $mensaje, 'asociado');
    }

    public function aAdmins(Pqrs $pqrs): void
    {
        $mensaje = "Nueva {$pqrs->tipoLabel()} radicada: {$pqrs->asunto}. Revísala en el panel de administración.";

        User::where('is_active', true)->where('is_super_admin', true)->pluck('email')->each(
            fn (string $email) => $this->enviar($pqrs, $email, "Nueva {$pqrs->tipoLabel()} {$pqrs->codigo}", $mensaje, 'admin')
        );
    }

    private function enviar(Pqrs $pqrs, string $email, string $asunto, string $mensaje, string $destino): void
    {
        // Mismo límite que en las solicitudes: el correo del asociado lo escribe quien radica, así que se limita
        // por destinatario para que el formulario público no sirva para enviar ráfagas de correos a un tercero.
        if ($destino === 'asociado' && ! RateLimiter::attempt('correo:'.strtolower($email), 10, fn () => true, 3600)) {
            Log::warning("Correo de la PQRS {$pqrs->codigo} al asociado omitido por exceso de envíos a la misma dirección.");

            return;
        }

        // Se envía después de responder: el SMTP tarda segundos y no debe retrasar la alerta de "radicada".
        defer(function () use ($email, $pqrs, $asunto, $mensaje, $destino) {
            try {
                Mail::to($email)->send(new PqrsNotificacion($pqrs, $asunto, $mensaje, paraAdmin: $destino === 'admin'));
            } catch (\Throwable $e) {
                Log::warning("No se pudo notificar ({$destino}) la PQRS {$pqrs->codigo}: ".$e->getMessage());
            }
        });
    }
}
