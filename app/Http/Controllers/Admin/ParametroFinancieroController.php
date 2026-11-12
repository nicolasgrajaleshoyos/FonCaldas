<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarTasaFinancieraRequest;
use App\Models\ParametroFinanciero;
use App\Models\ProductoFinanciero;
use Illuminate\Http\RedirectResponse;

class ParametroFinancieroController extends Controller
{
    public function index()
    {
        $productos = ProductoFinanciero::with([
            'parametros' => function ($consulta) {
                $consulta
                    ->where('codigo', 'tasa_interes')
                    ->orderByDesc('fecha_inicio_vigencia');
            },
            'tasaVigente',
        ])
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('pages.admin-parametros-financieros', compact('productos'));
    }

    public function historial(ProductoFinanciero $producto)
    {
        $tasas = $producto->parametros()
            ->where('codigo', 'tasa_interes')
            ->orderByDesc('fecha_inicio_vigencia')
            ->get();

        return view('pages.admin-parametros-financieros-historial', compact('producto', 'tasas'));
    }

    public function guardar(GuardarTasaFinancieraRequest $request)
    {
        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');

        if ($error = $this->errorPorSuperposicion($request, 'Ya existe una tasa registrada para este producto cuya vigencia se superpone con las fechas indicadas.')) {
            return $error;
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

    public function editar(ParametroFinanciero $tasa)
    {
        abort_unless($tasa->codigo === 'tasa_interes', 404);

        $productos = ProductoFinanciero::where('activo', true)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view(
            'pages.admin-parametros-financieros-editar',
            compact('tasa', 'productos')
        );
    }

    public function actualizar(GuardarTasaFinancieraRequest $request, ParametroFinanciero $tasa)
    {
        abort_unless($tasa->codigo === 'tasa_interes', 404);

        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');

        if ($error = $this->errorPorSuperposicion($request, 'Ya existe otra tasa activa para este producto cuya vigencia se superpone con las fechas indicadas.', $tasa)) {
            return $error;
        }

        $tasa->update([
            'producto_financiero_id' => $request->input('producto_financiero_id'),
            'valor' => $request->input('valor'),
            'unidad' => $request->input('unidad'),
            'fecha_inicio_vigencia' => $fechaInicio,
            'fecha_fin_vigencia' => $fechaFin,
            'descripcion' => $request->input('descripcion'),
        ]);

        return redirect()
            ->route('admin.parametros-financieros.historial', $tasa->producto_financiero_id)
            ->with('success', 'La tasa fue actualizada correctamente.');
    }

    public function desactivar(ParametroFinanciero $tasa)
    {
        abort_unless($tasa->codigo === 'tasa_interes', 404);

        $productoId = $tasa->producto_financiero_id;

        $tasa->update([
            'activo' => false,
        ]);

        return redirect()
            ->route('admin.parametros-financieros.historial', $productoId)
            ->with('success', 'La tasa fue desactivada correctamente y se conserva en el historial.');
    }

    /**
     * Comprobación compartida por el registro y la edición: si la vigencia se cruza con otra tasa activa del producto,
     * devuelve la redirección con el error; si no, null. Al editar se excluye la propia tasa.
     */
    private function errorPorSuperposicion(GuardarTasaFinancieraRequest $request, string $mensaje, ?ParametroFinanciero $tasa = null): ?RedirectResponse
    {
        $seSuperpone = ParametroFinanciero::haySuperposicion(
            (int) $request->input('producto_financiero_id'),
            $request->input('fecha_inicio_vigencia'),
            $request->input('fecha_fin_vigencia'),
            excluirId: $tasa?->id,
        );

        return $seSuperpone
            ? back()->withInput()->withErrors(['fecha_inicio_vigencia' => $mensaje])
            : null;
    }
}
