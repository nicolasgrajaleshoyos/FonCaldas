@extends('layouts.app')

@section('content')
@php
    // Ícono y color de cada tipo de PQRS (mismos que en el formulario público)
    $fichaTipo = [
        'peticion' => ['document', 'bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white'],
        'queja' => ['bolt', 'bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870]'],
        'reclamo' => ['shield', 'bg-linear-to-br from-rose-500 to-red-600 text-white'],
        'sugerencia' => ['sparkle', 'bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white'],
    ];
    $hayFiltros = count(request()->except('page')) > 0;
@endphp
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="pqrs" title="PQRS" icon="ticket"
            subtitle="Peticiones, quejas, reclamos y sugerencias radicadas por los asociados." />

        {{-- Filtros --}}
        <div class="adm-card">
            <p class="adm-titulo mb-5 text-lg"><x-icon name="search" class="w-5 h-5 text-[#0C67A3] dark:text-sky-300" /> Buscar PQRS</p>
            <form method="GET" class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="f-tipo" class="adm-etiqueta">Tipo</label>
                    <select id="f-tipo" name="tipo" class="adm-campo">
                        <option value="">Todos</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" @selected(request('tipo') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="f-estado" class="adm-etiqueta">Estado</label>
                    <select id="f-estado" name="estado" class="adm-campo">
                        <option value="">Todos</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="adm-btn flex-1"><x-icon name="search" class="w-4 h-4" /> Filtrar</button>
                    @if($hayFiltros)<a href="{{ url()->current() }}" class="adm-btn-sec py-3">Limpiar</a>@endif
                </div>
            </form>
        </div>

        {{-- Listado --}}
        <div>
            <p class="mb-3 px-1 text-[15px] font-semibold text-slate-600 dark:text-slate-300">
                {{ $pqrs->total() }} PQRS{{ $hayFiltros ? ' con estos filtros' : '' }}
            </p>

            <div class="space-y-3">
                @forelse($pqrs as $item)
                    @php [$icono, $ficha] = $fichaTipo[$item->tipo] ?? $fichaTipo['peticion']; @endphp
                    <div class="group flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#0B4870]/10 lg:flex-row lg:items-center dark:border-white/10 dark:bg-slate-800">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-lg {{ $ficha }}">
                            <x-icon :name="$icono" class="w-7 h-7" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-lg bg-slate-100 px-2 py-0.5 font-mono text-sm font-semibold text-slate-700 dark:bg-white/10 dark:text-slate-200">{{ $item->codigo }}</span>
                                <span class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-300">{{ $item->tipoLabel() }}</span>
                                <x-estado-badge :estado="$item->estado" :etiqueta="$item->estadoLabel()" />
                            </div>
                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ \Illuminate\Support\Str::limit($item->asunto, 90) }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="adm-chip"><x-icon name="user" class="w-4 h-4" /> {{ $item->nombre }}</span>
                                <span class="adm-chip"><x-icon name="calendar" class="w-4 h-4" /> {{ $item->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            <a href="{{ route('admin.pqrs.show', $item) }}" class="adm-btn py-2.5">Ver y responder <x-icon name="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" /></a>
                            <x-admin-delete :action="route('admin.pqrs.delete', $item)" message="¿Eliminar esta PQRS del panel? El registro se conserva." />
                        </div>
                    </div>
                @empty
                    <div class="adm-card flex flex-col items-center py-14 text-center">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="ticket" class="w-8 h-8" /></span>
                        <p class="mt-4 text-lg font-bold text-slate-800 dark:text-white">No hay PQRS para mostrar</p>
                        <p class="mt-1 text-[15px] text-slate-500 dark:text-slate-300">{{ $hayFiltros ? 'Prueba quitando algún filtro.' : 'Cuando un asociado radique una PQRS aparecerá aquí.' }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5">{{ $pqrs->links() }}</div>
        </div>
    </div>
</div>
@endsection
