<?php

namespace App\Http\Controllers;

use App\Models\ProductoFinanciero;

class SimuladorCreditoController extends Controller
{
    public function index()
    {
        $productos = ProductoFinanciero::with('tasaVigente')
            ->where('tipo', 'credito')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('simuladores.credito.index', compact('productos'));
    }
}
