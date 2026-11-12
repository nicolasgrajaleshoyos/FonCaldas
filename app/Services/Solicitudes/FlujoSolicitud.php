<?php

namespace App\Services\Solicitudes;

use App\Models\Solicitud;
use App\Models\User;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/** Transiciones de estado de una solicitud: cada una persiste, deja rastro de auditoría y notifica. */
class FlujoSolicitud
{
    public function __construct(private NotificadorSolicitudes $notificador) {}

    /** La verificación es manual: el funcionario compara los datos con la información de asociados de FONCALDAS. */
    public function verificarIdentidad(Solicitud $solicitud, User $actor): void
    {
        $anterior = null;

        $this->enTransaccion($solicitud, function () use ($solicitud, $actor, &$anterior) {
            if (! in_array($solicitud->estado, Solicitud::ESTADOS_ABIERTOS, true)) {
                throw ValidationException::withMessages(['estado' => 'Solo se puede verificar la identidad de una solicitud recibida o en revisión.']);
            }

            $anterior = $solicitud->estado;

            $solicitud->verificado_por = $actor->id;
            $solicitud->verificado_at = now();
            if ($anterior === Solicitud::ESTADO_RECIBIDA) {
                $solicitud->estado = Solicitud::ESTADO_EN_REVISION;
            }
            $solicitud->save();

            $solicitud->registrarEvento('identidad_verificada', 'Identidad verificada manualmente por el funcionario frente a la información de asociados de FONCALDAS.', $anterior, $solicitud->estado, $actor->id);
        });

        if ($anterior !== $solicitud->estado) {
            $this->notificador->alAsociado($solicitud);
        }
    }

    public function aprobar(Solicitud $solicitud, User $actor): void
    {
        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_APROBADA, 'solicitud_aprobada', exigido: [[Solicitud::ESTADO_EN_REVISION], 'Solo se puede aprobar una solicitud en revisión, es decir, con la identidad del asociado verificada.']);
    }

    public function rechazar(Solicitud $solicitud, User $actor, string $justificacion): void
    {
        // Desde «recibida» se rechaza sin marcar la identidad como verificada: es el caso en que los datos
        // del asociado no coinciden con la información de FONCALDAS, y la auditoría debe reflejarlo así.
        $detalle = fn () => $solicitud->estado === Solicitud::ESTADO_RECIBIDA
            ? "Rechazada sin verificar la identidad del asociado. Motivo: {$justificacion}"
            : $justificacion;

        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_RECHAZADA, 'solicitud_rechazada', $detalle, ['justificacion' => $justificacion, 'finalizado_at' => now()], [Solicitud::ESTADOS_ABIERTOS, 'Solo se puede rechazar una solicitud recibida o en revisión.']);
    }

    public function eliminar(Solicitud $solicitud, User $actor): void
    {
        // Borrado lógico: la solicitud sale del panel, pero su historial, auditoría y archivos se conservan.
        DB::transaction(function () use ($solicitud, $actor) {
            $solicitud->registrarEvento('solicitud_eliminada', 'Solicitud eliminada del panel por el administrador principal.', $solicitud->estado, null, $actor->id);
            $solicitud->delete();
        });
        Log::notice("Solicitud {$solicitud->codigo} eliminada (borrado lógico) por {$actor->username}.");
    }

    public function finalizar(Solicitud $solicitud, User $actor): void
    {
        $this->cambiarEstado($solicitud, $actor, Solicitud::ESTADO_FINALIZADA, 'solicitud_finalizada', null, ['finalizado_at' => now()], [[Solicitud::ESTADO_APROBADA], 'Solo se puede finalizar una solicitud aprobada.']);
    }

    public function reasignar(Solicitud $solicitud, User $actor, User $responsable): void
    {
        // Una solicitud rechazada o finalizada ya no tiene nada que gestionar: reasignarla solo enviaría un aviso engañoso.
        if (in_array($solicitud->estado, [Solicitud::ESTADO_RECHAZADA, Solicitud::ESTADO_FINALIZADA], true)) {
            throw ValidationException::withMessages(['asignado_a' => 'La solicitud ya está cerrada y no se puede reasignar.']);
        }

        if (! User::responsablesDe($solicitud->tramite_tipo_id)->whereKey($responsable->id)->exists()) {
            throw ValidationException::withMessages(['asignado_a' => 'Solo se puede reasignar a un usuario activo con acceso a este tipo de trámite.']);
        }

        DB::transaction(function () use ($solicitud, $actor, $responsable) {
            $solicitud->update(['asignado_a' => $responsable->id]);
            $solicitud->registrarEvento('solicitud_reasignada', "Reasignada a {$responsable->name}", userId: $actor->id);
        });
        $this->notificador->alResponsable($solicitud, $responsable);
    }

    /**
     * Reparte las solicitudes abiertas de un funcionario que ya no puede atenderlas (desactivado, eliminado o sin
     * acceso a ese tipo de trámite) entre los demás responsables, para que ninguna quede sin quién la gestione.
     *
     * @param  bool  $saliente  true cuando el usuario está por eliminarse: todas sus solicitudes abiertas se reparten.
     * @return int cuántas solicitudes cambiaron de responsable
     */
    public function redistribuirAbiertasDe(User $usuario, User $actor, bool $saliente = false): int
    {
        $abiertas = Solicitud::where('asignado_a', $usuario->id)->whereIn('estado', Solicitud::ESTADOS_ABIERTOS)->get();
        $movidas = 0;

        foreach ($abiertas as $solicitud) {
            if (! $saliente && User::responsablesDe($solicitud->tramite_tipo_id)->whereKey($usuario->id)->exists()) {
                continue; // aún puede atenderla
            }

            $nuevo = User::responsableSugerido($solicitud->tramite_tipo_id, excluir: $usuario->id);

            DB::transaction(function () use ($solicitud, $actor, $usuario, $nuevo) {
                $solicitud->update(['asignado_a' => $nuevo?->id]);
                $solicitud->registrarEvento('solicitud_reasignada', $nuevo
                    ? "Reasignada a {$nuevo->name} porque {$usuario->name} ya no puede atenderla."
                    : "Quedó sin responsable porque {$usuario->name} ya no puede atenderla y no hay otro disponible.", userId: $actor->id);
            });

            if ($nuevo) {
                $this->notificador->alResponsable($solicitud, $nuevo);
            }
            $movidas++;
        }

        return $movidas;
    }

    public function publicarDocumento(Solicitud $solicitud, User $actor, UploadedFile $archivo): void
    {
        DB::transaction(function () use ($solicitud, $actor, $archivo) {
            $solicitud->adjuntar($archivo, 'admin');
            $solicitud->registrarEvento('documento_publicado', "Se publicó el documento: {$archivo->getClientOriginalName()}", userId: $actor->id);
        });
        $this->notificador->alAsociado($solicitud);
    }

    /** Ejecuta la acción en una transacción sobre la fila bloqueada y recargada: dos funcionarios no pueden aplicar la misma transición a la vez. */
    private function enTransaccion(Solicitud $solicitud, Closure $accion): void
    {
        DB::transaction(function () use ($solicitud, $accion) {
            $actual = Solicitud::query()->lockForUpdate()->findOrFail($solicitud->id);
            $solicitud->setRawAttributes($actual->getAttributes(), true);
            $accion();
        });
    }

    /**
     * @param  string|Closure|null  $detalle  texto, o función evaluada con el estado ya recargado
     * @param  array{0: list<string>, 1: string}|null  $exigido  estados permitidos de origen y mensaje de error
     */
    private function cambiarEstado(Solicitud $solicitud, User $actor, string $nuevo, string $accion, string|Closure|null $detalle = null, array $extra = [], ?array $exigido = null): void
    {
        $this->enTransaccion($solicitud, function () use ($solicitud, $actor, $nuevo, $accion, $detalle, $extra, $exigido) {
            if ($exigido && ! in_array($solicitud->estado, $exigido[0], true)) {
                throw ValidationException::withMessages(['estado' => $exigido[1]]);
            }

            $anterior = $solicitud->estado;
            $detalle = $detalle instanceof Closure ? $detalle() : $detalle;

            $solicitud->update(['estado' => $nuevo] + $extra);
            $solicitud->registrarEvento($accion, $detalle, $anterior, $nuevo, $actor->id);
        });

        $this->notificador->alAsociado($solicitud);
    }
}
