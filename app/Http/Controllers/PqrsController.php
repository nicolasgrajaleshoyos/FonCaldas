<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultarPqrsRequest;
use App\Http\Requests\RadicarPqrsRequest;
use App\Models\Pqrs;
use App\Services\Pqrs\FlujoPqrs;

class PqrsController extends Controller
{
    public function crear()
    {
        return view('pages.pqrs.crear', ['tipos' => Pqrs::TIPOS]);
    }

    public function store(RadicarPqrsRequest $request, FlujoPqrs $flujo)
    {
        $pqrs = $flujo->radicar($request->datosPqrs());

        return redirect()->route('pqrs.crear')->with('success', "Tu {$pqrs->tipoLabel()} fue radicada con el código {$pqrs->codigo}. Guárdalo para consultar su estado.");
    }

    public function consultar()
    {
        return view('pages.pqrs.consultar');
    }

    public function buscar(ConsultarPqrsRequest $request)
    {
        $pqrs = Pqrs::where('codigo', strtoupper(trim($request->input('codigo'))))
            ->where('documento', trim($request->input('documento')))
            ->first();

        if (! $pqrs) {
            return back()->withErrors(['codigo' => 'No encontramos una PQRS con esos datos.'])->withInput();
        }

        return view('pages.pqrs.estado', ['pqrs' => $pqrs]);
    }
}
