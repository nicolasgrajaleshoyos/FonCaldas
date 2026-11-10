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

    /** Extensiones aceptadas en los adjuntos (regla `mimes:`); nada ejecutable ni HTML. */
    public const TIPOS_PERMITIDOS = 'pdf,jpg,jpeg,png,doc,docx,xls,xlsx';

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
        // Archivo perdido en disco: 404 en lugar de un error 500.
        abort_unless(Storage::disk(self::DISCO)->exists($this->path), 404, 'El archivo ya no está disponible.');

        // Siempre como descarga: un archivo subido nunca se renderiza dentro del sitio.
        return Storage::disk(self::DISCO)->download($this->path, $this->nombre_original);
    }
}
