@extends('layouts.app')

@section('title', 'PQRS | Foncaldas')

@section('content')
@php
    // Estilos compartidos del formulario
    $campo = 'w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-base text-slate-900 placeholder:text-slate-400 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:focus:ring-sky-400/20';
    $etiqueta = 'mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100';
    $error = 'mt-2 text-sm font-medium text-red-600 dark:text-red-400';
    $paso = 'inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md shadow-[#0C67A3]/30';

    // Ícono, explicación y color de cada tipo de PQRS (las claves vienen de Pqrs::TIPOS)
    $detalleTipos = [
        'peticion' => ['document', 'Solicitud de información o de un servicio.', 'bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white'],
        'queja' => ['bolt', 'Inconformidad con una persona o con el servicio.', 'bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870]'],
        'reclamo' => ['shield', 'Exigir el cumplimiento de un derecho.', 'bg-linear-to-br from-rose-500 to-red-600 text-white'],
        'sugerencia' => ['sparkle', 'Propuesta para que mejoremos.', 'bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white'],
    ];
@endphp
<div class="min-h-screen page-bg py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="formal" class="mb-6" sol />
            <x-page-header
                icon="clipboard"
                title="Peticiones, quejas, reclamos y sugerencias"
                subtitle="Cuéntanos tu caso. Te responderemos por correo y podrás consultar el estado con el código que recibas."
                center />
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)] lg:items-start">

            <!-- COLUMNA DE AYUDA: cómo funciona + consulta de estado (queda fija al bajar en pantallas grandes) -->
            <aside class="space-y-6 lg:sticky lg:top-28">

                <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-7 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15">
                    <span class="hero-sun absolute -right-28 -top-28 h-72 w-72 opacity-70" aria-hidden="true"></span>

                    <p class="relative text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Te escuchamos</p>
                    <h2 class="hero-title relative mt-1 text-2xl">¿Cómo funciona?</h2>

                    <ol class="relative mt-6 space-y-5">
                        @foreach ([
                            ['Cuéntanos tu caso', 'Elige el tipo de PQRS y descríbelo.'],
                            ['Recibe tu código', 'Te llega al correo que registres.'],
                            ['Te respondemos por correo', 'Y puedes consultar el estado con tu código.'],
                        ] as $i => [$titulo, $detalle])
                            <li class="flex gap-4">
                                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#FACC15] text-sm font-bold text-[#0B4870] shadow-md">{{ $i + 1 }}</span>
                                <div>
                                    <p class="font-semibold">{{ $titulo }}</p>
                                    <p class="mt-0.5 text-sm text-white/85">{{ $detalle }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="search" class="w-5 h-5" /></span>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">¿Ya radicaste una PQRS?</p>
                    </div>
                    <p class="mt-3 text-[15px] text-slate-600 dark:text-slate-300">Consulta en qué estado va con tu código y tu documento.</p>
                    <a href="{{ route('pqrs.consultar') }}" class="group mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15 dark:hover:text-white">
                        Consultar estado
                        <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>
                </div>

            </aside>

            <!-- FORMULARIO: dividido en pasos numerados -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9 dark:border-white/10 dark:bg-slate-800">
                <form method="POST" action="{{ route('pqrs.store') }}" data-cargando="Enviando tu PQRS…" class="space-y-9">
                    @csrf

                    <!-- PASO 1: el tipo se elige tocando una tarjeta, cada una explica qué significa -->
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">1</span> ¿Qué quieres radicar?
                        </legend>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($tipos as $key => $label)
                                @php [$icono, $explicacion, $ficha] = $detalleTipos[$key] ?? ['document', '', 'bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white']; @endphp
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tipo" value="{{ $key }}" required @checked(old('tipo') === $key) class="peer sr-only">
                                    <span class="flex h-full items-center gap-4 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:border-[#0C67A3]/50 peer-checked:border-[#0C67A3] peer-checked:bg-[#0C67A3]/10 peer-checked:shadow-lg peer-checked:shadow-[#0C67A3]/15 peer-focus-visible:ring-4 peer-focus-visible:ring-[#0C67A3]/30 dark:border-slate-600 dark:bg-slate-900 dark:hover:border-sky-400/60 dark:peer-checked:border-sky-400 dark:peer-checked:bg-sky-400/10">
                                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $ficha }} shadow-md"><x-icon :name="$icono" class="w-6 h-6" /></span>
                                        <span class="min-w-0">
                                            <span class="block text-lg font-bold text-slate-900 dark:text-white">{{ $label }}</span>
                                            <span class="block text-sm text-slate-600 dark:text-slate-300">{{ $explicacion }}</span>
                                        </span>
                                    </span>
                                    <!-- Chulo que aparece en la tarjeta elegida -->
                                    <span class="absolute right-3 top-3 hidden h-6 w-6 items-center justify-center rounded-full bg-[#0C67A3] text-white peer-checked:inline-flex dark:bg-sky-400 dark:text-slate-900"><x-icon name="check" class="w-3.5 h-3.5" /></span>
                                </label>
                            @endforeach
                        </div>
                        @error('tipo')<p class="{{ $error }}">{{ $message }}</p>@enderror
                    </fieldset>

                    <!-- PASO 2 -->
                    <fieldset class="border-t border-slate-200 pt-8 dark:border-white/10">
                        <legend class="float-left mb-5 flex w-full items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">2</span> Tus datos
                        </legend>
                        <div class="clear-both grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="f-nombre" class="{{ $etiqueta }}">Nombre completo</label>
                                <input type="text" id="f-nombre" name="nombre" value="{{ old('nombre') }}" required autocomplete="name" placeholder="Como aparece en tu cédula" class="{{ $campo }}">
                                @error('nombre')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-documento" class="{{ $etiqueta }}">Número de documento</label>
                                <input type="text" id="f-documento" name="documento" value="{{ old('documento') }}" required inputmode="numeric" placeholder="Sin puntos ni espacios" class="{{ $campo }}">
                                @error('documento')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-email" class="{{ $etiqueta }}">Correo electrónico</label>
                                <input type="email" id="f-email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="tucorreo@ejemplo.com" class="{{ $campo }}">
                                @error('email')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-telefono" class="{{ $etiqueta }}">Teléfono <span class="font-normal text-slate-500 dark:text-slate-400">(opcional)</span></label>
                                <input type="text" id="f-telefono" name="telefono" value="{{ old('telefono') }}" inputmode="tel" autocomplete="tel" class="{{ $campo }}">
                                @error('telefono')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <!-- PASO 3 -->
                    <fieldset class="border-t border-slate-200 pt-8 dark:border-white/10">
                        <legend class="float-left mb-5 flex w-full items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">3</span> Cuéntanos tu caso
                        </legend>
                        <div class="clear-both space-y-5">
                            <div>
                                <label for="f-asunto" class="{{ $etiqueta }}">Asunto</label>
                                <input type="text" id="f-asunto" name="asunto" value="{{ old('asunto') }}" required maxlength="255" placeholder="Resume tu caso en una frase" class="{{ $campo }}">
                                @error('asunto')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-descripcion" class="{{ $etiqueta }}">Describe tu caso</label>
                                <textarea id="f-descripcion" name="descripcion" rows="6" required maxlength="5000" placeholder="Cuéntanos qué pasó, con el mayor detalle posible" class="{{ $campo }}">{{ old('descripcion') }}</textarea>
                                @error('descripcion')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <div>
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-[15px] text-slate-700 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200">
                            <input type="checkbox" name="acepta_datos" value="1" required @checked(old('acepta_datos')) class="mt-0.5 h-5 w-5 shrink-0 rounded border-slate-300 accent-[#0C67A3]">
                            <span>Autorizo el tratamiento de mis datos personales para gestionar esta solicitud, según los <a href="{{ route('terminos') }}" target="_blank" rel="noopener" class="text-[#0C67A3] font-semibold hover:underline dark:text-sky-300">términos y condiciones</a>.</span>
                        </label>
                        @error('acepta_datos')<p class="{{ $error }}">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="group inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#0B4870] py-2.5 pl-8 pr-2.5 text-lg font-semibold text-white shadow-lg shadow-[#0B4870]/30 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                        Radicar PQRS
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                            <x-icon name="arrow-right" class="w-5 h-5" />
                        </span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
