@extends('layouts.app')

@section('title', 'Convenios | Foncaldas')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-5xl mx-auto px-6">

        <x-page-header
            icon="briefcase"
            title="Convenios"
            subtitle="Beneficios y descuentos exclusivos para asociados y beneficiarios de Foncaldas con nuestros aliados en salud, seguros, educación, viajes y más." />

        <!-- ACCIONES -->
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <a href="{{ asset('pdf/CONVENIOS.pdf') }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg bg-[#0B4870] px-5 py-3 text-white font-semibold hover:bg-[#0a3d60] transition">
                <x-icon name="document" class="w-4 h-4" />
                Descargar PDF
            </a>
            <a href="https://wa.me/573225947308" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-3 text-slate-800 dark:text-slate-100 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                <x-icon name="whatsapp" class="w-4 h-4 text-green-600" />
                ¿Tienes un convenio para proponer? Escríbenos
            </a>
        </div>

        <!-- PÁGINAS DEL CATÁLOGO -->
        <div class="space-y-6">
            @foreach (range(1, 5) as $page)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/convenios/pagina-' . $page . '.png') }}"
                         alt="Convenios Foncaldas — página {{ $page }}"
                         loading="{{ $page === 1 ? 'eager' : 'lazy' }}"
                         class="w-full h-auto">
                </div>
            @endforeach
        </div>

        <p class="text-slate-400 text-xs text-center mt-8">
            Información sujeta a cambios por parte de cada aliado. Última actualización: 15 de mayo de 2026.
        </p>

    </div>

</div>

@endsection
