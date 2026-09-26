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

    public function eliminar(Document $documento): void
    {
        $this->archivos->eliminar($documento->file_path);
        $documento->delete();
    }
}
