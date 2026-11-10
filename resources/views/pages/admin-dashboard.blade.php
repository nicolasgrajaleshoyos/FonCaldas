@extends('layouts.app')

@section('content')
@php
    $horas = $metricas['horasPromedio'];
    $tiempo = $horas === null ? '—' : ($horas < 48 ? round($horas, 1).' h' : round($horas / 24, 1).' días');
    $total = $metricas['total'];
    $nombre = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
    $fecha = now()->locale('es')->isoFormat('dddd D [de] MMMM');

    // Clases completas (no concatenadas) para que Tailwind las detecte.
    $colorEstado = [
        'recibida' => ['bg-sky-400/80', 'bg-sky-400'],
        'en_revision' => ['bg-amber-400/80', 'bg-amber-400'],
        'aprobada' => ['bg-emerald-400/80', 'bg-emerald-400'],
        'rechazada' => ['bg-rose-400/80', 'bg-rose-400'],
        'finalizada' => ['bg-slate-400/80', 'bg-slate-400'],
    ];

    $modulos = [
        ['grupo' => 'Gestión', 'route' => 'admin.solicitudes.index', 'icon' => 'clipboard', 'titulo' => 'Solicitudes y certificaciones', 'texto' => 'Revisa, aprueba o rechaza los trámites radicados.', 'super' => false],
        ['grupo' => 'Gestión', 'route' => 'admin.documents', 'icon' => 'document', 'titulo' => 'Documentos', 'texto' => 'Sube y gestiona los PDFs de transparencia.', 'super' => false],
        ['grupo' => 'Gestión', 'route' => 'admin.events', 'icon' => 'ticket', 'titulo' => 'Eventos', 'texto' => 'Crea y elimina los eventos del fondo.', 'super' => false],
        ['grupo' => 'Control', 'route' => 'admin.auditoria', 'icon' => 'eye', 'titulo' => 'Auditoría', 'texto' => 'Trazabilidad de acciones sobre las solicitudes.', 'super' => false],
        ['grupo' => 'Configuración', 'route' => 'admin.usuarios.index', 'icon' => 'users', 'titulo' => 'Usuarios y permisos', 'texto' => 'Crea funcionarios y controla su acceso a trámites.', 'super' => true],
        ['grupo' => 'Configuración', 'route' => 'admin.tramite-tipos.index', 'icon' => 'briefcase', 'titulo' => 'Tipos de trámite', 'texto' => 'Agrega categorías sin afectar la operación.', 'super' => true],
    ];
@endphp

<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="dashboard" title="Panel de administración"
            :subtitle="'Hola, '.$nombre.' · '.$fecha" />

        {{-- Resumen --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php $hay = $pendientes > 0; @endphp
            <a href="{{ route('admin.solicitudes.index') }}" class="group rounded-3xl border bg-white dark:bg-slate-800 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md {{ $hay ? 'border-amber-300/70 dark:border-amber-500/40' : 'border-slate-200 dark:border-slate-700' }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Pendientes por revisar</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $hay ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' }}"><x-icon :name="$hay ? 'clock' : 'check'" class="w-5 h-5" /></span>
                </div>
                <p class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $pendientes }}</p>
                <p class="mt-1 text-sm {{ $hay ? 'font-semibold text-amber-700 dark:text-amber-300' : 'text-slate-500 dark:text-slate-400' }}">{{ $hay ? 'Requieren tu revisión' : 'Todo al día' }}</p>
            </a>

            @foreach([
                ['Solicitudes radicadas', $total, 'clipboard', 'Total acumulado'],
                ['Últimos 30 días', $metricas['ultimos30'], 'calendar', 'Solicitudes nuevas'],
                ['Tiempo promedio', $tiempo, 'clock', 'Hasta finalizar'],
            ] as [$etiqueta, $valor, $ico, $apoyo])
                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $etiqueta }}</p>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#E7F3FF] dark:bg-[#0C67A3]/20 text-[#0C67A3] dark:text-[#5EB3E4]"><x-icon :name="$ico" class="w-5 h-5" /></span>
                    </div>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $valor }}</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $apoyo }}</p>
                </div>
            @endforeach
        </div>

        {{-- Distribución --}}
        @if($total > 0)
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Solicitudes por estado</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Distribución sobre el total radicado.</p>
                    <ul class="space-y-4">
                        @foreach(\App\Models\Solicitud::ESTADOS as $clave => $nombreEstado)
                            @php $n = $metricas['porEstado'][$clave] ?? 0; $pct = round($n / $total * 100); @endphp
                            <li>
                                <div class="flex items-center justify-between text-sm mb-1.5">
                                    <span class="inline-flex items-center gap-2 font-medium text-slate-700 dark:text-slate-200"><span class="h-2 w-2 rounded-full {{ $colorEstado[$clave][1] ?? 'bg-slate-400' }}"></span>{{ $nombreEstado }}</span>
                                    <span class="text-slate-500 dark:text-slate-400"><strong class="text-slate-900 dark:text-white">{{ $n }}</strong> · {{ $pct }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full rounded-full {{ $colorEstado[$clave][0] ?? 'bg-slate-500' }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Solicitudes por tipo de trámite</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Los trámites más solicitados.</p>
                    <ul class="space-y-4">
                        @foreach($metricas['porTipo'] as $tipo)
                            @php $pct = round($tipo->solicitudes_count / $total * 100); @endphp
                            <li>
                                <div class="flex items-center justify-between text-sm mb-1.5">
                                    <span class="font-medium text-slate-700 dark:text-slate-200">{{ $tipo->nombre }}</span>
                                    <span class="text-slate-500 dark:text-slate-400"><strong class="text-slate-900 dark:text-white">{{ $tipo->solicitudes_count }}</strong> · {{ $pct }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-[#0C67A3]/80 transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Módulos --}}
        <div class="space-y-7">
            @foreach(collect($modulos)->reject(fn ($m) => $m['super'] && ! auth()->user()->is_super_admin)->groupBy('grupo') as $grupo => $items)
                <section>
                    <div class="mb-3 flex items-center gap-3">
                        <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $grupo }}</h2>
                        <span class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($items as $m)
                            <a href="{{ route($m['route']) }}" class="group relative flex items-center gap-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#0B8B7B]/60 hover:shadow-lg hover:shadow-[#0C67A3]/10 focus-visible:outline-2 focus-visible:outline-[#0B8B7B]">
                                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-linear-to-br from-[#0B4870] to-[#0C67A3] text-white shadow-md shadow-[#0C67A3]/25 "><x-icon :name="$m['icon']" class="w-6 h-6" /></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[15px] font-bold text-slate-900 dark:text-white">{{ $m['titulo'] }}</span>
                                    <span class="mt-0.5 block text-sm leading-snug text-slate-500 dark:text-slate-400">{{ $m['texto'] }}</span>
                                </span>
                                <span class="inline-flex h-8 shrink-0 items-center rounded-full bg-slate-100 dark:bg-slate-700/70 px-3 text-xs font-semibold text-slate-500 dark:text-slate-300 transition group-hover:bg-[#0B8B7B] group-hover:text-white">
                                    Abrir
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</div>
@endsection
