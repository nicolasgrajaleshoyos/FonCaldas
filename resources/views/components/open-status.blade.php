{{-- Abierto/Cerrado según el horario de atención (Bogotá). Se recalcula cada 30 s. Enlaza al horario en Contacto. --}}
<a href="{{ route('contacto') }}#horario"
   x-data="{
        tick: Date.now(),
        init() { setInterval(() => this.tick = Date.now(), 30000); },
        get open() {
            const parts = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Bogota', weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: false }).formatToParts(new Date(this.tick));
            const m = {};
            parts.forEach((p) => m[p.type] = p.value);
            const mins = (parseInt(m.hour, 10) % 24) * 60 + parseInt(m.minute, 10);
            return m.weekday !== 'Sat' && m.weekday !== 'Sun' && ((mins >= 480 && mins < 720) || (mins >= 780 && mins < 960));
        }
   }"
   :class="open ? 'bg-emerald-500 hover:bg-emerald-400 ring-emerald-200/60' : 'bg-red-500 hover:bg-red-400 ring-red-200/60'"
   title="Ver horario de atención"
   aria-label="Ver horario de atención"
   {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 h-11 rounded-full px-5 text-sm font-bold text-white whitespace-nowrap shadow-md ring-2 transition hover:scale-105']) }}>
    <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
    <span x-text="open ? 'Abierto' : 'Cerrado'"></span>
    <x-icon name="clock" class="w-4 h-4" />
</a>
