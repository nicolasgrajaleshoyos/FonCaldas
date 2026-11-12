@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="documents" title="Gestión Documental" icon="document"
            subtitle="Sube, revisa y elimina documentos PDF de la página de transparencia." />

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] xl:items-start">

            {{-- Formulario para subir --}}
            <div class="adm-card xl:sticky xl:top-28">
                <h2 class="adm-titulo mb-6"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="upload" class="w-6 h-6" /></span>Subir nuevo documento</h2>
                <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="adm-etiqueta" for="title">Título</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="Ej.: Estados financieros 2026" class="adm-campo" required>
                        @error('title')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="description">Descripción</label>
                        <textarea id="description" name="description" rows="3" placeholder="Qué encontrará el asociado en este documento" class="adm-campo" required>{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="category">Categoría</label>
                        <select id="category" name="category" required class="adm-campo">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" @selected(old('category') === $key)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="adm-etiqueta" for="document">Archivo PDF</label>
                        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 transition hover:border-[#0C67A3] dark:border-slate-600 dark:bg-slate-900 dark:hover:border-sky-400">
                            <input id="document" name="document" type="file" accept="application/pdf" class="adm-archivo" required>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Solo archivos PDF.</p>
                        </div>
                        @error('document')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="adm-btn w-full"><x-icon name="upload" class="w-4 h-4" /> Subir documento</button>
                </form>
            </div>

            {{-- Documentos publicados --}}
            <div class="adm-card">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="adm-titulo"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-lg shadow-[#0B8B7B]/30"><x-icon name="layers" class="w-6 h-6" /></span>Documentos publicados</h2>
                    <span class="adm-chip font-semibold">{{ $documents->total() }} en total</span>
                </div>

                <div class="space-y-3">
                    @forelse($documents as $document)
                        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#0C67A3]/40 hover:bg-white hover:shadow-md sm:flex-row sm:items-center dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900">
                            <span class="inline-flex h-14 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-[11px] font-black tracking-wider text-white shadow-md shadow-rose-500/30">PDF</span>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $document->title }}</p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-[#0B8B7B]/10 px-2.5 py-0.5 text-xs font-semibold text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300">{{ $document->category }}</span>
                                    <span class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-300"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $document->published_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-wrap gap-2">
                                <a href="{{ asset($document->file_path) }}" target="_blank" rel="noopener" class="adm-btn-sec"><x-icon name="eye" class="w-4 h-4" /> Ver</a>
                                <a href="{{ route('admin.documents.edit', $document) }}" class="adm-btn-sec"><x-icon name="wrench" class="w-4 h-4" /> Editar</a>
                                <form method="POST" action="{{ route('admin.documents.delete', $document) }}" data-confirm="¿Eliminar este documento?">
                                    @csrf
                                    <button type="submit" class="adm-btn-peligro">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center py-12 text-center">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="folder" class="w-8 h-8" /></span>
                            <p class="mt-4 text-lg font-bold text-slate-800 dark:text-white">Aún no hay documentos</p>
                            <p class="mt-1 text-[15px] text-slate-500 dark:text-slate-300">Usa el formulario para subir el primero.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-5">{{ $documents->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
