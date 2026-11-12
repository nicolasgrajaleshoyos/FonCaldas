{{-- Etiqueta de color para el estado de una solicitud o PQRS. $grande la usa en las pantallas de consulta del asociado. --}}
@props(['estado', 'etiqueta', 'grande' => false])

@php
    $tono = match ($estado) {
        'en_revision', 'en_tramite' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300',
        'aprobada', 'finalizada', 'respondida' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300',
        'rechazada' => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300',
        default => 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300',
    };
    $tamano = $grande ? 'px-5 py-2 text-lg font-bold gap-2.5' : 'px-3 py-1 text-xs font-semibold gap-1.5';
@endphp

{{-- El punto usa el color del texto (bg-current) para reforzar el estado sin depender solo del color de fondo. --}}
<span {{ $attributes->class(['inline-flex items-center whitespace-nowrap rounded-full', $tamano, $tono]) }}><span class="{{ $grande ? 'h-2.5 w-2.5' : 'h-1.5 w-1.5' }} rounded-full bg-current" aria-hidden="true"></span>{{ $etiqueta }}</span>
