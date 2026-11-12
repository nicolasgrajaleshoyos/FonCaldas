@extends('layouts.app')

@section('title', 'Estado de tu solicitud | Foncaldas')

@section('content')
@php
    $vigente = $solicitud->documentos->where('origen', 'admin')->max('version');
    $firmar = fn ($documento) => URL::temporarySignedRoute('solicitudes.documentos.descargar', now()->addMinutes(30), $documento);
    $docsFoncaldas = $solicitud->documentos->where('origen', 'admin')->sortByDesc('version');
    $docsAsociado = $solicitud->documentos->where('origen', 'asociado');

    // Progreso en 3 pasos; el último toma el nombre y color del resultado (aprobada, rechazada o finalizada).
    $paso = match ($solicitud->estado) { 'recibida' => 1, 'en_revision' => 2, default => 3 };
    $final = $paso === 3;
    $rechazada = $solicitud->estado === 'rechazada';
    $pasos = [
        ['Recibida', 'clipboard'],
        ['En revisión', 'search'],
        [$final ? $solicitud->estadoLabel() : 'Resultado', $rechazada ? 'shield' : 'trophy'],
    ];

    // Lo que ve el asociado según el estado: pose de la mascota, titular, explicación y qué sigue.
    $mensaje = match ($solicitud->estado) {
        'recibida' => ['tramite', '¡Recibimos tu solicitud!', 'Ya está en manos de nuestro equipo. Primero verificaremos tu identidad y luego empezaremos a revisarla.', 'Vuelve a consultar aquí cuando quieras con tu código. Si necesitas agregar algo, puedes adjuntar más documentos abajo.'],
        'en_revision' => ['pensando', 'Estamos revisando tu solicitud', 'Ya verificamos tu identidad y un funcionario está trabajando en tu trámite.', 'Si nos hace falta información te contactaremos. Mientras tanto puedes adjuntar documentos abajo.'],
        'aprobada' => ['campeon', '¡Tu solicitud fue aprobada!', 'Buenas noticias: revisamos tu trámite y fue aprobado.', $docsFoncaldas->isNotEmpty() ? 'Descarga tu documento en la sección «Documentos de FONCALDAS».' : 'Pronto publicaremos aquí la respuesta o el documento de tu trámite.'],
        'rechazada' => ['pensativo', 'Tu solicitud no pudo continuar', 'Revisamos tu trámite, pero no fue posible aprobarlo. Abajo encontrarás el motivo.', 'Puedes radicar una nueva solicitud corrigiendo lo indicado, o escribirnos por WhatsApp si tienes dudas.'],
        default => ['alegre', 'Tu solicitud está finalizada', 'El proceso de este trámite terminó.', 'Si necesitas algo más, puedes radicar una nueva solicitud.'],
    };
    [$pose, $titular, $explicacion, $siguiente] = $mensaje;

    // Historial en palabras sencillas
    $acciones = [
        'solicitud_creada' => ['Radicaste tu solicitud', 'clipboard'],
        'solicitud_asignada' => ['Se asignó a un funcionario', 'user'],
        'solicitud_reasignada' => ['Cambió el funcionario a cargo', 'users'],
        'identidad_verificada' => ['Verificamos tu identidad', 'shield'],
        'documento_adjuntado' => ['Adjuntaste un documento', 'upload'],
        'documento_publicado' => ['FONCALDAS publicó un documento', 'document'],
    ];

    $tarjeta = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-800';
    $fila = 'group flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:-translate-y-0.5 hover:border-[#0C67A3]/40 hover:bg-white hover:shadow-md dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900';
    $tituloTarjeta = 'mb-4 flex items-center gap-3 font-[Sora] text-lg font-bold text-slate-900 dark:text-white';
@endphp
<div class="min-h-screen page-bg py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-6">

        @if(!empty($exito))
            <div class="hero-in flex items-center gap-3 rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-5 py-4 text-[15px] font-medium text-emerald-700 dark:text-emerald-300">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white"><x-icon name="check" class="w-4 h-4" /></span> {{ $exito }}
            </div>
        @endif

        {{-- ESTADO: mascota según el estado, titular en lenguaje sencillo y barra de progreso animada --}}
        <div class="hero-in relative overflow-hidden rounded-[2rem] bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-6 text-white shadow-2xl shadow-[#0B4870]/30 ring-1 ring-white/15 sm:p-10">
            <span class="hero-sun absolute -right-32 -top-32 h-[28rem] w-[28rem] opacity-50" aria-hidden="true"></span>
            <span class="absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-[#FACC15]/20 blur-3xl" aria-hidden="true"></span>

            {{-- Destellos de celebración cuando la solicitud se aprobó o finalizó --}}
            @if($final && ! $rechazada)
                @foreach(['left-[12%] top-[18%]', 'left-[46%] top-[10%]', 'right-[30%] top-[38%]', 'left-[30%] bottom-[30%]'] as $i => $lugar)
                    <span class="pointer-events-none absolute {{ $lugar }} h-2.5 w-2.5 rounded-full bg-[#FACC15] animate-ping" style="animation-delay: {{ $i * .35 }}s" aria-hidden="true"></span>
                @endforeach
            @endif

            <div class="relative flex flex-col items-center gap-6 text-center md:flex-row md:text-left">
                <x-mascot :pose="$pose" loading="eager" size="w-36 h-36 md:w-44 md:h-44" />

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-center gap-2 md:justify-start" x-data="{ copiado: false }">
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-[#FACC15] ring-1 ring-white/20">{{ $solicitud->tramiteTipo->nombre }}</span>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 font-mono text-sm font-semibold ring-1 ring-white/20 transition hover:bg-white/25"
                                @click="navigator.clipboard?.writeText('{{ $solicitud->codigo }}'); copiado = true; setTimeout(() => copiado = false, 1800)"
                                title="Copiar el código">
                            {{ $solicitud->codigo }}
                            <span class="font-sans text-xs" x-text="copiado ? '¡Copiado!' : 'Copiar'">Copiar</span>
                        </button>
                    </div>
                    <h1 class="hero-title mt-3 text-3xl leading-tight sm:text-4xl">{{ $titular }}</h1>
                    <p class="mt-3 text-base leading-relaxed text-white/90 sm:text-lg">{{ $explicacion }}</p>
                </div>
            </div>

            {{-- Barra de progreso --}}
            <div class="relative mt-10">
                <div class="absolute left-[16.66%] right-[16.66%] top-6 h-1.5 rounded-full bg-white/20" aria-hidden="true">
                    <div class="estado-llenar h-full rounded-full bg-linear-to-r from-[#FACC15] to-amber-300 shadow-[0_0_12px_rgba(250,204,21,.7)]" style="width: {{ ($paso - 1) * 50 }}%"></div>
                </div>
                <ol class="relative grid grid-cols-3">
                    @foreach($pasos as $i => [$nombre, $icono])
                        @php $n = $i + 1; $hecho = $n < $paso; $actual = $n === $paso; @endphp
                        <li class="hero-in flex flex-col items-center text-center" style="animation-delay: {{ .3 + $i * .2 }}s" @if($actual) aria-current="step" @endif>
                            <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-full shadow-lg transition
                                {{ $actual ? ($final ? ($rechazada ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white') : 'bg-[#FACC15] text-[#0B4870]') : ($hecho ? 'bg-[#FACC15] text-[#0B4870]' : 'bg-white/15 text-white/60 ring-1 ring-white/25') }}">
                                @if($actual)
                                    <span class="absolute inset-0 rounded-full animate-ping {{ $final ? ($rechazada ? 'bg-rose-400/50' : 'bg-emerald-400/50') : 'bg-[#FACC15]/50' }}" aria-hidden="true"></span>
                                @endif
                                <x-icon :name="$hecho ? 'check' : $icono" class="relative w-5 h-5" />
                            </span>
                            <span class="mt-3 text-sm font-bold sm:text-base {{ $n <= $paso ? 'text-white' : 'text-white/55' }}">{{ $nombre }}</span>
                            <span class="text-xs {{ $actual ? 'font-semibold text-[#FACC15]' : 'text-white/60' }}">{{ $actual ? 'Estás aquí' : ($hecho ? 'Listo' : 'Pendiente') }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            @if($rechazada && $solicitud->justificacion)
                <div class="relative mt-8 flex gap-3 rounded-2xl bg-white/10 p-4 ring-1 ring-white/20">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-500 text-white"><x-icon name="shield" class="w-4 h-4" /></span>
                    <p class="text-[15px]"><strong class="block">Motivo</strong>{{ $solicitud->justificacion }}</p>
                </div>
            @endif
        </div>

        {{-- QUÉ SIGUE --}}
        <div class="hero-in flex items-start gap-4 rounded-3xl border-2 border-[#FACC15]/60 bg-[#FACC15]/10 p-5 dark:bg-[#FACC15]/5" style="animation-delay:.15s">
            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FACC15] text-[#0B4870] shadow-md"><x-icon name="compass" class="w-5 h-5" /></span>
            <div>
                <p class="font-[Sora] text-lg font-bold text-slate-900 dark:text-white">¿Qué sigue?</p>
                <p class="mt-1 text-[15px] leading-relaxed text-slate-700 dark:text-slate-200">{{ $siguiente }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-5 lg:items-start">

            {{-- DOCUMENTOS --}}
            <div class="min-w-0 space-y-6 lg:col-span-3">

                @if($docsFoncaldas->isNotEmpty())
                    <section class="hero-in relative overflow-hidden rounded-3xl border-2 border-emerald-300 bg-emerald-50 p-6 shadow-sm dark:border-emerald-400/40 dark:bg-emerald-400/10" style="animation-delay:.25s">
                        <h2 class="{{ $tituloTarjeta }}"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-md"><x-icon name="document" class="w-5 h-5" /></span>Documentos de FONCALDAS</h2>
                        <p class="-mt-2 mb-4 text-[15px] text-slate-600 dark:text-slate-300">Estos son los documentos que preparamos para ti. Descárgalos con un clic.</p>
                        <ul class="space-y-2">
                            @foreach($docsFoncaldas as $documento)
                                <li>
                                    <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="{{ $fila }} bg-white dark:bg-slate-900">
                                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-300"><x-icon name="document" class="w-5 h-5" /></span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate font-semibold text-slate-800 dark:text-slate-100">{{ $documento->nombre_original }}</span>
                                            <span class="text-xs text-slate-500 dark:text-slate-300">Versión {{ $documento->version }}</span>
                                        </span>
                                        @if($documento->version === $vigente)
                                            <span class="rounded-full bg-emerald-500 px-2.5 py-0.5 text-xs font-semibold text-white">Vigente</span>
                                        @endif
                                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#0B4870] text-white transition group-hover:translate-y-0.5 dark:bg-[#0C67A3]"><x-icon name="arrow-right" class="w-4 h-4 rotate-90" /></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="hero-in {{ $tarjeta }}" style="animation-delay:.3s">
                    <h2 class="{{ $tituloTarjeta }}"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#FACC15]/20 text-[#a16207] dark:text-[#FACC15]"><x-icon name="folder" class="w-5 h-5" /></span>Documentos que adjuntaste</h2>
                    <ul class="space-y-2">
                        @forelse($docsAsociado as $documento)
                            <li>
                                <a href="{{ $firmar($documento) }}" target="_blank" rel="noopener" class="{{ $fila }}">
                                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FACC15]/20 text-[#a16207] dark:text-[#FACC15]"><x-icon name="document" class="w-5 h-5" /></span>
                                    <span class="min-w-0 flex-1 truncate font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#0C67A3] dark:group-hover:text-sky-300">{{ $documento->nombre_original }}</span>
                                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-[#0C67A3] dark:text-sky-300"><x-icon name="eye" class="w-4 h-4" /> Ver</span>
                                </a>
                            </li>
                        @empty
                            <li class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-center text-[15px] text-slate-500 dark:border-slate-600 dark:text-slate-300">Aún no has adjuntado documentos.</li>
                        @endforelse
                    </ul>

                    @if($solicitud->abierta())
                        <form method="POST" action="{{ URL::temporarySignedRoute('solicitudes.documentos.store', now()->addHour(), $solicitud) }}" data-cargando="Subiendo tus documentos…" enctype="multipart/form-data" class="mt-5 space-y-3"
                              x-data="{ archivos: [] }">
                            @csrf
                            <label for="f-documentos" class="group flex cursor-pointer flex-col items-center gap-2 rounded-2xl border-2 border-dashed border-[#0C67A3]/40 bg-[#0C67A3]/5 px-4 py-7 text-center transition hover:border-[#0C67A3] hover:bg-[#0C67A3]/10 dark:border-sky-400/40 dark:hover:border-sky-400"
                                   :class="archivos.length ? '!border-emerald-500 !bg-emerald-500/10' : ''">
                                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0C67A3] text-white shadow-lg transition-transform duration-300 group-hover:-translate-y-1 dark:bg-sky-500"
                                      :class="archivos.length ? '!bg-emerald-500' : ''">
                                    <x-icon name="upload" class="w-7 h-7" />
                                </span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100" x-text="archivos.length ? (archivos.length === 1 ? '1 archivo listo para enviar' : archivos.length + ' archivos listos para enviar') : 'Toca aquí para elegir documentos'">Toca aquí para elegir documentos</span>
                                <span class="text-sm text-slate-500 dark:text-slate-300" x-show="!archivos.length">Hasta 5 archivos de 10 MB cada uno</span>
                                <span id="nombres-archivos" class="break-all text-sm font-medium text-emerald-700 dark:text-emerald-300" x-text="archivos.join(' · ')"></span>
                            </label>
                            <input type="file" id="f-documentos" name="documentos[]" multiple required class="sr-only"
                                @change="archivos = [...$event.target.files].map(f => f.name)">
                            <button type="submit" class="group inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#0B4870] px-5 py-3 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] disabled:opacity-60 dark:bg-[#0C67A3] dark:hover:bg-sky-600"
                                    :disabled="!archivos.length">
                                <x-icon name="upload" class="w-4 h-4" /> Enviar documentos
                            </button>
                        </form>
                    @endif
                </section>
            </div>

            {{-- RESUMEN E HISTORIAL --}}
            <div class="min-w-0 space-y-6 lg:col-span-2">
                <section class="hero-in {{ $tarjeta }}" style="animation-delay:.35s">
                    <h2 class="{{ $tituloTarjeta }}"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="clipboard" class="w-5 h-5" /></span>Resumen</h2>
                    <dl class="space-y-3 text-[15px]">
                        <div class="flex items-center justify-between gap-3"><dt class="text-slate-500 dark:text-slate-300">Trámite</dt><dd class="text-right font-semibold text-slate-900 dark:text-white">{{ $solicitud->tramiteTipo->nombre }}</dd></div>
                        <div class="flex items-center justify-between gap-3"><dt class="text-slate-500 dark:text-slate-300">Radicada</dt><dd class="text-right font-semibold text-slate-900 dark:text-white">{{ $solicitud->created_at->format('d/m/Y') }}</dd></div>
                        <div class="flex items-center justify-between gap-3"><dt class="text-slate-500 dark:text-slate-300">Hace</dt><dd class="text-right font-semibold text-slate-900 dark:text-white">{{ $solicitud->created_at->locale('es')->diffForHumans(null, true) }}</dd></div>
                        <div class="flex items-center justify-between gap-3"><dt class="text-slate-500 dark:text-slate-300">Estado</dt><dd><x-estado-badge :estado="$solicitud->estado" :etiqueta="$solicitud->estadoLabel()" /></dd></div>
                    </dl>
                </section>

                <section class="hero-in {{ $tarjeta }}" style="animation-delay:.4s">
                    <h2 class="{{ $tituloTarjeta }}"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0B8B7B]/10 text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300"><x-icon name="clock" class="w-5 h-5" /></span>Lo que ha pasado</h2>
                    <ol class="relative space-y-4 before:absolute before:bottom-3 before:left-[1.1rem] before:top-3 before:w-0.5 before:bg-slate-200 dark:before:bg-white/10">
                        @foreach($solicitud->eventos as $evento)
                            @php [$texto, $icono] = $acciones[$evento->accion] ?? [\Illuminate\Support\Str::ucfirst(str_replace('_', ' ', $evento->accion)), 'check']; @endphp
                            <li class="relative flex gap-3">
                                <span class="relative z-10 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full ring-4 ring-white dark:ring-slate-800 {{ $loop->first ? 'bg-[#FACC15] text-[#0B4870]' : 'bg-[#0C67A3] text-white' }}"><x-icon :name="$icono" class="w-4 h-4" /></span>
                                <div class="pt-1">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $texto }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-300">{{ $evento->created_at->format('d/m/Y · g:i a') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <a href="{{ route('solicitudes.consultar') }}" class="inline-flex items-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15">
                <x-icon name="search" class="w-4 h-4" /> Consultar otra solicitud
            </a>
            <a href="https://wa.me/573148330328" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-green-600 px-6 py-2.5 font-semibold text-white shadow-lg shadow-green-600/25 transition hover:-translate-y-0.5 hover:bg-green-700">
                <x-icon name="whatsapp" class="w-4 h-4" /> ¿Dudas? Escríbenos
            </a>
        </div>
    </div>
</div>
@endsection
