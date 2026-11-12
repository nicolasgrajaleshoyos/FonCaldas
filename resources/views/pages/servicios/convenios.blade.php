@extends('layouts.app')

@section('title', 'Convenios | Foncaldas')

@section('content')
@php
    // Páginas del catálogo de convenios (imágenes recortadas del PDF). La 1 es la portada.
    // El ?v= (fecha del archivo) evita que el navegador muestre una versión anterior en caché de las imágenes.
    $paginas = collect(range(1, 5))->map(fn ($n) => asset("images/convenios/pagina-$n.webp").'?v='.filemtime(public_path("images/convenios/pagina-$n.webp")))->all();

    // Áreas de los aliados que aparecen en el catálogo.
    $areas = [
        ['icon' => 'heart', 'nombre' => 'Salud'],
        ['icon' => 'shield', 'nombre' => 'Seguros'],
        ['icon' => 'graduation', 'nombre' => 'Educación'],
        ['icon' => 'compass', 'nombre' => 'Viajes'],
        ['icon' => 'sparkle', 'nombre' => 'Odontología'],
        ['icon' => 'eye', 'nombre' => 'Óptica'],
        ['icon' => 'gift', 'nombre' => 'Mercado'],
        ['icon' => 'star', 'nombre' => 'Exequial'],
    ];
@endphp

<div class="min-h-screen page-bg px-6 py-14"
     x-data="{
        i: null,
        paginas: @js($paginas),
        abrir(n) { this.i = n },
        cerrar() { this.i = null },
        sig() { this.i = (this.i + 1) % this.paginas.length },
        ant() { this.i = (this.i - 1 + this.paginas.length) % this.paginas.length },
     }"
     @keydown.escape.window="cerrar()"
     @keydown.arrow-right.window="i !== null && sig()"
     @keydown.arrow-left.window="i !== null && ant()">

    <div class="max-w-6xl mx-auto">

        <x-back-link :route="route('servicios')" label="Volver a servicios" />

        {{-- PORTADA --}}
        <section class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B4870] p-8 shadow-xl shadow-[#0C67A3]/20 sm:p-12" aria-labelledby="titulo-convenios">
            <span class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#FACC15]/15 blur-2xl" aria-hidden="true"></span>
            <span class="pointer-events-none absolute -bottom-28 -left-12 h-72 w-72 rounded-full bg-[#38BDF8]/20 blur-3xl" aria-hidden="true"></span>

            <div class="relative grid items-center gap-10 md:grid-cols-2">
                <div class="text-center md:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#FACC15] ring-1 ring-white/20">
                        <x-icon name="briefcase" class="h-3.5 w-3.5" /> Beneficios para asociados
                    </span>

                    <h1 id="titulo-convenios" class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-5xl">Convenios</h1>
                    <p class="mt-4 text-base leading-relaxed text-sky-100 sm:text-lg">
                        Beneficios y descuentos exclusivos para asociados y beneficiarios de Foncaldas con nuestros aliados en salud, seguros, educación, viajes y más.
                    </p>

                    <div class="mt-7 flex flex-wrap justify-center gap-3 md:justify-start">
                        <a href="{{ asset('pdf/CONVENIOS.pdf') }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2.5 rounded-2xl bg-[#FACC15] px-6 py-3.5 font-extrabold text-[#0B4870] shadow-lg shadow-black/20 transition hover:-translate-y-0.5 hover:bg-[#FDE047]">
                            <x-icon name="document" class="h-5 w-5" /> Descargar PDF
                        </a>
                        <a href="https://wa.me/573225947308" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2.5 rounded-2xl bg-white/15 px-6 py-3.5 font-bold text-white ring-1 ring-white/30 transition hover:-translate-y-0.5 hover:bg-white/25">
                            <x-icon name="whatsapp" class="h-5 w-5" /> Más información
                        </a>
                    </div>
                </div>

                {{-- Portada del catálogo --}}
                <button type="button" @click="abrir(0)" aria-label="Ver la portada del catálogo en grande"
                        class="group mx-auto block w-full max-w-md rotate-1 overflow-hidden rounded-3xl shadow-2xl shadow-black/40 ring-4 ring-white/20 transition duration-300 hover:rotate-0 hover:scale-[1.02]">
                    <img src="{{ $paginas[0] }}" alt="Conoce nuestros convenios para asociados y beneficiarios" width="939" height="726" loading="eager" class="h-auto w-full">
                </button>
            </div>
        </section>

        {{-- ÁREAS --}}
        <section class="mt-10" aria-label="Áreas de los convenios">
            <ul class="flex flex-wrap justify-center gap-3">
                @foreach($areas as $a)
                    <li class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#0C67A3]/10 text-[#0C67A3] dark:text-[#5EB3E4]">
                            <x-icon :name="$a['icon']" class="h-4 w-4" />
                        </span>
                        {{ $a['nombre'] }}
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- CATÁLOGO --}}
        <section class="mt-12" aria-label="Catálogo de convenios">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">Nuestros aliados</h2>
                <p class="mt-2 text-slate-500 dark:text-slate-300">Toca una página para verla en grande y encuentra el contacto de cada asesor.</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                @foreach($paginas as $n => $url)
                    @continue($n === 0)
                    <button type="button" @click="abrir({{ $n }})" aria-label="Ver página {{ $n + 1 }} del catálogo en grande"
                            class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl dark:border-slate-700 dark:bg-slate-800">
                        <img src="{{ $url }}" alt="Convenios Foncaldas — página {{ $n + 1 }}" width="939" height="726" loading="lazy" class="h-auto w-full transition duration-500 group-hover:scale-[1.03]">
                        <span class="flex items-center justify-between px-5 py-3 text-sm font-semibold text-slate-600 dark:text-slate-300">
                            Página {{ $n + 1 }} de {{ count($paginas) }}
                            <span class="inline-flex items-center gap-1.5 text-[#0C67A3] dark:text-[#5EB3E4]">
                                <x-icon name="search" class="h-4 w-4" /> Ver en grande
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- PROPONER UN CONVENIO --}}
        <section class="mt-12 mb-6 flex flex-col items-center justify-between gap-5 rounded-3xl border border-[#25D366]/30 bg-[#25D366]/10 p-7 text-center sm:flex-row sm:text-left" aria-label="Proponer un convenio">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">¿Tienes un convenio para proponer?</h2>
                <p class="mt-1 text-slate-600 dark:text-slate-300">Escríbenos por WhatsApp y lo revisamos para beneficio de todos los asociados.</p>
            </div>
            <a href="https://wa.me/573225947308" target="_blank" rel="noopener"
               class="inline-flex shrink-0 items-center gap-2.5 rounded-2xl bg-[#25D366] px-6 py-3.5 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:brightness-110">
                <x-icon name="whatsapp" class="h-5 w-5" /> Escríbenos
            </a>
        </section>

        <p class="text-slate-400 text-xs text-center">
            Información sujeta a cambios por parte de cada aliado. Última actualización: 15 de mayo de 2026.
        </p>
    </div>

    {{-- VISOR EN GRANDE --}}
    <div x-show="i !== null" x-cloak x-transition.opacity
         class="fixed inset-0 z-[150] flex items-center justify-center bg-black/90 p-4"
         role="dialog" aria-modal="true" aria-label="Catálogo de convenios" @click.self="cerrar()">
        <button type="button" @click="cerrar()" aria-label="Cerrar"
                class="absolute right-4 top-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-2xl leading-none text-white transition hover:bg-white/30">&times;</button>
        <button type="button" @click="ant()" aria-label="Página anterior"
                class="absolute left-3 top-1/2 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/30 sm:left-6">
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12.5 4.5 7 10l5.5 5.5"/></svg>
        </button>
        <img :src="i !== null ? paginas[i] : ''" :alt="'Convenios Foncaldas — página ' + (i + 1)" class="max-h-[88vh] w-auto max-w-full rounded-2xl shadow-2xl">
        <button type="button" @click="sig()" aria-label="Página siguiente"
                class="absolute right-3 top-1/2 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/30 sm:right-6">
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.5 4.5 13 10l-5.5 5.5"/></svg>
        </button>
        <p class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-1 text-sm font-semibold text-white" x-text="(i + 1) + ' / ' + paginas.length"></p>
    </div>
</div>
@endsection
