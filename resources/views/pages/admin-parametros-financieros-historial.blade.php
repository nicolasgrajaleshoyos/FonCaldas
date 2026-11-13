@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-admin-tabs
                active="parametros-financieros"
                title="Parámetros financieros"
                icon="card"
                subtitle="Consulta los parámetros registrados y sus periodos de vigencia." />

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
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                bg-slate-700 hover:bg-slate-600
                                text-white text-sm font-medium transition">

                            Volver a parámetros financieros
                        </a>

                    </div>

                </div>

                <div class="p-6">

                    @if ($parametros->isEmpty())

                        <div class="border border-dashed border-slate-600 rounded-xl p-8 text-center">

                            <p class="text-slate-300 font-medium">
                                Este producto todavía no tiene parámetros registrados.
                            </p>

                            <p class="text-sm text-slate-500 mt-2">
                                Cuando se registren nuevos parámetros aparecerán aquí
                                sin eliminar los registros anteriores.
                            </p>

                        </div>

                    @else

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm text-left">

                                <thead class="text-xs uppercase text-slate-400 border-b border-slate-700">

                                    <tr>
                                        <th class="px-4 py-3">
                                            Parámetro
                                        </th>

                                        <th class="px-4 py-3">
                                            Valor
                                        </th>

                                        <th class="px-4 py-3">
                                            Unidad
                                        </th>

                                        <th class="px-4 py-3">
                                            Categoría
                                        </th>

                                        <th class="px-4 py-3">
                                            Origen
                                        </th>

                                        <th class="px-4 py-3">
                                            Vigencia
                                        </th>

                                        <th class="px-4 py-3">
                                            Estado
                                        </th>

                                        <th class="px-4 py-3">
                                            Observación
                                        </th>

                                        <th class="px-4 py-3">
                                            Acciones
                                        </th>
                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-700">

                                    @foreach ($parametros as $parametro)

                                        @php
                                            $hoy = now()->toDateString();

                                            $esVigente =
                                                $parametro->activo
                                                && $parametro->fecha_inicio_vigencia->toDateString() <= $hoy
                                                && (
                                                    is_null($parametro->fecha_fin_vigencia)
                                                    || $parametro->fecha_fin_vigencia->toDateString() >= $hoy
                                                );

                                            $esFuturo =
                                                $parametro->activo
                                                && $parametro->fecha_inicio_vigencia->toDateString() > $hoy;

                                            if (in_array($parametro->tipo_dato, ['decimal', 'entero'])) {

                                                $valorMostrar = rtrim(
                                                    rtrim(
                                                        number_format(
                                                            (float) $parametro->valor,
                                                            6,
                                                            '.',
                                                            ''
                                                        ),
                                                        '0'
                                                    ),
                                                    '.'
                                                );

                                            } elseif ($parametro->tipo_dato === 'booleano') {

                                                $valorMostrar =
                                                    $parametro->valor_texto === 'true'
                                                        ? 'Sí'
                                                        : 'No';

                                            } else {

                                                $valorMostrar =
                                                    $parametro->valor_texto ?? '—';
                                            }
                                        @endphp

                                        <tr class="text-slate-200">

                                            <td class="px-4 py-4">

                                                <div class="font-semibold">
                                                    {{ $parametro->nombre }}
                                                </div>

                                                <div class="text-xs text-slate-500 mt-1">
                                                    {{ $parametro->codigo }}
                                                </div>

                                            </td>

                                            <td class="px-4 py-4 font-semibold">
                                                {{ $valorMostrar }}
                                            </td>

                                            <td class="px-4 py-4">
                                                {{ $parametro->unidad ?? '—' }}
                                            </td>

                                            <td class="px-4 py-4">
                                                {{ ucfirst($parametro->categoria ?? '—') }}
                                            </td>

                                            <td class="px-4 py-4">

                                                @if ($parametro->origen === 'confirmado')

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-emerald-900/50 text-emerald-300">

                                                        Confirmado
                                                    </span>

                                                @elseif ($parametro->origen === 'referencia')

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-blue-900/50 text-blue-300">

                                                        Referencia
                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-amber-900/50 text-amber-300">

                                                        Pendiente
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="px-4 py-4">

                                                <div>
                                                    {{ $parametro->fecha_inicio_vigencia->format('d/m/Y') }}
                                                </div>

                                                <div class="text-xs text-slate-500 mt-1">

                                                    @if ($parametro->fecha_fin_vigencia)

                                                        hasta
                                                        {{ $parametro->fecha_fin_vigencia->format('d/m/Y') }}

                                                    @else

                                                        Sin fecha final

                                                    @endif

                                                </div>

                                            </td>

                                            <td class="px-4 py-4">

                                                @if (!$parametro->activo)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-slate-700 text-slate-300">

                                                        Inactivo
                                                    </span>

                                                @elseif ($esVigente)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-emerald-900/50 text-emerald-300">

                                                        Vigente
                                                    </span>

                                                @elseif ($esFuturo)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-blue-900/50 text-blue-300">

                                                        Programado
                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-slate-700 text-slate-300">

                                                        Histórico
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="px-4 py-4 text-slate-400">
                                                {{ $parametro->descripcion ?? '—' }}
                                            </td>

                                            <td class="px-4 py-4">

                                                <div class="flex flex-wrap gap-2">

                                                    <a
                                                        href="{{ route('admin.parametros-financieros.tasas.editar', $parametro) }}"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg
                                                            bg-sky-700 hover:bg-sky-600
                                                            text-white text-xs font-medium transition">

                                                        Editar
                                                    </a>

                                                    @if ($parametro->activo)

                                                        <form
                                                            method="POST"
                                                            action="{{ route('admin.parametros-financieros.tasas.desactivar', $parametro) }}"
                                                            onsubmit="return confirm('¿Deseas desactivar este parámetro? El registro se conservará en el historial.');">

                                                            @csrf
                                                            @method('PATCH')

                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 rounded-lg
                                                                    bg-amber-700 hover:bg-amber-600
                                                                    text-white text-xs font-medium transition">

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
