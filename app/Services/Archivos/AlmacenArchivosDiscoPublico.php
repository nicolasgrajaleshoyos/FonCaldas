<?php

namespace App\Services\Archivos;

use App\Contracts\AlmacenArchivos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlmacenArchivosDiscoPublico implements AlmacenArchivos
{
    private const PREFIJO = 'storage/';

    public function guardar(UploadedFile $archivo, string $carpeta): string
    {
        // La extensión se deduce del contenido real, no del nombre enviado por el cliente: así un archivo
        // "inocente.html" que por dentro es una imagen no se publica con una extensión ejecutable.
        $base = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $nombre = time().'_'.Str::random(6).'_'.preg_replace('/[^A-Za-z0-9\-_]/', '_', $base).'.'.($archivo->guessExtension() ?: 'bin');

        Storage::disk('public')->putFileAs($carpeta, $archivo, $nombre);

        return self::PREFIJO.trim($carpeta, '/').'/'.$nombre;
    }

    public function eliminar(?string $rutaPublica): void
    {
        if (! $rutaPublica) {
            return;
        }

        if (str_starts_with($rutaPublica, self::PREFIJO)) {
            Storage::disk('public')->delete(substr($rutaPublica, strlen(self::PREFIJO)));

            return;
        }

        // Archivos heredados guardados directamente bajo public/.
        $legado = public_path($rutaPublica);
        if (is_file($legado)) {
            unlink($legado);
        }
    }
}
