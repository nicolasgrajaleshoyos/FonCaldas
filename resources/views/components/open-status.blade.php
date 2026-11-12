{{-- Abierto/Cerrado según el horario de atención (Bogotá, lun-vie 8-12 y 1-4; no contempla festivos).
     Lleva un relojito con la hora real de Bogotá y dice hasta cuándo atienden o cuándo vuelven a abrir.
     Se recalcula cada 30 s y enlaza al horario en Contacto. --}}
<a href="{{ route('contacto') }}#horario"
   x-data="{
        tick: Date.now(),
        init() { setInterval(() => this.tick = Date.now(), 30000); },
        get ahora() {
            const parts = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Bogota', weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: false }).formatToParts(new Date(this.tick));
            const m = {};
            parts.forEach((p) => m[p.type] = p.value);
            return {
                mins: (parseInt(m.hour, 10) % 24) * 60 + parseInt(m.minute, 10),
                dia: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].indexOf(m.weekday),
            };
        },
        get estado() {
            const { mins, dia } = this.ahora;
            const habil = dia >= 1 && dia <= 5;
            if (habil && mins >= 480 && mins < 720) return { open: true, detalle: 'hasta 12:00 p. m.' };
            if (habil && mins >= 780 && mins < 960) return { open: true, detalle: 'hasta 4:00 p. m.' };
            if (habil && mins < 480) return { open: false, detalle: 'abre 8:00 a. m.' };
            if (habil && mins < 780) return { open: false, detalle: 'abre 1:00 p. m.' };
            return { open: false, detalle: (dia === 5 || dia === 6) ? 'abre lunes 8 a. m.' : 'abre mañana 8 a. m.' };
        },
        get texto() { return (this.estado.open ? 'Abierto' : 'Cerrado') + ' · ' + this.estado.detalle; }
   }"
   :class="estado.open ? 'bg-linear-to-br from-emerald-400 via-emerald-500 to-teal-600 shadow-emerald-400/50' : 'bg-linear-to-br from-rose-500 via-red-500 to-rose-700 shadow-red-500/50'"
   :title="texto + ' — ver horario de atención'"
   :aria-label="texto + '. Ver horario de atención'"
   {{ $attributes->merge(['class' => 'status-pill group relative overflow-hidden inline-flex items-center gap-2 h-10 rounded-full pl-1.5 pr-3 2xl:pr-4 text-white whitespace-nowrap shadow-lg ring-1 ring-white/40 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl']) }}>
    <!-- Reloj con la hora real de Bogotá; el anillo late mientras está abierto -->
    <span class="relative flex h-7 w-7 shrink-0 items-center justify-center">
        <span x-show="estado.open" class="absolute inset-0 rounded-full bg-white/70 animate-ping"></span>
        <svg viewBox="0 0 24 24" class="relative h-7 w-7 drop-shadow transition-transform duration-500 group-hover:rotate-[360deg]" aria-hidden="true">
            <circle cx="12" cy="12" r="11" fill="#fff"/>
            <path d="M12 3v1.5M21 12h-1.5M12 21v-1.5M3 12h1.5" stroke="#94A3B8" stroke-width="1.2" stroke-linecap="round"/>
            <line x1="12" y1="12" x2="12" y2="7" stroke="#0B4870" stroke-width="2.2" stroke-linecap="round" :transform="`rotate(${(ahora.mins % 720) / 2} 12 12)`"/>
            <line x1="12" y1="12" x2="12" y2="4.8" stroke="#0B4870" stroke-width="1.5" stroke-linecap="round" :transform="`rotate(${(ahora.mins % 60) * 6} 12 12)`"/>
            <circle cx="12" cy="12" r="1.6" :fill="estado.open ? '#10B981' : '#EF4444'"/>
        </svg>
    </span>
    <span class="relative max-[400px]:hidden flex flex-col leading-none">
        <span x-text="estado.open ? 'Abierto' : 'Cerrado'" class="text-[13px] font-bold tracking-wide"></span>
        <span x-text="estado.detalle" class="hidden min-[1400px]:block mt-1 text-[10.5px] font-medium text-white/90"></span>
    </span>
</a>
