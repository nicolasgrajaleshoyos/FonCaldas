@extends('layouts.app')

@section('content')
@php
    $input = 'w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5 focus:outline-none';
    $label = 'block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5';
    $card = 'rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-white/10';
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="tramite-tipos" title="Editar tipo de trámite" icon="briefcase" subtitle="{{ $tipo->nombre }}" />

        <a href="{{ route('admin.tramite-tipos.index') }}" class="inline-block text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">← Volver al listado</a>

        <form method="POST" action="{{ route('admin.tramite-tipos.update', $tipo) }}" class="grid gap-6 lg:grid-cols-3 items-start">
            @csrf
            @method('PUT')

            <div class="{{ $card }} lg:col-span-2 space-y-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Información del trámite</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300">Cambiar el nombre no afecta las solicitudes ya radicadas.</p>
                </div>

                <div>
                    <label class="{{ $label }}" for="nombre">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $tipo->nombre) }}" required class="{{ $input }}">
                    @error('nombre')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}" for="descripcion">Descripción (opcional)</label>
                    <input id="descripcion" type="text" name="descripcion" value="{{ old('descripcion', $tipo->descripcion) }}" class="{{ $input }}">
                    @error('descripcion')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/50 p-4 text-sm text-slate-700 dark:text-slate-300">
                    <input type="checkbox" name="es_certificacion" value="1" class="mt-0.5" @checked(old('es_certificacion', $tipo->es_certificacion))>
                    <span><span class="block font-semibold text-slate-800 dark:text-slate-100">Es una certificación</span>El asociado descarga un documento generado por FONCALDAS.</span>
                </label>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 dark:border-white/10 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.tramite-tipos.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 dark:border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Cancelar</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0C67A3] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#095c8f] transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5"><x-icon name="check" class="w-4 h-4" /> Guardar cambios</button>
                </div>
            </div>

            <div class="space-y-6 lg:sticky lg:top-24">
                <div class="{{ $card }} space-y-3 text-sm">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Resumen</h2>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-300">Estado</span><span class="font-semibold {{ $tipo->activo ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }}">{{ $tipo->activo ? 'Activo' : 'Inactivo' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-300">Solicitudes</span><span class="font-semibold text-slate-900 dark:text-white">{{ $tipo->solicitudes()->count() }}</span></div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
