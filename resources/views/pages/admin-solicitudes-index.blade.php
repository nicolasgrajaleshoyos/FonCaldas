@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-8">
        <x-admin-tabs active="solicitudes" title="Solicitudes y certificaciones" icon="clipboard"
            subtitle="Revisa, verifica y gestiona los trámites radicados por los asociados." />

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label for="f-estado" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                    <select id="f-estado" name="estado" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-tramite-tipo-id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de trámite</label>
                    <select id="f-tramite-tipo-id" name="tramite_tipo_id" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($tramiteTipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected((string) request('tramite_tipo_id') === (string) $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-desde" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Desde</label>
                    <input type="date" id="f-desde" name="desde" value="{{ request('desde') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-2.5">
                </div>
                <div>
                    <label for="f-hasta" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hasta</label>
                    <input type="date" id="f-hasta" name="hasta" value="{{ request('hasta') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-2.5">
                </div>
                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit" class="flex-1 rounded-lg bg-[#0B4870] px-4 py-2.5 text-white font-semibold hover:bg-[#0a3d60] transition">Filtrar</button>
                    @if(count(request()->except('page')))<a href="{{ url()->current() }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">Limpiar</a>@endif
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Código</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Trámite</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Asociado</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td class="px-4 py-4 font-mono text-slate-900 dark:text-slate-200">{{ $solicitud->codigo }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $solicitud->tramiteTipo->nombre }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $solicitud->asociado_nombre }}</td>
                            <td class="px-4 py-4">
                                <x-estado-badge :estado="$solicitud->estado" :etiqueta="$solicitud->estadoLabel()" />
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="rounded-full border border-slate-200 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Ver</a>
                                <x-admin-delete :action="route('admin.solicitudes.delete', $solicitud)" message="¿Eliminar esta solicitud del panel? Su historial y documentos se conservan para auditoría." />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">
                                <p class="font-semibold text-slate-700 dark:text-slate-200">No hay solicitudes para mostrar</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ count(request()->except('page')) ? 'Prueba quitando algún filtro.' : 'Cuando un asociado radique un trámite aparecerá aquí.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $solicitudes->links() }}</div>
        </div>
    </div>
</div>
@endsection
