<?php

namespace App\Services\Transparencia;

use App\Contracts\AlmacenArchivos;
use App\Models\Event;
use Illuminate\Http\UploadedFile;

class GestorEventos
{
    public function __construct(private AlmacenArchivos $archivos) {}

    public function crear(array $datos, ?UploadedFile $media): Event
    {
        return Event::create([
            'title' => $datos['title'],
            'description' => $datos['description'],
            'location' => $datos['location'],
            'event_date' => $datos['event_date'],
            'event_time' => $datos['event_time'],
            'media_type' => $media ? $this->tipoMedia($media) : null,
            'media_path' => $media ? $this->archivos->guardar($media, 'uploads/eventos') : null,
        ]);
    }

    /** Sin archivo nuevo se conserva el actual; con archivo nuevo se reemplaza y se borra el anterior. */
    public function actualizar(Event $evento, array $datos, ?UploadedFile $media): Event
    {
        $evento->fill(collect($datos)->only(['title', 'description', 'location', 'event_date', 'event_time'])->all());

        if ($media) {
            $anterior = $evento->media_path;
            $evento->media_type = $this->tipoMedia($media);
            $evento->media_path = $this->archivos->guardar($media, 'uploads/eventos');
        }

        $evento->save();

        // El archivo viejo se borra solo después de guardar el registro.
        if (isset($anterior)) {
            $this->archivos->eliminar($anterior);
        }

        return $evento;
    }

    public function eliminar(Event $evento): void
    {
        $evento->delete();
        $this->archivos->eliminar($evento->media_path);
    }

    private function tipoMedia(UploadedFile $media): string
    {
        return str_starts_with((string) $media->getMimeType(), 'video/') ? 'video' : 'image';
    }
}
