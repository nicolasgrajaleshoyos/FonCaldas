@extends('layouts.app')

@section('content')
@php
    $esSuper = auth()->user()->esPropietario(); // archivar registros es solo del propietario
    $hayFiltros = count(request()->except('page')) > 0;
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="auditoria" title="Auditoría" icon="eye"
            subtitle="Trazabilidad de acciones realizadas sobre las solicitudes." />

        {{-- Filtros --}}
        <div class="adm-card">
            <p class="adm-titulo mb-5 text-lg"><x-icon name="search" class="w-5 h-5 text-[#0C67A3] dark:text-sky-300" /> Buscar en el historial</p>
            <form method="GET" class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="f-codigo" class="adm-etiqueta">Código de solicitud</label>
                    <input type="text" id="f-codigo" name="codigo" value="{{ request('codigo') }}" class="adm-campo font-mono" placeholder="FC-26-XXXXXX">
                </div>
                <div>
                    <label for="f-user-id" class="adm-etiqueta">Usuario</label>
                    <select id="f-user-id" name="user_id" class="adm-campo">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected((string) request('user_id') === (string) $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="adm-btn flex-1"><x-icon name="search" class="w-4 h-4" /> Filtrar</button>
                    @if($hayFiltros)<a href="{{ url()->current() }}" class="adm-btn-sec py-3">Limpiar</a>@endif
                </div>
            </form>
        </div>

        {{-- Historial en forma de línea de tiempo --}}
        <div class="adm-card">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="adm-titulo"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-lg shadow-[#FACC15]/30"><x-icon name="clock" class="w-6 h-6" /></span>Historial de acciones</h2>
                <span class="adm-chip font-semibold">{{ $eventos->total() }} registros</span>
            </div>

            @if($esSuper)
                <div class="mb-6 flex flex-col gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-amber-400/30 dark:bg-amber-400/10">
                    <label class="inline-flex cursor-pointer items-center gap-3 text-[15px] font-semibold text-slate-800 dark:text-slate-100">
                        <input type="checkbox" class="h-5 w-5 rounded accent-[#0C67A3]" aria-label="Seleccionar todo" onclick="document.querySelectorAll('.sel-evento').forEach(c => c.checked = this.checked)">
                        Seleccionar todo
                    </label>
                    <p class="flex-1 text-sm text-amber-900 sm:px-2 dark:text-amber-100/90">Archivar quita los registros de este panel, pero se conservan en la base de datos.</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" form="auditoria-bulk" class="adm-btn-peligro">Archivar seleccionados</button>
                        <x-admin-delete :action="route('admin.auditoria.vaciar')" label="Vaciar auditoría" message="¿Vaciar el panel de auditoría? Los registros se archivan, no se borran." />
                    </div>
                </div>
                <form id="auditoria-bulk" method="POST" action="{{ route('admin.auditoria.destroy') }}" data-confirm="¿Archivar los registros seleccionados? Saldrán de este panel.">@csrf</form>
            @endif

            @if($eventos->isEmpty())
                <div class="flex flex-col items-center py-12 text-center">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="eye" class="w-8 h-8" /></span>
                    <p class="mt-4 text-lg font-bold text-slate-800 dark:text-white">No hay acciones registradas</p>
                    <p class="mt-1 text-[15px] text-slate-500 dark:text-slate-300">{{ $hayFiltros ? 'Prueba quitando algún filtro.' : 'Cada vez que alguien gestione una solicitud quedará anotado aquí.' }}</p>
                </div>
            @else
                <ol class="relative space-y-3 before:absolute before:bottom-4 before:left-[1.4rem] before:top-4 before:w-0.5 before:bg-slate-200 dark:before:bg-white/10 {{ $esSuper ? 'sm:pl-0' : '' }}">
                    @foreach($eventos as $evento)
                        <li class="relative flex gap-4">
                            <!-- Punto de la línea de tiempo con la inicial de quien hizo la acción -->
                            <span class="relative z-10 inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md ring-4 ring-white dark:ring-slate-800">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($evento->actor(), 0, 1)) }}</span>

                            <div class="flex min-w-0 flex-1 flex-col gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-white hover:shadow-md sm:flex-row sm:items-start dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-[15px] font-bold capitalize text-slate-900 dark:text-white">{{ str_replace('_', ' ', $evento->accion) }}</span>
                                        @if($evento->solicitud->trashed())
                                            <span class="rounded-lg bg-slate-200 px-2 py-0.5 font-mono text-xs font-semibold text-slate-500 dark:bg-white/10 dark:text-slate-400" title="Solicitud eliminada del panel">{{ $evento->solicitud->codigo }} (eliminada)</span>
                                        @else
                                            <a href="{{ route('admin.solicitudes.show', $evento->solicitud) }}" class="rounded-lg bg-[#0C67A3]/10 px-2 py-0.5 font-mono text-xs font-semibold text-[#0C67A3] transition hover:bg-[#0C67A3] hover:text-white dark:bg-sky-400/15 dark:text-sky-300">{{ $evento->solicitud->codigo }}</a>
                                        @endif
                                    </div>
                                    @if($evento->detalle)
                                        <p class="mt-1 text-[15px] text-slate-600 dark:text-slate-300">{{ $evento->detalle }}</p>
                                    @endif
                                    <p class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-500 dark:text-slate-300">
                                        <span class="inline-flex items-center gap-1.5"><x-icon name="user" class="w-4 h-4" /> {{ $evento->actor() }}</span>
                                        <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4" /> {{ $evento->created_at->format('d/m/Y · g:i a') }}</span>
                                    </p>
                                </div>
                                @if($esSuper)
                                    <label class="inline-flex shrink-0 cursor-pointer items-center gap-2 text-sm text-slate-500 dark:text-slate-300">
                                        <input type="checkbox" form="auditoria-bulk" name="ids[]" value="{{ $evento->id }}" class="sel-evento h-5 w-5 rounded accent-[#0C67A3]" aria-label="Seleccionar registro">
                                        Seleccionar
                                    </label>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif

            <div class="mt-5">{{ $eventos->links() }}</div>
        </div>
    </div>
</div>
@endsection
