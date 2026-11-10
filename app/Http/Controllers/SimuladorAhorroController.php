<?php

namespace App\Http\Controllers;

use App\Models\ProductoFinanciero;

class SimuladorAhorroController extends Controller
{
    public function index()
    {
        $productos = ProductoFinanciero::with('tasaVigente')
            ->where('tipo', 'ahorro')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('simuladores.ahorro.index', compact('productos'));
    }
}
