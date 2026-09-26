<?php

namespace App\Services\Transparencia;

use App\Contracts\AlmacenArchivos;
use App\Models\Event;
use Illuminate\Http\UploadedFile;

class GestorEventos
{
    private const EXTENSIONES_VIDEO = ['mp4', 'mov', 'avi', 'webm', 'mkv'];

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

    public function eliminar(Event $evento): void
    {
        $this->archivos->eliminar($evento->media_path);
        $evento->delete();
    }

    private function tipoMedia(UploadedFile $media): string
    {
        return in_array(strtolower($media->getClientOriginalExtension()), self::EXTENSIONES_VIDEO, true) ? 'video' : 'image';
    }
}
