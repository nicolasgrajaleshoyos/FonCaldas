@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-4xl mx-auto space-y-6">
        <x-admin-tabs active="pqrs" title="{{ $pqrs->tipoLabel() }} {{ $pqrs->codigo }}" icon="clipboard"
            subtitle="{{ $pqrs->asunto }}" />

        <a href="{{ route('admin.pqrs.index') }}" class="inline-block text-sm font-semibold text-[#0C67A3]">← Volver al listado</a>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Datos del asociado</h2>
                <span class="text-sm font-semibold text-[#0C67A3]">{{ $pqrs->estadoLabel() }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500 dark:text-slate-400">Nombre</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $pqrs->nombre }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Documento</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $pqrs->documento }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Correo</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $pqrs->email }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Teléfono</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $pqrs->telefono ?: '—' }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Radicada</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $pqrs->created_at->format('d/m/Y H:i') }}</dd></div>
            </dl>
            <div>
                <dt class="text-slate-500 dark:text-slate-400 text-sm">Descripción</dt>
                <dd class="text-slate-900 dark:text-slate-200 mt-1 whitespace-pre-line">{{ $pqrs->descripcion }}</dd>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Respuesta</h2>

            @if($pqrs->respuesta)
                <p class="text-slate-900 dark:text-slate-200 whitespace-pre-line">{{ $pqrs->respuesta }}</p>
                <p class="text-xs text-slate-400">Respondida por {{ $pqrs->respondidoPor?->name ?? '—' }} el {{ $pqrs->respondido_at->format('d/m/Y H:i') }}</p>
            @else
                @if($pqrs->estado === 'recibida')
                    <form method="POST" action="{{ route('admin.pqrs.tramitar', $pqrs) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-white text-sm font-semibold hover:bg-slate-900 transition">Marcar en trámite</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.pqrs.responder', $pqrs) }}" class="space-y-3">
                    @csrf
                    <textarea name="respuesta" rows="5" required maxlength="5000" placeholder="Escribe la respuesta para el asociado" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 text-sm">{{ old('respuesta') }}</textarea>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-white text-sm font-semibold hover:bg-emerald-700 transition">Enviar respuesta</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
