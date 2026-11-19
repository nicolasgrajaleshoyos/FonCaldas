<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductoFinanciero;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarParametroFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $codigo = $this->input('codigo');

        $definicion = config(
            "parametros_financieros.$codigo"
        );


        if (
            !$definicion
            || !in_array(
                $definicion['tipo_dato'],
                ['decimal', 'entero'],
                true
            )
        ) {
            return;
        }


        $valor = $this->input('valor');


        if (
            $valor === null
            || $valor === ''
        ) {
            return;
        }


        $this->merge([
            'valor' =>
                $this->normalizarValorNumerico(
                    (string) $valor,
                    $definicion
                ),
        ]);
    }


    private function normalizarValorNumerico(
        string $valor,
        array $definicion
    ): string {
        $valor = trim($valor);

        $valor = preg_replace(
            '/\s+/',
            '',
            $valor
        );


        $esMonetario =
            ($definicion['unidad_sugerida'] ?? null) === 'COP'
            || ($definicion['categoria'] ?? null) === 'monto';


        /*
         * Valores monetarios:
         *
         * 50000
         * 50.000
         * 50,000
         *
         * Todos se interpretan como 50000.
         */
        if ($esMonetario) {
            return preg_replace(
                '/[.,]/',
                '',
                $valor
            );
        }


        /*
         * Valores enteros:
         *
         * 365
         * 1.000
         *
         * Se eliminan separadores visuales.
         */
        if (
            $definicion['tipo_dato']
            === 'entero'
        ) {
            return preg_replace(
                '/[.,]/',
                '',
                $valor
            );
        }


        /*
         * Decimales no monetarios:
         *
         * 8,5  -> 8.5
         * 8.5  -> 8.5
         * 1.234,56 -> 1234.56
         * 1,234.56 -> 1234.56
         */
        $tieneComa =
            str_contains(
                $valor,
                ','
            );

        $tienePunto =
            str_contains(
                $valor,
                '.'
            );


        if (
            $tieneComa
            && $tienePunto
        ) {
            $ultimaComa =
                strrpos(
                    $valor,
                    ','
                );

            $ultimoPunto =
                strrpos(
                    $valor,
                    '.'
                );


            if (
                $ultimaComa
                > $ultimoPunto
            ) {
                $valor =
                    str_replace(
                        '.',
                        '',
                        $valor
                    );

                $valor =
                    str_replace(
                        ',',
                        '.',
                        $valor
                    );
            } else {
                $valor =
                    str_replace(
                        ',',
                        '',
                        $valor
                    );
            }


            return $valor;
        }


        if ($tieneComa) {
            return str_replace(
                ',',
                '.',
                $valor
            );
        }


        return $valor;
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
                Rule::in(
                    array_keys(
                        config(
                            'parametros_financieros',
                            []
                        )
                    )
                ),
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


    public function withValidator(
        $validator
    ): void {
        $validator->after(
            function ($validator) {

                $codigo =
                    $this->input(
                        'codigo'
                    );

                $definicion =
                    config(
                        "parametros_financieros.$codigo"
                    );


                $productoId =
                    $this->input(
                        'producto_financiero_id'
                    );

                $producto =
                    ProductoFinanciero::find(
                        $productoId
                    );


                if ($producto) {

                    $permitidos =
                        config(
                            "parametros_por_producto.{$producto->codigo}",
                            []
                        );


                    if (
                        !in_array(
                            $codigo,
                            $permitidos,
                            true
                        )
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'codigo',
                                'El parámetro seleccionado no corresponde al producto financiero.'
                            );

                        return;
                    }
                }


                if (!$definicion) {
                    return;
                }


                $tipoDato =
                    $definicion[
                        'tipo_dato'
                    ];


                if (
                    in_array(
                        $tipoDato,
                        ['decimal', 'entero'],
                        true
                    )
                    && !$this->filled(
                        'valor'
                    )
                ) {
                    $validator
                        ->errors()
                        ->add(
                            'valor',
                            'Debes ingresar un valor numérico para este parámetro.'
                        );
                }


                if (
                    in_array(
                        $tipoDato,
                        ['texto', 'booleano'],
                        true
                    )
                    && !$this->filled(
                        'valor_texto'
                    )
                ) {
                    $validator
                        ->errors()
                        ->add(
                            'valor_texto',
                            'Debes seleccionar o ingresar un valor para este parámetro.'
                        );
                }
            }
        );
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
                'El valor numérico debe ser válido. Puedes usar punto o coma para los decimales.',

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
