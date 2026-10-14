@props(['size' => 'w-40 h-40', 'pose' => 'hola'])

<img src="{{ asset("images/mascota/$pose.png") }}" alt="Mascota Foncaldas" loading="lazy"
     {{ $attributes->merge(['class' => "$size mascot-float shrink-0 select-none pointer-events-none object-contain"]) }}>
