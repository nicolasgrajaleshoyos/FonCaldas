@props(['route', 'label' => '← Volver'])

<div class="mt-10">
    <a href="{{ $route }}"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-slate-300 text-slate-800 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700/40 transition">
        {{ $label }}
    </a>
</div>
