@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-7xl mx-auto space-y-8">
        <x-admin-tabs active="solicitudes" title="Solicitudes y certificaciones" icon="clipboard"
            subtitle="Revisa, verifica y gestiona los trámites radicados por los asociados." />

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
        @endif

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                    <select name="estado" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de trámite</label>
                    <select name="tramite_tipo_id" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($tramiteTipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected((string) request('tramite_tipo_id') === (string) $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark] px-4 py-2.5">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-2.5 text-white font-semibold hover:bg-[#0a3d60] transition">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 overflow-x-auto">
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
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @class([
                                        'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' => $solicitud->estado === 'recibida',
                                        'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' => $solicitud->estado === 'en_revision',
                                        'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' => in_array($solicitud->estado, ['aprobada', 'finalizada']),
                                        'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' => $solicitud->estado === 'rechazada',
                                    ])">{{ $solicitud->estadoLabel() }}</span>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="rounded-full border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-slate-600 dark:text-slate-400">No hay solicitudes para los filtros seleccionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $solicitudes->links() }}</div>
        </div>
    </div>
</div>
@endsection
