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
    );
    $tabs[] = ['key' => 'usuarios', 'route' => 'admin.usuarios.index', 'label' => 'Usuarios', 'icon' => 'users'];
    $tabs[] = ['key' => 'tramite-tipos', 'route' => 'admin.tramite-tipos.index', 'label' => 'Tipos de trámite', 'icon' => 'briefcase'];
    $tabs[] = [
    'key' => 'parametros-financieros',
    'route' => 'admin.parametros-financieros.index',
    'label' => 'Parámetros financieros',
    'icon' => 'card',
];
}
@endphp

<div class="overflow-hidden rounded-3xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
    <div class="h-0.5 bg-linear-to-r from-[#0B4870]/70 via-[#0C67A3]/50 to-transparent"></div>

    <div class="p-6 pb-0 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4">
            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0B4870] to-[#0C67A3] text-white shadow-md shadow-[#0C67A3]/30"><x-icon :name="$icon" class="w-7 h-7" /></span>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm sm:text-base">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        @if($user)
            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 py-2 pl-2 pr-4" title="Sesión de administración">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/15 text-[#0C67A3] dark:text-[#5EB3E4] font-bold">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->is_super_admin ? 'Administrador principal' : 'Funcionario' }}</p>
                </div>
            </div>
        @endif
    </div>

    <nav id="admin-tabs-nav" class="mt-5 flex gap-1 overflow-x-auto px-4 border-t border-slate-100 dark:border-slate-700/70" aria-label="Secciones de administración">
        @foreach($tabs as $tab)
            @php $is = $active === $tab['key']; @endphp
            <a href="{{ route($tab['route']) }}" @if($is) aria-current="page" @endif
               class="relative inline-flex shrink-0 items-center gap-2 px-4 py-3.5 text-sm font-semibold transition {{ $is ? 'text-[#0B4870] dark:text-[#5EB3E4]' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                <x-icon :name="$tab['icon']" class="w-4 h-4" /> {{ $tab['label'] }}
                <span class="absolute inset-x-3 bottom-0 h-0.5 rounded-full transition {{ $is ? 'bg-[#0C67A3] dark:bg-[#5EB3E4]' : 'bg-transparent' }}"></span>
            </a>
        @endforeach
    </nav>
</div>

{{-- Con 8 pestañas el menú desborda en pantallas medianas: se centra la activa para que no quede oculta. --}}
<script>
    document.querySelector('#admin-tabs-nav [aria-current="page"]')?.scrollIntoView({ inline: 'center', block: 'nearest' });
</script>
