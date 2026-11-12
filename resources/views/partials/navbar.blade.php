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
<nav x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false" class="sticky top-0 z-50 w-full bg-linear-to-r from-[#0B4870]/90 via-[#0D5A8C]/90 to-[#0B4870]/90 backdrop-blur-xl border-b border-white/10 shadow-lg shadow-black/25">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">

        <!-- LOGO -->
        <a href="{{ url('/') }}" class="flex items-center shrink-0">
            <img src="{{ asset('images/logo-blanco-recortado.webp') }}" width="445" height="200" alt="Foncaldas — Fondo de Empleados" class="h-12 2xl:h-16 w-auto object-contain">
        </a>

        <!-- DESKTOP NAV -->
        <div class="hidden min-[1200px]:flex flex-1 items-center">

        <div class="flex flex-1 items-center justify-center">
            <!-- Cápsula de navegación: la pestaña activa es una píldora blanca -->
            <div class="flex items-center gap-0.5 2xl:gap-1 rounded-full bg-white/10 p-1 ring-1 ring-white/15 shadow-inner shadow-black/10">
                @foreach ($enlaces as [$etiqueta, $destino, $ruta])
                    <a href="{{ $destino }}" class="nav-link {{ request()->routeIs($ruta) ? 'is-active' : '' }}" @if(request()->routeIs($ruta)) aria-current="page" @endif>{{ $etiqueta }}</a>
                @endforeach
            </div>
        </div>

        <!-- Acciones: en pantallas anchas van en una cápsula igual a la del menú -->
        <div class="flex shrink-0 items-center gap-1.5 2xl:gap-2 2xl:rounded-full 2xl:bg-white/10 2xl:p-1 2xl:pl-1.5 2xl:ring-1 2xl:ring-white/15">

            @auth
                {{-- Cuenta con sesión iniciada: avatar con la inicial (anillo con los colores del logo y punto verde de "en línea").
                     Al hacer clic se despliega una tarjeta con los datos de la cuenta, accesos al panel y "Cerrar sesión". --}}
                @php
                    $cuenta = auth()->user();
                    $inicial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($cuenta->name, 0, 1));
                    $primerNombre = \Illuminate\Support\Str::before($cuenta->name, ' ');
                @endphp
                <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative">
                    <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="menu"
                            title="{{ $cuenta->name }}" aria-label="Menú de la cuenta"
                            class="group inline-flex h-10 items-center gap-2 rounded-full bg-white/10 py-1 pl-1 pr-2.5 ring-1 ring-white/25 transition hover:bg-white/20 focus-visible:ring-white"
                            :class="open ? 'bg-white/20 ring-[#FACC15]/70' : ''">
                        <span class="relative inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#FACC15] via-[#0B8B7B] to-[#0C67A3] p-[2px]">
                            <span class="inline-flex h-full w-full items-center justify-center rounded-full bg-[#FACC15] text-sm font-extrabold text-[#0B4870] transition-transform duration-300 group-hover:scale-105">{{ $inicial }}</span>
                            <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-[#0B4870] bg-emerald-400" aria-hidden="true"></span>
                        </span>
                        <span class="hidden max-w-[7rem] truncate text-[13px] font-semibold text-white 2xl:block">{{ $primerNombre }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-white/90 transition-transform duration-300" :class="open ? 'rotate-180' : ''" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         role="menu"
                         class="absolute right-0 top-full z-50 mt-3 w-80 origin-top-right overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-black/30 dark:border-white/10 dark:bg-slate-800">

                        <!-- Encabezado de la tarjeta con los colores de la marca -->
                        <div class="relative overflow-hidden bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] px-5 pb-5 pt-6 text-white">
                            <span class="hero-sun absolute -right-20 -top-24 h-56 w-56 opacity-50" aria-hidden="true"></span>
                            <div class="relative flex items-center gap-3">
                                <span class="relative inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#FACC15] text-2xl font-extrabold text-[#0B4870] shadow-lg ring-4 ring-white/25">
                                    {{ $inicial }}
                                    <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-[#0C67A3] bg-emerald-400" aria-hidden="true"></span>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold leading-tight line-clamp-2">{{ $cuenta->name }}</p>
                                    <span class="mt-1.5 inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-semibold ring-1 ring-white/20">
                                        <x-icon name="shield" class="h-3 w-3 text-[#FACC15]" />
                                        {{ $cuenta->is_super_admin ? 'Administrador principal' : 'Funcionario' }}
                                    </span>
                                </div>
                            </div>
                            <p class="relative mt-3 inline-flex items-center gap-1.5 text-xs text-white/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Sesión activa</p>
                        </div>

                        <div class="p-2">
                            @foreach ([
                                ['admin.dashboard', 'layers', 'Panel de administración', 'Resumen y accesos'],
                                ['admin.solicitudes.index', 'clipboard', 'Solicitudes', 'Revisa los trámites radicados'],
                            ] as [$ruta, $icono, $texto, $detalle])
                                <a href="{{ route($ruta) }}" role="menuitem" class="group flex items-center gap-3 rounded-2xl px-3 py-2.5 transition hover:bg-slate-100 dark:hover:bg-white/10">
                                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] transition group-hover:bg-[#0C67A3] group-hover:text-white dark:bg-sky-400/15 dark:text-sky-300"><x-icon :name="$icono" class="h-5 w-5" /></span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $texto }}</span>
                                        <span class="block text-xs text-slate-500 dark:text-slate-300">{{ $detalle }}</span>
                                    </span>
                                    <x-icon name="arrow-right" class="h-4 w-4 -translate-x-1 text-slate-400 opacity-0 transition group-hover:translate-x-0 group-hover:opacity-100" />
                                </a>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-slate-100 p-2 dark:border-white/10">
                            @csrf
                            <button type="submit" role="menuitem" class="group flex w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-left transition hover:bg-rose-50 dark:hover:bg-rose-500/10">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500/10 text-rose-600 transition group-hover:bg-rose-600 group-hover:text-white dark:text-rose-300"><x-icon name="logout" class="h-5 w-5" /></span>
                                <span class="text-sm font-semibold text-rose-600 dark:text-rose-300">Cerrar sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener" title="Abre villabeatriz.co en una pestaña nueva"
               {{-- Visible en todo el menú de escritorio; con sesión iniciada espera a 1280 px porque el avatar ocupa su espacio --}}
               class="group {{ auth()->check() ? 'hidden xl:inline-flex' : 'inline-flex' }} h-10 items-center gap-2 pl-3.5 2xl:pl-4 pr-1.5 rounded-full border border-[#FACC15]/60 bg-white/5 text-white text-[13px] 2xl:text-[14px] font-semibold hover:bg-[#FACC15] hover:text-[#0B4870] hover:-translate-y-0.5 transition whitespace-nowrap">
                Villa Beatriz
                <!-- Flecha de enlace externo -->
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition group-hover:bg-[#0B4870] group-hover:text-[#FACC15]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true"><path d="M7 17L17 7M9 7h8v8"/></svg>
                </span>
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

            @foreach ($enlaces as [$etiqueta, $destino, $ruta])
                <a href="{{ $destino }}" class="px-3 py-2.5 rounded-xl transition text-[15px] {{ request()->routeIs($ruta) ? 'bg-white text-[#0B4870] font-semibold' : 'hover:bg-white/10 font-medium' }}">{{ $etiqueta }}</a>
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
