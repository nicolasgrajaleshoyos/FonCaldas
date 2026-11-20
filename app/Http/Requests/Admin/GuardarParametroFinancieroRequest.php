<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductoFinanciero;
use App\Support\FormatoNumericoFinanciero;
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
        $valor = $this->input('valor');
        $formato = $this->formatoNumerico($this->definicionParametro());
        if (! is_string($valor) || $formato === null) {
            return;
        }

        $valorNormalizado = match ($formato) {
            'monto', 'entero' => FormatoNumericoFinanciero::normalizarEnteroAgrupado($valor),
            'plazo' => trim($valor),
            'decimal' => FormatoNumericoFinanciero::normalizarDecimal($valor),
        };

        $this->merge(['valor' => $valorNormalizado]);
    }

    private function definicionParametro(): array
    {
        $codigo = $this->input('codigo');

        return is_string($codigo) ? (config('parametros_financieros', [])[$codigo] ?? []) : [];
    }

    private function formatoNumerico(array $definicion): ?string
    {
        $unidad = $definicion['unidad_sugerida'] ?? null;
        $esMonetario = $unidad === 'COP' || ($definicion['categoria'] ?? null) === 'monto';

        return match ($definicion['tipo_dato'] ?? null) {
            'decimal' => $esMonetario ? 'monto' : 'decimal',
            'entero' => $unidad === 'meses' ? 'plazo' : 'entero',
            default => null,
        };
    }

    public function rules(): array
    {
        $definiciones = config('parametros_financieros', []);
        $definicion = $this->definicionParametro();
        $formato = $this->formatoNumerico($definicion);

        $reglasValor = ['bail', 'nullable'];
        if ($formato !== null) {
            $reglasValor[] = $formato === 'decimal'
                ? FormatoNumericoFinanciero::REGLA_DECIMAL
                : FormatoNumericoFinanciero::REGLA_ENTERO;
        }

        $reglasValor[] = 'numeric';
        $reglasValor[] = 'min:0';
        if (isset($definicion['valor_maximo'])) {
            $reglasValor[] = 'max:'.$definicion['valor_maximo'];
        }

        $unidadesPermitidas = $definicion['unidades_permitidas'] ?? [];
        $reglasUnidad = ['nullable', 'string', 'max:50'];
        if ($unidadesPermitidas !== []) {
            $reglasUnidad[] = Rule::in($unidadesPermitidas);
        }

        $reglasTexto = ['nullable', 'string', 'max:255'];
        if (isset($definicion['opciones_texto'])) {
            $reglasTexto[] = Rule::in(array_keys($definicion['opciones_texto']));
        }

        return [
            'producto_financiero_id' => [
                'required',
                'exists:producto_financieros,id',
            ],

            'codigo' => [
                'required',
                Rule::in(array_keys($definiciones)),
            ],

            'valor' => $reglasValor,

            'valor_texto' => $reglasTexto,

            'unidad' => $reglasUnidad,

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

                $definicion = $this->definicionParametro();


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

            'valor.regex' => match ($this->formatoNumerico($this->definicionParametro())) {
                'monto' => 'Ingresa el valor en pesos enteros. Puedes separar los miles con puntos o comas.',
                'plazo' => 'Ingresa el plazo en meses completos, sin separadores.',
                'entero' => 'Ingresa un número entero válido.',
                default => 'Ingresa un número válido. Puedes usar punto o coma para los decimales.',
            },

            'valor.min' =>
                'El valor numérico no puede ser negativo.',

            'valor.max' =>
                'El valor numérico no puede superar :max.',

            'unidad.in' =>
                'Para esta tasa, usa una de las unidades permitidas (:values).',

            'origen.required' =>
                'Debes indicar el origen del parámetro.',

            'fecha_inicio_vigencia.required' =>
                'Debes indicar la fecha de inicio de vigencia.',

            'fecha_fin_vigencia.after_or_equal' =>
                'La fecha final no puede ser anterior a la fecha inicial.',
        ];
    }
}
