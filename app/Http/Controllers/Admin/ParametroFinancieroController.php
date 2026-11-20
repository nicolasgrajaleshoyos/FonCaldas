<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarTasaFinancieraRequest;
use App\Http\Requests\Admin\GuardarParametroFinancieroRequest;
use App\Models\ParametroFinanciero;
use App\Models\ProductoFinanciero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

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
        $codigo = $request->input('codigo');
        $definicion = config("parametros_financieros.$codigo");
        $tipoDato = $definicion['tipo_dato'];

        if (in_array($tipoDato, ['decimal', 'entero'])) {
            $valor = $request->input('valor');
            $valorTexto = null;
        } else {
            $valor = null;
            $valorTexto = $request->input('valor_texto');
        }

        $guardado = $this->guardarVigencia($request->integer('producto_financiero_id'), $codigo, [
            'nombre' => $definicion['nombre'],
            'valor' => $valor,
            'valor_texto' => $valorTexto,
            'tipo_dato' => $tipoDato,
            'unidad' => $request->filled('unidad')
                ? $request->input('unidad')
                : $definicion['unidad_sugerida'],
            'categoria' => $definicion['categoria'],
            'origen' => $request->input('origen'),
            'editable_admin' => $request->boolean('editable_admin'),
            'fecha_inicio_vigencia' => $request->date('fecha_inicio_vigencia')->toDateString(),
            'fecha_fin_vigencia' => $request->date('fecha_fin_vigencia')?->toDateString(),
            'descripcion' => $request->input('descripcion'),
        ]);

        if (! $guardado) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' =>
                    'Ya existe un parámetro activo con el mismo código para este producto cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

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
        $guardado = $this->guardarVigencia($request->integer('producto_financiero_id'), 'tasa_interes', [
            'nombre' => 'Tasa de interés',
            'valor' => $request->input('valor'),
            'unidad' => $request->input('unidad'),
            'fecha_inicio_vigencia' => $request->date('fecha_inicio_vigencia')->toDateString(),
            'fecha_fin_vigencia' => $request->date('fecha_fin_vigencia')?->toDateString(),
            'descripcion' => $request->input('descripcion'),
        ]);

        if (! $guardado) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' => 'Ya existe una tasa registrada para este producto cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

        return back()->with(
            'success',
            'La tasa fue registrada correctamente.'
        );
    }

    public function editar(ParametroFinanciero $tasa)
    {
        abort_if($tasa->esSoloLecturaEnPanel(), 403);

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
        abort_if($tasa->esSoloLecturaEnPanel(), 403);

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

        if (in_array($tipoDato, ['decimal', 'entero'])) {
            $valor = $request->input('valor');
            $valorTexto = null;
        } else {
            $valor = null;
            $valorTexto = $request->input('valor_texto');
        }

        $guardado = $this->guardarVigencia((int) $tasa->producto_financiero_id, $codigo, [
            'nombre' => $definicion['nombre'],
            'valor' => $valor,
            'valor_texto' => $valorTexto,
            'tipo_dato' => $tipoDato,
            'categoria' => $definicion['categoria'],
            'unidad' => $request->filled('unidad')
                ? $request->input('unidad')
                : $definicion['unidad_sugerida'],
            'origen' => $request->input('origen'),
            'editable_admin' => $request->boolean('editable_admin'),
            'fecha_inicio_vigencia' => $request->date('fecha_inicio_vigencia')->toDateString(),
            'fecha_fin_vigencia' => $request->date('fecha_fin_vigencia')?->toDateString(),
            'descripcion' => $request->input('descripcion'),
        ], $tasa->id);

        if (! $guardado) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio_vigencia' =>
                    'Ya existe otro parámetro activo del mismo tipo cuya vigencia se superpone con las fechas indicadas.',
                ]);
        }

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
        abort_if($tasa->esSoloLecturaEnPanel(), 403);

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

    public function eliminar(ParametroFinanciero $tasa)
    {
        abort_if($tasa->esSoloLecturaEnPanel(), 403);

        $productoId = $tasa->producto_financiero_id;

        $tasa->delete();

        return redirect()
            ->route(
                'admin.parametros-financieros.historial',
                $productoId
            )
            ->with(
                'success',
                'El parámetro fue eliminado definitivamente.'
            );
    }

    private function guardarVigencia(int $productoId, string $codigo, array $atributos, ?int $parametroId = null): bool
    {
        $conexion = (new ParametroFinanciero())->getConnection();

        return $conexion->transaction(function () use ($productoId, $codigo, $atributos, $parametroId): bool {
            $this->bloquearProducto($productoId);

            $parametro = $parametroId === null
                ? new ParametroFinanciero([
                    'producto_financiero_id' => $productoId,
                    'codigo' => $codigo,
                    'activo' => true,
                ])
                : ParametroFinanciero::where('producto_financiero_id', $productoId)
                    ->where('codigo', $codigo)
                    ->lockForUpdate()
                    ->findOrFail($parametroId);

            $fechaInicio = $atributos['fecha_inicio_vigencia'];
            $fechaFin = $atributos['fecha_fin_vigencia'];
            $existeSuperposicion = $this->parametrosActivosEnIntervalo($productoId, $fechaInicio, $fechaFin)
                ->where('codigo', $codigo)
                ->when($parametroId !== null, fn ($consulta) => $consulta->where('id', '!=', $parametroId))
                ->exists();

            if ($existeSuperposicion) {
                return false;
            }

            if ($parametro->activo) {
                $this->validarLimitesCompatibles($productoId, $codigo, $atributos);
            }

            $parametro->fill($atributos)->save();

            return true;
        }, attempts: 3);
    }

    private function bloquearProducto(int $productoId): void
    {
        $consulta = ProductoFinanciero::whereKey($productoId);
        $conexion = $consulta->getModel()->getConnection();

        if ($conexion->getDriverName() === 'sqlite') {
            // SQLite obtiene el bloqueo de escritura con la primera actualización.
            $filas = $consulta->toBase()->update(['activo' => $conexion->raw('activo')]);
            abort_unless($filas > 0, 404);

            return;
        }

        $consulta->lockForUpdate()->firstOrFail();
    }

    private function parametrosActivosEnIntervalo(int $productoId, string $fechaInicio, ?string $fechaFin): Builder
    {
        return ParametroFinanciero::where('producto_financiero_id', $productoId)
            ->where('activo', true)
            ->where(fn ($consulta) => $consulta
                ->whereNull('fecha_fin_vigencia')
                ->orWhereDate('fecha_fin_vigencia', '>=', $fechaInicio))
            ->when(
                $fechaFin !== null,
                fn ($consulta) => $consulta->whereDate('fecha_inicio_vigencia', '<=', $fechaFin),
                fn ($consulta) => $consulta->whereNotNull('fecha_inicio_vigencia')
            );
    }

    private function validarLimitesCompatibles(int $productoId, string $codigo, array $atributos): void
    {
        $tipoLimite = match ($codigo) {
            'monto_minimo', 'monto_maximo' => 'monto',
            'plazo_minimo', 'plazo_maximo' => 'plazo',
            default => null,
        };
        if ($tipoLimite === null) {
            return;
        }

        $esMinimo = $codigo === "{$tipoLimite}_minimo";
        $codigoOpuesto = $esMinimo ? "{$tipoLimite}_maximo" : "{$tipoLimite}_minimo";
        $existeLimiteIncompatible = $this->parametrosActivosEnIntervalo(
            $productoId,
            $atributos['fecha_inicio_vigencia'],
            $atributos['fecha_fin_vigencia']
        )
            ->where('codigo', $codigoOpuesto)
            ->where('valor', $esMinimo ? '<' : '>', $atributos['valor'])
            ->exists();

        if ($existeLimiteIncompatible) {
            throw ValidationException::withMessages([
                'valor' => $esMinimo
                    ? "El $tipoLimite mínimo supera el $tipoLimite máximo del producto durante una vigencia compartida."
                    : "El $tipoLimite máximo queda por debajo del $tipoLimite mínimo del producto durante una vigencia compartida.",
            ]);
        }
    }
}
