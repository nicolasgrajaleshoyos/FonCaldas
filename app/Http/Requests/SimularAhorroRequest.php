<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimularAhorroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $normalizarMoneda = function ($valor) {
            if ($valor === null || $valor === '') {
                return $valor;
            }

            return preg_replace('/[.,\s]/', '', (string) $valor);
        };

        $this->merge([
            'monto' => $normalizarMoneda(
                $this->input('monto')
            ),

            'aporte_mensual' => $normalizarMoneda(
                $this->input('aporte_mensual')
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'producto' => [
                'required',
                Rule::in([
                    'ahorro_ordinario',
                    'ahorro_cdat',
                ]),
            ],

            'monto' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'plazo_meses' => [
                Rule::requiredIf(
                    $this->input('producto') === 'ahorro_ordinario'
                ),
                'nullable',
                'integer',
                'gt:0',
            ],

            'aporte_mensual' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

            'plazo_dias' => [
                Rule::requiredIf(
                    $this->input('producto') === 'ahorro_cdat'
                ),
                'nullable',
                'integer',
                Rule::in([
                    90,
                    180,
                    360,
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'producto.required' =>
                'Selecciona un producto de ahorro.',

            'producto.in' =>
                'El producto de ahorro seleccionado no es válido.',

            'monto.required' =>
                'Ingresa el monto que deseas simular.',

            'monto.numeric' =>
                'El monto debe ser un valor numérico.',

            'monto.gt' =>
                'El monto debe ser mayor que cero.',

            'plazo_meses.required' =>
                'Ingresa el plazo de la proyección.',

            'plazo_meses.integer' =>
                'El plazo debe expresarse en meses completos.',

            'plazo_meses.gt' =>
                'El plazo debe ser mayor que cero.',

            'aporte_mensual.numeric' =>
                'El aporte mensual debe ser un valor numérico.',

            'aporte_mensual.gte' =>
                'El aporte mensual no puede ser negativo.',

            'plazo_dias.required' =>
                'Selecciona el plazo del CDAT.',

            'plazo_dias.in' =>
                'El plazo del CDAT debe ser de 90, 180 o 360 días.',
        ];
    }
}
