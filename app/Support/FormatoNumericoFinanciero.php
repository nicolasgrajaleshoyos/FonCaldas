<?php

namespace App\Support;

final class FormatoNumericoFinanciero
{
    public const REGLA_ENTERO = 'regex:/\A[+-]?[0-9]+\z/';

    public const REGLA_DECIMAL = 'regex:/\A[+-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)\z/';

    /*
     * Topes técnicos de los simuladores públicos: evitan bucles de millones de meses
     * y desbordes de enteros cuando el producto no tiene límites configurados.
     */
    public const MONTO_MAXIMO = 100000000000;

    public const PLAZO_MAXIMO_MESES = 600;

    public const MENSAJE_PESOS ='Ingresa el valor en pesos enteros. Puedes separar los miles con puntos o comas.';

    // La referencia al grupo exige usar el mismo separador en todos los miles.
    private const ENTERO_AGRUPADO = '[+-]?[0-9]{1,3}([., \x{00A0}\x{202F}])[0-9]{3}(?:\1[0-9]{3})*';

    public static function normalizarEnteroAgrupado(string $valor): string
    {
        $valor = trim($valor);

        if (preg_match('/\A'.self::ENTERO_AGRUPADO.'\z/u', $valor, $grupos) === 1) {
            return str_replace($grupos[1], '', $valor);
        }

        return $valor;
    }

    public static function normalizarDecimal(string $valor): string
    {
        $valor = trim($valor);

        if (! str_contains($valor, '.') && ! str_contains($valor, ',')) {
            return self::normalizarEnteroAgrupado($valor);
        }

        if (preg_match('/\A'.self::ENTERO_AGRUPADO.'([.,])[0-9]+\z/u', $valor, $grupos) === 1
            && $grupos[1] !== $grupos[2]) {
            $valor = str_replace($grupos[1], '', $valor);
        }

        return str_replace(',', '.', $valor);
    }
}
