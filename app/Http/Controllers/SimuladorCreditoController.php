<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimularCreditoRequest;
use App\Models\ProductoFinanciero;
use App\Services\CalculadoraFinanciera;
use Illuminate\Validation\ValidationException;

class SimuladorCreditoController extends Controller
{
    public function index()
    {
        $productos = $this->obtenerProductos();

        return view(
            'simuladores.credito.index',
            compact('productos')
        );
    }


    public function simular(
        SimularCreditoRequest $request,
        CalculadoraFinanciera $calculadora
    ) {
        $datos = $request->validated();

        $producto = ProductoFinanciero::with('parametrosVigentes')
            ->where('codigo', $datos['producto'])
            ->where('tipo', 'credito')
            ->where('activo', true)
            ->where('simulable', true)
            ->firstOrFail();


        $parametros = $producto
            ->parametrosVigentes
            ->groupBy('codigo')
            ->map(function ($registros) {
                return $registros->first();
            });


        $monto = (float) $datos['monto'];
        $plazo = (int) $datos['plazo'];


        /*
         * VALIDACIONES GENERALES
         */
        $this->validarMonto(
            $monto,
            $parametros
        );

        $this->validarPlazo(
            $plazo,
            $parametros
        );


        /*
         * VALIDACIONES ESPECÍFICAS
         */
        if (
            in_array(
                $producto->codigo,
                [
                    'credito_vehiculo',
                    'credito_motocicleta',
                ],
                true
            )
        ) {
            $this->validarFinanciacionVehiculoMotocicleta(
                $producto->codigo,
                $datos,
                $monto,
                $parametros
            );
        }


        if (
            $producto->codigo ===
            'credito_avances'
        ) {
            $this->validarAvances(
                $datos,
                $monto,
                $parametros
            );
        }


        /*
         * DETERMINAR TASA
         */
        $tasa = $this->determinarTasa(
            $producto->codigo,
            $datos,
            $parametros
        );


        if ($tasa === null) {

            throw ValidationException::withMessages([
                'producto' =>
                    'Este producto no tiene una tasa vigente configurada para realizar la simulación.',
            ]);
        }


        /*
         * CÁLCULO
         */
        if (
            $producto->codigo ===
            'credito_avances'
        ) {

            $dias =
                $plazo * 30;


            $resultado =
                $calculadora->calcularAvance(
                    $monto,
                    $tasa,
                    $dias
                );


            $resultado['tipo_resultado'] =
                'pago_unico';


            $resultado['plazo_meses'] =
                $plazo;

        } else {

            $resultado =
                $calculadora->calcularCreditoCuotaFija(
                    $monto,
                    $tasa,
                    $plazo
                );


            $resultado['tipo_resultado'] =
                'cuota_fija';
        }


        /*
         * DATOS COMPLEMENTARIOS DEL RESULTADO
         */
        $resultado['producto'] =
            $producto->nombre;


        $resultado['codigo_producto'] =
            $producto->codigo;


        $resultado['tasa_aplicada'] =
            $tasa;


        $resultado['unidad_tasa'] =
            $this->obtenerUnidadTasa(
                $producto->codigo,
                $datos,
                $parametros
            );


        return redirect()
            ->route(
                'simuladores.credito.resultado'
            )
            ->with(
                'resultado',
                $resultado
            );
    }


    public function resultado()
    {
        $resultado =
            session('resultado');


        if (!$resultado) {

            return redirect()
                ->route(
                    'simuladores.credito'
                );
        }


        return view(
            'simuladores.credito.resultado',
            compact('resultado')
        );
    }


    private function obtenerProductos()
    {
        $productos =
            ProductoFinanciero::with([
                'tasaVigente',
                'parametrosVigentes',
            ])
                ->where(
                    'tipo',
                    'credito'
                )
                ->where(
                    'activo',
                    true
                )
                ->orderBy('nombre')
                ->get();


        $productos->each(
            function (
                ProductoFinanciero $producto
            ) {

                $producto->parametros_simulador =
                    $producto
                        ->parametrosVigentes
                        ->groupBy('codigo')
                        ->map(
                            function ($parametros) {

                                $parametro =
                                    $parametros->first();


                                return [

                                    'codigo' =>
                                        $parametro->codigo,

                                    'nombre' =>
                                        $parametro->nombre,

                                    'valor' =>
                                        $parametro->valor !== null
                                            ? (float) $parametro->valor
                                            : null,

                                    'valor_texto' =>
                                        $parametro->valor_texto,

                                    'tipo_dato' =>
                                        $parametro->tipo_dato,

                                    'unidad' =>
                                        $parametro->unidad,

                                    'categoria' =>
                                        $parametro->categoria,

                                    'origen' =>
                                        $parametro->origen,
                                ];
                            }
                        );
            }
        );


        return $productos;
    }


    private function determinarTasa(
        string $codigoProducto,
        array $datos,
        $parametros
    ): ?float {

        if (
            $codigoProducto ===
            'credito_libre_inversion'
        ) {

            $antiguedad =
                (float) $datos[
                    'dato_especifico'
                ];


            if ($antiguedad <= 5) {

                return $this->valorNumerico(
                    $parametros,
                    'tasa_interes_afiliacion_hasta_5'
                );
            }


            if ($antiguedad <= 15) {

                return $this->valorNumerico(
                    $parametros,
                    'tasa_interes_afiliacion_5_15'
                );
            }


            return $this->valorNumerico(
                $parametros,
                'tasa_interes_afiliacion_mas_15'
            );
        }


        return $this->valorNumerico(
            $parametros,
            'tasa_interes'
        );
    }


    private function validarMonto(
        float $monto,
        $parametros
    ): void {

        $montoMinimo =
            $this->valorNumerico(
                $parametros,
                'monto_minimo'
            );


        $montoMaximo =
            $this->valorNumerico(
                $parametros,
                'monto_maximo'
            );


        if (
            $montoMinimo !== null
            && $monto < $montoMinimo
        ) {

            throw ValidationException::withMessages([
                'monto' =>
                    'El monto solicitado es inferior al mínimo configurado.',
            ]);
        }


        if (
            $montoMaximo !== null
            && $monto > $montoMaximo
        ) {

            throw ValidationException::withMessages([
                'monto' =>
                    'El monto solicitado supera el máximo configurado.',
            ]);
        }
    }


    private function validarPlazo(
        int $plazo,
        $parametros
    ): void {

        $plazoMinimo =
            $this->valorNumerico(
                $parametros,
                'plazo_minimo'
            );


        $plazoMaximo =
            $this->valorNumerico(
                $parametros,
                'plazo_maximo'
            );


        if (
            $plazoMinimo !== null
            && $plazo < $plazoMinimo
        ) {

            throw ValidationException::withMessages([
                'plazo' =>
                    'El plazo seleccionado es inferior al mínimo configurado.',
            ]);
        }


        if (
            $plazoMaximo !== null
            && $plazo > $plazoMaximo
        ) {

            throw ValidationException::withMessages([
                'plazo' =>
                    'El plazo seleccionado supera el máximo configurado.',
            ]);
        }
    }


    private function validarFinanciacionVehiculoMotocicleta(
        string $codigoProducto,
        array $datos,
        float $montoSolicitado,
        $parametros
    ): void {

        /*
         * Validamos que realmente hayan llegado
         * los datos necesarios.
         */
        if (
            empty($datos['tipo_bien'])
        ) {

            throw ValidationException::withMessages([
                'tipo_bien' =>
                    'Debes indicar si el vehículo o motocicleta es nuevo o usado.',
            ]);
        }


        if (
            !isset($datos['dato_especifico'])
            || (float) $datos['dato_especifico'] <= 0
        ) {

            throw ValidationException::withMessages([
                'dato_especifico' =>
                    'Debes ingresar el valor comercial o asegurable del bien.',
            ]);
        }


        $tipoBien =
            $datos['tipo_bien'];


        $valorComercial =
            (float) $datos[
                'dato_especifico'
            ];


        /*
         * Determinamos qué porcentaje aplicar.
         */
        $codigoPorcentaje =
            $tipoBien === 'nuevo'
                ? 'porcentaje_financiacion_nuevo'
                : 'porcentaje_financiacion_usado';


        $porcentajeFinanciacion =
            $this->valorNumerico(
                $parametros,
                $codigoPorcentaje
            );


        /*
         * Si no existe porcentaje configurado,
         * no permitimos simular.
         */
        if (
            $porcentajeFinanciacion === null
        ) {

            throw ValidationException::withMessages([
                'producto' =>
                    'No es posible realizar la simulación porque el porcentaje máximo de financiación para bienes '
                    . $tipoBien
                    . ' no está configurado.',
            ]);
        }


        /*
         * Cálculo del máximo financiable.
         *
         * Ejemplo:
         * valor comercial = 20.000.000
         * porcentaje = 80
         *
         * máximo = 16.000.000
         */
        $montoMaximoFinanciable =
            $valorComercial
            *
            (
                $porcentajeFinanciacion
                / 100
            );


        /*
         * Esta es la validación crítica.
         */
        if (
            $montoSolicitado >
            $montoMaximoFinanciable
        ) {

            $nombreBien =
                $codigoProducto ===
                    'credito_motocicleta'
                    ? 'motocicleta'
                    : 'vehículo';


            throw ValidationException::withMessages([
                'monto' =>
                    'El monto solicitado supera el '
                    . $porcentajeFinanciacion
                    . ' % máximo financiable para el '
                    . $nombreBien
                    . '. El valor máximo financiable para esta simulación es $'
                    . number_format(
                        $montoMaximoFinanciable,
                        0,
                        ',',
                        '.'
                    )
                    . '.',
            ]);
        }


        /*
         * Validación adicional para usados.
         */
        if (
            $tipoBien === 'usado'
        ) {

            $antiguedadMaxima =
                $this->valorNumerico(
                    $parametros,
                    'antiguedad_maxima_usado'
                );


            if (
                $antiguedadMaxima === null
            ) {

                throw ValidationException::withMessages([
                    'producto' =>
                        'No es posible realizar la simulación porque la antigüedad máxima permitida para bienes usados no está configurada.',
                ]);
            }


            if (
                !isset(
                    $datos[
                        'antiguedad_bien'
                    ]
                )
            ) {

                throw ValidationException::withMessages([
                    'antiguedad_bien' =>
                        'Debes indicar la antigüedad del vehículo o motocicleta usada.',
                ]);
            }


            $antiguedadBien =
                (int) $datos[
                    'antiguedad_bien'
                ];


            if (
                $antiguedadBien >
                $antiguedadMaxima
            ) {

                throw ValidationException::withMessages([
                    'antiguedad_bien' =>
                        'La antigüedad del vehículo o motocicleta supera el máximo permitido de '
                        . (int) $antiguedadMaxima
                        . ' años.',
                ]);
            }
        }
    }


    private function validarAvances(
        array $datos,
        float $monto,
        $parametros
    ): void {

        $sueldoBasico =
            (float) $datos[
                'dato_especifico'
            ];


        $porcentajeMaximo =
            $this->valorNumerico(
                $parametros,
                'porcentaje_maximo_salario'
            );


        if (
            $porcentajeMaximo === null
        ) {

            throw ValidationException::withMessages([
                'producto' =>
                    'No es posible simular Avances porque el porcentaje máximo del salario no está configurado.',
            ]);
        }


        $montoMaximo =
            $sueldoBasico
            *
            (
                $porcentajeMaximo
                / 100
            );


        if (
            $monto >
            $montoMaximo
        ) {

            throw ValidationException::withMessages([
                'monto' =>
                    'El monto solicitado supera el '
                    . $porcentajeMaximo
                    . ' % del sueldo básico permitido para esta línea.',
            ]);
        }
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


        return (float) $parametro->valor;
    }


    private function obtenerUnidadTasa(
        string $codigoProducto,
        array $datos,
        $parametros
    ): ?string {

        $codigoTasa =
            'tasa_interes';


        if (
            $codigoProducto ===
            'credito_libre_inversion'
        ) {

            $antiguedad =
                (float) $datos[
                    'dato_especifico'
                ];


            if (
                $antiguedad <= 5
            ) {

                $codigoTasa =
                    'tasa_interes_afiliacion_hasta_5';

            } elseif (
                $antiguedad <= 15
            ) {

                $codigoTasa =
                    'tasa_interes_afiliacion_5_15';

            } else {

                $codigoTasa =
                    'tasa_interes_afiliacion_mas_15';
            }
        }


        return $parametros
            ->get(
                $codigoTasa
            )
            ?->unidad;
    }
}
