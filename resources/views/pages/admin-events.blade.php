@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="events" title="Gestión de Eventos" icon="calendar"
            subtitle="Sube, revisa y elimina eventos con foto o video opcional." />

        @php $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic']; @endphp
        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] xl:items-start">

            {{-- Formulario para crear --}}
            <div class="adm-card xl:sticky xl:top-28">
                <h2 class="adm-titulo mb-6"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-lg shadow-[#FACC15]/30"><x-icon name="sparkle" class="w-6 h-6" /></span>Crear nuevo evento</h2>
                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="adm-etiqueta" for="title">Nombre del evento</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="Ej.: Día de la familia" class="adm-campo" required>
                        @error('title')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="description">Descripción</label>
                        <textarea id="description" name="description" rows="3" placeholder="De qué se trata y a quién va dirigido" class="adm-campo" required>{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="location">Lugar</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Ej.: Villa Beatriz" class="adm-campo" required>
                        @error('location')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="adm-etiqueta" for="event_date">Fecha</label>
                            <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" class="adm-campo" required>
                            @error('event_date')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="adm-etiqueta" for="event_time">Hora</label>
                            <input id="event_time" name="event_time" type="time" value="{{ old('event_time') }}" class="adm-campo" required>
                            @error('event_time')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="media">Foto o video <span class="font-normal text-slate-500 dark:text-slate-400">(opcional)</span></label>
                        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 transition hover:border-[#0C67A3] dark:border-slate-600 dark:bg-slate-900 dark:hover:border-sky-400">
                            <input id="media" name="media" type="file" accept="image/*,video/mp4" class="adm-archivo">
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Imagen o video MP4.</p>
                        </div>
                        @error('media')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="adm-btn w-full"><x-icon name="sparkle" class="w-4 h-4" /> Crear evento</button>
                </form>
            </div>

            {{-- Eventos creados --}}
            <div class="adm-card">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="adm-titulo"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="calendar" class="w-6 h-6" /></span>Eventos creados</h2>
                    <span class="adm-chip font-semibold">{{ $events->total() }} en total</span>
                </div>

                @if($events->isEmpty())
                    <div class="flex flex-col items-center py-12 text-center">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="calendar" class="w-8 h-8" /></span>
                        <p class="mt-4 text-lg font-bold text-slate-800 dark:text-white">Aún no hay eventos</p>
                        <p class="mt-1 text-[15px] text-slate-500 dark:text-slate-300">Usa el formulario para crear el primero.</p>
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($events as $event)
                            @php
                                // Una ruta externa se usa tal cual; las internas pasan por asset().
                                $mediaUrl = $event->media_path ? (preg_match('/^https?:\/\//', $event->media_path) ? $event->media_path : asset($event->media_path)) : null;
                                $pasado = $event->event_date->isPast() && ! $event->event_date->isToday();
                            @endphp
                            <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-slate-900/60">
                                <div class="relative h-44 bg-slate-200 dark:bg-slate-700">
                                    @if($event->media_type === 'image' && $mediaUrl)
                                        <img src="{{ $mediaUrl }}" alt="{{ $event->title }}" loading="lazy" class="h-full w-full object-cover" />
                                    @elseif($event->media_type === 'video' && $mediaUrl)
                                        <video controls preload="metadata" class="h-full w-full bg-black object-cover">
                                            <source src="{{ $mediaUrl }}" type="video/mp4">
                                            Tu navegador no soporta video.
                                        </video>
                                    @else
                                        <div class="flex h-full items-center justify-center text-slate-500 dark:text-slate-300"><x-icon name="calendar" class="w-10 h-10 opacity-50" /></div>
                                    @endif
                                    <!-- Fecha tipo hoja de calendario -->
                                    <div class="pointer-events-none absolute left-3 top-3 w-14 overflow-hidden rounded-xl bg-white text-center shadow-lg">
                                        <span class="block bg-[#FACC15] py-0.5 text-[11px] font-bold uppercase tracking-widest text-[#0B4870]">{{ $meses[$event->event_date->month - 1] }}</span>
                                        <span class="hero-title block py-1 text-xl leading-none text-[#0B4870]">{{ $event->event_date->format('d') }}</span>
                                    </div>
                                    @if($pasado)
                                        <span class="pointer-events-none absolute right-3 top-3 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-semibold text-white">Ya pasó</span>
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-col p-4">
                                    <h3 class="text-lg font-bold text-slate-900 first-letter:uppercase dark:text-white">{{ $event->title }}</h3>
                                    <p class="mt-1 line-clamp-2 text-[15px] text-slate-600 dark:text-slate-300">{{ $event->description }}</p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="adm-chip"><x-icon name="map-pin" class="w-4 h-4" /> {{ $event->location }}</span>
                                        <span class="adm-chip"><x-icon name="clock" class="w-4 h-4" /> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i a') }}</span>
                                    </div>
                                    <div class="mt-auto flex flex-wrap gap-2 pt-4">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="adm-btn-sec"><x-icon name="wrench" class="w-4 h-4" /> Editar</a>
                                        <form method="POST" action="{{ route('admin.events.delete', $event) }}" data-confirm="¿Eliminar este evento?">
                                            @csrf
                                            <button type="submit" class="adm-btn-peligro">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">{{ $events->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
