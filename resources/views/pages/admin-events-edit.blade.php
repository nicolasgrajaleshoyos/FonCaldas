@extends('layouts.app')

@section('content')
@php
    $input = 'w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5 focus:outline-none';
    $label = 'block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5';
    $card = 'rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-white/10';
    $mediaUrl = $event->media_path ? (preg_match('/^https?:\/\//', $event->media_path) ? $event->media_path : asset($event->media_path)) : null;
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="events" title="Editar evento" icon="ticket" subtitle="{{ $event->title }}" />

        <a href="{{ route('admin.events') }}" class="inline-block text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">← Volver al listado</a>

        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3 items-start">
            @csrf
            @method('PUT')

            {{-- Datos del evento --}}
            <div class="{{ $card }} lg:col-span-2 space-y-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Información del evento</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300">Estos datos se muestran en la página pública de eventos.</p>
                </div>

                <div>
                    <label class="{{ $label }}" for="title">Nombre del evento</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" class="{{ $input }}" required>
                    @error('title')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}" for="description">Descripción</label>
                    <textarea id="description" name="description" rows="5" class="{{ $input }}" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-1">
                        <label class="{{ $label }}" for="location">Lugar</label>
                        <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" class="{{ $input }}" required>
                        @error('location')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="{{ $label }}" for="event_date">Fecha</label>
                        <input id="event_date" name="event_date" type="date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" class="{{ $input }} dark:[color-scheme:dark]" required>
                        @error('event_date')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="{{ $label }}" for="event_time">Hora</label>
                        <input id="event_time" name="event_time" type="time" value="{{ old('event_time', \Carbon\Carbon::parse($event->event_time)->format('H:i')) }}" class="{{ $input }} dark:[color-scheme:dark]" required>
                        @error('event_time')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

            </div>

            {{-- Multimedia --}}
            <div class="space-y-6 lg:sticky lg:top-24">
                <div class="{{ $card }} space-y-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Foto o video</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-300">Opcional. Sube un archivo nuevo solo si quieres reemplazar el actual.</p>
                    </div>

                    @if($mediaUrl)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900">
                            @if($event->media_type === 'video')
                                <video controls class="aspect-video w-full bg-black"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
                            @else
                                <img src="{{ $mediaUrl }}" alt="{{ $event->title }}" class="aspect-video w-full object-cover">
                            @endif
                        </div>
                    @else
                        <div class="flex aspect-video items-center justify-center rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 text-sm text-slate-500 dark:text-slate-300">Sin multimedia</div>
                    @endif

                    <input id="media" name="media" type="file" accept="image/*,video/mp4" aria-label="Reemplazar foto o video"
                        class="w-full text-sm text-slate-700 dark:text-slate-300 file:mr-3 file:rounded-full file:border file:border-slate-300 dark:file:border-slate-600 file:bg-slate-100 dark:file:bg-slate-700 file:px-4 file:py-2 file:text-slate-700 dark:file:text-slate-200 file:font-medium">
                    @error('media')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="lg:col-span-3 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.events') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 dark:border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Cancelar</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0C67A3] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#095c8f] transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5"><x-icon name="check" class="w-4 h-4" /> Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
