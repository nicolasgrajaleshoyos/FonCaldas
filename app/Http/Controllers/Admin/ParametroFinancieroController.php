<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarTasaFinancieraRequest;
use App\Http\Requests\Admin\GuardarParametroFinancieroRequest;
use App\Models\ParametroFinanciero;
use App\Models\ProductoFinanciero;

class ParametroFinancieroController extends Controller
{
    public function index()
    {
        $productos = ProductoFinanciero::with([
            'parametros' => function ($consulta) {
                $consulta
                    ->orderBy('categoria')
                    ->orderBy('codigo')
                    ->orderByDesc('fecha_inicio_vigencia');
            },
            'tasaVigente',
        ])
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        $tiposParametro = config('parametros_financieros');
        $parametrosPermitidos = config('parametros_por_producto');

        return view(
            'pages.admin-parametros-financieros',
            compact(
                'productos',
                'tiposParametro',
                'parametrosPermitidos'
            )
        );
    }

    public function guardarParametro(GuardarParametroFinancieroRequest $request)
    {

        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');
        $codigo = $request->input('codigo');
        $definicion = config("parametros_financieros.$codigo");
        $tipoDato = $definicion['tipo_dato'];

        $existeSuperposicion = ParametroFinanciero::where(
            'producto_financiero_id',
            $request->input('producto_financiero_id')
        )
            ->where('codigo', $codigo)
            ->where('activo', true)
            ->where(function ($consulta) use ($fechaInicio) {
                $consulta
                    ->whereNull('fecha_fin_vigencia')
                    ->orWhere('fecha_fin_vigencia', '>=', $fechaInicio);
            })
            ->when(
                $fechaFin,
                function ($consulta) use ($fechaFin) {
                    $consulta->where('fecha_inicio_vigencia', '<=', $fechaFin);
                },
                function ($consulta) {
                    $consulta->whereNotNull('fecha_inicio_vigencia');
                }
            )
            ->exists();

        if ($existeSuperposicion) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' =>
                    'Ya existe un parámetro activo con el mismo código para este producto cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

        if (in_array($tipoDato, ['decimal', 'entero'])) {
            $valor = $request->input('valor');
            $valorTexto = null;
        } else {
            $valor = null;
            $valorTexto = $request->input('valor_texto');
        }

        ParametroFinanciero::create([
            'producto_financiero_id' => $request->input('producto_financiero_id'),
            'nombre' => $definicion['nombre'],
            'codigo' => $codigo,
            'valor' => $valor,
            'valor_texto' => $valorTexto,
            'tipo_dato' => $tipoDato,
            'unidad' => $request->filled('unidad')
                ? $request->input('unidad')
                : $definicion['unidad_sugerida'],
            'categoria' => $definicion['categoria'],
            'origen' => $request->input('origen'),
            'editable_admin' => $request->boolean('editable_admin'),
            'fecha_inicio_vigencia' => $fechaInicio,
            'fecha_fin_vigencia' => $fechaFin,
            'activo' => true,
            'descripcion' => $request->input('descripcion'),
        ]);

        return back()->with(
            'success',
            'El parámetro financiero fue registrado correctamente.'
        );
    }

    public function historial(ProductoFinanciero $producto)
    {
        $parametros = $producto->parametros()
            ->orderBy('categoria')
            ->orderBy('codigo')
            ->orderByDesc('fecha_inicio_vigencia')
            ->get();

        return view(
            'pages.admin-parametros-financieros-historial',
            compact('producto', 'parametros')
        );
    }

    public function guardar(GuardarTasaFinancieraRequest $request)
    {
        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');

        $existeSuperposicion = ParametroFinanciero::where(
            'producto_financiero_id',
            $request->input('producto_financiero_id')
        )
            ->where('codigo', 'tasa_interes')->where('activo', true)
            ->where(function ($consulta) use ($fechaInicio) {
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
                    'fecha_inicio_vigencia' => 'Ya existe una tasa registrada para este producto cuya vigencia se superpone con las fechas indicadas.',
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

    public function editar(ParametroFinanciero $tasa)
    {
        $definicion = config("parametros_financieros.{$tasa->codigo}");

        abort_unless($definicion, 404);

        return view(
            'pages.admin-parametros-financieros-editar',
            compact('tasa', 'definicion')
        );
    }

    public function actualizar(
        GuardarParametroFinancieroRequest $request,
        ParametroFinanciero $tasa
    ) {
        /*
     * Por seguridad, desde la edición no permitimos cambiar
     * el producto ni el tipo de parámetro.
     */
        if (
            (int) $request->input('producto_financiero_id')
            !== (int) $tasa->producto_financiero_id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'producto_financiero_id' =>
                    'No es posible cambiar el producto financiero desde esta edición.',
                ]);
        }

        if ($request->input('codigo') !== $tasa->codigo) {
            return back()
                ->withInput()
                ->withErrors([
                    'codigo' =>
                    'No es posible cambiar el tipo de parámetro desde esta edición.',
                ]);
        }

        $codigo = $tasa->codigo;
        $definicion = config("parametros_financieros.$codigo");

        abort_unless($definicion, 404);

        $tipoDato = $definicion['tipo_dato'];

        $fechaInicio = $request->input('fecha_inicio_vigencia');
        $fechaFin = $request->input('fecha_fin_vigencia');

        $existeSuperposicion = ParametroFinanciero::where(
            'producto_financiero_id',
            $tasa->producto_financiero_id
        )
            ->where('codigo', $codigo)
            ->where('activo', true)
            ->where('id', '!=', $tasa->id)
            ->where(function ($consulta) use ($fechaInicio) {
                $consulta
                    ->whereNull('fecha_fin_vigencia')
                    ->orWhere(
                        'fecha_fin_vigencia',
                        '>=',
                        $fechaInicio
                    );
            })
            ->when(
                $fechaFin,
                function ($consulta) use ($fechaFin) {
                    $consulta->where(
                        'fecha_inicio_vigencia',
                        '<=',
                        $fechaFin
                    );
                },
                function ($consulta) {
                    $consulta->whereNotNull(
                        'fecha_inicio_vigencia'
                    );
                }
            )
            ->exists();

        if ($existeSuperposicion) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' =>
                    'Ya existe otro parámetro activo del mismo tipo cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

        if (in_array($tipoDato, ['decimal', 'entero'])) {
            $valor = $request->input('valor');
            $valorTexto = null;
        } else {
            $valor = null;
            $valorTexto = $request->input('valor_texto');
        }

        $tasa->update([
            'nombre' => $definicion['nombre'],
            'valor' => $valor,
            'valor_texto' => $valorTexto,
            'tipo_dato' => $tipoDato,
            'categoria' => $definicion['categoria'],

            'unidad' => $request->filled('unidad')
                ? $request->input('unidad')
                : $definicion['unidad_sugerida'],

            'origen' => $request->input('origen'),

            'editable_admin' =>
            $request->boolean('editable_admin'),

            'fecha_inicio_vigencia' => $fechaInicio,
            'fecha_fin_vigencia' => $fechaFin,

            'descripcion' =>
            $request->input('descripcion'),
        ]);

        return redirect()
            ->route(
                'admin.parametros-financieros.historial',
                $tasa->producto_financiero_id
            )
            ->with(
                'success',
                'El parámetro financiero fue actualizado correctamente.'
            );
    }

    public function desactivar(ParametroFinanciero $tasa)
    {
        $productoId = $tasa->producto_financiero_id;

        $tasa->update([
            'activo' => false,
        ]);

        return redirect()
            ->route(
                'admin.parametros-financieros.historial',
                $productoId
            )
            ->with(
                'success',
                'El parámetro fue desactivado correctamente y se conserva en el historial.'
            );
    }
}
