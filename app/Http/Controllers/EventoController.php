<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventoController extends Controller
{
    public function __invoke()
    {
        return view('pages.eventos', [
            'events' => Event::cronologico('desc')->get(),
        ]);
    }
}
