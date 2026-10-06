@props(['active', 'title', 'subtitle' => null, 'icon' => 'layers'])

@php
$user = auth()->user();

$tabs = [
    ['key' => 'dashboard', 'route' => 'admin.dashboard', 'label' => 'Panel', 'icon' => 'layers'],
    ['key' => 'solicitudes', 'route' => 'admin.solicitudes.index', 'label' => 'Solicitudes', 'icon' => 'clipboard'],
    ['key' => 'pqrs', 'route' => 'admin.pqrs.index', 'label' => 'PQRS', 'icon' => 'ticket'],
    ['key' => 'documents', 'route' => 'admin.documents', 'label' => 'Documentos', 'icon' => 'document'],
    ['key' => 'events', 'route' => 'admin.events', 'label' => 'Eventos', 'icon' => 'ticket'],
    ['key' => 'auditoria', 'route' => 'admin.auditoria', 'label' => 'Auditoría', 'icon' => 'eye'],
];

if ($user?->is_super_admin) {
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

<div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon :name="$icon" class="w-6 h-6" /></span>
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-slate-600 dark:text-slate-400 mt-2">{{ $subtitle }}</p>
            @endif
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="rounded-lg border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 transition">Cerrar sesión</button>
        </form>
    </div>

    <nav class="flex flex-wrap gap-2 border-t border-slate-100 dark:border-slate-700 pt-4">
        @foreach($tabs as $tab)
            <a href="{{ route($tab['route']) }}"
               class="inline-flex items-center gap-1.5 rounded-lg px-4 py-2 text-sm font-medium transition {{ $active === $tab['key'] ? 'bg-[#0B4870] text-white' : 'border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                <x-icon :name="$tab['icon']" class="w-4 h-4" /> {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>
