<?php

namespace App\Services\Pqrs;

use App\Mail\PqrsNotificacion;
use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        User::where('is_active', true)->pluck('email')->each(
            fn (string $email) => $this->enviar($pqrs, $email, "Nueva {$pqrs->tipoLabel()} {$pqrs->codigo}", $mensaje, 'admin')
        );
    }

    private function enviar(Pqrs $pqrs, string $email, string $asunto, string $mensaje, string $destino): void
    {
        try {
            Mail::to($email)->send(new PqrsNotificacion($pqrs, $asunto, $mensaje));
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar ({$destino}) la PQRS {$pqrs->codigo}: ".$e->getMessage());
        }
    }
}
