<?php

namespace App\Services\Pqrs;

use App\Models\Pqrs;
use App\Models\User;

class FlujoPqrs
{
    public function __construct(private NotificadorPqrs $notificador) {}

    public function radicar(array $datos): Pqrs
    {
        $pqrs = Pqrs::create($datos);

        $this->notificador->alAsociado($pqrs, "Recibimos tu {$pqrs->tipoLabel()} {$pqrs->codigo}", 'Hemos recibido tu solicitud. Puedes consultar su estado con tu documento y el código.');
        $this->notificador->aAdmins($pqrs);

        return $pqrs;
    }

    public function tramitar(Pqrs $pqrs): void
    {
        if ($pqrs->estado !== Pqrs::ESTADO_RECIBIDA) {
            return;
        }

        $pqrs->update(['estado' => Pqrs::ESTADO_EN_TRAMITE]);
        $this->notificador->alAsociado($pqrs, "Tu {$pqrs->tipoLabel()} {$pqrs->codigo} está en trámite", 'Ya estamos gestionando tu solicitud.');
    }

    public function responder(Pqrs $pqrs, User $actor, string $respuesta): void
    {
        $pqrs->update([
            'respuesta' => $respuesta,
            'estado' => Pqrs::ESTADO_RESPONDIDA,
            'respondido_por' => $actor->id,
            'respondido_at' => now(),
        ]);

        $this->notificador->alAsociado($pqrs, "Respuesta a tu {$pqrs->tipoLabel()} {$pqrs->codigo}", 'Hemos respondido tu solicitud.');
    }
}
