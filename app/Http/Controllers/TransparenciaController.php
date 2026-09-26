<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\Transparencia\CategoriasTransparencia;
use Illuminate\Http\Request;

class TransparenciaController extends Controller
{
    public function __construct(private CategoriasTransparencia $categorias) {}

    public function index(Request $request)
    {
        $documents = Document::orderByDesc('published_at')->get()->map(fn (Document $doc) => [
            'id' => $doc->id,
            'title' => $doc->title,
            'description' => $doc->description,
            'category' => $doc->category,
            'category_slug' => $this->categorias->slugDe($doc->category),
            'published_at' => optional($doc->published_at)->format('Y-m-d'),
            'published_at_display' => optional($doc->published_at)->format('d/m/Y'),
            'file_url' => asset($doc->file_path),
        ])->values();

        return view('pages.transparencia', [
            'categories' => $this->categorias->todas(),
            'documents' => $documents,
            'selectedCategory' => $request->input('category', ''),
        ]);
    }

    public function categoria(string $slug)
    {
        $etiqueta = $this->categorias->etiqueta($slug) ?? abort(404);

        return view('pages.transparencia-documents', [
            'category' => $etiqueta,
            'documents' => Document::where('category', $etiqueta)->orderByDesc('published_at')->get(),
            'categories' => $this->categorias->todas(),
        ]);
    }
}
