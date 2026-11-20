<?php

namespace App\Http\Requests\Admin;

use App\Support\FormatoNumericoFinanciero;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTasaFinancieraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $valor = $this->input('valor');

        if (is_string($valor)) {
            $this->merge(['valor' => FormatoNumericoFinanciero::normalizarDecimal($valor)]);
        }
    }

    public function rules(): array
    {
        return [
            'producto_financiero_id' => ['required', 'exists:producto_financieros,id'],
            'valor' => ['bail', 'required', FormatoNumericoFinanciero::REGLA_DECIMAL, 'numeric', 'min:0'],
            'unidad' => [
                'required',
                'string',
                'max:50',
                Rule::in(config('parametros_financieros.tasa_interes.unidades_permitidas')),
            ],
            'fecha_inicio_vigencia' => ['required', 'date'],
            'fecha_fin_vigencia' => ['nullable', 'date', 'after_or_equal:fecha_inicio_vigencia'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'producto_financiero_id.required' => 'Debes seleccionar un producto financiero.',
            'producto_financiero_id.exists' => 'El producto seleccionado no es válido.',
            'valor.required' => 'Debes ingresar la tasa.',
            'valor.numeric' => 'La tasa debe ser un valor numérico.',
            'valor.regex' => 'Ingresa una tasa válida. Puedes usar punto o coma para los decimales.',
            'valor.min' => 'La tasa no puede ser negativa.',
            'unidad.required' => 'Debes indicar cómo está expresada la tasa.',
            'unidad.in' => 'La tasa general debe expresarse en una unidad mensual (:values).',
            'fecha_inicio_vigencia.required' => 'Debes indicar la fecha de inicio de vigencia.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha final no puede ser anterior a la fecha inicial.',
        ];
    }
}
