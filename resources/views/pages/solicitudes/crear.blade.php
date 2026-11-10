@extends('layouts.app')

@section('title', 'Radicar un trámite | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-3xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot pose="tramite" class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="Radica tu trámite o solicitud"
                subtitle="No necesitas usuario ni contraseña. Solo ingresa tus datos y el equipo de FONCALDAS verificará tu identidad."
                center />
        </div>

        <div class="mb-8 flex flex-col items-center gap-4 rounded-2xl border border-[#0C67A3]/30 bg-[#0C67A3]/10 px-5 py-4 text-center sm:flex-row sm:justify-between sm:text-left">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0C67A3] text-white"><x-icon name="search" class="w-5 h-5" /></span>
                <div>
                    <p class="font-bold text-slate-900 dark:text-white">¿Ya radicaste una solicitud?</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Consulta en qué estado va con tu código y tu documento.</p>
                </div>
            </div>
            <a href="{{ route('solicitudes.consultar') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-[#0C67A3] px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#095c8f]">Consultar estado</a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
            <form method="POST" action="{{ route('solicitudes.store') }}" data-cargando="Radicando tu solicitud…" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="f-tramite-tipo-id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de trámite</label>
                    <select id="f-tramite-tipo-id" name="tramite_tipo_id" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        <option value="">Selecciona una opción</option>
                        @foreach($tramiteTipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected(old('tramite_tipo_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tramite_tipo_id')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="f-asociado-nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre completo</label>
                        <input type="text" id="f-asociado-nombre" name="asociado_nombre" value="{{ old('asociado_nombre') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_nombre')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-asociado-documento" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de documento</label>
                        <input type="text" id="f-asociado-documento" name="asociado_documento" value="{{ old('asociado_documento') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_documento')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-asociado-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo electrónico</label>
                        <input type="email" id="f-asociado-email" name="asociado_email" value="{{ old('asociado_email') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Aquí te enviaremos el código para consultar tu solicitud.</p>
                        @error('asociado_email')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-asociado-telefono" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teléfono (opcional)</label>
                        <input type="text" id="f-asociado-telefono" name="asociado_telefono" value="{{ old('asociado_telefono') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_telefono')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="f-descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cuéntanos qué necesitas</label>
                    <textarea id="f-descripcion" name="descripcion" rows="4" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="f-documentos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Documentos de soporte (opcional)</label>
                    <input type="file" id="f-documentos" name="documentos[]" multiple class="w-full text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium">
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Hasta 10 MB por archivo. Formatos: PDF, JPG, PNG, Word y Excel.</p>
                    @error('documentos.*')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition inline-flex items-center justify-center gap-2">
                    Radicar solicitud
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
