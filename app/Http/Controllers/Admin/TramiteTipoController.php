<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrearTramiteTipoRequest;
use App\Models\TramiteTipo;
use Illuminate\Support\Str;

class TramiteTipoController extends Controller
{
    public function index()
    {
        return view('pages.admin-tramite-tipos', [
            'tramiteTipos' => TramiteTipo::orderBy('nombre')->get(),
        ]);
    }

    public function store(CrearTramiteTipoRequest $request)
    {
        TramiteTipo::create([
            'nombre' => $request->input('nombre'),
            'slug' => Str::slug($request->input('nombre')),
            'descripcion' => $request->input('descripcion'),
            'es_certificacion' => $request->boolean('es_certificacion'),
            'activo' => true,
        ]);

        return back()->with('success', 'Tipo de trámite creado. Ya puede recibir solicitudes.');
    }

    public function toggleActive(TramiteTipo $tramiteTipo)
    {
        $tramiteTipo->update(['activo' => ! $tramiteTipo->activo]);

        return back()->with('success', $tramiteTipo->activo ? 'Trámite activado.' : 'Trámite desactivado.');
    }
}
