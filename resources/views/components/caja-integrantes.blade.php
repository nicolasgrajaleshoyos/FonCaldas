{{-- Tarjeta gris con una lista de integrantes y, opcionalmente, un subtítulo. --}}
@props(['nombres', 'titulo' => null])

<div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
    @if ($titulo)
        <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">{{ $titulo }}</h4>
    @endif
    <x-lista-nombres :nombres="$nombres" />
</div>
