<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudEvento;
use App\Models\User;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $visibles = $request->user()->tramiteTiposVisibles()->select('id');

        $eventos = SolicitudEvento::with(['solicitud.tramiteTipo', 'usuario'])
            ->whereHas('solicitud', fn ($q) => $q->whereIn('tramite_tipo_id', $visibles))
            ->when($request->input('codigo'), fn ($q, $codigo) => $q->whereHas('solicitud', fn ($s) => $s->where('codigo', 'like', "%{$codigo}%")))
            ->when($request->input('user_id'), fn ($q, $userId) => $q->where('user_id', $userId))
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('pages.admin-auditoria', [
            'eventos' => $eventos,
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }
}
