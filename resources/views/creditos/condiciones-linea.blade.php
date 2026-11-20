@php
    $tasa = $parametros?->get('tasa_interes');
    $plazo = $parametros?->get('plazo_maximo')?->valor;
@endphp

@if ($tasa?->valor !== null || $plazo !== null)
    <ul class="mt-4 space-y-1 text-sm text-slate-600 dark:text-slate-400">
        @if ($tasa?->valor !== null)
            <li>
                Tasa de referencia del {{ rtrim(rtrim(number_format((float) $tasa->valor, 6, ',', '.'), '0'), ',') }}
                {{ $tasa->unidad ?? '% mensual' }}
            </li>
        @endif
        @if ($plazo !== null)
            <li>Plazo máximo de {{ (int) $plazo }} {{ (int) $plazo === 1 ? 'mes' : 'meses' }}</li>
        @endif
    </ul>
@endif
