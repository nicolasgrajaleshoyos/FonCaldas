<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * Lectura de parámetros vigentes y límites de monto compartidos por los simuladores de crédito y ahorro.
 * $parametros es la colección de parametrosVigentesPorCodigo() (indexada por código).
 */
trait LeeParametrosSimulador
{
    private function valorNumerico(Collection $parametros, string $codigo): ?float
    {
        $valor = $parametros->get($codigo)?->valor;

        return $valor === null ? null : (float) $valor;
    }

    /**
     * Aplica monto_minimo y monto_maximo del producto. Los textos llegan sin el valor: se les añade "$X.".
     */
    private function validarLimitesMonto(Collection $parametros, float $monto, string $textoMinimo, string $textoMaximo): void
    {
        $minimo = $this->valorNumerico($parametros, 'monto_minimo');
        $maximo = $this->valorNumerico($parametros, 'monto_maximo');

        if ($minimo !== null && $monto < $minimo) {
            throw ValidationException::withMessages(['monto' => $textoMinimo.$this->formatoPesos($minimo).'.']);
        }

        if ($maximo !== null && $monto > $maximo) {
            throw ValidationException::withMessages(['monto' => $textoMaximo.$this->formatoPesos($maximo).'.']);
        }
    }

    private function formatoPesos(float $valor): string
    {
        return '$'.number_format($valor, 0, ',', '.');
    }
}
