@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-6xl mx-auto px-6">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3"><span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="document" class="w-6 h-6" /></span>{{ $category }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2">Documentos publicados en esta categoría.</p>
            </div>
            <a href="{{ route('transparencia') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-5 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">Volver a Transparencia</a>
        </div>

        @if($documents->isEmpty())
            <div class="rounded-3xl bg-white dark:bg-slate-800 p-8 text-center shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-slate-600 dark:text-slate-400">No hay documentos publicados en esta categoría todavía.</p>
            </div>
        @else
            @php
                $categoryStyles = [
                    'Gobierno Corporativo' => ['bg' => 'bg-[#E8F4FF]', 'text' => 'text-[#0C67A3]'],
                    'Normativa y Reglamentos' => ['bg' => 'bg-[#FFF4E6]', 'text' => 'text-[#A24B0C]'],
                    'Seguridad de la Información' => ['bg' => 'bg-[#E8F2FF]', 'text' => 'text-[#1B4ACB]'],
                    'Informes Institucionales' => ['bg' => 'bg-[#EEF8F0]', 'text' => 'text-[#11703D]'],
                    'Comunicados' => ['bg' => 'bg-[#FFF0F4]', 'text' => 'text-[#9E1750]'],
                ];
            @endphp
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($documents as $document)
                    @php $style = $categoryStyles[$document->category] ?? ['bg' => 'bg-slate-100 dark:bg-slate-700/40', 'text' => 'text-slate-700 dark:text-slate-300']; @endphp
                    <div class="group overflow-hidden rounded-[32px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center gap-4 border-b border-slate-100 dark:border-slate-700 px-6 py-5 bg-slate-50 dark:bg-slate-700/40">
                            <div class="flex h-14 w-14 items-center justify-center rounded-3xl {{ $style['bg'] }} {{ $style['text'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                                    <polyline points="14 2 14 8 20 8" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ $document->category }}</p>
                                <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $document->title }}</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-600 dark:text-slate-400">{{ $document->description }}</p>
                            <div class="mt-6 flex items-center justify-between gap-4">
                                <span class="rounded-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 px-4 py-2 text-sm text-slate-600 dark:text-slate-400">Publicado: {{ $document->published_at->format('d/m/Y') }}</span>
                                <a href="{{ asset($document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-[#0B4870] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0a3d60]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                        <path d="M12 5v14" />
                                        <path d="M5 12l7 7 7-7" />
                                    </svg>
                                    Ver PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
