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
            'documents' => Document::orderByDesc('published_at')->orderByDesc('id')->paginate(20),
            'categories' => $categorias->todas(),
        ]);
    }

    public function store(GuardarDocumentoRequest $request, GestorDocumentos $gestor)
    {
        $gestor->publicar($request->validated(), $request->file('document'));

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function edit(Document $document, CategoriasTransparencia $categorias)
    {
        return view('pages.admin-documents-edit', [
            'document' => $document,
            'categories' => $categorias->todas(),
            'categoriaActual' => $categorias->slugDe($document->category),
        ]);
    }

    public function update(GuardarDocumentoRequest $request, Document $document, GestorDocumentos $gestor)
    {
        $gestor->actualizar($document, $request->validated(), $request->file('document'));

        return redirect()->route('admin.documents')->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Document $document, GestorDocumentos $gestor)
    {
        $gestor->eliminar($document);

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}
