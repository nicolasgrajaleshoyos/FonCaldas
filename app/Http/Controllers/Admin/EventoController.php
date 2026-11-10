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
        return view('pages.admin-events', ['events' => Event::cronologico()->paginate(20)]);
    }

    public function store(GuardarEventoRequest $request, GestorEventos $gestor)
    {
        $gestor->crear($request->validated(), $request->file('media'));

        return back()->with('success', 'Evento creado correctamente.');
    }

    public function edit(Event $event)
    {
        return view('pages.admin-events-edit', ['event' => $event]);
    }

    public function update(GuardarEventoRequest $request, Event $event, GestorEventos $gestor)
    {
        $gestor->actualizar($event, $request->validated(), $request->file('media'));

        return redirect()->route('admin.events')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event, GestorEventos $gestor)
    {
        $gestor->eliminar($event);

        return back()->with('success', 'Evento eliminado correctamente.');
    }
}
