@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="coins"
            title="Ahorro"
            subtitle="Productos de ahorro diseñados para ayudarte a alcanzar tus metas financieras con seguridad y rentabilidad." />

        <!-- INTRO -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6">

            <x-section-title as="h2" class="mb-5">¿Por qué ahorrar en Foncaldas?</x-section-title>

            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                Foncaldas es una entidad del sector solidario que busca que tus ahorros crezcan de manera segura.
                Ofrecemos diferentes líneas de ahorro adaptadas a tus necesidades, con tasas competitivas y acompañamiento constante.
            </p>

        </div>

        <!-- LINEAS DE AHORRO -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6">

            <x-section-title as="h2" class="mb-7">Líneas de ahorro</x-section-title>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="p-5 border border-slate-100 dark:border-slate-700 rounded-2xl">
                    <div class="inline-flex items-center gap-3 mb-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="briefcase" class="w-5 h-5" /></span>
                        <h3 class="font-semibold text-slate-800 dark:text-slate-100">Ahorro ordinario</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-1">
                        Cuenta flexible sin monto mínimo de permanencia.
                    </p>
                </div>

                <div class="p-5 border border-slate-100 dark:border-slate-700 rounded-2xl">
                    <div class="inline-flex items-center gap-3 mb-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="trending-up" class="w-5 h-5" /></span>
                        <h3 class="font-semibold text-slate-800 dark:text-slate-100">Ahorro CDAT</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-1">
                        Certificado a término fijo con tasa preferencial, ideal para hacer crecer tus ahorros a mediano plazo.
                    </p>
                </div>

            </div>

        </div>

        <!-- VENTAJAS -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6">

            <x-section-title as="h2" class="mb-7">Ventajas de ahorrar con nosotros</x-section-title>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-slate-600 dark:text-slate-400">

                <p class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-[#0C67A3]" /> Tasas competitivas del mercado</p>
                <p class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-[#0C67A3]" /> Seguridad garantizada</p>
                <p class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-[#0C67A3]" /> Transparencia total</p>
                <p class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-[#0C67A3]" /> Atención personalizada</p>
                <p class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-[#0C67A3]" /> Retiros sin penalización</p>

            </div>

        </div>

        <!-- PROCESO -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6">

            <x-section-title as="h2" class="mb-7">Proceso de apertura</x-section-title>

            <ol class="space-y-3 text-slate-600 dark:text-slate-400 list-decimal pl-5">
                <li>Completa el formulario de vinculación como asociado</li>
                <li>Elige la línea de ahorro que se adapte a tu meta</li>
                <li>Realiza tu depósito inicial</li>
                <li>Accede a tu cuenta digital inmediatamente</li>
            </ol>

        </div>

        <!-- DATO IMPORTANTE -->
        <div class="bg-[#0B4870] rounded-3xl p-8 text-white">

            <x-section-title as="h2" light class="mb-4">Dato importante</x-section-title>

            <p class="text-white/80 leading-relaxed">
                Los asociados de Foncaldas acceden a beneficios adicionales como participación en las ganancias anuales de la cooperativa.
            </p>

        </div>

        <x-back-link :route="route('servicios')" label="← Volver a servicios" />

    </div>

</div>

@endsection