@props(['center' => false, 'light' => false, 'as' => 'h2'])

@php
$classes = 'section-title'
    . ($center ? ' section-title--center' : '')
    . ($light ? ' section-title--light' : '')
    . ' text-2xl md:text-3xl font-bold tracking-tight '
    . ($light ? 'text-white' : 'text-[#0F172A] dark:text-white');
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</{{ $as }}>
