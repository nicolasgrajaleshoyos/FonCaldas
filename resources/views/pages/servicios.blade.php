@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg px-6 py-16">

    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col items-center mb-4">
            <x-mascot class="mb-4" />
            <x-page-header
                title="Servicios Foncaldas"
                subtitle="Descubre nuestras soluciones para ahorrar, obtener créditos, afiliarte y aprovechar convenios exclusivos."
                center />
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            <!-- AFILIACIONES -->
            <a href="{{ route('afiliaciones') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]">
                        <x-icon name="users" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Afiliaciones
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Accede a beneficios exclusivos con aliados locales y mejora tu bienestar.
                </p>
            </a>

            <!-- AHORRO -->
            <a href="{{ route('servicios.ahorro') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0B8B7B]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0B8B7B]/10 text-[#0B8B7B]">
                        <x-icon name="coins" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Ahorro
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Descubre nuestros planes de ahorro flexibles y seguros para tus metas financieras.
                </p>
            </a>

            <!-- CRÉDITOS -->
            <a href="{{ route('servicios.creditos') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#CA8A04]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#CA8A04]/10 text-[#CA8A04]">
                        <x-icon name="card" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Créditos
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Líneas de crédito accesibles para vehículos, proyectos y necesidades personales.
                </p>
            </a>

            <!-- BIENESTAR -->
            <a href="{{ route('servicios.bienestar') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]">
                        <x-icon name="heart" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Bienestar
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Programas y servicios que protegen tu salud y la de tu familia.
                </p>
            </a>

            <!-- CONVENIOS -->
            <a href="{{ route('servicios.convenios') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0B8B7B]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0B8B7B]/10 text-[#0B8B7B]">
                        <x-icon name="briefcase" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Convenios
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Conoce nuestros convenios y las alianzas disponibles.
                </p>
            </a>

            <!-- ATENCIÓN -->
            <a href="{{ route('contacto') }}"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#CA8A04]/20">

                <div class="mb-4 inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#CA8A04]/10 text-[#CA8A04]">
                        <x-icon name="phone" class="w-5 h-5" />
                    </span>

                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Atención
                    </h2>
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Soporte rápido y cercano en cada paso de tu experiencia Foncaldas.
                </p>
            </a>

            <!-- VILLA BEATRIZ -->
            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener"
               class="group block rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">

                <div class="mb-4 inline-flex items-center justify-between gap-3">
                    <div class="inline-flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]">
                            <x-icon name="star" class="w-5 h-5" />
                        </span>

                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                            Villa Beatriz
                        </h2>
                    </div>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-300 -rotate-45 shrink-0" />
                </div>

                <p class="text-slate-600 dark:text-slate-400">
                    Centro vacacional de Foncaldas: alojamiento a tarifas preferenciales, pasadía gratuito
                    y eventos exclusivos para asociados y beneficiarios.
                </p>
            </a>

        </div>

    </div>

</div>
@endsection