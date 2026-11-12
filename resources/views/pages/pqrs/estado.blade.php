@extends('layouts.app')

@section('title', 'Estado de tu PQRS | Foncaldas')

@section('content')
@php
    // Progreso en 3 pasos: recibida → en trámite → respondida.
    $paso = match ($pqrs->estado) { 'recibida' => 1, 'en_tramite' => 2, default => 3 };
    $pasos = [['Recibida', 'clipboard'], ['En trámite', 'search'], ['Respondida', 'check']];

    // Mascota, titular y explicación según el estado
    [$pose, $titular, $explicacion] = match ($pqrs->estado) {
        'recibida' => ['tramite', '¡Recibimos tu '.mb_strtolower($pqrs->tipoLabel()).'!', 'Ya la tiene nuestro equipo. Pronto empezaremos a gestionarla.'],
        'en_tramite' => ['pensando', 'Estamos trabajando en tu caso', 'Un funcionario está revisando tu '.mb_strtolower($pqrs->tipoLabel()).'. Te responderemos por correo.'],
        default => ['campeon', '¡Ya te respondimos!', 'Encontrarás nuestra respuesta aquí abajo y también te la enviamos por correo.'],
    };
@endphp
<div class="min-h-screen page-bg py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-6">

        {{-- ESTADO --}}
        <div class="hero-in relative overflow-hidden rounded-[2rem] bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-6 text-white shadow-2xl shadow-[#0B4870]/30 ring-1 ring-white/15 sm:p-10">
            <span class="hero-sun absolute -right-32 -top-32 h-[28rem] w-[28rem] opacity-50" aria-hidden="true"></span>

            <div class="relative flex flex-col items-center gap-6 text-center md:flex-row md:text-left">
                <x-mascot :pose="$pose" loading="eager" size="w-36 h-36 md:w-44 md:h-44" />
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-center gap-2 md:justify-start">
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-[#FACC15] ring-1 ring-white/20">{{ $pqrs->tipoLabel() }}</span>
                        <span class="rounded-full bg-white/15 px-3 py-1 font-mono text-sm font-semibold ring-1 ring-white/20">{{ $pqrs->codigo }}</span>
                    </div>
                    <h1 class="hero-title mt-3 text-3xl leading-tight sm:text-4xl">{{ $titular }}</h1>
                    <p class="mt-3 text-base leading-relaxed text-white/90 sm:text-lg">{{ $explicacion }}</p>
                </div>
            </div>

            <div class="relative mt-10">
                <div class="absolute left-[16.66%] right-[16.66%] top-6 h-1.5 rounded-full bg-white/20" aria-hidden="true">
                    <div class="estado-llenar h-full rounded-full bg-linear-to-r from-[#FACC15] to-amber-300 shadow-[0_0_12px_rgba(250,204,21,.7)]" style="width: {{ ($paso - 1) * 50 }}%"></div>
                </div>
                <ol class="relative grid grid-cols-3">
                    @foreach($pasos as $i => [$nombre, $icono])
                        @php $n = $i + 1; $hecho = $n < $paso; $actual = $n === $paso; $listo = $actual && $paso === 3; @endphp
                        <li class="hero-in flex flex-col items-center text-center" style="animation-delay: {{ .3 + $i * .2 }}s" @if($actual) aria-current="step" @endif>
                            <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-full shadow-lg {{ $listo ? 'bg-emerald-500 text-white' : ($actual || $hecho ? 'bg-[#FACC15] text-[#0B4870]' : 'bg-white/15 text-white/60 ring-1 ring-white/25') }}">
                                @if($actual)<span class="absolute inset-0 rounded-full animate-ping {{ $listo ? 'bg-emerald-400/50' : 'bg-[#FACC15]/50' }}" aria-hidden="true"></span>@endif
                                <x-icon :name="$hecho ? 'check' : $icono" class="relative w-5 h-5" />
                            </span>
                            <span class="mt-3 text-sm font-bold sm:text-base {{ $n <= $paso ? 'text-white' : 'text-white/55' }}">{{ $nombre }}</span>
                            <span class="text-xs {{ $actual ? 'font-semibold text-[#FACC15]' : 'text-white/60' }}">{{ $actual ? 'Estás aquí' : ($hecho ? 'Listo' : 'Pendiente') }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        {{-- RESPUESTA DE FONCALDAS --}}
        @if($pqrs->respuesta)
            <section class="hero-in relative overflow-hidden rounded-3xl border-2 border-emerald-300 bg-emerald-50 p-6 shadow-sm sm:p-8 dark:border-emerald-400/40 dark:bg-emerald-400/10" style="animation-delay:.2s">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-md"><x-icon name="check" class="w-6 h-6" /></span>
                    <div>
                        <h2 class="font-[Sora] text-xl font-bold text-slate-900 dark:text-white">Respuesta de FONCALDAS</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-300">{{ $pqrs->respondido_at?->format('d/m/Y · g:i a') }}</p>
                    </div>
                </div>
                <p class="mt-5 whitespace-pre-line text-base leading-relaxed text-slate-800 dark:text-slate-100">{{ $pqrs->respuesta }}</p>
            </section>
        @endif

        {{-- TU MENSAJE --}}
        <section class="hero-in rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-white/10 dark:bg-slate-800" style="animation-delay:.3s">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="document" class="w-6 h-6" /></span>
                <div class="min-w-0">
                    <h2 class="font-[Sora] text-xl font-bold text-slate-900 dark:text-white">Tu mensaje</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300">Radicado el {{ $pqrs->created_at->format('d/m/Y · g:i a') }}</p>
                </div>
            </div>
            <p class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">{{ $pqrs->asunto }}</p>
            <p class="mt-2 whitespace-pre-line text-base leading-relaxed text-slate-600 dark:text-slate-300">{{ $pqrs->descripcion }}</p>
        </section>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <a href="{{ route('pqrs.consultar') }}" class="inline-flex items-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15">
                <x-icon name="search" class="w-4 h-4" /> Consultar otra PQRS
            </a>
            <a href="https://wa.me/573148330328" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-green-600 px-6 py-2.5 font-semibold text-white shadow-lg shadow-green-600/25 transition hover:-translate-y-0.5 hover:bg-green-700">
                <x-icon name="whatsapp" class="w-4 h-4" /> ¿Dudas? Escríbenos
            </a>
        </div>
    </div>
</div>
@endsection
