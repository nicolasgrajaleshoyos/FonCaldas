@props(['class' => ''])

<button type="button"
    onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
    aria-label="Cambiar entre modo claro y oscuro"
    {{ $attributes->merge(['class' => "shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/10 text-white hover:bg-white/20 transition $class"]) }}>
    <x-icon name="sun" class="w-5 h-5 dark:hidden" />
    <x-icon name="moon" class="w-5 h-5 hidden dark:block" />
</button>
