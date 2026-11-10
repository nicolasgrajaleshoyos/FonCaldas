{{-- Tabla de personal con filas alternadas. $fondo es la clase completa de fondo del encabezado. --}}
@props(['columnas', 'filas', 'fondo' => 'bg-[#0B4870]'])

<div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
    <table class="w-full text-left">
        <thead class="{{ $fondo }} text-white">
            <tr>
                @foreach ($columnas as $columna)
                    <th class="p-3 text-sm font-semibold">{{ $columna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-slate-700 dark:text-slate-300 text-[15px]">
            @foreach ($filas as $fila)
                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                    @foreach ($fila as $celda)
                        <td class="p-3">{{ $celda }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
