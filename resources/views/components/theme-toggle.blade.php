@props(['class' => ''])

{{-- Interruptor día/noche: cielo con nube y sol de día; cielo estrellado y luna de noche (estilos .theme-switch en app.css). --}}
<button type="button"
    onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
    aria-label="Cambiar entre modo claro y oscuro"
    title="Cambiar entre modo claro y oscuro"
    {{ $attributes->merge(['class' => "theme-switch shrink-0 $class"]) }}>
    <span class="theme-switch__knob">
        <x-icon name="sun" class="w-4 h-4 dark:hidden" />
        <x-icon name="moon" class="w-4 h-4 hidden dark:block" />
    </span>
</button>
