<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SolicitudDocumento extends Model
{
    /** Disco privado: los archivos solo se sirven a través de rutas autorizadas, nunca por URL directa. */
    public const DISCO = 'local';

    protected $fillable = [
        'solicitud_id',
        'origen',
        'nombre_original',
        'path',
        'version',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function respuesta(): StreamedResponse
    {
        return Storage::disk(self::DISCO)->response($this->path, $this->nombre_original);
    }
}
