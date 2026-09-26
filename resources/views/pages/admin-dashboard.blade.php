@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-6xl mx-auto space-y-6">
        <x-admin-tabs active="dashboard" title="Panel de administración"
            subtitle="Selecciona qué deseas administrar." />

        @if($pendientes > 0)
            <div class="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 px-4 py-3 text-sm text-amber-800 dark:text-amber-300">
                Tienes <strong>{{ $pendientes }}</strong> solicitud(es) pendiente(s) por revisar.
            </div>
        @endif

        @php
            $horas = $metricas['horasPromedio'];
            $tiempo = $horas === null ? '—' : ($horas < 48 ? round($horas, 1).' h' : round($horas / 24, 1).' días');
        @endphp
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach([['Solicitudes radicadas', $metricas['total']], ['Últimos 30 días', $metricas['ultimos30']], ['Tiempo promedio de atención', $tiempo]] as [$etiqueta, $valor])
                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $etiqueta }}</p>
                    <p class="mt-2 text-3xl font-bold text-[#0C67A3] dark:text-[#5EB3E4]">{{ $valor }}</p>
                </div>
            @endforeach
        </div>

        @if($metricas['total'] > 0)
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Por estado</h2>
                    <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                        @foreach(\App\Models\Solicitud::ESTADOS as $clave => $nombre)
                            <li class="flex justify-between"><span>{{ $nombre }}</span><strong class="text-slate-900 dark:text-slate-100">{{ $metricas['porEstado'][$clave] ?? 0 }}</strong></li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Por tipo de trámite</h2>
                    <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                        @foreach($metricas['porTipo'] as $tipo)
                            <li class="flex justify-between"><span>{{ $tipo->nombre }}</span><strong class="text-slate-900 dark:text-slate-100">{{ $tipo->solicitudes_count }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <a href="{{ route('admin.solicitudes.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Administrar</p>
                        <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Solicitudes y certificaciones</h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">Revisa, verifica, aprueba o rechaza los trámites radicados por los asociados.</p>
                    </div>
                    <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                        <x-icon name="clipboard" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a solicitudes →</div>
            </a>

            <a href="{{ route('admin.documents') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Administrar</p>
                        <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Documentos</h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">Sube y gestiona PDFs para la sección de transparencia.</p>
                    </div>
                    <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                        <x-icon name="document" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a documentos →</div>
            </a>

            <a href="{{ route('admin.events') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Administrar</p>
                        <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Eventos</h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">Crea y elimina eventos.</p>
                    </div>
                    <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                        <x-icon name="ticket" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a eventos →</div>
            </a>

            <a href="{{ route('admin.auditoria') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Consultar</p>
                        <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Auditoría</h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">Revisa la trazabilidad de acciones sobre las solicitudes.</p>
                    </div>
                    <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                        <x-icon name="eye" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a auditoría →</div>
            </a>

            @if(auth()->user()->is_super_admin)
                <a href="{{ route('admin.usuarios.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Administrar</p>
                            <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Usuarios y permisos</h2>
                            <p class="mt-3 text-slate-600 dark:text-slate-400">Crea funcionarios y controla a qué trámites tiene acceso cada uno.</p>
                        </div>
                        <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                            <x-icon name="users" class="w-6 h-6" />
                        </div>
                    </div>
                    <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a usuarios →</div>
                </a>

                <a href="{{ route('admin.tramite-tipos.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0C67A3]">Administrar</p>
                            <h2 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Tipos de trámite</h2>
                            <p class="mt-3 text-slate-600 dark:text-slate-400">Agrega nuevas categorías de trámite sin afectar la operación actual.</p>
                        </div>
                        <div class="rounded-2xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 p-4 text-[#0C67A3] dark:text-[#5EB3E4]">
                            <x-icon name="briefcase" class="w-6 h-6" />
                        </div>
                    </div>
                    <div class="mt-6 text-sm font-semibold text-[#0C67A3]">Ir a tipos de trámite →</div>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
