@extends('layouts.app')

@section('content')
@php
    $campo = 'w-full rounded-2xl border border-slate-300 bg-slate-50 py-3.5 pl-12 pr-4 text-base text-slate-900 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:focus:ring-sky-400/20';
    $etiqueta = 'mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100';
    $error = 'mt-2 text-sm font-medium text-red-600 dark:text-red-400';
@endphp
<div class="min-h-screen page-bg flex items-center px-4 py-14 sm:px-6">

    <div class="hero-in mx-auto grid w-full max-w-4xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-[#0B4870]/20 md:grid-cols-2 dark:border-white/10 dark:bg-slate-800">

        <!-- PANEL DE MARCA: mascota sobre el sol del logo -->
        <div class="relative flex flex-col items-center justify-center overflow-hidden bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] px-8 py-10 text-center text-white md:py-14">
            <span class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-[#FACC15]/20 blur-3xl" aria-hidden="true"></span>

            <div class="relative h-36 w-36 md:h-48 md:w-48">
                <span class="hero-sun absolute -inset-16 [animation-duration:40s]" aria-hidden="true"></span>
                <span class="absolute inset-4 rounded-full bg-[#FACC15]/40 blur-2xl" aria-hidden="true"></span>
                <x-mascot pose="pc" loading="eager" size="w-full h-full" class="relative" />
            </div>

            <p class="relative mt-6 text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Solo personal autorizado</p>
            <h1 class="hero-title relative mt-1 text-2xl md:text-3xl">Panel de administración</h1>
            <p class="relative mt-3 max-w-xs text-[15px] leading-relaxed text-white/85">
                Gestiona trámites, PQRS, eventos y documentos de Foncaldas.
            </p>
        </div>

        <!-- FORMULARIO -->
        <div class="p-7 sm:p-10 md:py-14">
            <div class="flex items-center gap-4">
                <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30">
                    <x-icon name="lock" class="w-7 h-7" />
                </span>
                <div>
                    <h2 class="hero-title text-2xl text-slate-900 dark:text-white">Iniciar sesión</h2>
                    <p class="text-[15px] text-slate-600 dark:text-slate-300">Ingresa con tu usuario y contraseña.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label class="{{ $etiqueta }}" for="username">Usuario</label>
                    <div class="relative">
                        <x-icon name="user" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                        <input id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" autofocus required class="{{ $campo }}">
                    </div>
                    @error('username')
                        <p class="{{ $error }}">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ ver: false }">
                    <label class="{{ $etiqueta }}" for="password">Contraseña</label>
                    <div class="relative">
                        <x-icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                        <input id="password" name="password" :type="ver ? 'text' : 'password'" type="password" autocomplete="current-password" required class="{{ $campo }} !pr-12">
                        <!-- Mostrar/ocultar la contraseña -->
                        <button type="button" @click="ver = !ver" :aria-pressed="ver" aria-label="Mostrar u ocultar la contraseña"
                                class="absolute right-2 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-200 hover:text-[#0B4870] dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white"
                                :class="ver ? '!text-[#0C67A3] dark:!text-sky-300' : ''">
                            <x-icon name="eye" class="w-5 h-5" />
                        </button>
                    </div>
                    @error('password')
                        <p class="{{ $error }}">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="group !mt-8 inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#0B4870] py-2.5 pl-8 pr-2.5 text-lg font-semibold text-white shadow-lg shadow-[#0B4870]/30 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                    Ingresar
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1">
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </span>
                </button>
            </form>

            <a href="{{ url('/') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-[#0B4870] dark:text-slate-300 dark:hover:text-white">
                Volver al sitio
            </a>
        </div>

    </div>

</div>
@endsection
