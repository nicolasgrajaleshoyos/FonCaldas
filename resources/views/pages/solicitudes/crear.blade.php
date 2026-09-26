@extends('layouts.app')

@section('title', 'Radicar un trámite | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-3xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="Radica tu trámite o solicitud"
                subtitle="No necesitas usuario ni contraseña. Solo ingresa tus datos y el equipo de FONCALDAS verificará tu identidad."
                center />
        </div>

        <div class="mb-6 text-center">
            <a href="{{ route('solicitudes.consultar') }}" class="text-sm font-semibold text-[#0C67A3] hover:underline">¿Ya radicaste una solicitud? Consulta su estado aquí →</a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
            <form method="POST" action="{{ route('solicitudes.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de trámite</label>
                    <select name="tramite_tipo_id" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        <option value="">Selecciona una opción</option>
                        @foreach($tramiteTipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected(old('tramite_tipo_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tramite_tipo_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre completo</label>
                        <input type="text" name="asociado_nombre" value="{{ old('asociado_nombre') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_nombre')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de documento</label>
                        <input type="text" name="asociado_documento" value="{{ old('asociado_documento') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_documento')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo electrónico</label>
                        <input type="email" name="asociado_email" value="{{ old('asociado_email') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                        @error('asociado_email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teléfono (opcional)</label>
                        <input type="text" name="asociado_telefono" value="{{ old('asociado_telefono') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cuéntanos qué necesitas</label>
                    <textarea name="descripcion" rows="4" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Documentos de soporte (opcional)</label>
                    <input type="file" name="documentos[]" multiple class="w-full text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium">
                    @error('documentos.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition inline-flex items-center justify-center gap-2">
                    Radicar solicitud <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
