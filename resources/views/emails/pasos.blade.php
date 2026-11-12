{{-- Barra de progreso para correos: $pasos = [nombre, emoji]; $actual = número del paso (1..n); $colorFinal para el último paso cuando ya terminó. --}}
@php $colorFinal = $colorFinal ?? '#10B981'; @endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 22px; background:#F1F6FB; border-radius:16px;">
    <tr>
        @foreach($pasos as $i => [$nombre, $emoji])
            @php
                $n = $i + 1;
                $hecho = $n < $actual;
                $esActual = $n === $actual;
                $fondo = $esActual ? ($n === count($pasos) ? $colorFinal : '#FACC15') : ($hecho ? '#0B8B7B' : '#CBD5E1');
            @endphp
            <td align="center" valign="top" width="{{ floor(100 / count($pasos)) }}%" style="padding:18px 4px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                    <td align="center" valign="middle" width="42" height="42" style="width:42px; height:42px; border-radius:999px; background:{{ $fondo }}; font-size:20px; line-height:42px; color:#FFFFFF; font-weight:800;">{{ $hecho ? '✓' : $emoji }}</td>
                </tr></table>
                <div style="margin-top:8px; font-size:13px; font-weight:700; color:{{ $n <= $actual ? '#0F172A' : '#94A3B8' }};">{{ $nombre }}</div>
                <div style="font-size:11px; font-weight:700; color:{{ $esActual ? '#B45309' : '#94A3B8' }};">{{ $esActual ? '● Aquí vas' : ($hecho ? 'Listo' : 'Pendiente') }}</div>
            </td>
        @endforeach
    </tr>
</table>
