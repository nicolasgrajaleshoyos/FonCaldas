@extends('layouts.app')

@section('content')

    <!-- HERO -->
    <div class="relative bg-gradient-to-br from-white via-[#EAF4FC] to-white dark:from-slate-900 dark:via-[#0B1220] dark:to-slate-900 overflow-hidden">

        <div class="absolute -top-16 left-10 w-[30rem] h-[30rem] bg-[#0C67A3]/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 w-72 h-72 bg-[#FACC15]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-10 w-96 h-96 bg-[#0B8B7B]/15 rounded-full blur-3xl"></div>

        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-32 bg-gradient-to-b from-transparent to-white dark:to-slate-900"></div>

        <div class="relative max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center px-4 sm:px-6 lg:px-8 py-14 lg:py-24">

            <!-- TEXTO -->
            <div class="min-w-0">

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-4xl xl:text-5xl 2xl:text-6xl font-bold leading-[1.05] tracking-tight text-[#0F172A] dark:text-white">
                    Tu aliado
                    <span class="relative inline-block text-[#0C67A3]">
                        financiero
                        <svg class="absolute left-0 -bottom-1.5 w-full" height="10" viewBox="0 0 200 10" fill="none" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M2 7.5C40 2 90 2 130 6C150 8 175 6 198 3" stroke="#0B8B7B" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <br class="hidden sm:block">
                    en Manizales
                </h1>

                <p class="mt-6 text-slate-600 dark:text-slate-400 text-base md:text-lg leading-relaxed max-w-xl">
                    Ahorro, créditos, afiliaciones y convenios pensados para el bienestar de tu familia y tu empresa.
                </p>

                <div class="flex flex-wrap gap-4 mt-9">
                    <a href="{{ route('servicios') }}"
                       class="group inline-flex items-center gap-2 bg-[#0B4870] text-white px-7 py-3.5 rounded-lg font-semibold shadow-lg shadow-[#0B4870]/25 hover:shadow-xl hover:shadow-[#0B4870]/30 hover:-translate-y-0.5 transition">
                        Ver servicios
                        <x-icon name="arrow-right" class="w-4 h-4 transition group-hover:translate-x-1" />
                    </a>
                    <a href="{{ route('afiliaciones') }}#como-asociarse"
                       class="border-2 border-[#0B4870] bg-white text-[#0B4870] shadow-md dark:border-sky-400 dark:bg-transparent dark:text-sky-200 dark:shadow-none px-7 py-3.5 rounded-lg font-semibold hover:bg-[#0B4870] hover:text-white dark:hover:bg-sky-400/15 dark:hover:text-white hover:-translate-y-0.5 transition">
                        Cómo asociarme
                    </a>
                </div>

            </div>

            <!-- PANEL DE SERVICIOS -->
            <div class="min-w-0 relative pt-10">

                <x-mascot class="absolute -top-2 right-6 z-10 w-28 h-28 md:w-36 md:h-36" />

                <div class="relative rounded-3xl bg-[#0B4870] p-8 shadow-xl overflow-hidden">

                    <div class="absolute w-56 h-56 bg-white/5 rounded-full blur-3xl -top-10 -right-10"></div>
                    <div class="absolute w-40 h-40 bg-[#0B8B7B]/20 rounded-full blur-3xl -bottom-10 -left-10"></div>

                    <div class="relative pb-6 mb-6 border-b border-white/10">
                        <p class="text-white font-semibold text-lg">¡Hola! Somos Foncaldas</p>
                        <p class="text-white/60 text-[13px] mt-0.5">Tu fondo de empleados de confianza en Manizales</p>
                    </div>

                    <p class="relative text-white/70 text-xs font-semibold uppercase tracking-wide mb-5">
                        Nuestros servicios
                    </p>

                    <div class="relative grid grid-cols-2 gap-4">

                        <a href="{{ route('servicios.ahorro') }}" class="group rounded-2xl bg-white/10 hover:bg-white/15 transition p-5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white">
                                <x-icon name="coins" class="w-5 h-5" />
                            </span>
                            <p class="mt-4 text-white font-semibold">Ahorro</p>
                            <p class="text-white/60 text-[13px] mt-1">Cuentas flexibles y seguras</p>
                        </a>

                        <a href="{{ route('servicios.creditos') }}" class="group rounded-2xl bg-white/10 hover:bg-white/15 transition p-5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white">
                                <x-icon name="card" class="w-5 h-5" />
                            </span>
                            <p class="mt-4 text-white font-semibold">Créditos</p>
                            <p class="text-white/60 text-[13px] mt-1">Líneas accesibles para ti</p>
                        </a>

                        <a href="{{ route('afiliaciones') }}" class="group rounded-2xl bg-white/10 hover:bg-white/15 transition p-5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white">
                                <x-icon name="users" class="w-5 h-5" />
                            </span>
                            <p class="mt-4 text-white font-semibold">Afiliaciones</p>
                            <p class="text-white/60 text-[13px] mt-1">Sé parte de Foncaldas</p>
                        </a>

                        <a href="{{ route('servicios.bienestar') }}" class="group rounded-2xl bg-white/10 hover:bg-white/15 transition p-5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white">
                                <x-icon name="heart" class="w-5 h-5" />
                            </span>
                            <p class="mt-4 text-white font-semibold">Bienestar</p>
                            <p class="text-white/60 text-[13px] mt-1">Beneficios para tu familia</p>
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- MISIÓN Y VISIÓN -->
    <div class="bg-gradient-to-b from-white via-[#EAF4FC] to-white dark:bg-none dark:bg-slate-900 py-16">
        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-10">
                <x-section-title center>Quiénes somos</x-section-title>
                <p class="text-slate-500 dark:text-slate-400 mt-5 max-w-2xl mx-auto">
                    Entidad del sector solidario comprometida con el bienestar de nuestros asociados,
                    promoviendo el ahorro, la cooperación y el desarrollo integral.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:bg-linear-to-br dark:from-sky-500/25 dark:via-slate-800 dark:to-slate-800 dark:border-sky-400/50 transition hover:-translate-y-1">
                    <div class="h-1.5 bg-[#0C67A3] dark:h-2 dark:bg-linear-to-r dark:from-sky-300 dark:to-blue-500"></div>
                    <div class="p-8">
                        <span class="inline-flex h-12 w-12 dark:h-16 dark:w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-linear-to-br dark:from-sky-300 dark:to-blue-500 dark:text-white dark:ring-2 dark:ring-sky-200/60 mb-5">
                            <x-icon name="target" class="w-6 h-6 dark:w-8 dark:h-8" />
                        </span>
                        <h3 class="text-xl font-bold text-[#0C67A3] dark:text-sky-200 mb-3">Misión</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                            Fomentar el ahorro y ofrecer soluciones financieras accesibles y responsables,
                            comprometidos con el bienestar de nuestros asociados y sus familias.
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:bg-linear-to-br dark:from-teal-500/25 dark:via-slate-800 dark:to-slate-800 dark:border-teal-400/50 transition hover:-translate-y-1">
                    <div class="h-1.5 bg-[#0B8B7B] dark:h-2 dark:bg-linear-to-r dark:from-emerald-300 dark:to-teal-500"></div>
                    <div class="p-8">
                        <span class="inline-flex h-12 w-12 dark:h-16 dark:w-16 items-center justify-center rounded-2xl bg-[#0B8B7B]/10 text-[#0B8B7B] dark:bg-linear-to-br dark:from-emerald-300 dark:to-teal-500 dark:text-white dark:ring-2 dark:ring-emerald-200/60 mb-5">
                            <x-icon name="eye" class="w-6 h-6 dark:w-8 dark:h-8" />
                        </span>
                        <h3 class="text-xl font-bold text-[#0B8B7B] dark:text-teal-200 mb-3">Visión</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                            Ser referente del sector solidario en el Eje Cafetero, innovando en servicios
                            que potencien el bienestar y el desarrollo integral de nuestros asociados.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @if($events->isNotEmpty())
    <!-- PRÓXIMOS EVENTOS -->
    <div class="bg-gradient-to-b from-white via-[#EAF4FC] to-white dark:bg-none dark:bg-slate-900 py-16">
        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-10">
                <x-section-title center>Próximos eventos</x-section-title>
                <p class="text-slate-500 dark:text-slate-400 mt-5 max-w-2xl mx-auto">
                    Actividades, talleres y encuentros para nuestros asociados.
                </p>
            </div>

            @php
                $mesesAbrev = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
            @endphp

            <div class="max-w-3xl mx-auto space-y-4">
                @foreach($events as $event)
                    <div class="flex items-center gap-5 rounded-2xl border border-slate-100 dark:border-slate-700 bg-[#f4f6f8] dark:bg-slate-900 p-5 hover:shadow-md hover:border-[#0C67A3]/20 transition">
                        <div class="shrink-0 w-16 h-16 rounded-2xl bg-[#0B4870] text-white flex flex-col items-center justify-center">
                            <span class="text-xl font-bold leading-none">{{ $event->event_date->format('d') }}</span>
                            <span class="text-[10px] uppercase tracking-wide text-white/70 mt-1">{{ $mesesAbrev[$event->event_date->month - 1] }}</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white truncate">{{ $event->title }}</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 flex items-center gap-1.5">
                                <x-icon name="map-pin" class="w-3.5 h-3.5 shrink-0" />
                                {{ $event->location }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('eventos') }}" class="inline-flex items-center gap-2 text-[#0B4870] font-semibold hover:underline">
                    Ver todos los eventos <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>

        </div>
    </div>
    @endif

    <!-- CTA FINAL: cierre propio de Inicio (el detalle de contacto vive en /contacto y en el pie de página) -->
    <div class="bg-[#0B4870] py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">

            <x-section-title center light>¿Listo para ser parte de Foncaldas?</x-section-title>
            <p class="text-white/70 mt-5 max-w-xl mx-auto">
                Asóciate y accede a ahorro, crédito y beneficios exclusivos para ti y tu familia.
            </p>

            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="{{ route('afiliaciones') }}#como-asociarse"
                   class="bg-white text-[#0B4870] px-7 py-3 rounded-lg font-semibold shadow-md hover:bg-slate-100 transition">
                    Cómo asociarme
                </a>
                <a href="{{ route('contacto') }}"
                   class="border border-white/30 text-white px-7 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                    Contáctanos
                </a>
            </div>

        </div>
    </div>

@endsection
