{{-- Lista de nombres (integrantes de comités y órganos de dirección): cada uno con su inicial en un círculo.
     Si el nombre trae el cargo entre paréntesis, "Nombre (Cargo)", el cargo se muestra debajo. --}}
@props(['nombres'])

<ul class="space-y-3">
    @foreach ($nombres as $nombre)
        @php
            preg_match('/^(.*?)\s*\((.*)\)$/u', $nombre, $partes);
            [$persona, $cargo] = $partes ? [$partes[1], $partes[2]] : [$nombre, null];
        @endphp
        <li class="flex items-center gap-3">
            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white" aria-hidden="true">{{ mb_substr($persona, 0, 1) }}</span>
            <span class="min-w-0">
                <span class="block text-base font-medium text-slate-800 dark:text-slate-100">{{ $persona }}</span>
                @if ($cargo)
                    <span class="block text-sm text-slate-500 dark:text-slate-300">{{ $cargo }}</span>
                @endif
            </span>
        </li>
    @endforeach
</ul>