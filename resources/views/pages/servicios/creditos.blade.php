@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="card"
            title="Opciones de financiamiento"
            subtitle="Explora nuestras líneas de crédito y elige la opción que mejor se adapte a ti." />

        <!-- GRID TARJETAS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- TARJETA 1 -->
    <a href="{{ route('creditos.expres') }}"
        class="group bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition">

        <!-- IMAGEN (más alta) -->
        <div class="h-[320px] overflow-hidden">
            <img src="{{ asset('images/credexpress.png') }}"
                loading="lazy"
                    class="w-full h-full object-contain bg-white dark:bg-slate-800 group-hover:scale-105 transition duration-500"
                    alt="Crédito Expres">
        </div>

        <!-- TEXTO ABAJO -->
        <div class="p-6">
            <div class="mb-4 inline-flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="bolt" class="w-5 h-5" /></span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Crédito Expres</h2>
            </div>

            <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                Aprobación rápida para necesidades inmediatas.
            </p>
        </div>

    </a>

    <!-- TARJETA 2 -->
    <a href="{{ route('creditos.lineas') }}"
        class="group bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition">

        <div class="h-[320px] overflow-hidden">
            <img src="{{ asset('images/lineascred.png') }}"
                    loading="lazy"
                    class="w-full h-full object-contain bg-white dark:bg-slate-800 group-hover:scale-105 transition duration-500"
                    alt="Líneas de crédito">
        </div>

        <div class="p-6">
            <div class="mb-4 inline-flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="bank" class="w-5 h-5" /></span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Líneas de Crédito</h2>
            </div>

            <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                Diferentes opciones de financiación según tu perfil.
            </p>
        </div>

    </a>

    <!-- TARJETA 3 -->
    <a href="{{ asset('pdf/ReglamentoDeCredito.pdf') }}" target="_blank" rel="noopener"
            class="group bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition">

        <div class="h-[320px] overflow-hidden">
            <img src="{{ asset('images/reglcred.png') }}"
                    loading="lazy"
                    class="w-full h-full object-contain bg-white dark:bg-slate-800 group-hover:scale-105 transition duration-500"
            alt="Reglamento de crédito">
        </div>

        <div class="p-6">
            <div class="mb-4 inline-flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="document" class="w-5 h-5" /></span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Reglamento de Crédito</h2>
            </div>

            <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                Normas y condiciones para acceder a créditos (PDF).
            </p>
        </div>

    </a>

</div>
<!--Boton Simulador-->
<div class="mt-8 text-center">
    <a href="{{ route('simuladores.credito') }}"
        class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#0C67A3] text-white font-semibold hover:bg-[#09598d] transition">
        Simular crédito
    </a>
</div>

        <x-back-link :route="route('servicios')" label="← Volver a servicios" />

    </div>

</div>

@endsection
