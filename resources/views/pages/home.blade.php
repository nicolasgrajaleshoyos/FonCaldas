@extends('layouts.app')

@section('content')

    <!-- HERO -->
    <div class="relative bg-gradient-to-br from-white via-[#EAF4FC] to-white dark:from-slate-900 dark:via-[#0B1220] dark:to-slate-900 overflow-hidden">

        <div class="absolute -top-16 left-10 w-[30rem] h-[30rem] bg-[#0C67A3]/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 w-72 h-72 bg-[#FACC15]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-10 w-96 h-96 bg-[#0B8B7B]/15 rounded-full blur-3xl"></div>

        <!-- Trama de puntos que se desvanece hacia los bordes -->
        <div class="hero-dots pointer-events-none absolute inset-0" aria-hidden="true"></div>

        <!-- Montañas de Manizales (eco del triángulo del logo): la cresta delantera tiene el color de la sección siguiente -->
        <svg class="pointer-events-none absolute inset-x-0 bottom-0 w-full h-20 md:h-28" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
            <path class="fill-[#0C67A3]/15 dark:fill-sky-400/20" d="M0 120V70L180 20l150 50 210-60 200 55 180-45 220 50 300-40v90Z"/>
            <path class="fill-[#0B8B7B]/15 dark:fill-teal-400/20" d="M0 120V90l240-45 200 40 260-55 240 50 220-35 280 45v30Z"/>
            <path class="fill-white dark:fill-slate-900" d="M0 120v-18l300-32 260 26 280-38 300 34 300-22v50Z"/>
        </svg>

        <div class="relative max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center px-4 sm:px-6 lg:px-8 pt-12 pb-28 md:pb-36 2xl:pt-24">

            <!-- TEXTO -->
            <div class="min-w-0">

                <p class="hero-in inline-flex items-center gap-2 rounded-full border border-[#0C67A3]/20 bg-white/70 backdrop-blur px-4 py-1.5 text-sm font-medium text-[#0B4870] shadow-sm dark:border-white/15 dark:bg-white/5 dark:text-sky-100">
                    <x-icon name="sun" class="w-4 h-4 text-amber-500 dark:text-[#FACC15]" />
                    Fondo de empleados · Manizales, Caldas
                </p>

                <h1 class="hero-title hero-in mt-6 text-[2.75rem] sm:text-6xl md:text-7xl lg:text-[clamp(3.25rem,5.2vw,5rem)] leading-[1.05] text-[#0F172A] dark:text-white" style="animation-delay:.08s">
                    <span class="block">Tu aliado</span>
                    <span class="hero-shine relative inline-block pb-1 bg-gradient-to-r from-[#0C67A3] via-[#0B8B7B] to-[#0C67A3] dark:from-sky-400 dark:via-teal-300 dark:to-sky-400 bg-clip-text text-transparent">
                        financiero
                        <svg class="absolute left-0 -bottom-1.5 w-full" height="12" viewBox="0 0 200 12" fill="none" preserveAspectRatio="none" aria-hidden="true">
                            <path class="hero-underline" pathLength="1" d="M2 8.5C40 3 90 3 130 7C150 9 175 7 198 4" stroke="#FACC15" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="hero-in mt-8 text-slate-600 dark:text-slate-400 text-base md:text-lg lg:text-[clamp(1rem,1.5vw,1.25rem)] leading-relaxed max-w-xl" style="animation-delay:.16s">
                    Ahorro, créditos, afiliaciones y convenios pensados para el bienestar de tu familia y tu empresa.
                </p>

                <div class="hero-in flex flex-wrap items-center gap-4 mt-9" style="animation-delay:.24s">
                    <a href="{{ route('servicios') }}"
                       class="group inline-flex items-center gap-3 bg-[#0B4870] text-white pl-7 pr-2 py-2 rounded-full font-semibold shadow-lg shadow-[#0B4870]/30 hover:shadow-xl hover:shadow-[#0B4870]/40 hover:-translate-y-0.5 transition">
                        Ver servicios
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </span>
                    </a>
                    <a href="{{ route('afiliaciones') }}#como-asociarse"
                       class="border-2 border-[#0B4870] bg-white/80 backdrop-blur text-[#0B4870] dark:border-sky-400 dark:bg-transparent dark:text-sky-200 px-7 py-3.5 rounded-full font-semibold hover:bg-[#0B4870] hover:text-white dark:hover:bg-sky-400/15 dark:hover:text-white hover:-translate-y-0.5 transition">
                        Cómo asociarme
                    </a>
                </div>

                <ul class="hero-in flex flex-wrap gap-x-6 gap-y-2 mt-8 text-sm text-slate-600 dark:text-slate-400" style="animation-delay:.32s">
                    @foreach (['Sector solidario', 'Ahorro y crédito', 'Bienestar para tu familia'] as $sello)
                        <li class="inline-flex items-center gap-2">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#0B8B7B]/15 text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300">
                                <x-icon name="check" class="w-3 h-3" />
                            </span>
                            {{ $sello }}
                        </li>
                    @endforeach
                </ul>

            </div>

            <!-- PANEL DE SERVICIOS: saludo con el sol del logo + tarjetas tipo bento -->
            <div class="min-w-0 relative pt-10">

                <x-mascot pose="hola" loading="eager" class="absolute -top-4 right-2 z-10 w-24 h-24 sm:-top-6 sm:right-4 sm:w-32 sm:h-32 md:w-48 md:h-48" />

                <!-- Escenario del saludo: el sol gira centrado detrás de la mascota -->
                <div class="hero-in relative flex min-h-36 md:min-h-44 flex-col justify-end overflow-hidden rounded-3xl bg-gradient-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-6 pr-28 sm:p-7 sm:pr-36 md:pr-56 shadow-xl shadow-[#0B4870]/30 ring-1 ring-white/10" style="animation-delay:.2s">
                    <div class="hero-sun absolute -top-48 -right-28 md:-top-40 md:-right-20 h-96 w-96" aria-hidden="true"></div>
                    <div class="absolute -top-6 right-6 md:right-12 h-40 w-40 rounded-full bg-[#FACC15]/50 blur-3xl" aria-hidden="true"></div>

                    <p class="hero-title relative text-white text-xl md:text-[1.7rem] leading-tight">¡Hola! Somos Foncaldas</p>
                    <p class="relative text-white/80 text-sm mt-1.5">Tu fondo de empleados de confianza en Manizales</p>
                </div>

                <!-- Bento de servicios: Ahorro destacado + tres accesos compactos -->
                <div class="grid grid-cols-1 gap-4 mt-4 min-[440px]:grid-cols-2">

                    <a href="{{ route('servicios.ahorro') }}" style="animation-delay:.28s"
                       class="hero-in group relative flex flex-col min-[440px]:row-span-2 overflow-hidden rounded-3xl bg-gradient-to-br from-[#0C67A3] to-[#0B8B7B] p-6 text-white shadow-lg shadow-[#0C67A3]/30 ring-1 ring-white/15 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#0B8B7B]/40 transition duration-300">
                        <!-- Ícono gigante de fondo, como marca de agua -->
                        <x-icon name="coins" class="pointer-events-none absolute -bottom-8 -right-8 h-40 w-40 text-white/10 transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110" />
                        <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/30 backdrop-blur">
                            <x-icon name="coins" class="w-6 h-6" />
                        </span>
                        <p class="hero-title relative mt-5 text-2xl">Ahorro</p>
                        <p class="relative mt-1 text-sm text-white/80">Cuentas flexibles y seguras</p>
                        <span class="relative mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold">
                            Conocer más
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                            </span>
                        </span>
                    </a>

                    @foreach ([
                        ['servicios.creditos', 'card', 'Créditos', 'Líneas accesibles para ti', 'bg-[#0B8B7B] text-white shadow-[#0B8B7B]/40', 'bg-[#0B8B7B]/30', ''],
                        ['afiliaciones', 'users', 'Afiliaciones', 'Sé parte de Foncaldas', 'bg-[#FACC15] text-[#0B4870] shadow-[#FACC15]/40', 'bg-[#FACC15]/40', ''],
                        ['servicios.bienestar', 'heart', 'Bienestar', 'Beneficios para tu familia', 'bg-sky-500 text-white shadow-sky-500/40', 'bg-sky-500/30', 'min-[440px]:col-span-2'],
                    ] as $i => [$ruta, $icono, $titulo, $detalle, $ficha, $halo, $ancho])
                        <a href="{{ route($ruta) }}" style="animation-delay:{{ .35 + $i * .07 }}s"
                           class="hero-in group relative {{ $ancho }} flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 overflow-hidden rounded-2xl border border-slate-200/80 bg-white/85 backdrop-blur p-4 shadow-sm hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/15 transition duration-300 dark:border-white/15 dark:bg-slate-800/70 dark:hover:bg-slate-800">
                            <span class="absolute -right-6 -top-10 h-28 w-28 rounded-full {{ $halo }} blur-2xl transition-transform duration-500 group-hover:scale-[2]" aria-hidden="true"></span>
                            <span class="relative inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $ficha }} shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                <x-icon :name="$icono" class="w-5 h-5" />
                            </span>
                            <span class="relative min-w-0 flex-1">
                                <span class="block font-semibold text-slate-900 dark:text-white">{{ $titulo }}</span>
                                <span class="block text-slate-500 dark:text-slate-300 text-[13px] mt-0.5">{{ $detalle }}</span>
                            </span>
                            <x-icon name="arrow-right" class="absolute right-3 top-3 hidden sm:block w-4 h-4 text-[#0B4870] dark:text-sky-200 -translate-x-2 opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100" />
                        </a>
                    @endforeach

                </div>
            </div>

        </div>
    </div>

    <!-- MISIÓN Y VISIÓN -->
    <div class="bg-gradient-to-b from-white via-[#EAF4FC] to-white dark:bg-none dark:bg-slate-900 py-16">
        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-10">
                <x-section-title center>Quiénes somos</x-section-title>
                <p class="text-slate-600 dark:text-slate-300 text-base md:text-lg leading-relaxed mt-6 max-w-2xl mx-auto">
                    Entidad del sector solidario comprometida con el bienestar de nuestros asociados,
                    promoviendo el ahorro, la cooperación y el desarrollo integral.
                </p>
            </div>

            <!-- Misión y Visión: texto grande y de alto contraste, con las ideas clave en negrita -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach ([
                    ['target', 'Nuestra razón de ser', 'Misión',
                     'Fomentar el <strong>ahorro</strong> y ofrecer <strong>soluciones financieras accesibles y responsables</strong>, comprometidos con el bienestar de nuestros asociados y sus familias.',
                     'bg-linear-to-br from-[#0C67A3] to-[#0B4870] shadow-[#0C67A3]/40', 'text-[#0C67A3] dark:text-sky-300', 'bg-[#0C67A3]/15 dark:bg-sky-400/20', 'bg-linear-to-r from-[#0C67A3] to-sky-400'],
                    ['eye', 'Hacia dónde vamos', 'Visión',
                     'Ser <strong>referente del sector solidario en el Eje Cafetero</strong>, innovando en servicios que potencien el <strong>bienestar y el desarrollo integral</strong> de nuestros asociados.',
                     'bg-linear-to-br from-[#12A594] to-[#0B8B7B] shadow-[#0B8B7B]/40', 'text-[#0B8B7B] dark:text-teal-300', 'bg-[#0B8B7B]/15 dark:bg-teal-400/20', 'bg-linear-to-r from-[#0B8B7B] to-emerald-400'],
                ] as [$icono, $antetitulo, $titulo, $texto, $ficha, $acento, $halo, $barra])
                    <div class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 md:p-9 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800">
                        <span class="absolute inset-x-0 top-0 h-1.5 {{ $barra }}" aria-hidden="true"></span>
                        <span class="absolute -right-10 -top-10 h-40 w-40 rounded-full {{ $halo }} blur-3xl" aria-hidden="true"></span>
                        <!-- Ícono gigante de fondo, como marca de agua -->
                        <x-icon :name="$icono" class="pointer-events-none absolute -bottom-10 -right-8 h-44 w-44 {{ $acento }} opacity-[0.07] transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110" />

                        <div class="relative flex items-center gap-4">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $ficha }} text-white shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                <x-icon :name="$icono" class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest {{ $acento }}">{{ $antetitulo }}</p>
                                <h3 class="hero-title text-2xl md:text-3xl text-slate-900 dark:text-white">{{ $titulo }}</h3>
                            </div>
                        </div>

                        <p class="relative mt-6 text-base md:text-[17px] leading-relaxed text-slate-700 dark:text-slate-200 [&_strong]:font-semibold [&_strong]:text-slate-900 dark:[&_strong]:text-white">
                            {!! $texto !!}
                        </p>
                    </div>
                @endforeach

            </div>

        </div>
    </div>

    @if($events->isNotEmpty())
    <!-- PRÓXIMOS EVENTOS -->
    <div class="bg-gradient-to-b from-white via-[#EAF4FC] to-white dark:bg-none dark:bg-slate-900 py-16">
        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-10">
                <x-section-title center>Próximos eventos</x-section-title>
                <p class="text-slate-600 dark:text-slate-300 text-base md:text-lg leading-relaxed mt-6 max-w-2xl mx-auto">
                    Actividades, talleres y encuentros para nuestros asociados.
                </p>
            </div>

            @php
                $mesesAbrev = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
            @endphp

            <div class="max-w-4xl mx-auto space-y-4">
                @foreach($events as $event)
                    @php
                        // Días que faltan para el evento (negativo si ya pasó: en ese caso no se muestra la etiqueta)
                        $faltan = (int) today()->diffInDays($event->event_date, false);
                        $cuando = match (true) {
                            $faltan < 0 => null,
                            $faltan === 0 => '¡Hoy!',
                            $faltan === 1 => 'Mañana',
                            default => "En $faltan días",
                        };
                    @endphp
                    <a href="{{ route('eventos') }}"
                       class="group relative flex items-center gap-4 sm:gap-6 overflow-hidden rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800">
                        <!-- Barra de color que crece al pasar el mouse -->
                        <span class="absolute inset-y-0 left-0 w-1.5 origin-top scale-y-0 bg-linear-to-b from-[#FACC15] via-[#0B8B7B] to-[#0C67A3] transition-transform duration-300 group-hover:scale-y-100" aria-hidden="true"></span>

                        <!-- Fecha tipo hoja de calendario -->
                        <div class="shrink-0 w-[4.5rem] sm:w-20 overflow-hidden rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-center text-white shadow-lg shadow-[#0C67A3]/30 transition-transform duration-300 group-hover:-rotate-3 group-hover:scale-105">
                            <span class="block bg-[#FACC15] py-1 text-[11px] font-bold uppercase tracking-widest text-[#0B4870]">{{ $mesesAbrev[$event->event_date->month - 1] }}</span>
                            <span class="hero-title block py-2 text-3xl leading-none">{{ $event->event_date->format('d') }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            @if($cuando)
                                <span class="mb-1.5 inline-flex items-center gap-1.5 rounded-full bg-[#0B8B7B]/10 px-2.5 py-0.5 text-xs font-semibold text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current animate-pulse"></span>
                                    {{ $cuando }}
                                </span>
                            @endif
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white line-clamp-2 first-letter:uppercase">{{ $event->title }}</h3>
                            <p class="mt-2 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm sm:text-[15px] text-slate-600 dark:text-slate-300">
                                @if($event->event_time)
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icon name="clock" class="w-4 h-4 shrink-0 text-[#0C67A3] dark:text-sky-300" />
                                        {{ $event->event_time->format('g:i a') }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="map-pin" class="w-4 h-4 shrink-0 text-[#0C67A3] dark:text-sky-300" />
                                    {{ $event->location }}
                                </span>
                            </p>
                        </div>

                        <span class="hidden sm:inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[#0B4870] transition duration-300 group-hover:bg-[#FACC15] group-hover:translate-x-1 dark:bg-white/10 dark:text-white dark:group-hover:text-[#0B4870]">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('eventos') }}" class="group inline-flex items-center gap-3 rounded-full border-2 border-[#0B4870] pl-6 pr-2 py-2 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15 dark:hover:text-white">
                    Ver todos los eventos
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </span>
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
