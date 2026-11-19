@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-5xl mx-auto px-6">


        <x-page-header
            icon="coins"
            title="Resultado de la simulación"
            subtitle="Proyección orientativa del producto de ahorro seleccionado." />


        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">


            <div class="mb-7">

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Producto
                </p>

                <h2 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">
                    {{ $resultado['producto_nombre'] }}
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">


                <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Monto inicial
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                        ${{ number_format($resultado['monto_inicial'], 0, ',', '.') }}
                    </p>

                </div>


                <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Plazo
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                        {{ $resultado['plazo'] }}
                        {{ $resultado['unidad_plazo'] }}
                    </p>

                </div>


                <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Tasa aplicada
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                        {{ rtrim(rtrim(number_format($resultado['tasa'], 2, ',', '.'), '0'), ',') }}
                        {{ $resultado['unidad_tasa'] }}
                    </p>

                </div>


                @if ($resultado['tipo_resultado'] === 'ahorro_ordinario')


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Aporte mensual
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['aporte_mensual'], 0, ',', '.') }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Total de aportes adicionales
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['total_aportes'], 0, ',', '.') }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Rendimiento estimado
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['rendimiento_estimado'], 0, ',', '.') }}
                        </p>

                    </div>


                @else


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Rendimiento bruto
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['rendimiento_bruto'], 0, ',', '.') }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Retención estimada
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['retencion'], 0, ',', '.') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ $resultado['porcentaje_retencion'] }} % sobre rendimientos
                        </p>

                    </div>


                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Rendimiento neto
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                            ${{ number_format($resultado['rendimiento_neto'], 0, ',', '.') }}
                        </p>

                    </div>


                @endif


            </div>


            <div class="mt-7 rounded-2xl border border-sky-200 dark:border-sky-900 bg-sky-50 dark:bg-sky-950/30 p-6">

                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Valor final estimado
                </p>

                <p class="mt-2 text-3xl font-bold text-[#0B4870] dark:text-sky-300">
                    ${{ number_format($resultado['valor_final'], 0, ',', '.') }}
                </p>

            </div>


            @if (!empty($resultado['nota_calculo']))

                <p class="mt-5 text-xs text-slate-500 dark:text-slate-400">
                    {{ $resultado['nota_calculo'] }}
                </p>

            @endif


            <div class="mt-8 flex flex-col sm:flex-row gap-3">

                <a
                    href="{{ route('simuladores.ahorro') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#0B4870] hover:bg-[#083653] text-white font-semibold transition">

                    Nueva simulación

                </a>


                <a
                    href="{{ route('servicios.ahorro') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-semibold">

                    Salir

                </a>

            </div>


            <p class="mt-7 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">

                Los resultados obtenidos en este simulador son únicamente
                informativos y corresponden a una estimación realizada con
                los parámetros configurados para FONCALDAS. Los valores,
                tasas y condiciones están sujetos a actualización y
                confirmación institucional.

            </p>

        </div>

    </div>

</div>

@endsection
