<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultarSolicitudRequest;
use App\Http\Requests\RadicarSolicitudRequest;
use App\Models\Solicitud;
use App\Models\SolicitudDocumento;
use App\Models\TramiteTipo;
use App\Services\Solicitudes\RadicadorSolicitudes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SolicitudController extends Controller
{
    public function crear()
    {
        return view('pages.solicitudes.crear', [
            'tramiteTipos' => TramiteTipo::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(RadicarSolicitudRequest $request, RadicadorSolicitudes $radicador)
    {
        $solicitud = $radicador->radicar($request->datosSolicitud(), $request->file('documentos', []));

        return redirect()->route('solicitudes.crear')->with('success', "Tu solicitud fue radicada con el código {$solicitud->codigo}. Guárdalo para consultar su estado.");
    }

    public function consultar()
    {
        return view('pages.solicitudes.consultar');
    }

    public function buscar(ConsultarSolicitudRequest $request)
    {
        $solicitud = Solicitud::query()
            ->where('codigo', strtoupper(trim($request->input('codigo'))))
            ->where('asociado_documento', trim($request->input('asociado_documento')))
            ->first();

        if (! $solicitud) {
            return back()->withErrors(['codigo' => 'No encontramos una solicitud con esos datos.'])->withInput();
        }

        return redirect($this->urlEstado($solicitud));
    }

    /** Estado de la solicitud; se llega solo por un enlace firmado que se entrega tras validar código y documento. */
    public function estado(Solicitud $solicitud)
    {
        return view('pages.solicitudes.estado', [
            'solicitud' => $solicitud->load(['tramiteTipo', 'documentos', 'eventos']),
            'exito' => session('exito'),
        ]);
    }

    private function urlEstado(Solicitud $solicitud): string
    {
        return URL::temporarySignedRoute('solicitudes.estado', now()->addMinutes(30), ['solicitud' => $solicitud]);
    }

    /** Las rutas de documentos van firmadas: solo quien consultó con código y documento recibe los enlaces. */
    public function descargarDocumento(SolicitudDocumento $documento)
    {
        // Si la solicitud fue eliminada del panel, sus documentos dejan de ser públicos.
        abort_unless($documento->solicitud !== null, 404);

        return $documento->respuesta();
    }

    public function adjuntarDocumentos(Request $request, Solicitud $solicitud, RadicadorSolicitudes $radicador)
    {
        abort_unless($solicitud->abierta(), 403, 'La solicitud ya fue resuelta y no admite más documentos.');

        $request->validate([
            'documentos' => 'required|array|max:5',
            'documentos.*' => 'file|max:10240|mimes:'.SolicitudDocumento::TIPOS_PERMITIDOS,
        ]);
        $radicador->agregarDocumentos($solicitud, $request->file('documentos'));

        // Enviar y redirigir: si el asociado recarga la página, no se vuelven a subir los mismos archivos.
        return redirect($this->urlEstado($solicitud))->with('exito', 'Documentos adjuntados correctamente.');
    }
}
