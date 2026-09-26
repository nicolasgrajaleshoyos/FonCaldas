<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pqrs;
use App\Services\Pqrs\FlujoPqrs;
use Illuminate\Http\Request;

class PqrsController extends Controller
{
    public function __construct(private FlujoPqrs $flujo) {}

    public function index(Request $request)
    {
        $pqrs = Pqrs::query()
            ->when($request->input('tipo'), fn ($q, $tipo) => $q->where('tipo', $tipo))
            ->when($request->input('estado'), fn ($q, $estado) => $q->where('estado', $estado))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.admin-pqrs-index', [
            'pqrs' => $pqrs,
            'tipos' => Pqrs::TIPOS,
            'estados' => Pqrs::ESTADOS,
        ]);
    }

    public function show(Pqrs $pqrs)
    {
        return view('pages.admin-pqrs-show', ['pqrs' => $pqrs->load('respondidoPor')]);
    }

    public function tramitar(Pqrs $pqrs)
    {
        $this->flujo->tramitar($pqrs);

        return back()->with('success', 'PQRS marcada en trámite.');
    }

    public function responder(Request $request, Pqrs $pqrs)
    {
        $data = $request->validate(['respuesta' => 'required|string|max:5000']);
        $this->flujo->responder($pqrs, $request->user(), $data['respuesta']);

        return back()->with('success', 'Respuesta enviada al asociado.');
    }
}
