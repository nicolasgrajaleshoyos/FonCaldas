@props(['size' => 'w-40 h-40'])

<img src="{{ asset('images/pajaro.png') }}" alt="Mascota Foncaldas"
     {{ $attributes->merge(['class' => "$size mascot-float shrink-0 select-none pointer-events-none"]) }}>
