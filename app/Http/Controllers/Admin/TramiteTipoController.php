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

    public function edit(TramiteTipo $tramiteTipo)
    {
        return view('pages.admin-tramite-tipos-edit', ['tipo' => $tramiteTipo]);
    }

    /** El slug no cambia al renombrar, para no romper referencias existentes. */
    public function update(CrearTramiteTipoRequest $request, TramiteTipo $tramiteTipo)
    {
        $tramiteTipo->update([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'es_certificacion' => $request->boolean('es_certificacion'),
        ]);

        return redirect()->route('admin.tramite-tipos.index')->with('success', 'Tipo de trámite actualizado.');
    }

    public function toggleActive(TramiteTipo $tramiteTipo)
    {
        $tramiteTipo->update(['activo' => ! $tramiteTipo->activo]);

        return back()->with('success', $tramiteTipo->activo ? 'Trámite activado.' : 'Trámite desactivado.');
    }

    public function destroy(TramiteTipo $tramiteTipo)
    {
        $n = $tramiteTipo->solicitudes()->withTrashed()->count();

        if ($n > 0) {
            return back()->with('error', "No se puede eliminar «{$tramiteTipo->nombre}»: tiene {$n} solicitud(es). Elimínalas primero o desactiva el trámite.");
        }

        $tramiteTipo->delete();

        return back()->with('success', 'Tipo de trámite eliminado.');
    }
}
