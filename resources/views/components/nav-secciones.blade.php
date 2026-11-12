{{-- Pastillas de navegación interna de una página: cada sección es [id del ancla, etiqueta]. --}}
@props(['secciones'])

<div class="flex flex-wrap justify-center gap-3 mb-14">
    @foreach ($secciones as [$ancla, $etiqueta])
        <a href="#{{ $ancla }}" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/15 text-slate-700 dark:text-slate-200 text-sm font-semibold shadow-sm hover:-translate-y-0.5 hover:border-transparent hover:bg-[#0B4870] hover:text-white dark:hover:bg-[#0C67A3] transition">{{ $etiqueta }}</a>
    @endforeach
</div>
