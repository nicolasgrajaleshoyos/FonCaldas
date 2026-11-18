@extends('layouts.app')

@section('content')

    <div class="min-h-screen page-bg py-14">

        <div class="max-w-5xl mx-auto px-6">


            <x-page-header
                icon="card"
                title="Resultado de la simulación"
                subtitle="{{ $resultado['producto'] }}" />


            <div
                class="rounded-3xl
                    border border-sky-200 dark:border-sky-900
                    bg-white dark:bg-slate-800
                    shadow-sm overflow-hidden">


                <div class="p-8">


                    <div
                        class="grid grid-cols-1
                            sm:grid-cols-2 lg:grid-cols-3 gap-5">


                        {{-- MONTO --}}
                        <div
                            class="rounded-2xl
                                bg-slate-50 dark:bg-slate-900/50
                                p-5">

                            <p
                                class="text-xs uppercase tracking-wide
                                    text-slate-500">

                                Monto solicitado
                            </p>

                            <p
                                class="mt-2 text-xl font-bold
                                    text-slate-800 dark:text-white">

                                ${{ number_format(
                                    $resultado['capital'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </p>

                        </div>


                        {{-- TASA --}}
                        <div
                            class="rounded-2xl
                                bg-slate-50 dark:bg-slate-900/50
                                p-5">

                            <p
                                class="text-xs uppercase tracking-wide
                                    text-slate-500">

                                Tasa aplicada
                            </p>

                            <p
                                class="mt-2 text-xl font-bold
                                    text-slate-800 dark:text-white">

                                {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $resultado['tasa_aplicada'],
                                            6,
                                            '.',
                                            ''
                                        ),
                                        '0'
                                    ),
                                    '.'
                                ) }}

                                {{ $resultado['unidad_tasa'] ?? '% mensual' }}
                            </p>

                        </div>


                        @if ($resultado['tipo_resultado'] === 'cuota_fija')


                            {{-- PLAZO --}}
                            <div
                                class="rounded-2xl
                                    bg-slate-50 dark:bg-slate-900/50
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Plazo
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-slate-800 dark:text-white">

                                    {{ $resultado['numero_cuotas'] }}
                                    meses
                                </p>

                            </div>


                            {{-- CUOTA --}}
                            <div
                                class="rounded-2xl
                                    bg-sky-50 dark:bg-sky-950/30
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Cuota estimada
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-sky-700 dark:text-sky-300">

                                    ${{ number_format(
                                        $resultado['cuota'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>


                            {{-- INTERESES --}}
                            <div
                                class="rounded-2xl
                                    bg-slate-50 dark:bg-slate-900/50
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Intereses estimados
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-slate-800 dark:text-white">

                                    ${{ number_format(
                                        $resultado['intereses'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>


                            {{-- TOTAL --}}
                            <div
                                class="rounded-2xl
                                    bg-slate-50 dark:bg-slate-900/50
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Total estimado a pagar
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-slate-800 dark:text-white">

                                    ${{ number_format(
                                        $resultado['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>


                        @else


                            {{-- PLAZO AVANCES --}}
                            <div
                                class="rounded-2xl
                                    bg-slate-50 dark:bg-slate-900/50
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Plazo
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-slate-800 dark:text-white">

                                    {{ $resultado['plazo_meses'] }}
                                    meses
                                </p>

                            </div>


                            {{-- INTERÉS --}}
                            <div
                                class="rounded-2xl
                                    bg-sky-50 dark:bg-sky-950/30
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Interés estimado
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-sky-700 dark:text-sky-300">

                                    ${{ number_format(
                                        $resultado['interes'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>


                            {{-- TOTAL --}}
                            <div
                                class="rounded-2xl
                                    bg-slate-50 dark:bg-slate-900/50
                                    p-5">

                                <p
                                    class="text-xs uppercase tracking-wide
                                        text-slate-500">

                                    Total estimado a pagar
                                </p>

                                <p
                                    class="mt-2 text-xl font-bold
                                        text-slate-800 dark:text-white">

                                    ${{ number_format(
                                        $resultado['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>

                        @endif

                    </div>


                    {{-- TÉRMINOS --}}
                    <div
                        class="mt-8 rounded-2xl
                            border border-amber-200
                            dark:border-amber-900
                            bg-amber-50 dark:bg-amber-950/20
                            px-5 py-4">

                        <p
                            class="text-sm leading-6
                                text-amber-900 dark:text-amber-200">

                            Los resultados obtenidos en este simulador
                            son únicamente informativos y corresponden
                            a una estimación realizada con los parámetros
                            configurados para FONCALDAS. La simulación
                            no constituye aprobación, desembolso, oferta
                            definitiva ni compromiso contractual.
                            Los valores finales pueden variar de acuerdo
                            con la capacidad de pago, requisitos, garantías,
                            políticas vigentes, condiciones del producto
                            y validaciones realizadas por FONCALDAS.
                            Las tasas, montos, plazos y demás condiciones
                            están sujetas a actualización y confirmación
                            institucional.

                        </p>

                    </div>


                    {{-- BOTONES --}}
                    <div
                        class="mt-8 flex flex-col
                            sm:flex-row gap-3
                            justify-center">


                        <a
                            href="{{ route('simuladores.credito') }}"
                            class="inline-flex items-center justify-center
                                px-6 py-3 rounded-xl
                                bg-[#0B4870] hover:bg-[#083653]
                                text-white font-semibold transition">

                            Nueva simulación
                        </a>


                        <a
                            href="{{ route('servicios.creditos') }}"
                            class="inline-flex items-center justify-center
                                px-6 py-3 rounded-xl
                                bg-slate-200 hover:bg-slate-300
                                dark:bg-slate-700 dark:hover:bg-slate-600
                                text-slate-800 dark:text-white
                                font-semibold transition">

                            Salir
                        </a>

                    </div>

                </div>

            </div>


            <p
                class="mt-4 text-center text-xs
                    text-slate-500 dark:text-slate-400">

                Simulación informativa. Los resultados están sujetos
                a las condiciones, políticas y validaciones vigentes
                de FONCALDAS.

            </p>

        </div>

    </div>

@endsection
