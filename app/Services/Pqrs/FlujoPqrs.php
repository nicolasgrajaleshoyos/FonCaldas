<?php

namespace App\Services\Pqrs;

use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Validation\ValidationException;

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
        // Transición atómica: solo avanza si en BD sigue "recibida" (evita pisar una respuesta concurrente).
        $cambiadas = Pqrs::whereKey($pqrs->getKey())
            ->where('estado', Pqrs::ESTADO_RECIBIDA)
            ->update(['estado' => Pqrs::ESTADO_EN_TRAMITE, 'updated_at' => now()]);

        if (! $cambiadas) {
            return;
        }

        $pqrs->refresh();
        $this->notificador->alAsociado($pqrs, "Tu {$pqrs->tipoLabel()} {$pqrs->codigo} está en trámite", 'Ya estamos gestionando tu solicitud.');
    }

    public function responder(Pqrs $pqrs, User $actor, string $respuesta): void
    {
        // Actualización condicional: dos respuestas simultáneas no pueden sobrescribirse entre sí.
        $cambiadas = Pqrs::whereKey($pqrs->getKey())
            ->where('estado', '!=', Pqrs::ESTADO_RESPONDIDA)
            ->update([
                'respuesta' => $respuesta,
                'estado' => Pqrs::ESTADO_RESPONDIDA,
                'respondido_por' => $actor->id,
                'respondido_at' => now(),
                'updated_at' => now(),
            ]);

        if (! $cambiadas) {
            throw ValidationException::withMessages(['respuesta' => 'Esta PQRS ya fue respondida; no se puede sobrescribir la respuesta.']);
        }

        $pqrs->refresh();

        $this->notificador->alAsociado($pqrs, "Respuesta a tu {$pqrs->tipoLabel()} {$pqrs->codigo}", 'Hemos respondido tu solicitud.');
    }
}
