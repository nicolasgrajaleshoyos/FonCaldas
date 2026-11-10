@extends('layouts.app')

@section('title', 'Estado de tu solicitud | Foncaldas')

@section('content')
@php
    $vigente = $solicitud->documentos->where('origen', 'admin')->max('version');
    $firmar = fn ($documento) => URL::temporarySignedRoute('solicitudes.documentos.descargar', now()->addMinutes(30), $documento);
@endphp
<div class="min-h-screen page-bg py-14">
    <div class="max-w-2xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot pose="pulgar" class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="Solicitud {{ $solicitud->codigo }}"
                subtitle="{{ $solicitud->tramiteTipo->nombre }}"
                center />
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-6">
            @if(!empty($exito))
                <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">{{ $exito }}</div>
            @endif

            <div class="text-center">
                <x-estado-badge :estado="$solicitud->estado" :etiqueta="$solicitud->estadoLabel()" grande />

                @if($solicitud->estado === 'rechazada' && $solicitud->justificacion)
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400"><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
                @endif
            </div>

            @if($solicitud->documentos->where('origen', 'admin')->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Documentos de FONCALDAS</h3>
                    <ul class="space-y-2">
                        @foreach($solicitud->documentos->where('origen', 'admin')->sortByDesc('version') as $documento)
                            <li class="flex flex-wrap items-center gap-2">
                                <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-[#0C67A3] font-semibold hover:underline">
                                    <x-icon name="document" class="w-4 h-4" /> {{ $documento->nombre_original }}
                                </a>
                                <span class="text-xs text-slate-400">v{{ $documento->version }}</span>
                                @if($documento->version === $vigente)
                                    <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300">Vigente</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Documentos que adjuntaste</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($solicitud->documentos->where('origen', 'asociado') as $documento)
                        <li>
                            <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-[#0C67A3] font-semibold hover:underline">
                                <x-icon name="document" class="w-4 h-4" /> {{ $documento->nombre_original }}
                            </a>
                        </li>
                    @empty
                        <li class="text-slate-500 dark:text-slate-400">No has adjuntado documentos.</li>
                    @endforelse
                </ul>

                @if($solicitud->abierta())
                    <form method="POST" action="{{ URL::temporarySignedRoute('solicitudes.documentos.store', now()->addHour(), $solicitud) }}" data-cargando="Subiendo tus documentos…" enctype="multipart/form-data" class="mt-4 space-y-2 border-t border-slate-100 dark:border-slate-700 pt-4">
                        @csrf
                        <label for="f-documentos" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Adjuntar más documentos (máx. 5, 10 MB c/u)</label>
                        <input type="file" id="f-documentos" name="documentos[]" multiple required class="block w-full text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium">
                        <button type="submit" class="rounded-lg bg-[#0C67A3] px-4 py-2 text-white text-sm font-semibold hover:bg-[#095c8f] transition">Adjuntar</button>
                    </form>
                @endif
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Historial</h3>
                <ul class="space-y-3">
                    @foreach($solicitud->eventos as $evento)
                        <li class="border-l-2 border-slate-200 dark:border-slate-700 pl-3 text-sm">
                            <p class="text-slate-900 dark:text-slate-100 font-medium">{{ str_replace('_', ' ', $evento->accion) }}</p>
                            <p class="text-slate-400 text-xs">{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('solicitudes.consultar') }}" class="text-[#0C67A3] font-semibold hover:underline">← Consultar otra solicitud</a>
        </p>
    </div>
</div>
@endsection
