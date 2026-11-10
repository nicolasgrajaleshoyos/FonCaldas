{{-- Bloque de un comité u órgano: título con borde de color y contenido libre. $borde es la clase completa de Tailwind. --}}
@props(['titulo', 'borde' => 'border-[#0C67A3]'])

<div>
    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 {{ $borde }}">
        {{ $titulo }}
    </h3>

    {{ $slot }}
</div>
