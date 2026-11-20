<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimularAhorroRequest;
use App\Models\ProductoFinanciero;
use App\Services\CalculadoraFinanciera;
use Illuminate\Validation\ValidationException;

class SimuladorAhorroController extends Controller
{
    public function index()
    {
        $productos = $this->obtenerProductos();

        return view(
            'simuladores.ahorro.index',
            compact('productos')
        );
    }


    public function simular(
        SimularAhorroRequest $request,
        CalculadoraFinanciera $calculadora
    ) {
        $datos = $request->validated();

        $producto = ProductoFinanciero::with('parametrosVigentes')
            ->where('codigo', $datos['producto'])
            ->where('tipo', 'ahorro')
            ->where('activo', true)
            ->where('simulable', true)
            ->first();


        if (!$producto) {
            throw ValidationException::withMessages([
                'producto' =>
                    'El producto seleccionado no está disponible para simulación.',
            ]);
        }


        $parametros = $producto->parametrosVigentesPorCodigo();


        $monto =
            (float) $datos['monto'];


        $this->validarMonto(
            $monto,
            $parametros
        );


        if (
            $producto->codigo ===
            'ahorro_ordinario'
        ) {
            $resultado =
                $this->simularAhorroOrdinario(
                    $producto,
                    $datos,
                    $monto,
                    $parametros,
                    $calculadora
                );

        } elseif (
            $producto->codigo ===
            'ahorro_cdat'
        ) {
            $resultado =
                $this->simularCdat(
                    $producto,
                    $datos,
                    $monto,
                    $parametros,
                    $calculadora
                );

        } else {
            throw ValidationException::withMessages([
                'producto' =>
                    'No existe un método de cálculo configurado para este producto.',
            ]);
        }


        session([
            'resultado_ahorro' =>
                $resultado,
        ]);


        return redirect()
            ->route(
                'simuladores.ahorro.resultado'
            );
    }


    public function resultado()
    {
        $resultado =
            session(
                'resultado_ahorro'
            );


        if (!$resultado) {
            return redirect()
                ->route(
                    'simuladores.ahorro'
                );
        }


        return view(
            'simuladores.ahorro.resultado',
            compact('resultado')
        );
    }


    private function simularAhorroOrdinario(
        ProductoFinanciero $producto,
        array $datos,
        float $monto,
        $parametros,
        CalculadoraFinanciera $calculadora
    ): array {
        $tasa =
            $this->valorNumerico(
                $parametros,
                'tasa_interes_ea'
            );


        if ($tasa === null) {
            throw ValidationException::withMessages([
                'producto' =>
                    'No es posible realizar la simulación porque la tasa E.A. de Ahorro Ordinario no está configurada.',
            ]);
        }


        $plazoMeses =
            (int) $datos[
                'plazo_meses'
            ];


        /*
         * Este parámetro corresponde a un límite técnico
         * del simulador y no a un plazo máximo institucional
         * confirmado para el producto.
         */
        $plazoMaximoSimulacion =
            $this->valorNumerico(
                $parametros,
                'plazo_maximo_simulacion'
            );


        if (
            $plazoMaximoSimulacion !== null
            && $plazoMeses >
                $plazoMaximoSimulacion
        ) {
            throw ValidationException::withMessages([
                'plazo_meses' =>
                    'El plazo máximo permitido por el simulador es de '
                    . (int) $plazoMaximoSimulacion
                    . ' meses.',
            ]);
        }


        /*
         * Para la proyección se utiliza una equivalencia
         * técnica de 30 días por mes.
         */
        $dias =
            $plazoMeses * 30;


        $aporteMensual =
            isset(
                $datos[
                    'aporte_mensual'
                ]
            )
                ? (float) $datos[
                    'aporte_mensual'
                ]
                : 0;


        $aportesPermitidos =
            $this->valorBooleano(
                $parametros,
                'aportes_permitidos'
            );


        if (
            $aporteMensual > 0
            && $aportesPermitidos === false
        ) {
            throw ValidationException::withMessages([
                'aporte_mensual' =>
                    'Este producto no tiene habilitados aportes adicionales.',
            ]);
        }


        $aporteMinimo =
            $this->valorNumerico(
                $parametros,
                'aporte_minimo'
            );


        if (
            $aporteMensual > 0
            && $aporteMinimo !== null
            && $aporteMensual < $aporteMinimo
        ) {
            throw ValidationException::withMessages([
                'aporte_mensual' =>
                    'El aporte mensual mínimo permitido es $'
                    . number_format(
                        $aporteMinimo,
                        0,
                        ',',
                        '.'
                    )
                    . '.',
            ]);
        }


        /*
         * Rendimiento del monto inicial.
         */
        $calculoInicial =
            $calculadora
                ->calcularAhorroOrdinario(
                    $monto,
                    $tasa,
                    $dias
                );


        $rendimientoAportes = 0;
        $totalAportes = 0;


        /*
         * Para la proyección de aportes mensuales
         * se asume el aporte al finalizar cada mes.
         *
         * Cada aporte comienza a generar rendimiento
         * durante el tiempo restante de la proyección.
         */
        if ($aporteMensual > 0) {

            for (
                $mes = 1;
                $mes <= $plazoMeses;
                $mes++
            ) {
                $totalAportes +=
                    $aporteMensual;


                $diasRestantes =
                    (
                        $plazoMeses
                        - $mes
                    ) * 30;


                if (
                    $diasRestantes > 0
                ) {
                    $calculoAporte =
                        $calculadora
                            ->calcularAhorroOrdinario(
                                $aporteMensual,
                                $tasa,
                                $diasRestantes
                            );


                    $rendimientoAportes +=
                        $calculoAporte[
                            'rendimiento_exacto'
                        ];
                }
            }
        }


        $rendimientoTotal =
            $calculoInicial[
                'rendimiento_exacto'
            ]
            + $rendimientoAportes;


        $capitalTotal =
            $monto
            + $totalAportes;


        $valorFinal =
            $capitalTotal
            + $rendimientoTotal;


        return [
            'producto_codigo' =>
                $producto->codigo,

            'producto_nombre' =>
                $producto->nombre,

            'tipo_resultado' =>
                'ahorro_ordinario',

            'monto_inicial' =>
                (int) round(
                    $monto,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'plazo' =>
                $plazoMeses,

            'unidad_plazo' =>
                $plazoMeses === 1
                    ? 'mes'
                    : 'meses',

            'dias_proyectados' =>
                $dias,

            'tasa' =>
                $tasa,

            'unidad_tasa' =>
                '% E.A.',

            'aporte_mensual' =>
                (int) round(
                    $aporteMensual,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'total_aportes' =>
                (int) round(
                    $totalAportes,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'capital_total' =>
                (int) round(
                    $capitalTotal,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'rendimiento_estimado' =>
                (int) round(
                    $rendimientoTotal,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'valor_final' =>
                (int) round(
                    $valorFinal,
                    0,
                    PHP_ROUND_HALF_UP
                ),

            'nota_calculo' =>
                'La proyección utiliza una equivalencia de 30 días por mes. Cuando se ingresan aportes mensuales, se proyectan al finalizar cada mes.',
        ];
    }


    private function simularCdat(
        ProductoFinanciero $producto,
        array $datos,
        float $monto,
        $parametros,
        CalculadoraFinanciera $calculadora
    ): array {
        $dias =
            (int) $datos[
                'plazo_dias'
            ];


        $codigoTasa =
            match ($dias) {
                90 =>
                    'tasa_interes_90_dias',

                180 =>
                    'tasa_interes_180_dias',

                360 =>
                    'tasa_interes_360_dias',

                default =>
                    null,
            };


        if (!$codigoTasa) {
            throw ValidationException::withMessages([
                'plazo_dias' =>
                    'El plazo seleccionado no está disponible para CDAT.',
            ]);
        }


        $tasa =
            $this->valorNumerico(
                $parametros,
                $codigoTasa
            );


        if ($tasa === null) {
            throw ValidationException::withMessages([
                'producto' =>
                    'No se encuentra configurada la tasa correspondiente al plazo seleccionado.',
            ]);
        }


        $retencion =
            $this->valorNumerico(
                $parametros,
                'porcentaje_retencion'
            ) ?? 0;


        $calculo =
            $calculadora
                ->calcularCdat(
                    $monto,
                    $tasa,
                    $dias,
                    $retencion
                );


        return [
            'producto_codigo' =>
                $producto->codigo,

            'producto_nombre' =>
                $producto->nombre,

            'tipo_resultado' =>
                'cdat',

            'monto_inicial' =>
                $calculo[
                    'capital'
                ],

            'plazo' =>
                $dias,

            'unidad_plazo' =>
                'días',

            'tasa' =>
                $tasa,

            'unidad_tasa' =>
                '% E.A.',

            'rendimiento_bruto' =>
                $calculo[
                    'rendimiento_bruto'
                ],

            'porcentaje_retencion' =>
                $retencion,

            'retencion' =>
                $calculo[
                    'retencion'
                ],

            'rendimiento_neto' =>
                $calculo[
                    'rendimiento_neto'
                ],

            'valor_final' =>
                $calculo[
                    'valor_final'
                ],
        ];
    }


    private function validarMonto(
        float $monto,
        $parametros
    ): void {
        $minimo =
            $this->valorNumerico(
                $parametros,
                'monto_minimo'
            );


        $maximo =
            $this->valorNumerico(
                $parametros,
                'monto_maximo'
            );


        if (
            $minimo !== null
            && $monto < $minimo
        ) {
            throw ValidationException::withMessages([
                'monto' =>
                    'El monto mínimo permitido es $'
                    . number_format(
                        $minimo,
                        0,
                        ',',
                        '.'
                    )
                    . '.',
            ]);
        }


        if (
            $maximo !== null
            && $monto > $maximo
        ) {
            throw ValidationException::withMessages([
                'monto' =>
                    'El monto máximo permitido es $'
                    . number_format(
                        $maximo,
                        0,
                        ',',
                        '.'
                    )
                    . '.',
            ]);
        }
    }


    private function obtenerProductos()
    {
        $productos =
            ProductoFinanciero::with('parametrosVigentes')
                ->where(
                    'tipo',
                    'ahorro'
                )
                ->where(
                    'activo',
                    true
                )
                ->where(
                    'simulable',
                    true
                )
                ->orderBy(
                    'nombre'
                )
                ->get();


        foreach (
            $productos
            as $producto
        ) {
            $parametros = $producto->parametrosVigentesPorCodigo();

            $producto->aportes_simulador_permitidos =
                $this->valorBooleano($parametros, 'aportes_permitidos') !== false;

            $producto->parametros_simulador = $parametros
                ->map(fn ($parametro) => [
                    'valor' => $parametro->valor,
                    'valor_texto' => $parametro->valor_texto,
                    'tipo_dato' => $parametro->tipo_dato,
                    'unidad' => $parametro->unidad,
                    'origen' => $parametro->origen,
                ])
                ->toArray();
        }


        return $productos;
    }


    private function valorNumerico(
        $parametros,
        string $codigo
    ): ?float {
        if (
            !$parametros->has(
                $codigo
            )
        ) {
            return null;
        }


        $parametro =
            $parametros->get(
                $codigo
            );


        if (
            $parametro->valor === null
        ) {
            return null;
        }


        return (float)
            $parametro->valor;
    }


    private function valorBooleano(
        $parametros,
        string $codigo
    ): ?bool {
        if (
            !$parametros->has(
                $codigo
            )
        ) {
            return null;
        }


        $parametro =
            $parametros->get(
                $codigo
            );


        if (
            $parametro->valor_texto !== null
        ) {
            return filter_var(
                $parametro->valor_texto,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
        }


        if (
            $parametro->valor !== null
        ) {
            return
                (float) $parametro->valor
                !== 0.0;
        }


        return null;
    }
}
