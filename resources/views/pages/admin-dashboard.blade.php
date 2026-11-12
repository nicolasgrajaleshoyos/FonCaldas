@extends('layouts.app')

@section('content')
@php
    $horas = $metricas['horasPromedio'];
    $tiempo = $horas === null ? '—' : ($horas < 48 ? round($horas, 1).' h' : round($horas / 24, 1).' días');
    $total = $metricas['total'];
    $nombre = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
    $fecha = now()->locale('es')->isoFormat('dddd D [de] MMMM');
    $hay = $pendientes > 0;

    // Clases completas (no concatenadas) para que Tailwind las detecte.
    $colorEstado = [
        'recibida' => ['bg-sky-400/80', 'bg-sky-400'],
        'en_revision' => ['bg-amber-400/80', 'bg-amber-400'],
        'aprobada' => ['bg-emerald-400/80', 'bg-emerald-400'],
        'rechazada' => ['bg-rose-400/80', 'bg-rose-400'],
        'finalizada' => ['bg-slate-400/80', 'bg-slate-400'],
    ];

    // Ficha de color de cada grupo de módulos
    $fichaGrupo = [
        'Gestión' => 'bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-[#0C67A3]/40',
        'Control' => 'bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-[#FACC15]/40',
        'Configuración' => 'bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-[#0B8B7B]/40',
    ];

    $modulos = [
        ['grupo' => 'Gestión', 'route' => 'admin.solicitudes.index', 'icon' => 'clipboard', 'titulo' => 'Solicitudes y certificaciones', 'texto' => 'Revisa, aprueba o rechaza los trámites radicados.', 'super' => false],
        ['grupo' => 'Gestión', 'route' => 'admin.pqrs.index', 'icon' => 'ticket', 'titulo' => 'PQRS', 'texto' => 'Responde peticiones, quejas, reclamos y sugerencias.', 'super' => true],
        ['grupo' => 'Gestión', 'route' => 'admin.documents', 'icon' => 'document', 'titulo' => 'Documentos', 'texto' => 'Sube y gestiona los PDFs de transparencia.', 'super' => true],
        ['grupo' => 'Gestión', 'route' => 'admin.events', 'icon' => 'calendar', 'titulo' => 'Eventos', 'texto' => 'Crea y elimina los eventos del fondo.', 'super' => true],
        ['grupo' => 'Control', 'route' => 'admin.auditoria', 'icon' => 'eye', 'titulo' => 'Auditoría', 'texto' => 'Trazabilidad de acciones sobre las solicitudes.', 'super' => true],
        ['grupo' => 'Configuración', 'route' => 'admin.usuarios.index', 'icon' => 'users', 'titulo' => 'Usuarios y permisos', 'texto' => 'Crea funcionarios y controla su acceso a trámites.', 'super' => true],
        ['grupo' => 'Configuración', 'route' => 'admin.tramite-tipos.index', 'icon' => 'briefcase', 'titulo' => 'Tipos de trámite', 'texto' => 'Agrega categorías sin afectar la operación.', 'super' => true],
        ['grupo' => 'Configuración', 'route' => 'admin.parametros-financieros.index', 'icon' => 'card', 'titulo' => 'Parámetros financieros', 'texto' => 'Tasas que usan los simuladores de ahorro y crédito.', 'super' => true],
    ];
@endphp

<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-8">
        <x-admin-tabs active="dashboard" :title="'Hola, '.$nombre" :subtitle="\Illuminate\Support\Str::ucfirst($fecha).' · Este es el resumen de hoy.'" />

        {{-- Aviso principal: lo primero que necesita saber quien entra --}}
        <a href="{{ route('admin.solicitudes.index') }}"
           class="group flex flex-col gap-4 rounded-3xl border-2 p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg sm:flex-row sm:items-center {{ $hay ? 'border-amber-300 bg-amber-50 dark:border-amber-400/40 dark:bg-amber-400/10' : 'border-emerald-300 bg-emerald-50 dark:border-emerald-400/40 dark:bg-emerald-400/10' }}">
            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl text-white shadow-lg {{ $hay ? 'bg-amber-500 shadow-amber-500/30' : 'bg-emerald-500 shadow-emerald-500/30' }}">
                <x-icon :name="$hay ? 'clock' : 'check'" class="w-7 h-7" />
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-lg font-bold {{ $hay ? 'text-amber-900 dark:text-amber-200' : 'text-emerald-900 dark:text-emerald-200' }}">
                    {{ $hay ? ($pendientes === 1 ? 'Tienes 1 solicitud por revisar' : "Tienes {$pendientes} solicitudes por revisar") : '¡Todo al día! No hay solicitudes pendientes' }}
                </span>
                <span class="mt-0.5 block text-[15px] {{ $hay ? 'text-amber-800 dark:text-amber-200/80' : 'text-emerald-800 dark:text-emerald-200/80' }}">
                    {{ $hay ? 'Entra a Solicitudes para verificarlas y darles respuesta.' : 'Cuando un asociado radique un trámite aparecerá aquí.' }}
                </span>
            </span>
            <span class="inline-flex shrink-0 items-center gap-2 self-start rounded-full px-5 py-2.5 font-semibold text-white shadow-md transition sm:self-center {{ $hay ? 'bg-amber-600 group-hover:bg-amber-700' : 'bg-emerald-600 group-hover:bg-emerald-700' }}">
                Ver solicitudes <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
            </span>
        </a>

        {{-- Resumen en números --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['Pendientes por revisar', $pendientes, $hay ? 'clock' : 'check', $hay ? 'Requieren revisión' : 'Todo al día', $hay ? 'bg-linear-to-br from-amber-400 to-amber-600 text-white shadow-amber-500/30' : 'bg-linear-to-br from-emerald-400 to-emerald-600 text-white shadow-emerald-500/30'],
                ['Solicitudes radicadas', $total, 'clipboard', 'Total acumulado', 'bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-[#0C67A3]/30'],
                ['Últimos 30 días', $metricas['ultimos30'], 'calendar', 'Solicitudes nuevas', 'bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-[#0B8B7B]/30'],
                ['Tiempo promedio', $tiempo, 'clock', 'Desde que llega hasta que se finaliza', 'bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-[#FACC15]/30'],
            ] as [$etiqueta, $valor, $ico, $apoyo, $ficha])
                <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-slate-800">
                    <x-icon :name="$ico" class="pointer-events-none absolute -bottom-6 -right-6 h-28 w-28 text-slate-900 opacity-[0.04] dark:text-white dark:opacity-[0.06]" />
                    <div class="relative flex items-center gap-3">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl shadow-lg {{ $ficha }}"><x-icon :name="$ico" class="w-6 h-6" /></span>
                        <p class="text-[15px] font-semibold leading-snug text-slate-700 dark:text-slate-200">{{ $etiqueta }}</p>
                    </div>
                    <p class="hero-title relative mt-5 text-5xl text-slate-900 dark:text-white">{{ $valor }}</p>
                    <p class="relative mt-2 text-sm text-slate-500 dark:text-slate-300">{{ $apoyo }}</p>
                </div>
            @endforeach
        </div>

        {{-- Distribución --}}
        @if($total > 0)
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 p-7 shadow-sm">
                    <h2 class="hero-title text-xl text-slate-900 dark:text-white">Solicitudes por estado</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-6">Distribución sobre el total radicado.</p>
                    <ul class="space-y-5">
                        @foreach(\App\Models\Solicitud::ESTADOS as $clave => $nombreEstado)
                            @php $n = $metricas['porEstado'][$clave] ?? 0; $pct = round($n / $total * 100); @endphp
                            <li>
                                <div class="flex items-center justify-between text-[15px] mb-2">
                                    <span class="inline-flex items-center gap-2 font-semibold text-slate-700 dark:text-slate-200"><span class="h-2.5 w-2.5 rounded-full {{ $colorEstado[$clave][1] ?? 'bg-slate-400' }}"></span>{{ $nombreEstado }}</span>
                                    <span class="text-slate-500 dark:text-slate-300"><strong class="text-slate-900 dark:text-white">{{ $n }}</strong> · {{ $pct }}%</span>
                                </div>
                                <div class="h-3 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full rounded-full {{ $colorEstado[$clave][0] ?? 'bg-slate-500' }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 p-7 shadow-sm">
                    <h2 class="hero-title text-xl text-slate-900 dark:text-white">Solicitudes por tipo de trámite</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-6">Los trámites más solicitados.</p>
                    <ul class="space-y-5">
                        @foreach($metricas['porTipo'] as $tipo)
                            @php $pct = round($tipo->solicitudes_count / $total * 100); @endphp
                            <li>
                                <div class="flex items-center justify-between text-[15px] mb-2">
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $tipo->nombre }}</span>
                                    <span class="text-slate-500 dark:text-slate-300"><strong class="text-slate-900 dark:text-white">{{ $tipo->solicitudes_count }}</strong> · {{ $pct }}%</span>
                                </div>
                                <div class="h-3 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-linear-to-r from-[#0C67A3] to-[#0B8B7B] transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Accesos a cada módulo, agrupados --}}
        <div class="space-y-8">
            @foreach(collect($modulos)->reject(fn ($m) => $m['super'] && ! auth()->user()->is_super_admin)->groupBy('grupo') as $grupo => $items)
                <section>
                    <div class="mb-4 flex items-center gap-3">
                        <h2 class="hero-title text-lg text-slate-900 dark:text-white">{{ $grupo }}</h2>
                        <span class="h-px flex-1 bg-slate-200 dark:bg-white/10"></span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach($items as $m)
                            <a href="{{ route($m['route']) }}" class="group relative flex flex-col overflow-hidden rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10 focus-visible:outline-2 focus-visible:outline-[#0B8B7B]">
                                <x-icon :name="$m['icon']" class="pointer-events-none absolute -bottom-6 -right-6 h-28 w-28 text-slate-900 opacity-[0.04] transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110 dark:text-white dark:opacity-[0.06]" />
                                <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110 {{ $fichaGrupo[$grupo] }}"><x-icon :name="$m['icon']" class="w-7 h-7" /></span>
                                <span class="relative mt-5 block text-lg font-bold text-slate-900 dark:text-white">{{ $m['titulo'] }}</span>
                                <span class="relative mt-1 block text-[15px] leading-snug text-slate-600 dark:text-slate-300">{{ $m['texto'] }}</span>
                                <span class="relative mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-[#0C67A3] dark:text-sky-300">
                                    Abrir <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1.5" />
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
