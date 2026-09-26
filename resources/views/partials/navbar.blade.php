<nav x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false" class="sticky top-0 z-50 w-full bg-linear-to-r from-[#0B4870] via-[#0D5A8C] to-[#0B4870] border-b border-white/10 shadow-lg shadow-black/25">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">

        <!-- LOGO -->
        <a href="{{ url('/') }}" class="flex items-center shrink-0">
            <img src="{{ asset('images/logo-lockup.png') }}" alt="Foncaldas — Fondo de Empleados" class="brand-logo h-16 w-auto object-contain">
        </a>

        <!-- DESKTOP NAV -->
        <div class="hidden xl:flex items-center gap-1.5">

            <a href="{{ url('/') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                Inicio
            </a>

            <a href="{{ route('servicios') }}" class="nav-link {{ request()->routeIs('servicios*') ? 'is-active' : '' }}">
                Servicios
            </a>

            <a href="{{ route('eventos') }}" class="nav-link {{ request()->routeIs('eventos') ? 'is-active' : '' }}">
                Eventos
            </a>

            <a href="{{ route('solicitudes.crear') }}" class="nav-link {{ request()->routeIs('solicitudes.*') ? 'is-active' : '' }}">
                Trámites
            </a>

            <a href="{{ route('afiliaciones') }}" class="nav-link {{ request()->routeIs('afiliaciones') ? 'is-active' : '' }}">
                Afiliaciones
            </a>

            <a href="{{ route('institucional') }}" class="nav-link {{ request()->routeIs('institucional') ? 'is-active' : '' }}">
                Institucional
            </a>

            <a href="{{ route('transparencia') }}" class="nav-link {{ request()->routeIs('transparencia*') ? 'is-active' : '' }}">
                Transparencia
            </a>

            <a href="{{ route('contacto') }}" class="nav-link {{ request()->routeIs('contacto') ? 'is-active' : '' }}">
                Contacto
            </a>

            <!-- Divisor: separa la navegación del sitio del enlace externo -->
            <div class="w-px h-7 bg-white/20 mx-3"></div>

            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener"
               class="group ml-1 inline-flex h-11 items-center gap-2 px-5 rounded-full bg-white text-[#0B4870] text-[15px] font-semibold hover:bg-sky-50 hover:-translate-y-0.5 transition whitespace-nowrap shadow-md">
                Villa Beatriz <x-icon name="arrow-right" class="w-4 h-4 transition group-hover:translate-x-0.5" />
            </a>

            <x-theme-toggle class="ml-2" />

            <x-open-status class="ml-2" />
        </div>

        <!-- MOBILE: interruptor + botón de menú -->
        <div class="xl:hidden flex items-center gap-2 shrink-0">
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
    <div id="mobile-menu" x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms class="xl:hidden border-t border-white/15 bg-[#0B4870]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col gap-1 text-white">

            <a href="{{ url('/') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Inicio</a>
            <a href="{{ route('servicios') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Servicios</a>
            <a href="{{ route('eventos') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Eventos</a>

            <a href="{{ route('solicitudes.crear') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Trámites</a>

            <a href="{{ route('afiliaciones') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Afiliaciones</a>

            <a href="{{ route('institucional') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Institucional</a>

            <a href="{{ route('transparencia') }}" class="mt-2 px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Transparencia</a>
            <a href="{{ route('contacto') }}" class="px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-[15px] font-medium">Contacto</a>
            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener" class="mt-2 px-3 py-2.5 rounded-lg bg-white text-[#0B4870] text-[15px] font-semibold text-center">Villa Beatriz</a>
        </div>
    </div>
</nav>
