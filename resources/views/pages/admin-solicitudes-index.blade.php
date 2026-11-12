@extends('layouts.app')

@section('content')
@php
    // Ficha de color según el estado de la solicitud (clases completas para que Tailwind las detecte)
    $fichaEstado = [
        'recibida' => 'bg-linear-to-br from-sky-400 to-sky-600 text-white',
        'en_revision' => 'bg-linear-to-br from-amber-400 to-amber-600 text-white',
        'aprobada' => 'bg-linear-to-br from-emerald-400 to-emerald-600 text-white',
        'rechazada' => 'bg-linear-to-br from-rose-400 to-rose-600 text-white',
        'finalizada' => 'bg-linear-to-br from-slate-400 to-slate-600 text-white',
    ];
    $hayFiltros = count(request()->except('page')) > 0;
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="solicitudes" title="Solicitudes y certificaciones" icon="clipboard"
            subtitle="Revisa, verifica y gestiona los trámites radicados por los asociados." />

        {{-- Filtros --}}
        <div class="adm-card">
            <p class="adm-titulo mb-5 text-lg"><x-icon name="search" class="w-5 h-5 text-[#0C67A3] dark:text-sky-300" /> Buscar solicitudes</p>
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label for="f-estado" class="adm-etiqueta">Estado</label>
                    <select id="f-estado" name="estado" class="adm-campo">
                        <option value="">Todos</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-tramite-tipo-id" class="adm-etiqueta">Tipo de trámite</label>
                    <select id="f-tramite-tipo-id" name="tramite_tipo_id" class="adm-campo">
                        <option value="">Todos</option>
                        @foreach($tramiteTipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected((string) request('tramite_tipo_id') === (string) $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-desde" class="adm-etiqueta">Desde</label>
                    <input type="date" id="f-desde" name="desde" value="{{ request('desde') }}" class="adm-campo">
                </div>
                <div>
                    <label for="f-hasta" class="adm-etiqueta">Hasta</label>
                    <input type="date" id="f-hasta" name="hasta" value="{{ request('hasta') }}" class="adm-campo">
                </div>
                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit" class="adm-btn flex-1"><x-icon name="search" class="w-4 h-4" /> Filtrar</button>
                    @if($hayFiltros)<a href="{{ url()->current() }}" class="adm-btn-sec py-3">Limpiar</a>@endif
                </div>
            </form>
        </div>

        {{-- Listado --}}
        <div>
            <p class="mb-3 px-1 text-[15px] font-semibold text-slate-600 dark:text-slate-300">
                {{ $solicitudes->total() }} {{ $solicitudes->total() === 1 ? 'solicitud' : 'solicitudes' }}{{ $hayFiltros ? ' con estos filtros' : '' }}
            </p>

            <div class="space-y-3">
                @forelse($solicitudes as $solicitud)
                    <div class="group relative flex flex-col gap-4 overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#0B4870]/10 lg:flex-row lg:items-center dark:border-white/10 dark:bg-slate-800">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-lg {{ $fichaEstado[$solicitud->estado] ?? $fichaEstado['finalizada'] }}">
                            <x-icon name="clipboard" class="w-7 h-7" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-lg bg-slate-100 px-2 py-0.5 font-mono text-sm font-semibold text-slate-700 dark:bg-white/10 dark:text-slate-200">{{ $solicitud->codigo }}</span>
                                <x-estado-badge :estado="$solicitud->estado" :etiqueta="$solicitud->estadoLabel()" />
                            </div>
                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ $solicitud->tramiteTipo->nombre }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="adm-chip"><x-icon name="user" class="w-4 h-4" /> {{ $solicitud->asociado_nombre }}</span>
                                <span class="adm-chip"><x-icon name="calendar" class="w-4 h-4" /> {{ $solicitud->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="adm-btn py-2.5">Ver detalle <x-icon name="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" /></a>
                            <x-admin-delete :action="route('admin.solicitudes.delete', $solicitud)" message="¿Eliminar esta solicitud del panel? Su historial y documentos se conservan para auditoría." />
                        </div>
                    </div>
                @empty
                    <div class="adm-card flex flex-col items-center py-14 text-center">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="clipboard" class="w-8 h-8" /></span>
                        <p class="mt-4 text-lg font-bold text-slate-800 dark:text-white">No hay solicitudes para mostrar</p>
                        <p class="mt-1 text-[15px] text-slate-500 dark:text-slate-300">{{ $hayFiltros ? 'Prueba quitando algún filtro.' : 'Cuando un asociado radique un trámite aparecerá aquí.' }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5">{{ $solicitudes->links() }}</div>
        </div>
    </div>
</div>
@endsection
