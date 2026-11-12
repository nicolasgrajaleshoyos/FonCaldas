@extends('layouts.app')

@section('content')
@php
    $input = 'w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5 focus:outline-none';
    $label = 'block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5';
    $card = 'rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-white/10';
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="documents" title="Editar documento" icon="document" subtitle="{{ $document->title }}" />

        <a href="{{ route('admin.documents') }}" class="inline-block text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">← Volver al listado</a>

        <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3 items-start">
            @csrf
            @method('PUT')

            <div class="{{ $card }} lg:col-span-2 space-y-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Información del documento</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300">Estos datos se muestran en la página de transparencia.</p>
                </div>

                <div>
                    <label class="{{ $label }}" for="title">Título</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $document->title) }}" class="{{ $input }}" required>
                    @error('title')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}" for="category">Categoría</label>
                    <select id="category" name="category" required class="{{ $input }}">
                        @unless($categoriaActual)
                            <option value="" disabled selected>Selecciona una categoría</option>
                        @endunless
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}" @selected(old('category', $categoriaActual) === $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}" for="description">Descripción</label>
                    <textarea id="description" name="description" rows="5" class="{{ $input }}" required>{{ old('description', $document->description) }}</textarea>
                    @error('description')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

            </div>

            <div class="space-y-6 lg:sticky lg:top-24">
                <div class="{{ $card }} space-y-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Archivo PDF</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-300">Sube un PDF nuevo solo si quieres reemplazar el actual.</p>
                    </div>

                    <a href="{{ asset($document->file_path) }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/50 p-3 transition hover:border-[#0C67A3]/50">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 text-[#0C67A3] dark:text-[#5EB3E4]"><x-icon name="document" class="w-5 h-5" /></span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">Documento actual</span>
                            <span class="block truncate text-xs text-slate-500 dark:text-slate-300">Publicado el {{ $document->published_at?->format('d/m/Y') }} · abrir PDF</span>
                        </span>
                    </a>

                    <input id="document" name="document" type="file" accept="application/pdf" aria-label="Reemplazar documento PDF"
                        class="w-full text-sm text-slate-700 dark:text-slate-300 file:mr-3 file:rounded-full file:border file:border-slate-300 dark:file:border-slate-600 file:bg-slate-100 dark:file:bg-slate-700 file:px-4 file:py-2 file:text-slate-700 dark:file:text-slate-200 file:font-medium">
                    @error('document')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="lg:col-span-3 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.documents') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 dark:border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Cancelar</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0C67A3] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#095c8f] transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5"><x-icon name="check" class="w-4 h-4" /> Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
