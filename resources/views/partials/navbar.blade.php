@php
    // Fuente única de los enlaces: la usan el menú de escritorio y el móvil.
    $enlaces = [
        ['Inicio', url('/'), 'home'],
        ['Servicios', route('servicios'), 'servicios*'],
        ['Eventos', route('eventos'), 'eventos'],
        ['Trámites', route('solicitudes.crear'), 'solicitudes.*'],
        ['Afiliaciones', route('afiliaciones'), 'afiliaciones'],
        ['Institucional', route('institucional'), 'institucional'],
        ['Transparencia', route('transparencia'), 'transparencia*'],
        ['Contacto', route('contacto'), 'contacto'],
    ];
@endphp
<nav x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false" class="sticky top-0 z-50 w-full bg-linear-to-r from-[#0B4870] via-[#0D5A8C] to-[#0B4870] border-b border-white/10 shadow-lg shadow-black/25">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">

        <!-- LOGO -->
        <a href="{{ url('/') }}" class="flex items-center shrink-0">
            <img src="{{ asset('images/logo-blanco-recortado.webp') }}" width="445" height="200" alt="Foncaldas — Fondo de Empleados" class="h-12 2xl:h-16 w-auto object-contain">
        </a>

        <!-- DESKTOP NAV -->
        <div class="hidden min-[1200px]:flex flex-1 items-center">

        <div class="flex flex-1 items-center justify-center gap-0.5 2xl:gap-1.5">

            @foreach ($enlaces as [$etiqueta, $destino, $ruta])
                <a href="{{ $destino }}" class="nav-link {{ request()->routeIs($ruta) ? 'is-active' : '' }}">{{ $etiqueta }}</a>
            @endforeach
        </div>

        <div class="flex shrink-0 items-center gap-1.5">
            <!-- Divisor: separa la navegación del sitio del enlace externo -->
            <div class="hidden 2xl:block w-px h-7 bg-white/20 mx-2"></div>

            @auth
                {{-- Cuenta con sesión iniciada: un solo avatar; al hacer clic se despliega el menú con el panel y "Cerrar sesión". --}}
                <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative">
                    <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="menu"
                            title="{{ auth()->user()->name }}" aria-label="Menú de la cuenta"
                            class="inline-flex h-10 items-center gap-1.5 rounded-full bg-white/10 py-1 pl-1 pr-2.5 ring-1 ring-white/25 transition hover:bg-white/20 focus-visible:ring-white" :class="open ? 'bg-white/20' : ''">
                        <span class="inline-flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-white/90 text-[#0B4870]">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="mt-1.5 h-7 w-7" aria-hidden="true"><circle cx="12" cy="8" r="4.2"/><path d="M3.5 22a8.5 8.5 0 0117 0z"/></svg>
                        </span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-white transition-transform duration-200" :class="open ? 'rotate-180' : ''" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top.right role="menu"
                         class="absolute right-0 top-full z-50 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3.5 dark:border-slate-700">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="mt-1 h-7 w-7" aria-hidden="true"><circle cx="12" cy="8" r="4.2"/><path d="M3.5 22a8.5 8.5 0 0117 0z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->is_super_admin ? 'Administrador principal' : 'Funcionario' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" role="menuitem" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/50">
                            <x-icon name="layers" class="h-4 w-4 text-[#0C67A3] dark:text-[#5EB3E4]" /> Ir al panel de administración
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-slate-100 dark:border-slate-700">
                            @csrf
                            <button type="submit" role="menuitem" class="flex w-full items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                                <x-icon name="logout" class="h-4 w-4" /> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener"
               class="group hidden 2xl:inline-flex h-10 items-center gap-2 px-5 rounded-full border border-white/40 text-white text-[14px] font-semibold hover:bg-white hover:text-[#0B4870] transition whitespace-nowrap">
                Villa Beatriz
            </a>

            <x-theme-toggle />

            <x-open-status />
        </div>
        </div>

        <!-- MOBILE: interruptor + botón de menú -->
        <div class="min-[1200px]:hidden flex items-center gap-2 shrink-0">
            <x-theme-toggle />

            <x-open-status />

            <button type="button" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-controls="mobile-menu" aria-label="Abrir menú"
                class="inline-flex items-center justify-center w-11 h-11 rounded-full text-white hover:bg-white/10 transition">
                <svg x-show="!mobileOpen" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div class="h-0.5 bg-linear-to-r from-[#FACC15] via-[#0B8B7B] to-[#0C67A3]"></div>

    <!-- MOBILE MENU -->
    <div id="mobile-menu" x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms class="min-[1200px]:hidden border-t border-white/15 bg-[#0B4870]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col gap-1 text-white">

            @foreach ($enlaces as [$etiqueta, $destino])
                <a href="{{ $destino }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">{{ $etiqueta }}</a>
            @endforeach
            @auth
                <div class="mt-2 flex items-stretch overflow-hidden rounded-lg text-[15px] font-bold ring-1 ring-white/40">
                    <a href="{{ route('admin.dashboard') }}" class="flex-1 px-3 py-2.5 bg-linear-to-b from-[#12A594] to-[#0B8B7B] text-white">Administración · {{ auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="flex">
                        @csrf
                        <button type="submit" class="px-4 bg-[#082F4A] text-white">Salir</button>
                    </form>
                </div>
            @endauth
            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener" class="mt-2 px-3 py-2.5 rounded-lg bg-white text-[#0B4870] text-[15px] font-semibold text-center">Villa Beatriz</a>
        </div>
    </div>
</nav>
