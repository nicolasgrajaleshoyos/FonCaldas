<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\LeeParametrosSimulador;
use App\Http\Requests\SimularCreditoRequest;
use App\Models\ProductoFinanciero;
use App\Services\CalculadoraFinanciera;
use Illuminate\Validation\ValidationException;

class SimuladorCreditoController extends Controller
{
    use LeeParametrosSimulador;

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


        $producto = ProductoFinanciero::with(
            'parametrosVigentes'
        )
            ->where(
                'codigo',
                $datos['producto']
            )
            ->where(
                'tipo',
                'credito'
            )
            ->where(
                'activo',
                true
            )
            ->where(
                'simulable',
                true
            )
            ->firstOrFail();


        $parametros = $producto->parametrosVigentesPorCodigo();


        $monto =
            (float) $datos['monto'];

        $plazo =
            (int) $datos['plazo'];


        /*
         * VALIDACIONES GENERALES
         */
        $this->validarLimitesMonto(
            $parametros,
            $monto,
            'El monto solicitado es inferior al mínimo configurado de ',
            'El monto solicitado supera el máximo configurado de '
        );


        $this->validarPlazo(
            $plazo,
            $parametros
        );


        /*
         * VALIDACIONES ESPECÍFICAS
         */
        $this->validarReglasEspecificas(
            $producto->codigo,
            $datos,
            $monto,
            $parametros
        );


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


        $request->session()->put('resultado_credito', $resultado);

        return redirect()->route('simuladores.credito.resultado');
    }


    public function resultado()
    {
        $resultado =
            session('resultado_credito');


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
            ProductoFinanciero::with('parametrosVigentes')
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
                        ->parametrosVigentesPorCodigo()
                        ->map(fn ($parametro) => [
                            'codigo' => $parametro->codigo,
                            'nombre' => $parametro->nombre,
                            'valor' => $parametro->valor !== null ? (float) $parametro->valor : null,
                            'valor_texto' => $parametro->valor_texto,
                            'tipo_dato' => $parametro->tipo_dato,
                            'unidad' => $parametro->unidad,
                            'categoria' => $parametro->categoria,
                            'origen' => $parametro->origen,
                        ]);
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


            if (
                $antiguedad <= 5
            ) {

                return $this->valorNumerico(
                    $parametros,
                    'tasa_interes_afiliacion_hasta_5'
                );
            }


            if (
                $antiguedad <= 15
            ) {

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
                    'El plazo seleccionado es inferior al mínimo configurado de '
                    . (int) $plazoMinimo
                    . ' meses.',
            ]);
        }


        if (
            $plazoMaximo !== null
            && $plazo > $plazoMaximo
        ) {

            throw ValidationException::withMessages([
                'plazo' =>
                    'El plazo seleccionado supera el máximo configurado de '
                    . (int) $plazoMaximo
                    . ' meses.',
            ]);
        }
    }


    private function validarReglasEspecificas(
        string $codigoProducto,
        array $datos,
        float $monto,
        $parametros
    ): void {

        /*
         * AVANCES
         */
        if (
            $codigoProducto ===
            'credito_avances'
        ) {

            $this->validarAvances(
                $datos,
                $monto,
                $parametros
            );

            return;
        }


        /*
         * VEHÍCULO Y MOTOCICLETA
         */
        if (
            in_array(
                $codigoProducto,
                [
                    'credito_vehiculo',
                    'credito_motocicleta',
                ],
                true
            )
        ) {

            $this->validarFinanciacionVehiculoMotocicleta(
                $codigoProducto,
                $datos,
                $monto,
                $parametros
            );

            return;
        }


        /*
         * TECNOLOGÍA Y TURISMO
         *
         * El monto solicitado no puede
         * superar el valor de la cotización.
         */
        if (
            in_array(
                $codigoProducto,
                [
                    'credito_tecnologia',
                    'credito_turismo',
                ],
                true
            )
        ) {

            $this->validarMontoContraDatoEspecifico(
                $datos,
                $monto,
                'El monto solicitado no puede superar el valor de la cotización. '
            );

            return;
        }


        /*
         * COMPRA DE CARTERA
         *
         * El monto solicitado no puede
         * superar el saldo informado.
         */
        if (
            $codigoProducto ===
            'credito_compra_cartera'
        ) {

            $this->validarMontoContraDatoEspecifico(
                $datos,
                $monto,
                'El monto solicitado no puede superar el saldo de la obligación informado. '
            );
        }
    }


    private function validarFinanciacionVehiculoMotocicleta(
        string $codigoProducto,
        array $datos,
        float $montoSolicitado,
        $parametros
    ): void {

        // SimularCreditoRequest ya exige tipo_bien, dato_especifico > 0 y, si es usado, antiguedad_bien.
        $tipoBien =
            $datos[
                'tipo_bien'
            ];


        $valorComercial =
            (float) $datos[
                'dato_especifico'
            ];


        $codigoPorcentaje =
            $tipoBien ===
                'nuevo'
                ? 'porcentaje_financiacion_nuevo'
                : 'porcentaje_financiacion_usado';


        $porcentajeFinanciacion =
            $this->valorNumerico(
                $parametros,
                $codigoPorcentaje
            );


        if (
            $porcentajeFinanciacion ===
            null
        ) {

            throw ValidationException::withMessages([
                'producto' =>
                    'No es posible realizar la simulación porque el porcentaje máximo de financiación para bienes '
                    . $tipoBien
                    . ' no está configurado.',
            ]);
        }


        $montoMaximoFinanciable =
            $valorComercial
            *
            (
                $porcentajeFinanciacion
                / 100
            );


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


        if (
            $tipoBien ===
            'usado'
        ) {

            $antiguedadMaxima =
                $this->valorNumerico(
                    $parametros,
                    'antiguedad_maxima_usado'
                );


            if (
                $antiguedadMaxima ===
                null
            ) {

                throw ValidationException::withMessages([
                    'producto' =>
                        'No es posible realizar la simulación porque la antigüedad máxima permitida para bienes usados no está configurada.',
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
            $porcentajeMaximo ===
            null
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
                    . ' % del sueldo básico permitido para esta línea. '
                    . 'El valor máximo permitido para esta simulación es $'
                    . number_format(
                        $montoMaximo,
                        0,
                        ',',
                        '.'
                    )
                    . '.',
            ]);
        }
    }


    /**
     * Tecnología/turismo (cotización) y compra de cartera (saldo): el monto no puede superar
     * el valor informado en dato_especifico, que SimularCreditoRequest ya exige mayor que cero.
     */
    private function validarMontoContraDatoEspecifico(array $datos, float $monto, string $texto): void
    {
        $tope = (float) $datos['dato_especifico'];

        if ($monto > $tope) {
            throw ValidationException::withMessages([
                'monto' => $texto.'El valor máximo permitido para esta simulación es '.$this->formatoPesos($tope).'.',
            ]);
        }
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
