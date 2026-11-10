<?php

namespace App\Services\Solicitudes;

use App\Mail\NuevaSolicitudAdmin;
use App\Mail\SolicitudEstadoCambiado;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class NotificadorSolicitudes
{
    // ponytail: envío síncrono con MAIL_MAILER=log en dev; si el volumen crece, mover a cola (ShouldQueue).
    /** Informa el estado actual; en "Recibida" el mismo mensaje confirma la radicación con su código. */
    public function alAsociado(Solicitud $solicitud): void
    {
        $this->enviar($solicitud->asociado_email, new SolicitudEstadoCambiado($solicitud), 'asociado');
    }

    public function aAdmins(Solicitud $solicitud): void
    {
        User::responsablesDe($solicitud->tramite_tipo_id)->pluck('email')->each(
            fn (string $email) => $this->enviar($email, new NuevaSolicitudAdmin($solicitud), 'admin')
        );
    }

    public function alResponsable(Solicitud $solicitud, User $responsable): void
    {
        $this->enviar($responsable->email, new NuevaSolicitudAdmin($solicitud, reasignada: true), 'responsable');
    }

    private function enviar(string $email, object $mailable, string $destino): void
    {
        // El correo del asociado lo escribe quien radica: se limita por destinatario para que no sirva para
        // enviar ráfagas de correos a un tercero. Los funcionarios no tienen este límite.
        if ($destino === 'asociado' && ! RateLimiter::attempt('correo:'.strtolower($email), 10, fn () => true, 3600)) {
            Log::warning('Correo al asociado omitido por exceso de envíos a la misma dirección.');

            return;
        }

        try {
            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar ({$destino}) por correo: ".$e->getMessage());
        }
    }
}
