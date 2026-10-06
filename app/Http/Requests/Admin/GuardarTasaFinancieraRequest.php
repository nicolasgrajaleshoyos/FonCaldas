<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GuardarTasaFinancieraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producto_financiero_id' => ['required', 'exists:producto_financieros,id'],
            'valor' => ['required', 'numeric', 'min:0'],
            'unidad' => ['required', 'string', 'max:50'],
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
            'valor.min' => 'La tasa no puede ser negativa.',
            'unidad.required' => 'Debes indicar cómo está expresada la tasa.',
            'fecha_inicio_vigencia.required' => 'Debes indicar la fecha de inicio de vigencia.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha final no puede ser anterior a la fecha inicial.',
        ];
    }
}
