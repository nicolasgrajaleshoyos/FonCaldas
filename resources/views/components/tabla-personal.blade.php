{{-- Tabla de personal con filas alternadas. $fondo es la clase completa de fondo del encabezado. --}}
@props(['columnas', 'filas', 'fondo' => 'bg-[#0B4870]'])

<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-white/10">
    <table class="w-full text-left">
        <thead class="{{ $fondo }} text-white">
            <tr>
                @foreach ($columnas as $columna)
                    <th class="px-5 py-3.5 text-sm font-semibold uppercase tracking-wide">{{ $columna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-slate-700 dark:text-slate-200 text-base">
            @foreach ($filas as $fila)
                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-slate-50 dark:even:bg-slate-700/40 transition hover:bg-[#0C67A3]/5 dark:hover:bg-sky-400/10">
                    @foreach ($fila as $celda)
                        <td class="px-5 py-3.5 {{ $loop->first ? 'font-semibold text-slate-900 dark:text-white' : '' }}">{{ $celda }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>