@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex flex-col items-center mb-10">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="phone"
                title="Contacto"
                subtitle="Estamos disponibles para atenderte. Escríbenos, visítanos o comunícate con nosotros por WhatsApp."
                center />
        </div>

        <!-- GRID PRINCIPAL -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- CARD IZQUIERDA -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition hover:shadow-md"
                 x-data="{
                    get status() {
                        const parts = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Bogota', weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: false }).formatToParts(new Date());
                        const m = {};
                        parts.forEach((p) => m[p.type] = p.value);
                        const day = m.weekday;
                        const mins = parseInt(m.hour, 10) * 60 + parseInt(m.minute, 10);
                        const open = day !== 'Sat' && day !== 'Sun' && ((mins >= 480 && mins < 720) || (mins >= 780 && mins < 960));
                        return { open, day };
                    }
                 }">

                <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-6 inline-flex items-center gap-3">

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]">
                        <x-icon name="map-pin" class="w-5 h-5" />
                    </span>

                    Visítanos

                </h2>

                <div class="space-y-2 text-slate-600 dark:text-slate-400 text-[15px]">

                    <p>Calle 60 #25-01</p>
                    <p>Manizales, Caldas, Colombia</p>

                </div>

                <a href="https://www.google.com/maps/search/?api=1&query=Calle+60+25-01+Manizales"
                   target="_blank"
                   class="mt-6 inline-flex items-center justify-center px-6 py-3 rounded-lg bg-slate-900 text-white text-[14px] font-medium hover:bg-slate-800 transition">

                    Ver en Google Maps

                </a>

                <div class="border-t border-slate-100 dark:border-slate-700 my-8"></div>

                <div id="horario" class="scroll-mt-28 flex flex-wrap items-center justify-between gap-3 mb-5">

                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 inline-flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]">
                            <x-icon name="clock" class="w-5 h-5" />
                        </span>
                        Horario de atención
                    </h3>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                          :class="status.open ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'">
                        <span class="w-1.5 h-1.5 rounded-full" :class="status.open ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></span>
                        <span x-text="status.open ? 'Abierto ahora' : 'Cerrado ahora'"></span>
                    </span>

                </div>

                <div class="space-y-2 text-[14px]">

                    <div class="flex justify-between rounded-xl px-3 py-2.5 -mx-3 transition"
                         :class="(status.day !== 'Sat' && status.day !== 'Sun') ? 'bg-[#0C67A3]/5' : ''">
                        <span class="text-slate-600 dark:text-slate-400">Lunes a Viernes</span>
                        <span class="text-slate-600 dark:text-slate-400">08:00 a.m - 12:00 p.m / 01:00 p.m - 04:00 p.m</span>
                    </div>

                    <div class="flex justify-between rounded-xl px-3 py-2.5 -mx-3 transition"
                         :class="status.day === 'Sat' ? 'bg-[#0C67A3]/5' : ''">
                        <span class="text-slate-600 dark:text-slate-400">Sábado</span>
                        <span class="text-red-500 font-medium">Cerrado</span>
                    </div>

                    <div class="flex justify-between rounded-xl px-3 py-2.5 -mx-3 transition"
                         :class="status.day === 'Sun' ? 'bg-[#0C67A3]/5' : ''">
                        <span class="text-slate-600 dark:text-slate-400">Domingo</span>
                        <span class="text-red-500 font-medium">Cerrado</span>
                    </div>

                </div>

            </div>

            <!-- CARD DERECHA -->
            <div class="space-y-6">

                <!-- MAPA -->
                <div x-data="{ mapLoaded: false }" class="relative bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden h-[320px]">

                    <iframe
                        src="https://www.google.com/maps?q=Calle+60+25-01+Manizales&output=embed"
                        class="w-full h-full"
                        @load="mapLoaded = true">
                    </iframe>

                    <div x-show="!mapLoaded" x-transition.opacity.duration.300ms
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-[#F8FAFC] dark:bg-slate-700/40">
                        <x-icon name="map-pin" class="w-7 h-7 text-[#0C67A3] animate-bounce" />
                        <p class="text-sm text-slate-400">Cargando mapa…</p>
                    </div>

                </div>

                <!-- WHATSAPP -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition hover:shadow-md">

                    <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-3 inline-flex items-center gap-3">

                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-green-500/10 text-green-600">
                            <x-icon name="whatsapp" class="w-5 h-5" />
                        </span>

                        Atención por WhatsApp

                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed mb-6">
                        Escríbenos directamente y uno de nuestros asesores te responderá lo antes posible.
                    </p>

                    <a href="https://wa.me/573148330328"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-green-600 text-white text-[14px] font-medium hover:bg-green-700 transition">

                        <x-icon name="whatsapp" class="w-4 h-4" />
                        Enviar mensaje

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection