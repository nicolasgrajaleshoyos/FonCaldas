<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FiltrarSolicitudesRequest;
use App\Models\Solicitud;
use App\Models\SolicitudDocumento;
use App\Models\User;
use App\Services\Solicitudes\FlujoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SolicitudController extends Controller
{
    public function __construct(private FlujoSolicitud $flujo) {}

    public function index(FiltrarSolicitudesRequest $request)
    {
        $tipos = $request->user()->tramiteTiposVisibles();

        return view('pages.admin-solicitudes-index', [
            'solicitudes' => Solicitud::with('tramiteTipo')
                ->whereIn('tramite_tipo_id', (clone $tipos)->select('id'))
                ->filtrar($request->validated())
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'tramiteTipos' => $tipos->orderBy('nombre')->get(),
            'estados' => Solicitud::ESTADOS,
        ]);
    }

    public function show(Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);

        return view('pages.admin-solicitudes-show', [
            'solicitud' => $solicitud->load(['tramiteTipo', 'documentos', 'eventos.usuario', 'verificadoPor', 'asignadoA']),
            'estados' => Solicitud::ESTADOS,
            'responsables' => User::responsablesDe($solicitud->tramite_tipo_id)->orderBy('name')->get(),
        ]);
    }

    public function descargarDocumento(Solicitud $solicitud, SolicitudDocumento $documento)
    {
        Gate::authorize('gestionar', $solicitud);
        abort_unless($documento->solicitud_id === $solicitud->id, 404);

        return $documento->respuesta();
    }

    public function verificar(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $this->flujo->verificarIdentidad($solicitud, $request->user());

        return back()->with('success', 'Identidad verificada correctamente.');
    }

    public function aprobar(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $this->flujo->aprobar($solicitud, $request->user());

        return back()->with('success', 'Solicitud aprobada.');
    }

    public function rechazar(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $data = $request->validate(['justificacion' => 'required|string']);
        $this->flujo->rechazar($solicitud, $request->user(), $data['justificacion']);

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function reasignar(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $data = $request->validate(['asignado_a' => 'required|exists:users,id']);
        $this->flujo->reasignar($solicitud, $request->user(), User::findOrFail($data['asignado_a']));

        return back()->with('success', 'Solicitud reasignada.');
    }

    public function finalizar(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $this->flujo->finalizar($solicitud, $request->user());

        return back()->with('success', 'Solicitud marcada como finalizada.');
    }

    public function subirDocumento(Request $request, Solicitud $solicitud)
    {
        Gate::authorize('gestionar', $solicitud);
        $data = $request->validate(['documento' => 'required|file|max:10240']);
        $this->flujo->publicarDocumento($solicitud, $request->user(), $data['documento']);

        return back()->with('success', 'Documento publicado para el asociado.');
    }
}
