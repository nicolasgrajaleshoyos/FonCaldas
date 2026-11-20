@extends('layouts.app')

@section('content')
    @php
        $pesos = static fn ($valor) => '$'.number_format($valor, 0, ',', '.');
        $tasa = rtrim(rtrim(number_format($resultado['tasa'], 2, ',', '.'), '0'), ',');
        $datos = [
            ['etiqueta' => 'Monto inicial', 'valor' => $pesos($resultado['monto_inicial'])],
            ['etiqueta' => 'Plazo', 'valor' => $resultado['plazo'].' '.$resultado['unidad_plazo']],
            ['etiqueta' => 'Tasa aplicada', 'valor' => $tasa.' '.$resultado['unidad_tasa']],
        ];

        if ($resultado['tipo_resultado'] === 'ahorro_ordinario') {
            $frecuenciaAporte = $resultado['frecuencia_aporte'] ?? 'mensual';
            $datos[] = ['etiqueta' => 'Aporte '.$frecuenciaAporte, 'valor' => $pesos($resultado['aporte_mensual'])];
            $datos[] = ['etiqueta' => 'Total de aportes adicionales', 'valor' => $pesos($resultado['total_aportes'])];
            $datos[] = ['etiqueta' => 'Rendimiento estimado', 'valor' => $pesos($resultado['rendimiento_estimado'])];
        } else {
            $datos[] = ['etiqueta' => 'Rendimiento bruto', 'valor' => $pesos($resultado['rendimiento_bruto'])];
            $datos[] = [
                'etiqueta' => 'Retención estimada',
                'valor' => $pesos($resultado['retencion']),
                'detalle' => $resultado['porcentaje_retencion'].' % sobre rendimientos',
            ];
            $datos[] = ['etiqueta' => 'Rendimiento neto', 'valor' => $pesos($resultado['rendimiento_neto'])];
        }

        $total = ['etiqueta' => 'Valor final estimado', 'valor' => $pesos($resultado['valor_final'])];
    @endphp

    <div class="min-h-screen page-bg py-10 sm:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <x-page-header
                icon="coins"
                title="Resultado de la simulación"
                subtitle="Proyección orientativa del producto de ahorro seleccionado." />

            <x-resultado-simulacion
                :producto="$resultado['producto_nombre']"
                :datos="$datos"
                :total="$total"
                :nota="$resultado['nota_calculo'] ?? null"
                :ruta-nueva="route('simuladores.ahorro')"
                :ruta-salir="route('servicios.ahorro')">
                <x-slot:aviso>
                    Los resultados obtenidos en este simulador son únicamente informativos y corresponden a una
                    estimación realizada con los parámetros configurados para FONCALDAS. La simulación no constituye
                    aprobación, desembolso, oferta definitiva ni compromiso contractual. Los valores finales pueden
                    variar de acuerdo con la capacidad de pago, requisitos, garantías, políticas vigentes, condiciones
                    del producto y validaciones realizadas por FONCALDAS. Las tasas, montos, plazos y demás condiciones
                    están sujetas a actualización y confirmación institucional.
                </x-slot:aviso>
            </x-resultado-simulacion>
        </div>
    </div>
@endsection
