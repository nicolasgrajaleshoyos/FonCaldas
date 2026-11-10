@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-admin-tabs
                active="parametros-financieros"
                title="Historial de tasas"
                icon="card"
                subtitle="Consulta las tasas registradas y sus periodos de vigencia." />

            <div class="mt-6 bg-slate-800 border border-slate-700 rounded-2xl shadow-xl overflow-hidden">

                <div class="p-6 border-b border-slate-700">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <h2 class="text-xl font-semibold text-white">
                                {{ $producto->nombre }}
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Tipo: {{ ucfirst($producto->tipo) }}
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.parametros-financieros.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium transition">
                            Volver a parámetros financieros
                        </a>

                    </div>
                </div>

                <div class="p-6">

                    @if ($tasas->isEmpty())
                        <div class="border border-dashed border-slate-600 rounded-xl p-8 text-center">
                            <p class="text-slate-300 font-medium">
                                Este producto todavía no tiene tasas registradas.
                            </p>

                            <p class="text-sm text-slate-500 mt-2">
                                Cuando se registren nuevas tasas, aparecerán aquí sin eliminar los registros anteriores.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">

                                <thead class="text-xs uppercase text-slate-400 border-b border-slate-700">
                                    <tr>
                                        <th class="px-4 py-3">Tasa</th>
                                        <th class="px-4 py-3">Unidad</th>
                                        <th class="px-4 py-3">Inicio de vigencia</th>
                                        <th class="px-4 py-3">Fin de vigencia</th>
                                        <th class="px-4 py-3">Estado</th>
                                        <th class="px-4 py-3">Observación</th>
                                        <th class="px-4 py-3">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-700">

                                    @foreach ($tasas as $tasa)
                                        @php
                                            $hoy = now()->toDateString();

                                            $esVigente = $tasa->activo && $tasa->fecha_inicio_vigencia->toDateString() <= $hoy && (is_null($tasa->fecha_fin_vigencia) || $tasa->fecha_fin_vigencia->toDateString() >= $hoy);

                                            $esFutura = $tasa->activo && $tasa->fecha_inicio_vigencia->toDateString() > $hoy;
                                        @endphp

                                        <tr class="text-slate-200">

                                            <td class="px-4 py-4 font-semibold">
                                                {{ rtrim(rtrim(number_format((float) $tasa->valor, 6, '.', ''), '0'), '.') }}
                                            </td>

                                            <td class="px-4 py-4">
                                                {{ $tasa->unidad ?? '—' }}
                                            </td>

                                            <td class="px-4 py-4">
                                                {{ $tasa->fecha_inicio_vigencia->format('d/m/Y') }}
                                            </td>

                                            <td class="px-4 py-4">
                                                {{ $tasa->fecha_fin_vigencia ? $tasa->fecha_fin_vigencia->format('d/m/Y') : 'Sin fecha definida' }}
                                            </td>

                                            <td class="px-4 py-4">

                                                @if (!$tasa->activo)
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-slate-700 text-slate-300">
                                                        Inactiva
                                                    </span>
                                                @elseif ($esVigente)
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-emerald-900/50 text-emerald-300">
                                                        Vigente
                                                    </span>
                                                @elseif ($esFutura)
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-blue-900/50 text-blue-300">
                                                        Programada
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-slate-700 text-slate-300">
                                                        Histórica
                                                    </span>
                                                @endif

                                            </td>

                                            <td class="px-4 py-4 text-slate-400">
                                                {{ $tasa->descripcion ?? '—' }}
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap gap-2">

                                                    <a
                                                        href="{{ route('admin.parametros-financieros.tasas.editar', $tasa) }}"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-700 hover:bg-sky-600 text-white text-xs font-medium transition">
                                                        Editar
                                                    </a>

                                                    @if ($tasa->activo)
                                                        <form
                                                            method="POST"
                                                            action="{{ route('admin.parametros-financieros.tasas.desactivar', $tasa) }}"
                                                            onsubmit="return confirm('¿Deseas desactivar esta tasa? El registro se conservará en el historial.');">
                                                            @csrf
                                                            @method('PATCH')

                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-700 hover:bg-amber-600 text-white text-xs font-medium transition">
                                                                Desactivar
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
@endsection
