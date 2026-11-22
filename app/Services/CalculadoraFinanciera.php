<?php

namespace App\Services;

use InvalidArgumentException;

class CalculadoraFinanciera
{
    /**
     * Crédito con cuota mensual fija - Sistema francés.
     *
     * @param  float  $capital  Capital solicitado en pesos.
     * @param  float  $tasaMensualPorcentaje  Tasa mensual expresada en porcentaje.
     * @param  int  $numeroCuotas  Número de cuotas mensuales.
     */
    public function calcularCreditoCuotaFija(
        float $capital,
        float $tasaMensualPorcentaje,
        int $numeroCuotas
    ): array {
        $this->validarCapital($capital);
        $this->validarTasa($tasaMensualPorcentaje);
        $this->validarPeriodo($numeroCuotas);

        $tasaMensual = $tasaMensualPorcentaje / 100;

        if ($tasaMensual == 0.0) {
            $cuota = $capital / $numeroCuotas;
        } else {
            $cuota = $capital
                * $tasaMensual
                / (1 - pow(1 + $tasaMensual, -$numeroCuotas));
        }

        $total = $cuota * $numeroCuotas;
        $intereses = $total - $capital;

        return [
            'capital' => $this->redondearPeso($capital),
            'tasa_mensual_porcentaje' => $tasaMensualPorcentaje,
            'numero_cuotas' => $numeroCuotas,

            'cuota_exacta' => $cuota,
            'intereses_exactos' => $intereses,
            'total_exacto' => $total,

            'cuota' => $this->redondearPeso($cuota),
            'intereses' => $this->redondearPeso($intereses),
            'total' => $this->redondearPeso($total),
        ];
    }

    /**
     * Crédito Avances - Pago único al vencimiento.
     */
    public function calcularAvance(
        float $capital,
        float $tasaMensualPorcentaje,
        int $dias
    ): array {
        $this->validarCapital($capital);
        $this->validarTasa($tasaMensualPorcentaje);
        $this->validarPeriodo($dias);

        $tasaMensual = $tasaMensualPorcentaje / 100;

        $interes = $capital
            * (
                pow(
                    1 + $tasaMensual,
                    $dias / 30
                ) - 1
            );

        $total = $capital + $interes;

        return [
            'capital' => $this->redondearPeso($capital),
            'tasa_mensual_porcentaje' => $tasaMensualPorcentaje,
            'dias' => $dias,

            'interes_exacto' => $interes,
            'total_exacto' => $total,

            'interes' => $this->redondearPeso($interes),
            'total' => $this->redondearPeso($total),
        ];
    }

    /**
     * Ahorro Ordinario.
     *
     * Se calcula la tasa diaria a partir de la tasa E.A.
     * y se acumula el rendimiento diariamente.
     *
     * @param  int  $baseAnualDias  Días del año financiero (365 o 360, según el parámetro base_anual_dias).
     */
    public function calcularAhorroOrdinario(
        float $capital,
        float $tasaEfectivaAnualPorcentaje,
        int $dias,
        int $baseAnualDias = 365
    ): array {
        $this->validarCapital($capital);
        $this->validarTasa($tasaEfectivaAnualPorcentaje);
        $this->validarPeriodo($dias);
        $this->validarBaseAnual($baseAnualDias);

        $tasaEa = $tasaEfectivaAnualPorcentaje / 100;

        $tasaDiaria = pow(
            1 + $tasaEa,
            1 / $baseAnualDias
        ) - 1;

        $saldoFinal = $capital
            * pow(
                1 + $tasaDiaria,
                $dias
            );

        $rendimiento = $saldoFinal - $capital;

        return [
            'capital' => $this->redondearPeso($capital),
            'tasa_ea_porcentaje' => $tasaEfectivaAnualPorcentaje,
            'dias' => $dias,

            'tasa_diaria' => $tasaDiaria,

            'rendimiento_exacto' => $rendimiento,
            'saldo_final_exacto' => $saldoFinal,

            'rendimiento' => $this->redondearPeso($rendimiento),
            'saldo_final' => $this->redondearPeso($saldoFinal),
        ];
    }

    /**
     * CDAT.
     *
     * @param  int  $baseAnualDias  Días del año financiero (365 o 360, según el parámetro base_anual_dias).
     */
    public function calcularCdat(
        float $capital,
        float $tasaEfectivaAnualPorcentaje,
        int $dias,
        float $retencionPorcentaje = 0,
        int $baseAnualDias = 365
    ): array {
        $this->validarCapital($capital);
        $this->validarTasa($tasaEfectivaAnualPorcentaje);
        $this->validarPeriodo($dias);
        $this->validarTasa($retencionPorcentaje);
        $this->validarBaseAnual($baseAnualDias);

        $tasaEa = $tasaEfectivaAnualPorcentaje / 100;

        $rendimientoBruto = $capital
            * (
                pow(
                    1 + $tasaEa,
                    $dias / $baseAnualDias
                ) - 1
            );

        $retencion = $rendimientoBruto
            * ($retencionPorcentaje / 100);

        $rendimientoNeto =
            $rendimientoBruto - $retencion;

        $valorFinal =
            $capital + $rendimientoNeto;

        return [
            'capital' => $this->redondearPeso($capital),
            'tasa_ea_porcentaje' => $tasaEfectivaAnualPorcentaje,
            'dias' => $dias,
            'retencion_porcentaje' => $retencionPorcentaje,

            'rendimiento_bruto_exacto' => $rendimientoBruto,
            'retencion_exacta' => $retencion,
            'rendimiento_neto_exacto' => $rendimientoNeto,
            'valor_final_exacto' => $valorFinal,

            'rendimiento_bruto' => $this->redondearPeso($rendimientoBruto),
            'retencion' => $this->redondearPeso($retencion),
            'rendimiento_neto' => $this->redondearPeso($rendimientoNeto),
            'valor_final' => $this->redondearPeso($valorFinal),
        ];
    }

    /**
     * Redondeo monetario al peso colombiano.
     */
    private function redondearPeso(float $valor): int
    {
        return (int) round(
            $valor,
            0,
            PHP_ROUND_HALF_UP
        );
    }

    private function validarCapital(float $capital): void
    {
        if ($capital <= 0) {
            throw new InvalidArgumentException(
                'El capital debe ser mayor que cero.'
            );
        }
    }

    private function validarTasa(float $tasa): void
    {
        if ($tasa < 0) {
            throw new InvalidArgumentException(
                'La tasa no puede ser negativa.'
            );
        }
    }

    private function validarBaseAnual(int $baseAnualDias): void
    {
        if (! in_array($baseAnualDias, [360, 365], true)) {
            throw new InvalidArgumentException(
                'La base anual debe ser de 360 o 365 días.'
            );
        }
    }

    private function validarPeriodo(int $periodo): void
    {
        if ($periodo <= 0) {
            throw new InvalidArgumentException(
                'El periodo debe ser mayor que cero.'
            );
        }
    }
}
