<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.home', [
            'events' => Event::cronologico()->limit(3)->get(),
        ]);
    }
}
