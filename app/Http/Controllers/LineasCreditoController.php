<?php

namespace App\Http\Controllers;

use App\Models\ProductoFinanciero;
use Illuminate\Contracts\View\View;

class LineasCreditoController extends Controller
{
    public function __invoke(): View
    {
        $parametrosPorProducto = ProductoFinanciero::query()
            ->with([
                'parametrosVigentes' => fn ($consulta) => $consulta->whereIn('codigo', ['tasa_interes', 'plazo_maximo']),
            ])
            ->whereIn('codigo', ['credito_vehiculo', 'credito_motocicleta'])
            ->where('tipo', 'credito')
            ->where('activo', true)
            ->where('simulable', true)
            ->get()
            ->mapWithKeys(fn (ProductoFinanciero $producto) => [
                $producto->codigo => $producto->parametrosVigentesPorCodigo(),
            ]);

        return view('creditos.lineas', compact('parametrosPorProducto'));
    }
}
