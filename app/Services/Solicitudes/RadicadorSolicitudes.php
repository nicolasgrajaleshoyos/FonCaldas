<?php

namespace App\Services\Solicitudes;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RadicadorSolicitudes
{
    public function __construct(private NotificadorSolicitudes $notificador) {}

    /** @param array<int, UploadedFile|null> $documentos */
    public function radicar(array $datos, array $documentos = []): Solicitud
    {
        // El estado se fija aquí: el default de la BD no se refleja en el modelo recién creado.
        // Todo o nada: no queda una solicitud a medias (sin evento, adjuntos o responsable) si algo falla.
        $solicitud = DB::transaction(function () use ($datos, $documentos) {
            $solicitud = Solicitud::create($datos + ['estado' => Solicitud::ESTADO_RECIBIDA]);
            $solicitud->registrarEvento('solicitud_creada', 'Solicitud radicada por el asociado.', null, $solicitud->estado);

            $this->adjuntar($solicitud, $documentos);
            $this->asignarResponsable($solicitud);

            return $solicitud;
        });

        $this->notificador->alAsociado($solicitud);
        $this->notificador->aAdmins($solicitud);

        return $solicitud;
    }

    /** Documentos que el asociado agrega a una solicitud que sigue en trámite. */
    public function agregarDocumentos(Solicitud $solicitud, array $documentos): void
    {
        DB::transaction(function () use ($solicitud, $documentos) {
            $nombres = $this->adjuntar($solicitud, $documentos);

            if ($nombres) {
                $solicitud->registrarEvento('documento_adjuntado', 'El asociado adjuntó: '.implode(', ', $nombres));
            }
        });
    }

    /** @return list<string> nombres de los archivos guardados */
    private function adjuntar(Solicitud $solicitud, array $documentos): array
    {
        $nombres = [];

        foreach ($documentos as $archivo) {
            if ($archivo?->isValid()) {
                $nombres[] = $solicitud->adjuntar($archivo, 'asociado')->nombre_original;
            }
        }

        return $nombres;
    }

    private function asignarResponsable(Solicitud $solicitud): void
    {
        if ($responsable = User::responsableSugerido($solicitud->tramite_tipo_id)) {
            $solicitud->update(['asignado_a' => $responsable->id]);
            $solicitud->registrarEvento('solicitud_asignada', "Asignada automáticamente a {$responsable->name}.");
        }
    }
}
