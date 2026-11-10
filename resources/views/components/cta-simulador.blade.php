{{-- Tarjeta de invitación a un simulador: fondo institucional, mascota, argumentos breves y botón destacado. --}}
@props(['route', 'titulo', 'texto', 'boton', 'pose' => 'celular', 'puntos' => []])

<section class="relative mt-10 mb-8 overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B4870] p-7 shadow-xl shadow-[#0C67A3]/20 sm:p-9" aria-label="{{ $titulo }}">
    {{-- Adornos de fondo --}}
    <span class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-[#FACC15]/15 blur-2xl" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -bottom-24 -left-10 h-64 w-64 rounded-full bg-[#38BDF8]/20 blur-3xl" aria-hidden="true"></span>

    <div class="relative flex flex-col items-center gap-6 text-center md:flex-row md:gap-10 md:text-left">
        <x-mascot :pose="$pose" size="w-32 h-32 md:w-44 md:h-44" />

        <div class="min-w-0 flex-1">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#FACC15] ring-1 ring-white/20">
                <x-icon name="sparkle" class="h-3.5 w-3.5" /> Simulador gratuito
            </span>

            <h2 class="mt-3 text-2xl font-extrabold leading-tight text-white sm:text-3xl">{{ $titulo }}</h2>
            <p class="mt-2 max-w-xl text-sm leading-relaxed text-sky-100 sm:text-base">{{ $texto }}</p>

            @if($puntos)
                <ul class="mt-4 flex flex-wrap justify-center gap-2 md:justify-start">
                    @foreach($puntos as $punto)
                        <li class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white ring-1 ring-white/15">
                            <x-icon name="check" class="h-3.5 w-3.5 text-[#FACC15]" /> {{ $punto }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <a href="{{ $route }}"
           class="group inline-flex shrink-0 items-center gap-3 rounded-2xl bg-[#FACC15] px-7 py-4 text-base font-extrabold text-[#0B4870] shadow-lg shadow-black/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#FDE047] hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="2.5" width="14" height="19" rx="2.5"/><rect x="8" y="5.5" width="8" height="3.5" rx="1"/><path d="M8.5 13h.01M12 13h.01M15.5 13h.01M8.5 16.5h.01M12 16.5h.01M15.5 16.5h.01"/></svg>
            {{ $boton }}
            <svg class="h-5 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.5 4.5 13 10l-5.5 5.5"/></svg>
        </a>
    </div>
</section>
