@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-7xl mx-auto space-y-8">
        <x-admin-tabs active="events" title="Gestión de Eventos" icon="ticket"
            subtitle="Sube, revisa y elimina eventos con foto o video opcional." />

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="sparkle" class="w-5 h-5" /></span>Crear nuevo evento</h2>
                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="title">Nombre del evento</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                            required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="description">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="location">Lugar</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}"
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                            required>
                        @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="event_date">Fecha</label>
                            <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}"
                                class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                                required>
                            @error('event_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="event_time">Hora</label>
                            <input id="event_time" name="event_time" type="time" value="{{ old('event_time') }}"
                                class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                                required>
                            @error('event_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="media">Foto o video (opcional)</label>
                        <input id="media" name="media" type="file" accept="image/*,video/mp4"
                            class="w-full text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium">
                        @error('media')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#0C67A3] px-5 py-3 text-white font-semibold hover:bg-[#095c8f] transition">
                        Crear evento
                    </button>
                </form>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 overflow-x-auto">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="calendar" class="w-5 h-5" /></span>Eventos creados</h2>

                @if($events->isEmpty())
                    <p class="text-slate-600 dark:text-slate-400">No hay eventos creados aún.</p>
                @else
                    <div class="space-y-4">
                        @foreach($events as $event)
                            @php
                                $mediaUrl = null;
                                if ($event->media_path) {
                                    if (preg_match('/^https?:\/\//', $event->media_path)) {
                                        $mediaUrl = $event->media_path;
                                    } elseif (strpos($event->media_path, 'storage/') === 0 || strpos($event->media_path, 'uploads/') === 0) {
                                        $mediaUrl = asset($event->media_path);
                                    } else {
                                        $mediaUrl = asset($event->media_path);
                                    }
                                }
                            @endphp
                            <div class="rounded-3xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                                    <div class="w-full lg:w-1/3">
                                        @if($event->media_type === 'image' && $mediaUrl)
                                            <img src="{{ $mediaUrl }}" alt="{{ $event->title }}" class="h-40 w-full rounded-3xl object-cover" />
                                        @elseif($event->media_type === 'video' && $mediaUrl)
                                            <video controls class="h-40 w-full rounded-3xl bg-black">
                                                <source src="{{ $mediaUrl }}" type="video/mp4">
                                                Tu navegador no soporta video.
                                            </video>
                                        @else
                                            <div class="flex h-40 items-center justify-center rounded-3xl bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400">Sin multimedia</div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $event->title }}</h3>
                                        <p class="mt-2 text-slate-600 dark:text-slate-400">{{ $event->description }}</p>
                                        <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-400">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white dark:bg-slate-800 px-3 py-1 border border-slate-200 dark:border-slate-600"><x-icon name="map-pin" class="w-3.5 h-3.5" /> Lugar: {{ $event->location }}</span>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white dark:bg-slate-800 px-3 py-1 border border-slate-200 dark:border-slate-600"><x-icon name="calendar" class="w-3.5 h-3.5" /> Fecha: {{ $event->event_date->format('d/m/Y') }}</span>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white dark:bg-slate-800 px-3 py-1 border border-slate-200 dark:border-slate-600"><x-icon name="clock" class="w-3.5 h-3.5" /> Hora: {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                                        </div>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('admin.events.delete', $event) }}" onsubmit="return confirm('Eliminar este evento?');">
                                                @csrf
                                                <button type="submit" class="rounded-full border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-3 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 transition">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
