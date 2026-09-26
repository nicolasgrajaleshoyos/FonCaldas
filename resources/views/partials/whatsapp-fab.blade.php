@php
$waChannels = [
    ['label' => 'Créditos', 'phone' => '573148330328', 'display' => '314 833 0328', 'icon' => 'card'],
    ['label' => 'Afiliaciones', 'phone' => '573245048815', 'display' => '324 504 8815', 'icon' => 'users'],
    ['label' => 'Bienestar', 'phone' => '573225947308', 'display' => '322 594 7308', 'icon' => 'heart'],
];
@endphp

<div x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false" class="fixed bottom-5 right-5 z-40">

    <div id="wa-fab-panel" x-show="open" x-cloak x-transition.origin.bottom.right
        class="mb-3 w-72 rounded-2xl bg-white dark:bg-slate-800 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden absolute bottom-full right-0">
        <div class="px-5 py-4 bg-[#0B4870] text-white">
            <p class="font-semibold text-sm">¿En qué te podemos ayudar?</p>
            <p class="text-white/70 text-xs mt-0.5">Elige un área y te atendemos por WhatsApp</p>
        </div>

        @foreach($waChannels as $ch)
            <a href="https://wa.me/{{ $ch['phone'] }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-600">
                    <x-icon name="{{ $ch['icon'] }}" class="w-4 h-4" />
                </span>
                <span>
                    <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">{{ $ch['label'] }}</span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $ch['display'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="relative">
        <span x-show="!open" class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-75 pointer-events-none"></span>

        <button type="button" @click="open = !open" :aria-expanded="open" aria-controls="wa-fab-panel" aria-label="Contactar por WhatsApp"
            class="relative w-14 h-14 rounded-full bg-green-600 text-white shadow-xl flex items-center justify-center hover:bg-green-700 hover:scale-110 transition">
            <x-icon name="whatsapp" class="w-6 h-6" />
        </button>
    </div>

</div>
