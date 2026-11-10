@extends('layouts.app')

@section('title', 'Estado de tu solicitud | Foncaldas')

@section('content')
@php
    $vigente = $solicitud->documentos->where('origen', 'admin')->max('version');
    $firmar = fn ($documento) => URL::temporarySignedRoute('solicitudes.documentos.descargar', now()->addMinutes(30), $documento);

    // Progreso en 3 pasos; el último toma el nombre y color del resultado (aprobada, rechazada o finalizada).
    $paso = match ($solicitud->estado) { 'recibida' => 1, 'en_revision' => 2, default => 3 };
    $final = $paso === 3;
    $rechazada = $solicitud->estado === 'rechazada';
    $pasos = [['Recibida', '📥'], ['En revisión', '🔍'], [$final ? $solicitud->estadoLabel() : 'Resultado', $rechazada ? '✕' : '🎉']];
    $tarjeta = 'rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm';
    $fila = 'group flex items-center gap-3 rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 px-4 py-3 transition hover:-translate-y-0.5 hover:border-[#0C67A3]/40 hover:shadow-md';
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

        <div class="space-y-6">
            @if(!empty($exito))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-5 py-4 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-500 font-bold text-white">✓</span> {{ $exito }}
                </div>
            @endif

            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0B4870] via-[#0C67A3] to-[#0e7fc4] p-8 text-white shadow-xl">
                <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full bg-[#FACC15]/20 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-12 -left-8 h-40 w-40 rounded-full bg-sky-300/20 blur-2xl"></div>
                <p class="relative text-center text-xs font-semibold uppercase tracking-widest text-white/70">Estado actual</p>
                <p class="relative mt-1 text-center font-[Sora] text-3xl font-extrabold">{{ $solicitud->estadoLabel() }}</p>

                <ol class="relative mt-8 flex items-start">
                    @foreach($pasos as $i => [$nombre, $icono])
                        @php $n = $i + 1; $hecho = $n < $paso; $actual = $n === $paso; @endphp
                        <li class="relative flex flex-1 flex-col items-center text-center">
                            @if($n > 1)
                                <span class="absolute right-1/2 top-5 h-1 w-full rounded-full {{ $n <= $paso ? 'bg-[#FACC15]' : 'bg-white/20' }}"></span>
                            @endif
                            <span class="relative z-10 inline-flex h-10 w-10 items-center justify-center rounded-full text-lg shadow-lg
                                {{ $actual ? ($final ? ($rechazada ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white') : 'bg-[#FACC15] text-[#0B4870] ring-4 ring-[#FACC15]/30') : ($hecho ? 'bg-[#FACC15] text-[#0B4870]' : 'bg-white/15 text-white/60') }}">{{ $hecho ? '✓' : $icono }}</span>
                            <span class="mt-2 text-xs font-semibold {{ $n <= $paso ? 'text-white' : 'text-white/50' }}">{{ $nombre }}</span>
                        </li>
                    @endforeach
                </ol>

                @if($rechazada && $solicitud->justificacion)
                    <p class="relative mt-6 rounded-2xl bg-white/10 px-4 py-3 text-sm"><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
                @endif
            </div>

            @if($solicitud->documentos->where('origen', 'admin')->isNotEmpty())
                <section class="{{ $tarjeta }}">
                    <h3 class="mb-4 font-[Sora] text-base font-bold text-slate-800 dark:text-slate-100">🏢 Documentos de FONCALDAS</h3>
                    <ul class="space-y-2">
                        @foreach($solicitud->documentos->where('origen', 'admin')->sortByDesc('version') as $documento)
                            <li>
                                <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="{{ $fila }}">
                                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="document" class="w-5 h-5" /></span>
                                    <span class="min-w-0 flex-1 truncate font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#0C67A3]">{{ $documento->nombre_original }}</span>
                                    <span class="text-xs text-slate-400">v{{ $documento->version }}</span>
                                    @if($documento->version === $vigente)
                                        <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300">Vigente</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="{{ $tarjeta }}">
                <h3 class="mb-4 font-[Sora] text-base font-bold text-slate-800 dark:text-slate-100">📎 Documentos que adjuntaste</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($solicitud->documentos->where('origen', 'asociado') as $documento)
                        <li>
                            <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="{{ $fila }}">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FACC15]/20 text-[#a16207] dark:text-[#FACC15]"><x-icon name="document" class="w-5 h-5" /></span>
                                <span class="min-w-0 flex-1 truncate font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#0C67A3]">{{ $documento->nombre_original }}</span>
                                <span class="text-xs font-semibold text-[#0C67A3] opacity-0 transition group-hover:opacity-100">Ver ↗</span>
                            </a>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-600 px-4 py-5 text-center text-slate-500 dark:text-slate-400">Aún no has adjuntado documentos.</li>
                    @endforelse
                </ul>

                @if($solicitud->abierta())
                    <form method="POST" action="{{ URL::temporarySignedRoute('solicitudes.documentos.store', now()->addHour(), $solicitud) }}" data-cargando="Subiendo tus documentos…" enctype="multipart/form-data" class="mt-5 space-y-3">
                        @csrf
                        <label for="f-documentos" class="flex cursor-pointer flex-col items-center gap-1 rounded-2xl border-2 border-dashed border-[#0C67A3]/40 bg-[#0C67A3]/5 px-4 py-6 text-center transition hover:border-[#0C67A3] hover:bg-[#0C67A3]/10">
                            <span class="text-3xl">☁️</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">Adjuntar más documentos</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Máx. 5 archivos, 10 MB c/u · toca para elegir</span>
                            <span id="nombres-archivos" class="mt-1 break-all text-sm font-medium text-[#0C67A3]"></span>
                        </label>
                        <input type="file" id="f-documentos" name="documentos[]" multiple required class="sr-only"
                            onchange="document.getElementById('nombres-archivos').textContent = [...this.files].map(f => f.name).join(' · ')">
                        <button type="submit" class="w-full rounded-full bg-[#0C67A3] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#095c8f] focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/30">Adjuntar documentos</button>
                    </form>
                @endif
            </section>

            <section class="{{ $tarjeta }}">
                <h3 class="mb-5 font-[Sora] text-base font-bold text-slate-800 dark:text-slate-100">🕒 Historial</h3>
                <ul class="relative space-y-5 border-l-2 border-slate-200 dark:border-slate-700 pl-6">
                    @foreach($solicitud->eventos as $evento)
                        <li class="relative">
                            <span class="absolute -left-[33px] top-1 h-4 w-4 rounded-full border-4 border-white dark:border-slate-800 {{ $loop->first ? 'bg-[#FACC15] ring-2 ring-[#FACC15]/40' : 'bg-[#0C67A3]' }}"></span>
                            <p class="font-semibold capitalize text-slate-900 dark:text-slate-100">{{ str_replace('_', ' ', $evento->accion) }}</p>
                            <p class="text-xs text-slate-400">{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('solicitudes.consultar') }}" class="text-[#0C67A3] font-semibold hover:underline">← Consultar otra solicitud</a>
        </p>
    </div>
</div>
@endsection
