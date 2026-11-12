{{-- Pantalla de consulta (trámite o PQRS): a la izquierda dónde encontrar el código; a la derecha el formulario.
     $campoDocumento es el nombre del campo de cédula que espera cada controlador. --}}
@props(['accion', 'mensajeCarga', 'prefijo', 'campoDocumento', 'que', 'nuevaRuta', 'nuevaTexto'])

@php
    $campo = 'w-full rounded-2xl border border-slate-300 bg-slate-50 py-3.5 pl-12 pr-4 text-base text-slate-900 placeholder:text-slate-400 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-sky-400 dark:focus:bg-slate-900';
@endphp

<div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.3fr)] lg:items-start">

    <!-- AYUDA -->
    <aside class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-7 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15">
        <span class="hero-sun absolute -right-28 -top-28 h-72 w-72 opacity-60" aria-hidden="true"></span>
        <p class="relative text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Necesitas dos datos</p>
        <h2 class="hero-title relative mt-1 text-2xl">¿Dónde encuentro mi código?</h2>

        <ul class="relative mt-6 space-y-5">
            <li class="flex gap-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] shadow-md"><x-icon name="ticket" class="w-5 h-5" /></span>
                <div>
                    <p class="font-semibold">Tu código</p>
                    <p class="mt-0.5 text-sm text-white/85">Aparece al radicar y te llegó por correo. Se ve así: <span class="rounded-md bg-white/15 px-1.5 py-0.5 font-mono">{{ $prefijo }}-26-XXXXXX</span></p>
                </div>
            </li>
            <li class="flex gap-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] shadow-md"><x-icon name="user" class="w-5 h-5" /></span>
                <div>
                    <p class="font-semibold">Tu número de documento</p>
                    <p class="mt-0.5 text-sm text-white/85">El mismo que escribiste al radicar {{ $que }}.</p>
                </div>
            </li>
        </ul>

        <p class="relative mt-7 flex items-center gap-2 rounded-2xl bg-white/10 px-4 py-3 text-sm ring-1 ring-white/20">
            <x-icon name="lock" class="w-4 h-4 shrink-0 text-[#FACC15]" /> Solo tú puedes ver {{ $que }}: hacen falta los dos datos.
        </p>
    </aside>

    <!-- FORMULARIO -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9 dark:border-white/10 dark:bg-slate-800">
        <div class="flex items-center gap-4">
            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="search" class="w-7 h-7" /></span>
            <div>
                <h2 class="hero-title text-2xl text-slate-900 dark:text-white">Consultar</h2>
                <p class="text-[15px] text-slate-600 dark:text-slate-300">Escribe tus datos y mira cómo va.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-[15px] text-rose-700 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-300">
                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-500 font-bold text-white">!</span>
                <div>@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ $accion }}" data-cargando="{{ $mensajeCarga }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="f-codigo" class="mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100">Código</label>
                <div class="relative">
                    <x-icon name="ticket" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                    <input type="text" id="f-codigo" name="codigo" value="{{ old('codigo') }}" required autocomplete="off" placeholder="{{ $prefijo }}-26-XXXXXX" class="{{ $campo }} font-mono uppercase tracking-wider">
                </div>
            </div>
            <div>
                <label for="f-documento" class="mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100">Número de documento</label>
                <div class="relative">
                    <x-icon name="user" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                    <input type="text" id="f-documento" name="{{ $campoDocumento }}" value="{{ old($campoDocumento) }}" required inputmode="numeric" placeholder="Sin puntos ni espacios" class="{{ $campo }}">
                </div>
            </div>
            <button type="submit" class="group !mt-8 inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#0B4870] py-2.5 pl-8 pr-2.5 text-lg font-semibold text-white shadow-lg shadow-[#0B4870]/30 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                Consultar
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform duration-300 group-hover:translate-x-1"><x-icon name="arrow-right" class="w-5 h-5" /></span>
            </button>
        </form>

        <a href="{{ $nuevaRuta }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-[#0B4870] dark:text-slate-300 dark:hover:text-white">
            ¿Aún no has radicado? {{ $nuevaTexto }} →
        </a>
    </div>
</div>
