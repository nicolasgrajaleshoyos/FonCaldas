@props(['active', 'title', 'subtitle' => null, 'icon' => 'layers'])

@php
$user = auth()->user();

$tabs = [
    ['key' => 'dashboard', 'route' => 'admin.dashboard', 'label' => 'Panel', 'icon' => 'layers'],
    ['key' => 'solicitudes', 'route' => 'admin.solicitudes.index', 'label' => 'Solicitudes', 'icon' => 'clipboard'],
];

if ($user?->is_super_admin) {
    array_push($tabs,
        ['key' => 'pqrs', 'route' => 'admin.pqrs.index', 'label' => 'PQRS', 'icon' => 'ticket'],
        ['key' => 'documents', 'route' => 'admin.documents', 'label' => 'Documentos', 'icon' => 'document'],
        ['key' => 'events', 'route' => 'admin.events', 'label' => 'Eventos', 'icon' => 'calendar'],
        ['key' => 'auditoria', 'route' => 'admin.auditoria', 'label' => 'Auditoría', 'icon' => 'eye'],
        ['key' => 'usuarios', 'route' => 'admin.usuarios.index', 'label' => 'Usuarios', 'icon' => 'users'],
        ['key' => 'tramite-tipos', 'route' => 'admin.tramite-tipos.index', 'label' => 'Tipos de trámite', 'icon' => 'briefcase'],
        ['key' => 'parametros-financieros', 'route' => 'admin.parametros-financieros.index', 'label' => 'Parámetros financieros', 'icon' => 'card'],
    );
}
@endphp

{{-- Encabezado del panel: franja de marca con el título de la sección y, debajo, las pestañas en una cápsula. --}}
<div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-800">

    <div class="relative overflow-hidden bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] px-5 py-6 text-white sm:px-8 sm:py-7">
        <span class="hero-sun absolute -right-24 -top-36 h-96 w-96 opacity-60" aria-hidden="true"></span>
        <span class="absolute -bottom-24 left-1/3 h-56 w-56 rounded-full bg-[#FACC15]/20 blur-3xl" aria-hidden="true"></span>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <span class="hidden h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 sm:inline-flex text-white shadow-lg ring-1 ring-white/30 backdrop-blur"><x-icon :name="$icon" class="w-8 h-8" /></span>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#FACC15] sm:text-xs sm:tracking-widest">Panel de administración</p>
                    <h1 class="hero-title mt-0.5 break-words text-[1.6rem] leading-tight sm:text-3xl">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="mt-1 text-sm text-white/85 sm:text-base">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            @if($user)
                <div class="flex items-center gap-3 self-start rounded-full bg-white/10 py-1.5 pl-1.5 pr-5 ring-1 ring-white/25 backdrop-blur md:self-center" title="Sesión de administración">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#FACC15] text-lg font-bold text-[#0B4870]">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</span>
                    <div class="leading-tight">
                        <p class="text-sm font-semibold">{{ $user->name }}</p>
                        <p class="text-xs text-white/80">{{ $user->is_super_admin ? 'Administrador principal' : 'Funcionario' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pestañas: la activa es una píldora de color. Siempre en una sola fila; si no caben, la fila se desliza de lado. -->
    <nav id="admin-tabs-nav" class="flex gap-1 overflow-x-auto px-3 py-3 [scrollbar-width:none] sm:px-4 xl:justify-between" aria-label="Secciones de administración">
        @foreach($tabs as $tab)
            @php $is = $active === $tab['key']; @endphp
            <a href="{{ route($tab['route']) }}" @if($is) aria-current="page" @endif
               class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-2.5 text-[13px] font-semibold xl:gap-2 xl:px-3 xl:text-sm 2xl:px-4 transition {{ $is ? 'bg-[#0B4870] text-white shadow-md shadow-[#0B4870]/30 dark:bg-[#0C67A3]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white' }}">
                <x-icon :name="$tab['icon']" class="w-4 h-4 {{ $is ? 'text-[#FACC15]' : '' }}" /> {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>

{{-- Con 9 pestañas el menú desborda en pantallas medianas: se centra la activa para que no quede oculta. --}}
<script>
    document.querySelector('#admin-tabs-nav [aria-current="page"]')?.scrollIntoView({ inline: 'center', block: 'nearest' });
</script>
