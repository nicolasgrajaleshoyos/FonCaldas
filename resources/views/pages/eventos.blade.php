@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex flex-col items-center mb-12">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="calendar"
                title="Eventos"
                subtitle="Explora los eventos disponibles en Foncaldas. Aquí encontrarás todas las actividades programadas con descripción, lugar, fecha, hora y multimedia cuando esté disponible."
                center />
        </div>

        @if($events->isEmpty())

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-10 shadow-sm border border-slate-100 dark:border-slate-700 text-center">

                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] mb-5">
                    <x-icon name="calendar" class="w-7 h-7" />
                </span>

                <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                    No hay eventos disponibles por ahora.
                </h2>

                <p class="mt-3 text-slate-600 dark:text-slate-400">
                    Vuelve pronto para ver los próximos talleres, ferias y encuentros.
                </p>

            </div>

        @else

            <div class="grid gap-6 lg:grid-cols-2 items-start">

                @foreach($events as $event)

                    <article class="overflow-hidden rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <!-- MULTIMEDIA -->
                        <div class="relative overflow-hidden rounded-t-3xl max-h-[480px] bg-slate-50 dark:bg-slate-700/40 flex items-center justify-center">

                            @if($event->media_path)

                                <a href="{{ asset($event->media_path) }}"
                                   download
                                   class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-600 dark:text-slate-400 shadow-sm ring-1 ring-slate-200 transition hover:bg-white dark:hover:bg-slate-800">

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-5 w-5">

                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="7 10 12 15 17 10" />
                                        <line x1="12" y1="15" x2="12" y2="3" />

                                    </svg>

                                    <span class="sr-only">Descargar</span>

                                </a>

                            @endif

                            @if($event->media_type === 'image' && $event->media_path)

                                <img
                                    src="{{ asset($event->media_path) }}"
                                    alt="{{ $event->title }}"
                                    loading="lazy"
                                    class="w-full h-auto max-h-[480px] rounded-t-3xl object-contain"
                                />

                            @elseif($event->media_type === 'video' && $event->media_path)

                                <video controls class="w-full h-auto max-h-[480px] rounded-t-3xl">

                                    <source src="{{ asset($event->media_path) }}" type="video/mp4">

                                    Tu navegador no soporta reproducir este video.

                                </video>

                            @else

                                <div class="flex h-56 w-full items-center justify-center bg-slate-100 dark:bg-slate-700/40 text-slate-500 dark:text-slate-400">
                                    Sin multimedia disponible
                                </div>

                            @endif

                        </div>

                        <!-- CONTENIDO -->
                        <div class="p-6">

                            <!-- TAGS -->
                            <div class="flex flex-wrap gap-2 text-sm mb-4">

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#0C67A3]/10 text-[#0C67A3] px-3 py-1">
                                    <x-icon name="map-pin" class="w-3.5 h-3.5" /> {{ $event->location }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#0B8B7B]/10 text-[#0B8B7B] px-3 py-1">
                                    <x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $event->event_date->format('d/m/Y') }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#CA8A04]/10 text-[#CA8A04] px-3 py-1">
                                    <x-icon name="clock" class="w-3.5 h-3.5" /> {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                                </span>

                            </div>

                            <!-- TITULO -->
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                                {{ $event->title }}
                            </h2>

                            <!-- DESCRIPCION -->
                            <p class="mt-4 text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ $event->description }}
                            </p>

                        </div>

                    </article>

                @endforeach

            </div>

        @endif

    </div>

</div>
@endsection