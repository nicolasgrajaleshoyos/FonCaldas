<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarEventoRequest;
use App\Models\Event;
use App\Services\Transparencia\GestorEventos;

class EventoController extends Controller
{
    public function index()
    {
        return view('pages.admin-events', ['events' => Event::cronologico()->get()]);
    }

    public function store(GuardarEventoRequest $request, GestorEventos $gestor)
    {
        $gestor->crear($request->validated(), $request->file('media'));

        return back()->with('success', 'Evento creado correctamente.');
    }

    public function destroy(Event $event, GestorEventos $gestor)
    {
        $gestor->eliminar($event);

        return back()->with('success', 'Evento eliminado correctamente.');
    }
}
