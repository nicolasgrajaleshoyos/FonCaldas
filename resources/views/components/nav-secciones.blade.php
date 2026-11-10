{{-- Pastillas de navegación interna de una página: cada sección es [id del ancla, etiqueta]. --}}
@props(['secciones'])

<div class="flex flex-wrap justify-center gap-3 mb-14">
    @foreach ($secciones as [$ancla, $etiqueta])
        <a href="#{{ $ancla }}" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">{{ $etiqueta }}</a>
    @endforeach
</div>
