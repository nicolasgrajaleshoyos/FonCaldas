<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarParametroFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producto_financiero_id' => [
                'required',
                'exists:producto_financieros,id',
            ],

            'codigo' => [
                'required',
                Rule::in(array_keys(config('parametros_financieros', []))),
            ],

            'valor' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'valor_texto' => [
                'nullable',
                'string',
                'max:255',
            ],

            'unidad' => [
                'nullable',
                'string',
                'max:50',
            ],

            'origen' => [
                'required',
                'in:confirmado,referencia,pendiente',
            ],

            'editable_admin' => [
                'nullable',
                'boolean',
            ],

            'fecha_inicio_vigencia' => [
                'required',
                'date',
            ],

            'fecha_fin_vigencia' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio_vigencia',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $codigo = $this->input('codigo');
            $definicion = config("parametros_financieros.$codigo");

            $productoId = $this->input('producto_financiero_id');

            $producto = \App\Models\ProductoFinanciero::find($productoId);

            if ($producto) {
                $permitidos = config(
                    "parametros_por_producto.{$producto->codigo}",
                    []
                );

                if (!in_array($codigo, $permitidos, true)) {
                    $validator->errors()->add(
                        'codigo',
                        'El parámetro seleccionado no corresponde al producto financiero.'
                    );

                    return;
                }
            }

            if (!$definicion) {
                return;
            }

            $tipoDato = $definicion['tipo_dato'];

            if (
                in_array($tipoDato, ['decimal', 'entero']) &&
                !$this->filled('valor')
            ) {
                $validator->errors()->add(
                    'valor',
                    'Debes ingresar un valor numérico para este parámetro.'
                );
            }

            if (
                in_array($tipoDato, ['texto', 'booleano']) &&
                !$this->filled('valor_texto')
            ) {
                $validator->errors()->add(
                    'valor_texto',
                    'Debes seleccionar o ingresar un valor para este parámetro.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'producto_financiero_id.required' =>
            'Debes seleccionar un producto financiero.',

            'producto_financiero_id.exists' =>
            'El producto seleccionado no es válido.',

            'codigo.required' =>
            'Debes seleccionar el parámetro que deseas configurar.',

            'codigo.in' =>
            'El parámetro seleccionado no es válido.',

            'valor.numeric' =>
            'El valor numérico debe ser válido.',

            'valor.min' =>
            'El valor numérico no puede ser negativo.',

            'origen.required' =>
            'Debes indicar el origen del parámetro.',

            'fecha_inicio_vigencia.required' =>
            'Debes indicar la fecha de inicio de vigencia.',

            'fecha_fin_vigencia.after_or_equal' =>
            'La fecha final no puede ser anterior a la fecha inicial.',
        ];
    }
}
