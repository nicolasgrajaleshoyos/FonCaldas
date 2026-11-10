<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudEvento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $visibles = $request->user()->tramiteTiposVisibles()->select('id');

        // withTrashed: los eventos de solicitudes eliminadas (borrado lógico) siguen siendo auditables.
        $eventos = SolicitudEvento::with(['solicitud' => fn ($q) => $q->withTrashed()->with('tramiteTipo'), 'usuario'])
            ->whereNull('archivado_at')
            ->whereHas('solicitud', fn ($q) => $q->withTrashed()->whereIn('tramite_tipo_id', $visibles))
            ->when($request->input('codigo'), fn ($q, $codigo) => $q->whereHas('solicitud', fn ($s) => $s->withTrashed()->where('codigo', 'like', "%{$codigo}%")))
            ->when($request->input('user_id'), fn ($q, $userId) => $q->where('user_id', $userId))
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('pages.admin-auditoria', [
            'eventos' => $eventos,
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }

    public function destroy(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer'])['ids'];
        // Se archivan en vez de borrarse: salen del panel pero la evidencia se conserva en la base de datos.
        $archivados = SolicitudEvento::whereIn('id', $ids)->whereNull('archivado_at')->update(['archivado_at' => now()]);
        Log::notice("Auditoría: {$request->user()->username} archivó {$archivados} registro(s).");

        return back()->with('success', "Se archivaron {$archivados} registro(s) de auditoría.");
    }

    public function vaciar(Request $request)
    {
        $archivados = SolicitudEvento::whereNull('archivado_at')->update(['archivado_at' => now()]);
        Log::notice("Auditoría: {$request->user()->username} vació el panel ({$archivados} registros archivados).");

        return back()->with('success', "Auditoría vaciada ({$archivados} registros archivados).");
    }
}
