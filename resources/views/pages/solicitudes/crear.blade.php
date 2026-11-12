@extends('layouts.app')

@section('title', 'Radicar un trámite | Foncaldas')

@section('content')
@php
    // Estilos compartidos del formulario
    $campo = 'w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-base text-slate-900 placeholder:text-slate-400 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:focus:ring-sky-400/20';
    $etiqueta = 'mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100';
    $ayuda = 'mt-2 text-sm text-slate-500 dark:text-slate-300';
    $error = 'mt-2 text-sm font-medium text-red-600 dark:text-red-400';
    $paso = 'inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md shadow-[#0C67A3]/30';
@endphp
<div class="min-h-screen page-bg py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="tramite" class="mb-6" sol />
            <x-page-header
                icon="clipboard"
                title="Radica tu trámite o solicitud"
                subtitle="No necesitas usuario ni contraseña. Solo ingresa tus datos y el equipo de FONCALDAS verificará tu identidad."
                center />
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)] lg:items-start">

            <!-- COLUMNA DE AYUDA: cómo funciona + consulta de estado (queda fija al bajar en pantallas grandes) -->
            <aside class="space-y-6 lg:sticky lg:top-28">

                <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-7 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15">
                    <span class="hero-sun absolute -right-28 -top-28 h-72 w-72 opacity-70" aria-hidden="true"></span>

                    <p class="relative text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Así de fácil</p>
                    <h2 class="hero-title relative mt-1 text-2xl">¿Cómo funciona?</h2>

                    <ol class="relative mt-6 space-y-5">
                        @foreach ([
                            ['Llena el formulario', 'Elige el trámite y cuéntanos qué necesitas.'],
                            ['Recibe tu código', 'Te lo enviamos al correo que registres.'],
                            ['Consulta el estado', 'Con tu código y tu documento, cuando quieras.'],
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
                        <p class="text-lg font-bold text-slate-900 dark:text-white">¿Ya radicaste una solicitud?</p>
                    </div>
                    <p class="mt-3 text-[15px] text-slate-600 dark:text-slate-300">Consulta en qué estado va con tu código y tu documento.</p>
                    <a href="{{ route('solicitudes.consultar') }}" class="group mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15 dark:hover:text-white">
                        Consultar estado
                        <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>
                </div>

            </aside>

            <!-- FORMULARIO: dividido en pasos numerados para que sea fácil de seguir -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9 dark:border-white/10 dark:bg-slate-800">
                <form method="POST" action="{{ route('solicitudes.store') }}" data-cargando="Radicando tu solicitud…" enctype="multipart/form-data" class="space-y-9">
                    @csrf

                    <!-- PASO 1 -->
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">1</span> ¿Qué trámite necesitas?
                        </legend>
                        <label for="f-tramite-tipo-id" class="{{ $etiqueta }}">Tipo de trámite</label>
                        <select id="f-tramite-tipo-id" name="tramite_tipo_id" required class="{{ $campo }}">
                            <option value="">Selecciona una opción</option>
                            @foreach($tramiteTipos as $tipo)
                                <option value="{{ $tipo->id }}" @selected(old('tramite_tipo_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tramite_tipo_id')<p class="{{ $error }}">{{ $message }}</p>@enderror
                    </fieldset>

                    <!-- PASO 2 -->
                    <fieldset class="border-t border-slate-200 pt-8 dark:border-white/10">
                        <legend class="float-left mb-5 flex w-full items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">2</span> Tus datos
                        </legend>
                        <div class="clear-both grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="f-asociado-nombre" class="{{ $etiqueta }}">Nombre completo</label>
                                <input type="text" id="f-asociado-nombre" name="asociado_nombre" value="{{ old('asociado_nombre') }}" required autocomplete="name" placeholder="Como aparece en tu cédula" class="{{ $campo }}">
                                @error('asociado_nombre')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-asociado-documento" class="{{ $etiqueta }}">Número de documento</label>
                                <input type="text" id="f-asociado-documento" name="asociado_documento" value="{{ old('asociado_documento') }}" required inputmode="numeric" placeholder="Sin puntos ni espacios" class="{{ $campo }}">
                                @error('asociado_documento')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-asociado-email" class="{{ $etiqueta }}">Correo electrónico</label>
                                <input type="email" id="f-asociado-email" name="asociado_email" value="{{ old('asociado_email') }}" required autocomplete="email" placeholder="tucorreo@ejemplo.com" class="{{ $campo }}">
                                <p class="{{ $ayuda }}">Aquí te enviaremos el código para consultar tu solicitud.</p>
                                @error('asociado_email')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="f-asociado-telefono" class="{{ $etiqueta }}">Teléfono <span class="font-normal text-slate-500 dark:text-slate-400">(opcional)</span></label>
                                <input type="text" id="f-asociado-telefono" name="asociado_telefono" value="{{ old('asociado_telefono') }}" inputmode="tel" autocomplete="tel" class="{{ $campo }}">
                                @error('asociado_telefono')<p class="{{ $error }}">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <!-- PASO 3 -->
                    <fieldset class="border-t border-slate-200 pt-8 dark:border-white/10">
                        <legend class="float-left mb-5 flex w-full items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="{{ $paso }}">3</span> Cuéntanos qué necesitas
                        </legend>
                        <div class="clear-both">
                            <label for="f-descripcion" class="sr-only">Cuéntanos qué necesitas</label>
                            <textarea id="f-descripcion" name="descripcion" rows="5" required placeholder="Describe tu solicitud con el mayor detalle posible" class="{{ $campo }}">{{ old('descripcion') }}</textarea>
                            @error('descripcion')<p class="{{ $error }}">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-6">
                            <label for="f-documentos" class="{{ $etiqueta }}">Documentos de soporte <span class="font-normal text-slate-500 dark:text-slate-400">(opcional)</span></label>
                            <div class="flex flex-col gap-4 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 transition hover:border-[#0C67A3] sm:flex-row sm:items-center dark:border-slate-600 dark:bg-slate-900 dark:hover:border-sky-400">
                                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="upload" class="w-6 h-6" /></span>
                                <div class="min-w-0 flex-1">
                                    <input type="file" id="f-documentos" name="documentos[]" multiple class="w-full text-sm text-slate-700 dark:text-slate-200 file:mr-3 file:cursor-pointer file:rounded-full file:border-0 file:bg-[#0B4870] file:px-5 file:py-2.5 file:font-semibold file:text-white hover:file:bg-[#0a3d60] dark:file:bg-[#0C67A3]">
                                    <p class="{{ $ayuda }}">Hasta 10 MB por archivo. Formatos: PDF, JPG, PNG, Word y Excel.</p>
                                </div>
                            </div>
                            @error('documentos.*')<p class="{{ $error }}">{{ $message }}</p>@enderror
                        </div>
                    </fieldset>

                    <button type="submit" class="group inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#0B4870] py-2.5 pl-8 pr-2.5 text-lg font-semibold text-white shadow-lg shadow-[#0B4870]/30 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                        Radicar solicitud
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
