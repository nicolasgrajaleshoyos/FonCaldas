@props(['size' => 'w-40 h-40', 'pose' => 'hola', 'loading' => 'lazy', 'sol' => false])

@if($sol)
    {{-- Con "sol": la mascota va sobre el sol del logo girando (encabezados de página). El margen/posición se aplica al contenedor. --}}
    <div {{ $attributes->merge(['class' => "relative $size shrink-0"]) }}>
        <span class="hero-sun hero-sun--pagina absolute -inset-14 [animation-duration:40s]" aria-hidden="true"></span>
        <span class="absolute inset-4 rounded-full bg-[#FACC15]/40 blur-2xl" aria-hidden="true"></span>
        <img src="{{ asset("images/mascota/$pose.webp") }}" alt="Mascota Foncaldas" width="360" height="360" loading="{{ $loading }}" decoding="async"
             class="relative h-full w-full mascot-float select-none pointer-events-none object-contain">
    </div>
@else
    <img src="{{ asset("images/mascota/$pose.webp") }}" alt="Mascota Foncaldas" width="360" height="360" loading="{{ $loading }}" decoding="async"
         {{ $attributes->merge(['class' => "$size mascot-float shrink-0 select-none pointer-events-none object-contain"]) }}>
@endif
