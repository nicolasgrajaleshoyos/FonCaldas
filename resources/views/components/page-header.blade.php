@props(['icon' => null, 'title', 'subtitle' => null, 'center' => false])

<div class="{{ $center ? 'text-center mb-12' : 'mb-10' }}">
    <h1 class="{{ $center ? 'text-3xl md:text-4xl justify-center' : 'text-3xl' }} font-bold text-slate-900 dark:text-white flex items-center gap-3">
        @if($icon)
            <span class="page-title-icon inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0">
                <x-icon :name="$icon" class="w-6 h-6" />
            </span>
        @endif
        <span class="page-title-text">{{ $title }}</span>
    </h1>

    @if($subtitle)
        <p class="text-slate-500 dark:text-slate-400 mt-3 text-[16px] max-w-2xl {{ $center ? 'mx-auto' : '' }}">{{ $subtitle }}</p>
    @endif
</div>
