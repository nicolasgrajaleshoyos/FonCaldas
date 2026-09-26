@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="bank"
            title="Líneas de Crédito"
            subtitle="Soluciones financieras diseñadas para cada necesidad de nuestros asociados." />

        <!-- GRID -->
        <div class="space-y-8">

            <!-- 1 VEHÍCULO -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden grid md:grid-cols-2">

                <img src="{{ asset('images/credito-carro.png') }}"
                     alt="Crédito de vehículo"
                     loading="lazy"
                     class="w-full h-full object-contain min-h-[260px] bg-white dark:bg-slate-800 mx-auto">

                <div class="p-8 flex flex-col justify-center">
                    <x-section-title as="h2" class="mb-4">
                        Crédito de vehículo
                    </x-section-title>

                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Crédito de vehículo nuevo o seminuevo con una tasa especial desde el 1.10% mensual.
                        <br><br>
                        Aprovecha este beneficio exclusivo para nuestros asociados y pide tu asesoría personalizada hoy mismo.
                    </p>
                </div>

            </div>

            <!-- 2 LIBRE INVERSIÓN -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden grid md:grid-cols-2">

                <img src="{{ asset('images/credito-libre.png') }}"
                     alt="Crédito de libre inversión"
                     loading="lazy"
                     class="w-full h-full object-contain min-h-[260px] bg-white dark:bg-slate-800 mx-auto">

                <div class="p-8 flex flex-col justify-center">
                    <x-section-title as="h2" class="mb-4">
                        Crédito de libre inversión (15+ años)
                    </x-section-title>

                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Si llevas más de 15 años como asociado, accede a nuestro crédito de libre inversión con una tasa preferencial del 1.35% mensual.
                        <br><br>
                        Renueva tu hogar, estrena electrodomésticos o date un gusto.
                        <br>
                        ¡Tú eliges!
                    </p>
                </div>

            </div>

            <!-- 3 COMPRA DE DEUDAS -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden grid md:grid-cols-2">

                <img src="{{ asset('images/credito-deudas.png') }}"
                     alt="Compra de cartera"
                     loading="lazy"
                     class="w-full h-full object-contain min-h-[260px] bg-white dark:bg-slate-800 mx-auto">

                <div class="p-8 flex flex-col justify-center">
                    <x-section-title as="h2" class="mb-4">
                        Compra de cartera
                    </x-section-title>

                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Paga tus deudas de tarjetas de crédito o créditos externos con un plazo hasta de 96 meses. Con la mejor tasa del mercado de 0.90% mensual.
                        <br><br>
                        Te ayudamos a recuperar el control de tus finanzas con asesoría personalizada.
                    </p>
                </div>

            </div>

            <!-- 4 MOTO -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden grid md:grid-cols-2">

                <img src="{{ asset('images/credito-moto.png') }}"
                     alt="Crédito de motocicleta"
                     loading="lazy"
                     class="w-full h-full object-contain min-h-[260px] bg-white dark:bg-slate-800 mx-auto">

                <div class="p-8 flex flex-col justify-center">
                    <x-section-title as="h2" class="mb-4">
                        Crédito de motocicleta
                    </x-section-title>

                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        ¡Tu nueva moto te espera en FONCALDAS!
                        <br><br>
                        Financia la moto que desees con una excelente tasa de interés del 1.10% mensual y plazos de hasta 84 meses.
                        <br><br>
                        Conduce tu libertad con respaldo y facilidades pensadas para nuestros asociados.
                    </p>
                </div>

            </div>

            <!-- 5 TECNOLOGÍA -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden grid md:grid-cols-2">

                <img src="{{ asset('images/credito-celular.png') }}"
                     alt="Crédito de tecnología"
                     loading="lazy"
                     class="w-full h-full object-contain min-h-[260px] bg-white dark:bg-slate-800 mx-auto">

                <div class="p-8 flex flex-col justify-center">
                    <x-section-title as="h2" class="mb-4">
                        Crédito de tecnología
                    </x-section-title>

                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        ¡Estrena articulos tecnologicos de tus sueños con FONCALDAS!
                        <br><br>
                        Si llevas 7 o 15 años como asociado, accede a crédito de libre inversión para comprar tu iPhone o el celular que desees, con una tasa desde 1.25% mensual y plazo de hasta 72 meses.
                        <br><br>
                        ¡Tecnología a tu alcance con beneficios exclusivos!
                    </p>
                </div>

            </div>

        </div>

        <x-back-link :route="route('servicios.creditos')" label="← Volver a créditos" />

    </div>

</div>

@endsection