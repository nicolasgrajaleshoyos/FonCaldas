@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-8">
        <x-admin-tabs active="auditoria" title="Auditoría" icon="eye"
            subtitle="Trazabilidad de acciones realizadas sobre las solicitudes." />

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <form method="GET" class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="f-codigo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Código de solicitud</label>
                    <input type="text" id="f-codigo" name="codigo" value="{{ request('codigo') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5" placeholder="FC-26-XXXXXX">
                </div>
                <div>
                    <label for="f-user-id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Usuario</label>
                    <select id="f-user-id" name="user_id" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected((string) request('user_id') === (string) $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-2.5 text-white font-semibold hover:bg-[#0a3d60] transition">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 overflow-x-auto">
            @if(auth()->user()->is_super_admin)
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Selecciona registros para archivarlos o vacía el panel. Los registros archivados se conservan en la base de datos.</p>
                    <div class="flex gap-2">
                        <button type="submit" form="auditoria-bulk" onclick="return confirm('¿Archivar los registros seleccionados? Saldrán de este panel.')" class="rounded-full border border-rose-200 dark:border-rose-500/40 px-4 py-2 text-sm font-medium text-rose-600 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition">Archivar seleccionados</button>
                        <x-admin-delete :action="route('admin.auditoria.vaciar')" label="Vaciar auditoría" message="¿Vaciar el panel de auditoría? Los registros se archivan, no se borran." class="px-4" />
                    </div>
                </div>
                <form id="auditoria-bulk" method="POST" action="{{ route('admin.auditoria.destroy') }}">@csrf</form>
            @endif
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                <thead>
                    <tr>
                        @if(auth()->user()->is_super_admin)
                            <th class="px-4 py-3"><input type="checkbox" aria-label="Seleccionar todo" onclick="document.querySelectorAll('.sel-evento').forEach(c => c.checked = this.checked)"></th>
                        @endif
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Solicitud</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Acción</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Usuario</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($eventos as $evento)
                        <tr>
                            @if(auth()->user()->is_super_admin)
                                <td class="px-4 py-4"><input type="checkbox" form="auditoria-bulk" name="ids[]" value="{{ $evento->id }}" class="sel-evento" aria-label="Seleccionar registro"></td>
                            @endif
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400 whitespace-nowrap">{{ $evento->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-4">
                                @if($evento->solicitud->trashed())<span class="font-mono text-slate-500" title="Solicitud eliminada del panel">{{ $evento->solicitud->codigo }} (eliminada)</span>@else<a href="{{ route('admin.solicitudes.show', $evento->solicitud) }}" class="font-mono text-[#0C67A3]">{{ $evento->solicitud->codigo }}</a>@endif
                            </td>
                            <td class="px-4 py-4 text-slate-900 dark:text-slate-200">{{ str_replace('_', ' ', $evento->accion) }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $evento->actor() }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $evento->detalle }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-slate-600 dark:text-slate-400">No hay eventos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $eventos->links() }}</div>
        </div>
    </div>
</div>
@endsection
