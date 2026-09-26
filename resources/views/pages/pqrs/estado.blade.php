@extends('layouts.app')

@section('title', 'Estado de tu PQRS | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-2xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="{{ $pqrs->tipoLabel() }} {{ $pqrs->codigo }}"
                subtitle="{{ $pqrs->asunto }}"
                center />
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-6">
            <div class="text-center">
                <span class="inline-block rounded-full px-5 py-2 text-lg font-bold
                    @class([
                        'bg-slate-100 text-slate-700' => $pqrs->estado === 'recibida',
                        'bg-amber-100 text-amber-700' => $pqrs->estado === 'en_tramite',
                        'bg-emerald-100 text-emerald-700' => $pqrs->estado === 'respondida',
                    ])">{{ $pqrs->estadoLabel() }}</span>
                <p class="mt-2 text-xs text-slate-400">Radicada el {{ $pqrs->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tu mensaje</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $pqrs->descripcion }}</p>
            </div>

            @if($pqrs->respuesta)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 p-5">
                    <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 mb-2">Respuesta de Foncaldas</h3>
                    <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $pqrs->respuesta }}</p>
                    <p class="mt-3 text-xs text-slate-400">{{ $pqrs->respondido_at->format('d/m/Y H:i') }}</p>
                </div>
            @endif
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('pqrs.consultar') }}" class="text-[#0C67A3] font-semibold hover:underline">← Consultar otra PQRS</a>
        </p>
    </div>
</div>
@endsection
