<?php

namespace App\Services\Solicitudes;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/** Transiciones de estado de una solicitud: cada una persiste, deja rastro de auditoría y notifica. */
class FlujoSolicitud
{
    public function __construct(private NotificadorSolicitudes $notificador) {}

    /** La verificación es manual: el funcionario compara los datos con la información de asociados de FONCALDAS. */
    public function verificarIdentidad(Solicitud $solicitud, User $actor): void
    {
        $anterior = $solicitud->estado;

        $solicitud->verificado_por = $actor->id;
        $solicitud->verificado_at = now();
        if ($anterior === Solicitud::ESTADO_RECIBIDA) {
            $solicitud->estado = Solicitud::ESTADO_EN_REVISION;
        }
        $solicitud->save();

        $solicitud->registrarEvento('identidad_verificada', 'Identidad verificada manualmente por el funcionario frente a la información de asociados de FONCALDAS.', $anterior, $solicitud->estado, $actor->id);

        if ($anterior !== $solicitud->estado) {
            $this->notificador->alAsociado($solicitud);
        }
    }

    public function aprobar(Solicitud $solicitud, User $actor): void
    {
        $this->exigirEstado($solicitud, Solicitud::ESTADO_EN_REVISION, 'Solo se puede aprobar una solicitud en revisión, es decir, con la identidad del asociado verificada.');
        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_APROBADA, 'solicitud_aprobada');
    }

    public function rechazar(Solicitud $solicitud, User $actor, string $justificacion): void
    {
        $this->exigirEstado($solicitud, Solicitud::ESTADO_EN_REVISION, 'Solo se puede rechazar una solicitud en revisión, es decir, con la identidad del asociado verificada.');
        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_RECHAZADA, 'solicitud_rechazada', $justificacion, ['justificacion' => $justificacion, 'finalizado_at' => now()]);
    }

    public function finalizar(Solicitud $solicitud, User $actor): void
    {
        $this->exigirEstado($solicitud, Solicitud::ESTADO_APROBADA, 'Solo se puede finalizar una solicitud aprobada.');
        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_FINALIZADA, 'solicitud_finalizada', null, ['finalizado_at' => now()]);
    }

    public function reasignar(Solicitud $solicitud, User $actor, User $responsable): void
    {
        $solicitud->update(['asignado_a' => $responsable->id]);
        $solicitud->registrarEvento('solicitud_reasignada', "Reasignada a {$responsable->name}", userId: $actor->id);
        $this->notificador->alResponsable($solicitud, $responsable);
    }

    public function publicarDocumento(Solicitud $solicitud, User $actor, UploadedFile $archivo): void
    {
        $solicitud->adjuntar($archivo, 'admin');

        $solicitud->registrarEvento('documento_publicado', "Se publicó el documento: {$archivo->getClientOriginalName()}", userId: $actor->id);
        $this->notificador->alAsociado($solicitud);
    }

    private function exigirEstado(Solicitud $solicitud, string $estado, string $mensaje): void
    {
        if ($solicitud->estado !== $estado) {
            throw ValidationException::withMessages(['estado' => $mensaje]);
        }
    }

    private function cambiarEstado(Solicitud $solicitud, User $actor, string $nuevo, string $accion, ?string $detalle = null, array $extra = []): void
    {
        $anterior = $solicitud->estado;

        $solicitud->update(['estado' => $nuevo] + $extra);
        $solicitud->registrarEvento($accion, $detalle, $anterior, $nuevo, $actor->id);
        $this->notificador->alAsociado($solicitud);
    }
}
