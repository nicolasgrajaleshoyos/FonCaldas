<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface AlmacenArchivos
{
    /** Guarda el archivo y devuelve su ruta pública (relativa a public/). */
    public function guardar(UploadedFile $archivo, string $carpeta): string;

    public function eliminar(?string $rutaPublica): void;
}
