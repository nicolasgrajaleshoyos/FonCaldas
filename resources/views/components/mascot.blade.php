@props(['size' => 'w-40 h-40', 'pose' => 'hola', 'loading' => 'lazy'])

<img src="{{ asset("images/mascota/$pose.webp") }}" alt="Mascota Foncaldas" width="360" height="360" loading="{{ $loading }}" decoding="async"
     {{ $attributes->merge(['class' => "$size mascot-float shrink-0 select-none pointer-events-none object-contain"]) }}>
