@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-7xl mx-auto space-y-8">
        <x-admin-tabs active="documents" title="Gestión Documental" icon="document"
            subtitle="Sube, revisa y elimina documentos PDF de la página de transparencia." />

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="upload" class="w-5 h-5" /></span>Subir nuevo documento</h2>
                <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="title">Título</label>
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
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="category">Categoría</label>
                        <select id="category" name="category" required
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" @selected(old('category') === $key)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="document">Archivo PDF</label>
                        <input id="document" name="document" type="file" accept="application/pdf"
                            class="w-full text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium"
                            required>
                        @error('document')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#0C67A3] px-5 py-3 text-white font-semibold hover:bg-[#095c8f] transition">
                        Subir documento
                    </button>
                </form>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 overflow-x-auto">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="layers" class="w-5 h-5" /></span>Documentos actuales</h2>
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Título</th>
                            <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Categoría</th>
                            <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                            <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($documents as $document)
                            <tr>
                                <td class="px-4 py-4 text-slate-900 dark:text-slate-200">{{ $document->title }}</td>
                                <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $document->category }}</td>
                                <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $document->published_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ asset($document->file_path) }}" target="_blank" class="rounded-full border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Ver</a>
                                        <form method="POST" action="{{ route('admin.documents.delete', $document) }}" onsubmit="return confirm('Eliminar este documento?');">
                                            @csrf
                                            <button type="submit" class="rounded-full border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-3 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 transition">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-slate-600 dark:text-slate-400">No hay documentos cargados aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
