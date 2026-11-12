{{-- Tarjeta de un comité u órgano: barra de color arriba, título y contenido libre. $borde es la clase completa de Tailwind del color. --}}
@props(['titulo', 'borde' => 'border-[#0C67A3]'])

<div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm transition duration-300 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800"]) }}>
    <span class="absolute inset-x-0 top-0 border-t-[6px] {{ $borde }}" aria-hidden="true"></span>

    <h3 class="hero-title mb-6 text-xl md:text-2xl text-slate-900 dark:text-white">
        {{ $titulo }}
    </h3>

    {{ $slot }}
</div>