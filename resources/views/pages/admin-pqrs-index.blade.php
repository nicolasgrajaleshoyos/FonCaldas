@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-8">
        <x-admin-tabs active="pqrs" title="PQRS" icon="clipboard"
            subtitle="Peticiones, quejas, reclamos y sugerencias radicadas por los asociados." />

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <form method="GET" class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="f-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <select id="f-tipo" name="tipo" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" @selected(request('tipo') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-estado" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                    <select id="f-estado" name="estado" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-[#0B4870] px-4 py-2.5 text-white font-semibold hover:bg-[#0a3d60] transition">Filtrar</button>
                    @if(count(request()->except('page')))<a href="{{ url()->current() }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">Limpiar</a>@endif
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                <thead>
                    <tr>
                        @foreach(['Código', 'Tipo', 'Asunto', 'Asociado', 'Estado', 'Fecha', 'Acciones'] as $th)
                            <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($pqrs as $item)
                        <tr>
                            <td class="px-4 py-4 font-mono text-slate-900 dark:text-slate-200">{{ $item->codigo }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $item->tipoLabel() }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($item->asunto, 50) }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $item->nombre }}</td>
                            <td class="px-4 py-4">
                                <x-estado-badge :estado="$item->estado" :etiqueta="$item->estadoLabel()" />
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.pqrs.show', $item) }}" class="rounded-full border border-slate-200 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Ver</a>
                                    <x-admin-delete :action="route('admin.pqrs.delete', $item)" message="¿Eliminar esta PQRS del panel? El registro se conserva." />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center">
                            <p class="font-semibold text-slate-700 dark:text-slate-200">No hay PQRS para mostrar</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ count(request()->except('page')) ? 'Prueba quitando algún filtro.' : 'Cuando un asociado radique una PQRS aparecerá aquí.' }}</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $pqrs->links() }}</div>
        </div>
    </div>
</div>
@endsection
