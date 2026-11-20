@extends('layouts.app')

@section('content')
    @php
        $pesos = static fn ($valor) => '$'.number_format($valor, 0, ',', '.');
        $cuotaFija = $resultado['tipo_resultado'] === 'cuota_fija';
        $plazo = $cuotaFija ? $resultado['numero_cuotas'] : $resultado['plazo_meses'];
        $tasa = rtrim(rtrim(number_format($resultado['tasa_aplicada'], 6, ',', '.'), '0'), ',');
        $datos = [
            ['etiqueta' => 'Monto solicitado', 'valor' => $pesos($resultado['capital'])],
            ['etiqueta' => 'Plazo', 'valor' => $plazo.' '.($plazo == 1 ? 'mes' : 'meses')],
            ['etiqueta' => 'Tasa aplicada', 'valor' => $tasa.' '.($resultado['unidad_tasa'] ?? '% mensual')],
        ];

        if ($cuotaFija) {
            $datos[] = ['etiqueta' => 'Cuota estimada', 'valor' => $pesos($resultado['cuota']), 'destacado' => true];
            $datos[] = ['etiqueta' => 'Intereses estimados', 'valor' => $pesos($resultado['intereses'])];
        } else {
            $datos[] = ['etiqueta' => 'Interés estimado', 'valor' => $pesos($resultado['interes'])];
        }

        $datos[] = ['etiqueta' => 'Total estimado a pagar', 'valor' => $pesos($resultado['total']), 'destacado' => ! $cuotaFija];
    @endphp

    <div class="min-h-screen page-bg py-10 sm:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <x-page-header icon="card" title="Resultado de la simulación" />

            <x-resultado-simulacion
                :producto="$resultado['producto']"
                :datos="$datos"
                :ruta-nueva="route('simuladores.credito')"
                :ruta-salir="route('servicios.creditos')">
                <x-slot:aviso>
                    Los resultados obtenidos en este simulador son únicamente informativos y corresponden a una
                    estimación realizada con los parámetros configurados para FONCALDAS. La simulación no constituye
                    aprobación, desembolso, oferta definitiva ni compromiso contractual. Los valores finales pueden
                    variar de acuerdo con la capacidad de pago, requisitos, garantías, políticas vigentes, condiciones
                    del producto y validaciones realizadas por FONCALDAS. Las tasas, montos, plazos y demás condiciones
                    están sujetos a actualización y confirmación institucional.
                </x-slot:aviso>
            </x-resultado-simulacion>
        </div>
    </div>
@endsection
