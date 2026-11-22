<?php

namespace App\Http\Requests;

use App\Support\FormatoNumericoFinanciero;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimularCreditoRequest extends FormRequest
{
    private const PRODUCTOS_CON_DATO_MONETARIO = [
        'credito_avances',
        'credito_compra_cartera',
        'credito_vehiculo',
        'credito_motocicleta',
        'credito_tecnologia',
        'credito_turismo',
    ];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $producto = $this->input('producto');

        $datos = [
            'monto' => $this->normalizarMoneda(
                $this->input('monto')
            ),
        ];

        if (in_array($producto, self::PRODUCTOS_CON_DATO_MONETARIO, true)) {
            $datos['dato_especifico'] =
                $this->normalizarMoneda(
                    $this->input(
                        'dato_especifico'
                    )
                );
        }

        $this->merge($datos);
    }

    public function rules(): array
    {
        $producto =
            $this->input('producto');

        $tipoBien =
            $this->input('tipo_bien');

        $datoEsMonetario = in_array($producto, self::PRODUCTOS_CON_DATO_MONETARIO, true);
        $requiereDatoEspecifico = $datoEsMonetario || $producto === 'credito_libre_inversion';
        $reglasDatoEspecifico = ['bail', Rule::requiredIf($requiereDatoEspecifico), 'nullable'];
        if ($datoEsMonetario) {
            $reglasDatoEspecifico[] = FormatoNumericoFinanciero::REGLA_ENTERO;
        }
        $reglasDatoEspecifico[] = 'numeric';
        $reglasDatoEspecifico[] = 'gt:0';
        $reglasDatoEspecifico[] = 'lte:'.FormatoNumericoFinanciero::MONTO_MAXIMO;

        $requiereTipoBien =
            in_array(
                $producto,
                [
                    'credito_vehiculo',
                    'credito_motocicleta',
                ],
                true
            );

        $requiereAntiguedadBien =
            $requiereTipoBien
            && $tipoBien === 'usado';

        return [

            'producto' => [
                'required',
                'string',

                Rule::exists(
                    'producto_financieros',
                    'codigo'
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
                    ),
            ],

            /*
             * $1.000 se utiliza únicamente como
             * mínimo técnico del simulador.
             *
             * Si el producto tiene un monto mínimo
             * institucional configurado, el
             * controlador aplica además ese límite.
             */
            'monto' => [
                'bail',
                'required',
                FormatoNumericoFinanciero::REGLA_ENTERO,
                'numeric',
                'gte:1000',
                'lte:'.FormatoNumericoFinanciero::MONTO_MAXIMO,
            ],

            'plazo' => [
                'required',
                'integer',
                'gt:0',
                'max:'.FormatoNumericoFinanciero::PLAZO_MAXIMO_MESES,
            ],

            'dato_especifico' => $reglasDatoEspecifico,

            'tipo_bien' => [
                Rule::requiredIf(
                    $requiereTipoBien
                ),

                'nullable',

                Rule::in([
                    'nuevo',
                    'usado',
                ]),
            ],

            'antiguedad_bien' => [
                Rule::requiredIf(
                    $requiereAntiguedadBien
                ),

                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'producto.required' => 'Debes seleccionar una línea de crédito.',

            'producto.exists' => 'La línea seleccionada no está disponible para simulación.',

            'monto.required' => 'Debes ingresar el monto solicitado.',

            'monto.numeric' => 'El monto solicitado debe ser un valor numérico.',

            'monto.regex' => FormatoNumericoFinanciero::MENSAJE_PESOS,

            'monto.gte' => 'Para realizar una simulación, el monto solicitado debe ser de al menos $1.000.',

            'monto.lte' => 'El monto solicitado supera el máximo que se puede simular.',

            'plazo.required' => 'Debes ingresar el plazo.',

            'plazo.integer' => 'El plazo debe expresarse en meses completos.',

            'plazo.gt' => 'El plazo debe ser mayor que cero.',

            'plazo.max' => 'El plazo no puede superar '.FormatoNumericoFinanciero::PLAZO_MAXIMO_MESES.' meses.',

            'dato_especifico.required' => 'Debes completar la información adicional requerida para esta línea.',

            'dato_especifico.numeric' => 'La información adicional debe ser un valor numérico.',

            'dato_especifico.regex' => FormatoNumericoFinanciero::MENSAJE_PESOS,

            'dato_especifico.gt' => 'La información adicional debe ser mayor que cero.',

            'dato_especifico.lte' => 'La información adicional supera el máximo permitido.',

            'tipo_bien.required' => 'Debes indicar si el vehículo o motocicleta es nuevo o usado.',

            'tipo_bien.in' => 'Selecciona si el vehículo o motocicleta es nuevo o usado.',

            'antiguedad_bien.required' => 'Debes indicar la antigüedad del vehículo o motocicleta usada.',

            'antiguedad_bien.integer' => 'La antigüedad debe expresarse en años completos.',

            'antiguedad_bien.min' => 'La antigüedad no puede ser negativa.',

            'antiguedad_bien.max' => 'La antigüedad indicada no es válida.',
        ];
    }

    private function normalizarMoneda(mixed $valor): mixed
    {
        return is_string($valor) ? FormatoNumericoFinanciero::normalizarEnteroAgrupado($valor) : $valor;
    }
}
