@extends('layouts.app')

@section('content')

@php
    $tarjeta = 'rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-800';
@endphp

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <!-- HEADER -->
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="celular" class="mb-6" sol />
            <x-page-header
                icon="phone"
                title="Contacto"
                subtitle="Estamos disponibles para atenderte. Escríbenos, visítanos o comunícate con nosotros por WhatsApp."
                center />
        </div>

        <div class="grid gap-6 lg:grid-cols-5">

            <!-- COLUMNA IZQUIERDA: WhatsApp + dirección -->
            <div class="flex flex-col gap-6 lg:col-span-2">

                <!-- WHATSAPP: la vía más rápida, por eso es la tarjeta destacada -->
                <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-green-500 via-green-600 to-[#0B8B7B] p-8 text-white shadow-xl shadow-green-600/25 ring-1 ring-white/20">
                    <x-icon name="whatsapp" class="pointer-events-none absolute -bottom-8 -right-6 h-44 w-44 text-white/15" />

                    <p class="relative text-xs font-semibold uppercase tracking-widest text-white/90">La vía más rápida</p>
                    <h2 class="hero-title relative mt-1 text-2xl md:text-3xl">Atención por WhatsApp</h2>
                    <p class="relative mt-3 text-base leading-relaxed text-white/90">
                        Escríbenos directamente y uno de nuestros asesores te responderá lo antes posible.
                    </p>

                    <a href="https://wa.me/573148330328" target="_blank" rel="noopener"
                       class="group relative mt-6 inline-flex items-center gap-3 rounded-full bg-white py-2 pl-6 pr-2 text-lg font-semibold text-green-700 shadow-lg transition hover:-translate-y-0.5">
                        Enviar mensaje
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-white transition-transform duration-300 group-hover:translate-x-1">
                            <x-icon name="whatsapp" class="w-5 h-5" />
                        </span>
                    </a>
                </div>

                <!-- DIRECCIÓN -->
                <div class="{{ $tarjeta }} flex flex-1 flex-col p-8">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30">
                            <x-icon name="map-pin" class="w-7 h-7" />
                        </span>
                        <h2 class="hero-title text-2xl text-slate-900 dark:text-white">Visítanos</h2>
                    </div>

                    <p class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Calle 60 #25-01</p>
                    <p class="mt-1 text-base text-slate-600 dark:text-slate-300">Manizales, Caldas, Colombia</p>

                    <a href="https://www.google.com/maps/search/?api=1&query=Calle+60+25-01+Manizales"
                       target="_blank" rel="noopener"
                       class="group mt-6 inline-flex items-center justify-center gap-2 self-start rounded-full bg-[#0B4870] px-6 py-3 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                        Ver en Google Maps
                        <x-icon name="arrow-right" class="w-4 h-4 -rotate-45 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                    </a>
                </div>

            </div>

            <!-- MAPA -->
            <!-- Marco con los colores del logo; el mapa se oscurece en modo oscuro y abajo va la dirección con "Cómo llegar" -->
            <div x-data="{ mapLoaded: false }" class="flex min-h-[460px] flex-col rounded-3xl bg-linear-to-br from-[#0C67A3] via-[#0B8B7B] to-[#FACC15] p-1.5 shadow-xl shadow-[#0B4870]/20 lg:col-span-3">

                <div class="relative min-h-[320px] flex-1 overflow-hidden rounded-t-[1.2rem] bg-slate-200 dark:bg-slate-900">

                    <iframe
                        title="Mapa de la sede de Foncaldas"
                        src="https://www.google.com/maps?q=Calle+60+25-01+Manizales&output=embed"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full dark:[filter:invert(.92)_hue-rotate(180deg)_saturate(.85)]"
                        @load="mapLoaded = true">
                    </iframe>

                    <!-- Etiqueta flotante de la sede (arriba a la derecha, donde no tapa los controles de Google) -->
                    <span class="pointer-events-none absolute right-4 top-4 hidden items-center gap-2.5 rounded-full bg-[#0B4870] py-2 pl-2 pr-4 text-sm font-bold text-white shadow-xl ring-2 ring-white/40 sm:inline-flex">
                        <span class="relative flex h-7 w-7 items-center justify-center">
                            <span class="absolute inset-0 rounded-full bg-[#FACC15]/70 animate-ping"></span>
                            <span class="relative inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870]"><x-icon name="map-pin" class="w-4 h-4" /></span>
                        </span>
                        Sede Foncaldas
                    </span>

                    <div x-show="!mapLoaded" x-transition.opacity.duration.300ms
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-[#F8FAFC] dark:bg-slate-800">
                        <x-icon name="map-pin" class="w-8 h-8 text-[#0C67A3] animate-bounce dark:text-sky-300" />
                        <p class="text-sm text-slate-500 dark:text-slate-300">Cargando mapa…</p>
                    </div>

                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 rounded-b-[1.2rem] bg-white px-5 py-4 dark:bg-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="map-pin" class="w-5 h-5" /></span>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Calle 60 #25-01</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Manizales, Caldas</p>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=Calle+60+25-01+Manizales" target="_blank" rel="noopener"
                       class="group inline-flex items-center gap-3 rounded-full bg-[#0B4870] py-1.5 pl-5 pr-1.5 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                        Cómo llegar
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                            <x-icon name="compass" class="w-4 h-4" />
                        </span>
                    </a>
                </div>

            </div>

        </div>

        <!-- HORARIO: el día de hoy queda resaltado y dice si estamos abiertos en este momento -->
        <div id="horario" class="scroll-mt-28 mt-6 {{ $tarjeta }} p-8"
             x-data="{
                get status() {
                    const parts = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Bogota', weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: false }).formatToParts(new Date());
                    const m = {};
                    parts.forEach((p) => m[p.type] = p.value);
                    const day = m.weekday;
                    const mins = parseInt(m.hour, 10) * 60 + parseInt(m.minute, 10);
                    const open = day !== 'Sat' && day !== 'Sun' && ((mins >= 480 && mins < 720) || (mins >= 780 && mins < 960));
                    return { open, day, grupo: day === 'Sat' ? 'sab' : (day === 'Sun' ? 'dom' : 'sem') };
                }
             }">

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-lg shadow-[#0B8B7B]/30">
                        <x-icon name="clock" class="w-7 h-7" />
                    </span>
                    <h2 class="hero-title text-2xl text-slate-900 dark:text-white">Horario de atención</h2>
                </div>

                <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold"
                      :class="status.open ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-300'">
                    <span class="h-2 w-2 rounded-full" :class="status.open ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></span>
                    <span x-text="status.open ? 'Abierto ahora' : 'Cerrado ahora'"></span>
                </span>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-4">

                <div class="relative rounded-2xl border-2 p-5 transition md:col-span-2"
                     :class="status.grupo === 'sem' ? 'border-[#0C67A3] bg-[#0C67A3]/5 dark:border-sky-400 dark:bg-sky-400/10' : 'border-slate-200 dark:border-white/10'">
                    <span x-show="status.grupo === 'sem'" x-cloak class="absolute right-4 top-4 rounded-full bg-[#0C67A3] px-2.5 py-0.5 text-xs font-bold text-white">Hoy</span>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">Lunes a Viernes</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3.5 py-1.5 text-[15px] font-semibold text-slate-800 dark:bg-white/10 dark:text-white"><x-icon name="sun" class="w-4 h-4 text-amber-500" /> 8:00 a. m. – 12:00 p. m.</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3.5 py-1.5 text-[15px] font-semibold text-slate-800 dark:bg-white/10 dark:text-white"><x-icon name="clock" class="w-4 h-4 text-[#0C67A3] dark:text-sky-300" /> 1:00 p. m. – 4:00 p. m.</span>
                    </div>
                </div>

                @foreach ([['sab', 'Sábado'], ['dom', 'Domingo']] as [$grupo, $dia])
                    <div class="relative rounded-2xl border-2 p-5 transition"
                         :class="status.grupo === '{{ $grupo }}' ? 'border-[#0C67A3] bg-[#0C67A3]/5 dark:border-sky-400 dark:bg-sky-400/10' : 'border-slate-200 dark:border-white/10'">
                        <span x-show="status.grupo === '{{ $grupo }}'" x-cloak class="absolute right-4 top-4 rounded-full bg-[#0C67A3] px-2.5 py-0.5 text-xs font-bold text-white">Hoy</span>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $dia }}</p>
                        <span class="mt-3 inline-flex rounded-full bg-red-100 px-3.5 py-1.5 text-[15px] font-semibold text-red-600 dark:bg-red-900/40 dark:text-red-300">Cerrado</span>
                    </div>
                @endforeach

            </div>

        </div>

        <!-- OTRAS FORMAS DE COMUNICARTE -->
        <div class="mt-6 grid gap-6 md:grid-cols-2">
            @foreach ([
                [route('solicitudes.crear'), 'clipboard', 'Radica un trámite', 'Envía tu solicitud en línea, sin usuario ni contraseña.'],
                [route('pqrs.crear'), 'document', 'Peticiones, quejas y reclamos', 'Cuéntanos tu petición, queja, reclamo o sugerencia.'],
            ] as [$destino, $icono, $titulo, $detalle])
                <a href="{{ $destino }}" class="group flex items-center gap-5 {{ $tarjeta }} p-6 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10">
                    <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110 dark:bg-sky-400/15 dark:text-sky-300">
                        <x-icon :name="$icono" class="w-7 h-7" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-lg font-bold text-slate-900 dark:text-white">{{ $titulo }}</span>
                        <span class="mt-0.5 block text-[15px] text-slate-600 dark:text-slate-300">{{ $detalle }}</span>
                    </span>
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[#0B4870] transition duration-300 group-hover:translate-x-1 group-hover:bg-[#FACC15] dark:bg-white/10 dark:text-white dark:group-hover:text-[#0B4870]">
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </span>
                </a>
            @endforeach
        </div>

    </div>

</div>

@endsection
