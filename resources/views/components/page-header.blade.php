@props(['icon' => null, 'title', 'subtitle' => null, 'center' => false])

{{-- Encabezado de página: ficha de ícono con degradado de marca, título en Sora y una barra con los tres colores del logo. --}}
<div class="{{ $center ? 'text-center mb-12' : 'mb-10' }}">
    <h1 class="{{ $center ? 'text-3xl md:text-[2.75rem] md:leading-tight justify-center flex-col text-balance' : 'text-3xl md:text-4xl' }} font-bold text-slate-900 dark:text-white flex items-center gap-3 md:gap-4">
        @if($icon)
            <span class="page-title-icon inline-flex h-12 w-12 md:h-14 md:w-14 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30 ring-1 ring-white/20 shrink-0">
                <x-icon :name="$icon" class="w-6 h-6 md:w-7 md:h-7" />
            </span>
        @endif
        <span class="page-title-text">{{ $title }}</span>
    </h1>

    <span class="mt-5 block h-1.5 w-20 rounded-full bg-linear-to-r from-[#FACC15] via-[#0B8B7B] to-[#0C67A3] {{ $center ? 'mx-auto' : '' }}" aria-hidden="true"></span>

    @if($subtitle)
        <p class="text-slate-600 dark:text-slate-300 mt-5 text-base md:text-lg leading-relaxed max-w-2xl {{ $center ? 'mx-auto' : '' }}">{{ $subtitle }}</p>
    @endif
</div>
