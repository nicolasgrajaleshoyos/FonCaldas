<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarTasaFinancieraRequest;
use App\Models\ParametroFinanciero;
use App\Models\ProductoFinanciero;

class ParametroFinancieroController extends Controller
{
    public function index()
    {
        $productos = ProductoFinanciero::with([
            'parametros' => function ($consulta) {
                $consulta
                    ->where('codigo', 'tasa_interes')
                    ->orderByDesc('fecha_inicio_vigencia');
            }
        ])
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('pages.admin-parametros-financieros', [
            'productos' => $productos,
        ]);
    }

    public function guardar(GuardarTasaFinancieraRequest $request)
    {
        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');

        $existeSuperposicion = ParametroFinanciero::where(
            'producto_financiero_id',
            $request->input('producto_financiero_id')
        )
            ->where('codigo', 'tasa_interes')
            ->where(function ($consulta) use ($fechaInicio, $fechaFin) {
                $consulta
                    ->whereNull('fecha_fin_vigencia')
                    ->orWhere('fecha_fin_vigencia', '>=', $fechaInicio);
            })
            ->when($fechaFin, function ($consulta) use ($fechaFin) {
                $consulta->where('fecha_inicio_vigencia', '<=', $fechaFin);
            }, function ($consulta) {
                $consulta->whereNotNull('fecha_inicio_vigencia');
            })
            ->exists();

        if ($existeSuperposicion) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' =>
                    'Ya existe una tasa registrada para este producto cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

        ParametroFinanciero::create([
            'producto_financiero_id' => $request->input('producto_financiero_id'),
            'nombre' => 'Tasa de interés',
            'codigo' => 'tasa_interes',
            'valor' => $request->input('valor'),
            'unidad' => $request->input('unidad'),
            'fecha_inicio_vigencia' => $fechaInicio,
            'fecha_fin_vigencia' => $fechaFin,
            'activo' => true,
            'descripcion' => $request->input('descripcion'),
        ]);

        return back()->with(
            'success',
            'La tasa fue registrada correctamente.'
        );
    }
}
