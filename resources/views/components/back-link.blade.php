@props(['route', 'label' => 'Volver'])

<div class="mb-6">
    <a href="{{ $route }}"
       class="group inline-flex items-center gap-2.5 rounded-full border border-[#0C67A3]/40 bg-[#0C67A3]/10 px-5 py-2.5 text-sm font-semibold text-[#0C67A3] transition hover:border-[#0C67A3] hover:bg-[#0C67A3] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0C67A3] dark:border-[#5EB3E4]/40 dark:bg-[#5EB3E4]/10 dark:text-[#5EB3E4] dark:hover:border-[#5EB3E4] dark:hover:bg-[#5EB3E4] dark:hover:text-slate-900">
        <svg class="h-4 w-4 transition group-hover:-translate-x-0.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12.5 4.5 7 10l5.5 5.5"/></svg>
        {{ $label }}
    </a>
</div>
