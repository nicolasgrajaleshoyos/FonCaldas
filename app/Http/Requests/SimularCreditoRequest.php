<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimularCreditoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $producto = $this->input('producto');
        $tipoBien = $this->input('tipo_bien');


        $requiereDatoEspecifico = in_array(
            $producto,
            [
                'credito_avances',
                'credito_libre_inversion',
                'credito_compra_cartera',
                'credito_vehiculo',
                'credito_motocicleta',
                'credito_tecnologia',
                'credito_turismo',
            ],
            true
        );


        $requiereTipoBien = in_array(
            $producto,
            [
                'credito_vehiculo',
                'credito_motocicleta',
            ],
            true
        );


        $requiereAntiguedadBien =
            $requiereTipoBien &&
            $tipoBien === 'usado';


        return [

            'producto' => [
                'required',
                'string',
                Rule::exists(
                    'producto_financieros',
                    'codigo'
                )
                    ->where('tipo', 'credito')
                    ->where('activo', true)
                    ->where('simulable', true),
            ],


            'monto' => [
                'required',
                'numeric',
                'gt:0',
            ],


            'plazo' => [
                'required',
                'integer',
                'gt:0',
            ],


            'dato_especifico' => [
                Rule::requiredIf(
                    $requiereDatoEspecifico
                ),
                'nullable',
                'numeric',
                'gt:0',
            ],


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
            ],
        ];
    }


    public function messages(): array
    {
        return [

            'producto.required' =>
                'Debes seleccionar una línea de crédito.',

            'producto.exists' =>
                'La línea seleccionada no está disponible para simulación.',


            'monto.required' =>
                'Debes ingresar el monto solicitado.',

            'monto.numeric' =>
                'El monto solicitado debe ser un valor numérico.',

            'monto.gt' =>
                'El monto solicitado debe ser mayor que cero.',


            'plazo.required' =>
                'Debes ingresar el plazo.',

            'plazo.integer' =>
                'El plazo debe expresarse en meses.',

            'plazo.gt' =>
                'El plazo debe ser mayor que cero.',


            'dato_especifico.required' =>
                'Debes completar la información adicional requerida para esta línea.',

            'dato_especifico.numeric' =>
                'La información adicional debe ser un valor numérico.',

            'dato_especifico.gt' =>
                'La información adicional debe ser mayor que cero.',


            'tipo_bien.required' =>
                'Debes indicar si el vehículo o motocicleta es nuevo o usado.',

            'tipo_bien.in' =>
                'Selecciona si el vehículo o motocicleta es nuevo o usado.',


            'antiguedad_bien.required' =>
                'Debes indicar la antigüedad del vehículo o motocicleta usada.',

            'antiguedad_bien.integer' =>
                'La antigüedad debe expresarse en años completos.',

            'antiguedad_bien.min' =>
                'La antigüedad no puede ser negativa.',
        ];
    }
}
