<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarDocumentoRequest;
use App\Models\Document;
use App\Services\Transparencia\CategoriasTransparencia;
use App\Services\Transparencia\GestorDocumentos;

class DocumentoController extends Controller
{
    public function index(CategoriasTransparencia $categorias)
    {
        return view('pages.admin-documents', [
            'documents' => Document::orderByDesc('published_at')->get(),
            'categories' => $categorias->todas(),
        ]);
    }

    public function store(GuardarDocumentoRequest $request, GestorDocumentos $gestor)
    {
        $gestor->publicar($request->validated(), $request->file('document'));

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function destroy(Document $document, GestorDocumentos $gestor)
    {
        $gestor->eliminar($document);

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}
