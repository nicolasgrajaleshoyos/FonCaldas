<?php

namespace App\Services\Transparencia;

use App\Contracts\AlmacenArchivos;
use App\Models\Document;
use Illuminate\Http\UploadedFile;

class GestorDocumentos
{
    public function __construct(
        private AlmacenArchivos $archivos,
        private CategoriasTransparencia $categorias,
    ) {}

    public function publicar(array $datos, UploadedFile $pdf): Document
    {
        return Document::create([
            'title' => $datos['title'],
            'description' => $datos['description'],
            'category' => $this->categorias->etiqueta($datos['category']),
            'file_path' => $this->archivos->guardar($pdf, 'uploads/transparencia'),
            'published_at' => now(),
        ]);
    }

    /** Sin PDF nuevo se conserva el actual; con PDF nuevo se reemplaza y se borra el anterior. */
    public function actualizar(Document $documento, array $datos, ?UploadedFile $pdf): Document
    {
        $documento->fill([
            'title' => $datos['title'],
            'description' => $datos['description'],
            'category' => $this->categorias->etiqueta($datos['category']),
        ]);

        if ($pdf) {
            $anterior = $documento->file_path;
            $documento->file_path = $this->archivos->guardar($pdf, 'uploads/transparencia');
        }

        $documento->save();

        // El archivo viejo se borra solo después de guardar el registro: si falla, no se pierde el PDF vigente.
        if (isset($anterior)) {
            $this->archivos->eliminar($anterior);
        }

        return $documento;
    }

    public function eliminar(Document $documento): void
    {
        $documento->delete();
        $this->archivos->eliminar($documento->file_path);
    }
}
