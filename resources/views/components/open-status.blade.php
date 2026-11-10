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
   :class="open ? 'bg-linear-to-b from-emerald-400 to-emerald-600 shadow-emerald-400/50' : 'bg-linear-to-b from-red-500 to-rose-700 shadow-red-500/50'"
   title="Ver horario de atención"
   aria-label="Ver horario de atención"
   {{ $attributes->merge(['class' => 'group inline-flex items-center gap-2 h-10 rounded-full pl-2.5 pr-3.5 text-[13px] font-semibold text-white whitespace-nowrap shadow-md ring-1 ring-white/30 transition hover:brightness-110']) }}>
    <span class="relative flex items-center justify-center w-6 h-6 rounded-full bg-white/25">
        <span class="absolute inline-flex h-full w-full rounded-full bg-white/60 animate-ping"></span>
        <span class="relative w-2.5 h-2.5 rounded-full bg-white"></span>
    </span>
    <span x-text="open ? 'Abierto' : 'Cerrado'" class="tracking-wide"></span>
    <x-icon name="clock" class="hidden 2xl:block w-4 h-4 transition group-hover:rotate-12" />
</a>
