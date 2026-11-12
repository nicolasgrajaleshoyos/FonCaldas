{{-- Lista de integrantes con un subtítulo opcional (Principales, Suplentes…). --}}
@props(['nombres', 'titulo' => null])

<div>
    @if ($titulo)
        <h4 class="mb-3 inline-flex rounded-full bg-[#0C67A3]/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300">{{ $titulo }}</h4>
    @endif
    <x-lista-nombres :nombres="$nombres" />
</div>