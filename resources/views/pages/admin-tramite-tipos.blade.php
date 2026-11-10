@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-8">
        <x-admin-tabs active="tramite-tipos" title="Tipos de trámite" icon="briefcase"
            subtitle="Agrega nuevas categorías de trámite o certificación sin afectar la operación actual." />

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">
            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Nuevo tipo de trámite</h2>
                <form method="POST" action="{{ route('admin.tramite-tipos.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="f-nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                        <input type="text" id="f-nombre" name="nombre" value="{{ old('nombre') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3">
                        @error('nombre')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                        <input type="text" id="f-descripcion" name="descripcion" value="{{ old('descripcion') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3">
                        @error('descripcion')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="es_certificacion" value="1" @checked(old('es_certificacion'))>
                        Es una certificación (el asociado descarga un documento generado por FONCALDAS)
                    </label>
                    <button type="submit" class="w-full rounded-lg bg-[#0C67A3] px-4 py-3 text-white font-semibold hover:bg-[#095c8f] transition">Crear</button>
                </form>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 overflow-x-auto">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Tipos existentes</h2>
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 font-medium text-slate-700 dark:text-slate-300">Nombre</th>
                            <th class="px-3 py-2 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                            <th class="px-3 py-2 font-medium text-slate-700 dark:text-slate-300"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($tramiteTipos as $tipo)
                            <tr>
                                <td class="px-3 py-3 text-slate-900 dark:text-slate-200">{{ $tipo->nombre }} @if($tipo->es_certificacion)<span class="text-xs text-slate-400">(certificación)</span>@endif</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tipo->activo ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">{{ $tipo->activo ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                    <form method="POST" action="{{ route('admin.tramite-tipos.toggle', $tipo) }}" onsubmit="return confirm('{{ $tipo->activo ? '¿Desactivar este tipo de trámite? Dejará de aparecer en el formulario público.' : '¿Activar este tipo de trámite?' }}')">
                                        @csrf
                                        <button type="submit" class="rounded-full border border-slate-200 dark:border-slate-600 px-3.5 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">{{ $tipo->activo ? 'Desactivar' : 'Activar' }}</button>
                                    </form>
                                    <a href="{{ route('admin.tramite-tipos.edit', $tipo) }}" class="inline-flex rounded-full border border-slate-200 dark:border-slate-600 px-3.5 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Editar</a>
                                    <x-admin-delete :action="route('admin.tramite-tipos.delete', $tipo)" class="py-2 text-sm" message="¿Eliminar el tipo de trámite «{{ $tipo->nombre }}»? Solo es posible si no tiene solicitudes." />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
