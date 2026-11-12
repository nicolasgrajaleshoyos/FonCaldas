{{-- Ficha de una línea de crédito: tabla de condiciones y listas de requisitos, forma de pago y garantía. --}}
@props(['titulo', 'resumen', 'descripcion', 'plazo', 'monto', 'otras', 'requisitos', 'pago', 'garantia'])

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10">

    <div class="mb-8">
        <x-section-title as="h2" class="mb-4">
            {{ $titulo }}
        </x-section-title>

        <p class="text-slate-500 dark:text-slate-300">
            {{ $resumen }}
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
            <thead class="bg-[#0C67A3] text-white">
                <tr>
                    <th class="px-5 py-4 text-left">Descripción</th>
                    <th class="px-5 py-4 text-left">Plazo</th>
                    <th class="px-5 py-4 text-left">Monto</th>
                    <th class="px-5 py-4 text-left">Otras especificaciones</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-t border-slate-200 dark:border-slate-700">
                    @foreach ([$descripcion, $plazo, $monto, $otras] as $celda)
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                            {{ $celda }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mt-10">
        @foreach (['Requisitos y condiciones' => $requisitos, 'Forma de pago' => $pago, 'Garantía' => $garantia] as $encabezado => $items)
            <div>
                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                    {{ $encabezado }}
                </h3>

                <ul class="space-y-3 text-slate-600 dark:text-slate-300 text-[15px]">
                    @foreach ($items as $item)
                        <li>• {{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

</div>
