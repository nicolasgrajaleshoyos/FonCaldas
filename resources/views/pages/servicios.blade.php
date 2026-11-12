@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg px-6 py-16">

    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="abierto" class="mb-6" sol />
            <x-page-header
                title="Servicios Foncaldas"
                subtitle="Descubre nuestras soluciones para ahorrar, obtener créditos, afiliarte y aprovechar convenios exclusivos."
                center />
        </div>

        @php
            // Colores de cada tarjeta: [ficha del ícono, halo de fondo, color de la marca de agua]
            $azul = ['bg-linear-to-br from-[#0C67A3] to-[#0B4870] shadow-[#0C67A3]/40', 'bg-[#0C67A3]/20 dark:bg-sky-400/20', 'text-[#0C67A3] dark:text-sky-300'];
            $verde = ['bg-linear-to-br from-[#12A594] to-[#0B8B7B] shadow-[#0B8B7B]/40', 'bg-[#0B8B7B]/20 dark:bg-teal-400/20', 'text-[#0B8B7B] dark:text-teal-300'];
            $ambar = ['bg-linear-to-br from-[#FACC15] to-[#EAB308] shadow-[#FACC15]/40 !text-[#0B4870]', 'bg-[#FACC15]/30 dark:bg-amber-300/20', 'text-[#CA8A04] dark:text-amber-300'];
        @endphp

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ([
                [route('afiliaciones'), 'users', 'Afiliaciones', 'Accede a beneficios exclusivos con aliados locales y mejora tu bienestar.', $azul],
                [route('servicios.ahorro'), 'coins', 'Ahorro', 'Descubre nuestros planes de ahorro flexibles y seguros para tus metas financieras.', $verde],
                [route('servicios.creditos'), 'card', 'Créditos', 'Líneas de crédito accesibles para vehículos, proyectos y necesidades personales.', $ambar],
                [route('servicios.bienestar'), 'heart', 'Bienestar', 'Programas y servicios que protegen tu salud y la de tu familia.', $azul],
                [route('servicios.convenios'), 'briefcase', 'Convenios', 'Conoce nuestros convenios y las alianzas disponibles.', $verde],
                [route('contacto'), 'phone', 'Atención', 'Soporte rápido y cercano en cada paso de tu experiencia Foncaldas.', $ambar],
            ] as $i => [$destino, $icono, $titulo, $detalle, [$ficha, $halo, $acento]])
                <a href="{{ $destino }}" style="animation-delay:{{ $i * .06 }}s"
                   class="hero-in group relative flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800">
                    <span class="absolute -right-10 -top-10 h-36 w-36 rounded-full {{ $halo }} blur-3xl transition-transform duration-500 group-hover:scale-150" aria-hidden="true"></span>
                    <!-- Ícono gigante de fondo, como marca de agua -->
                    <x-icon :name="$icono" class="pointer-events-none absolute -bottom-8 -right-6 h-36 w-36 {{ $acento }} opacity-[0.08] transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110" />

                    <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl {{ $ficha }} text-white shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                        <x-icon :name="$icono" class="w-7 h-7" />
                    </span>

                    <h2 class="hero-title relative mt-5 text-2xl text-slate-900 dark:text-white">{{ $titulo }}</h2>
                    <p class="relative mt-2 text-base leading-relaxed text-slate-600 dark:text-slate-300">{{ $detalle }}</p>

                    <span class="relative mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold {{ $acento }}">
                        Conocer más
                        <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1.5" />
                    </span>
                </a>
            @endforeach

            <!-- VILLA BEATRIZ: tarjeta destacada a lo ancho (enlace externo) -->
            <a href="https://www.villabeatriz.co/" target="_blank" rel="noopener" style="animation-delay:.36s"
               class="hero-in group relative flex flex-col gap-6 overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-7 md:p-9 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1.5 hover:shadow-2xl sm:col-span-2 lg:col-span-3 md:flex-row md:items-center">
                <span class="hero-sun absolute -right-24 -top-32 h-96 w-96" aria-hidden="true"></span>

                <span class="relative inline-flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#FACC15] text-[#0B4870] shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                    <x-icon name="star" class="w-8 h-8" />
                </span>

                <div class="relative min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Centro vacacional</p>
                    <h2 class="hero-title mt-1 text-2xl md:text-3xl">Villa Beatriz</h2>
                    <p class="mt-2 max-w-2xl text-base leading-relaxed text-white/90">
                        Centro vacacional de Foncaldas: alojamiento a tarifas preferenciales, pasadía gratuito
                        y eventos exclusivos para asociados y beneficiarios.
                    </p>
                </div>

                <span class="relative inline-flex shrink-0 items-center gap-3 self-start rounded-full bg-white pl-6 pr-2 py-2 font-semibold text-[#0B4870] shadow-lg md:self-center">
                    Visitar sitio
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FACC15] transition-transform duration-300 group-hover:translate-x-1">
                        <x-icon name="arrow-right" class="w-4 h-4 -rotate-45" />
                    </span>
                </span>
            </a>

        </div>

    </div>

</div>
@endsection
