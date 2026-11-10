@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="pqrs" title="{{ $pqrs->tipoLabel() }} {{ $pqrs->codigo }}" icon="clipboard"
            subtitle="{{ $pqrs->asunto }}" />

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('admin.pqrs.index') }}" class="inline-block py-2 text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">← Volver al listado</a>
            <x-admin-delete :action="route('admin.pqrs.delete', $pqrs)" label="Eliminar PQRS" message="¿Eliminar esta PQRS del panel? El registro se conserva." />
        </div>

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Datos del asociado</h2>
                <x-estado-badge :estado="$pqrs->estado" :etiqueta="$pqrs->estadoLabel()" />
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500 dark:text-slate-400">Nombre</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $pqrs->nombre }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Documento</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $pqrs->documento }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Correo</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $pqrs->email }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Teléfono</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $pqrs->telefono ?: '—' }}</dd></div>
                <div><dt class="text-slate-500 dark:text-slate-400">Radicada</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $pqrs->created_at->format('d/m/Y H:i') }}</dd></div>
            </dl>
            <div>
                <dt class="text-slate-500 dark:text-slate-400 text-sm">Descripción</dt>
                <dd class="text-slate-900 dark:text-slate-200 mt-1 whitespace-pre-line">{{ $pqrs->descripcion }}</dd>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700 space-y-4">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Respuesta</h2>

            @if($pqrs->respuesta)
                <p class="text-slate-900 dark:text-slate-200 whitespace-pre-line">{{ $pqrs->respuesta }}</p>
                <p class="text-xs text-slate-400">Respondida por {{ $pqrs->respondidoPor?->name ?? '—' }} el {{ $pqrs->respondido_at->format('d/m/Y H:i') }}</p>
            @else
                @if($pqrs->estado === 'recibida')
                    <form method="POST" action="{{ route('admin.pqrs.tramitar', $pqrs) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-slate-800 dark:bg-slate-600 px-4 py-2.5 text-white text-sm font-semibold hover:bg-slate-900 dark:hover:bg-slate-500 transition">Marcar en trámite</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.pqrs.responder', $pqrs) }}" class="space-y-3" data-confirm="¿Enviar esta respuesta? Se enviará por correo al asociado y no podrá editarse.">
                    @csrf
                    <label for="f-respuesta" class="sr-only">Respuesta para el asociado</label>
                    <textarea id="f-respuesta" name="respuesta" rows="5" required maxlength="5000" placeholder="Escribe la respuesta para el asociado" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3 text-sm">{{ old('respuesta') }}</textarea>
                    @error('respuesta')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-white text-sm font-semibold hover:bg-emerald-700 transition">Enviar respuesta</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
