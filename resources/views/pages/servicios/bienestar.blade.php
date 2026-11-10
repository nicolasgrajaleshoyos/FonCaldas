@extends('layouts.app')

@section('content')
@php
    // Programas del Área de Bienestar: ícono, nombre y color de acento.
    $programas = [
        ['icon' => 'sun', 'nombre' => 'Recreativos'],
        ['icon' => 'star', 'nombre' => 'Culturales'],
        ['icon' => 'graduation', 'nombre' => 'Educativos'],
        ['icon' => 'trophy', 'nombre' => 'Deportivos'],
        ['icon' => 'users', 'nombre' => 'Sociales'],
    ];

    $opciones = [
        ['icon' => 'graduation', 'titulo' => 'Cursos', 'texto' => 'Participa en cursos y capacitaciones para nuestros asociados.', 'ruta' => route('eventos'), 'acento' => 'from-[#0C67A3] to-[#38BDF8]'],
        ['icon' => 'sparkle', 'titulo' => 'Actividades', 'texto' => 'Disfruta actividades recreativas, deportivas y culturales.', 'ruta' => route('eventos'), 'acento' => 'from-[#F59E0B] to-[#FACC15]'],
        ['icon' => 'briefcase', 'titulo' => 'Convenios', 'texto' => 'Conoce los beneficios y alianzas disponibles para asociados.', 'ruta' => route('servicios.convenios'), 'acento' => 'from-[#0B8B7B] to-[#34D399]'],
    ];
@endphp

<div class="min-h-screen page-bg px-6 py-14">
    <div class="max-w-6xl mx-auto">

        <x-back-link :route="route('servicios')" label="Volver a servicios" />

        {{-- PORTADA --}}
        <section class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B4870] p-8 shadow-xl shadow-[#0C67A3]/20 sm:p-12" aria-labelledby="titulo-bienestar">
            <span class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#FACC15]/15 blur-2xl" aria-hidden="true"></span>
            <span class="pointer-events-none absolute -bottom-28 -left-12 h-72 w-72 rounded-full bg-[#38BDF8]/20 blur-3xl" aria-hidden="true"></span>

            <div class="relative flex flex-col-reverse items-center gap-8 text-center md:flex-row md:gap-12 md:text-left">
                <div class="min-w-0 flex-1">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#FACC15] ring-1 ring-white/20">
                        <x-icon name="heart" class="h-3.5 w-3.5" /> Bienestar FONCALDAS
                    </span>

                    <h1 id="titulo-bienestar" class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-5xl">Área de Bienestar</h1>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-sky-100 sm:text-lg">
                        Espacios, actividades y beneficios diseñados para promover el bienestar integral de nuestros asociados y sus familias.
                    </p>

                    <div class="mt-7 flex flex-wrap justify-center gap-3 md:justify-start">
                        <a href="https://wa.me/573225947308" target="_blank" rel="noopener"
                           class="group inline-flex items-center gap-2.5 rounded-2xl bg-[#25D366] px-6 py-3.5 font-bold text-white shadow-lg shadow-black/20 transition hover:-translate-y-0.5 hover:brightness-110">
                            <x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp Bienestar
                        </a>
                        <a href="{{ route('eventos') }}"
                           class="inline-flex items-center gap-2.5 rounded-2xl bg-white/15 px-6 py-3.5 font-bold text-white ring-1 ring-white/30 transition hover:-translate-y-0.5 hover:bg-white/25">
                            <x-icon name="calendar" class="h-5 w-5" /> Ver eventos
                        </a>
                    </div>
                </div>

                <x-mascot pose="alegre" size="w-44 h-44 md:w-64 md:h-64" loading="eager" />
            </div>
        </section>

        {{-- QUÉ HACEMOS --}}
        <section class="mt-10 grid gap-6 lg:grid-cols-5" aria-label="Qué hace el Área de Bienestar">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:col-span-3">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">¿Qué hacemos?</h2>
                <div class="mt-1 h-1 w-14 rounded-full bg-linear-to-r from-[#0C67A3] to-[#0B8B7B]"></div>

                <p class="mt-5 leading-relaxed text-slate-600 dark:text-slate-300">
                    El Área de Bienestar de <span class="font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">FONCALDAS</span>
                    trabaja para generar espacios, actividades y beneficios que contribuyan al bienestar integral
                    de nuestros asociados y sus familias.
                </p>
                <p class="mt-4 leading-relaxed text-slate-600 dark:text-slate-300">
                    A través de programas recreativos, culturales, educativos, deportivos y sociales,
                    buscamos fortalecer la calidad de vida, la integración y el sentido de pertenencia
                    hacia nuestra gran familia FONCALDAS.
                </p>

                <ul class="mt-6 flex flex-wrap gap-2.5">
                    @foreach($programas as $p)
                        <li class="inline-flex items-center gap-2 rounded-full bg-[#0C67A3]/10 px-4 py-2 text-sm font-semibold text-[#0C67A3] ring-1 ring-[#0C67A3]/20 dark:bg-[#5EB3E4]/10 dark:text-[#5EB3E4] dark:ring-[#5EB3E4]/20">
                            <x-icon :name="$p['icon']" class="h-4 w-4" /> {{ $p['nombre'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <aside class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#FACC15] to-[#F59E0B] p-8 shadow-sm lg:col-span-2">
                <x-icon name="heart" class="absolute -bottom-6 -right-6 h-36 w-36 text-white/25" />
                <p class="relative text-xs font-bold uppercase tracking-wider text-[#0B4870]">Nuestro propósito</p>
                <p class="relative mt-3 text-lg font-semibold leading-relaxed text-[#0B4870]">
                    Acompañar a los asociados con experiencias, convenios y servicios que promuevan el crecimiento personal,
                    la sana convivencia y el disfrute de momentos especiales, reafirmando así el compromiso solidario que nos caracteriza.
                </p>
            </aside>
        </section>

        {{-- OPCIONES --}}
        <section class="mt-10" aria-label="Opciones de bienestar">
            <h2 class="mb-5 text-2xl font-bold text-slate-900 dark:text-white">Explora lo que tenemos para ti</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($opciones as $o)
                    <a href="{{ $o['ruta'] }}"
                       class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl dark:border-slate-700 dark:bg-slate-800">
                        <span class="absolute inset-x-0 top-0 h-1.5 bg-linear-to-r {{ $o['acento'] }}"></span>
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br {{ $o['acento'] }} text-white shadow-md">
                            <x-icon :name="$o['icon']" class="h-7 w-7" />
                        </span>
                        <h3 class="mt-5 text-xl font-bold text-slate-900 dark:text-white">{{ $o['titulo'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $o['texto'] }}</p>
                        <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">
                            Conocer más
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.5 4.5 13 10l-5.5 5.5"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- CONTACTO --}}
        <section class="mt-10 mb-8 flex flex-col items-center justify-between gap-5 rounded-3xl border border-[#25D366]/30 bg-[#25D366]/10 p-7 text-center sm:flex-row sm:text-left" aria-label="Contacto del Área de Bienestar">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">¿Quieres participar o tienes dudas?</h2>
                <p class="mt-1 text-slate-600 dark:text-slate-300">Escríbenos por WhatsApp y el equipo de Bienestar te acompaña.</p>
            </div>
            <a href="https://wa.me/573225947308" target="_blank" rel="noopener"
               class="inline-flex shrink-0 items-center gap-2.5 rounded-2xl bg-[#25D366] px-6 py-3.5 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:brightness-110">
                <x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp Bienestar
            </a>
        </section>

    </div>
</div>
@endsection
