<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $tipos = $request->user()->tramiteTiposVisibles();
        $solicitudes = Solicitud::whereIn('tramite_tipo_id', (clone $tipos)->select('id'));

        $porEstado = (clone $solicitudes)->selectRaw('estado, count(*) as total')->groupBy('estado')->pluck('total', 'estado');

        // ponytail: promedio calculado en PHP sobre las solicitudes cerradas; pasar a SQL si el volumen lo justifica.
        $horasPromedio = (clone $solicitudes)->whereNotNull('finalizado_at')->get(['created_at', 'finalizado_at'])
            ->avg(fn ($s) => $s->created_at->diffInMinutes($s->finalizado_at) / 60);

        return view('pages.admin-dashboard', [
            'pendientes' => $porEstado->only(Solicitud::ESTADOS_ABIERTOS)->sum(),
            'metricas' => [
                'total' => $porEstado->sum(),
                'ultimos30' => (clone $solicitudes)->where('created_at', '>=', now()->subDays(30))->count(),
                'horasPromedio' => $horasPromedio,
                'porEstado' => $porEstado,
                'porTipo' => $tipos->withCount('solicitudes')->orderByDesc('solicitudes_count')->get()->where('solicitudes_count', '>', 0),
            ],
        ]);
    }
}
