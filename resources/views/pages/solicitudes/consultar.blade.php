@extends('layouts.app')

@section('title', 'Consultar solicitud | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-md mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot pose="pensando" class="mb-4" />
            <x-page-header
                icon="search"
                title="Consulta el estado de tu solicitud"
                subtitle="Ingresa tu número de documento y el código que recibiste al radicar."
                center />
        </div>

        <div class="relative overflow-hidden rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl">
            <div class="h-2 bg-gradient-to-r from-[#0B4870] via-[#0C67A3] to-[#FACC15]"></div>
            <div class="p-8">
            @if($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 px-4 py-3 text-sm text-rose-700 dark:text-rose-300">
                    <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-500 font-bold text-white">!</span>
                    <div>@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('solicitudes.buscar') }}" data-cargando="Consultando tu solicitud…" class="space-y-5">
                @csrf

                <div>
                    <label for="f-codigo" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Código de solicitud</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-lg">🎫</span>
                        <input type="text" id="f-codigo" name="codigo" value="{{ old('codigo') }}" required placeholder="FC-26-XXXXXX" autocomplete="off" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 py-3 pl-12 pr-4 font-mono uppercase tracking-wider transition focus:border-[#0C67A3] focus:ring-2 focus:ring-[#0C67A3]/40">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Lo recibiste en el mensaje de confirmación y por correo al radicar.</p>
                </div>

                <div>
                    <label for="f-asociado-documento" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Número de documento</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-lg">🪪</span>
                        <input type="text" id="f-asociado-documento" name="asociado_documento" value="{{ old('asociado_documento') }}" required inputmode="numeric" placeholder="Tu número de cédula" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 py-3 pl-12 pr-4 transition focus:border-[#0C67A3] focus:ring-2 focus:ring-[#0C67A3]/40">
                    </div>
                </div>

                <button type="submit" class="w-full rounded-full bg-[#0C67A3] px-5 py-3 font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#095c8f] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/30">🔍 Consultar mi solicitud</button>
            </form>

            <p class="mt-5 rounded-2xl bg-[#FACC15]/15 px-4 py-3 text-center text-xs text-slate-600 dark:text-slate-300">🔒 Solo tú puedes ver tu solicitud: necesitas el código y tu documento.</p>
            </div>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('solicitudes.crear') }}" class="text-[#0C67A3] font-semibold hover:underline">← Radicar una nueva solicitud</a>
        </p>
    </div>
</div>
@endsection
