@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">

        <div class="max-w-6xl mx-auto space-y-6">

            <x-admin-tabs
                active="parametros-financieros"
                title="Parámetros financieros"
                icon="card"
                subtitle="Administra las tasas y parámetros utilizados por los simuladores." />

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800
                        bg-emerald-50 dark:bg-emerald-950/40
                        px-4 py-3 text-sm
                        text-emerald-700 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm
                    border border-slate-100 dark:border-slate-700">

                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Registrar nueva tasa
                </h2>

                <p class="mt-1 mb-6 text-sm text-slate-500 dark:text-slate-400">
                    Registra una nueva tasa sin eliminar el historial de vigencias anteriores.
                </p>

                <form method="POST"
                    action="{{ route('admin.parametros-financieros.tasas.guardar') }}"
                    class="space-y-5">

                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Producto financiero
                        </label>

                        <select name="producto_financiero_id"
                            required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-white dark:bg-slate-900
                                   text-slate-900 dark:text-slate-100 px-4 py-3">

                            <option value="">Selecciona un producto</option>

                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}"
                                    @selected(old('producto_financiero_id') == $producto->id)>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach

                        </select>

                        @error('producto_financiero_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Valor de la tasa
                            </label>

                            <input type="number"
                                name="valor"
                                value="{{ old('valor') }}"
                                step="0.000001"
                                min="0"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                      bg-white dark:bg-slate-900
                                      text-slate-900 dark:text-slate-100 px-4 py-3">

                            @error('valor')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Tipo / unidad de tasa
                            </label>

                            <input type="text"
                                name="unidad"
                                value="{{ old('unidad') }}"
                                placeholder="Según información oficial de FONCALDAS"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                      bg-white dark:bg-slate-900
                                      text-slate-900 dark:text-slate-100 px-4 py-3">

                            @error('unidad')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid gap-5 md:grid-cols-2">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Inicio de vigencia
                            </label>

                            <input type="date"
                                name="fecha_inicio_vigencia"
                                value="{{ old('fecha_inicio_vigencia') }}"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                      bg-white dark:bg-slate-900
                                      text-slate-900 dark:text-slate-100 px-4 py-3">

                            @error('fecha_inicio_vigencia')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Fin de vigencia
                            </label>

                            <input type="date"
                                name="fecha_fin_vigencia"
                                value="{{ old('fecha_fin_vigencia') }}"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                      bg-white dark:bg-slate-900
                                      text-slate-900 dark:text-slate-100 px-4 py-3">

                            @error('fecha_fin_vigencia')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Observación (opcional)
                        </label>

                        <textarea name="descripcion"
                            rows="3"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                     bg-white dark:bg-slate-900
                                     text-slate-900 dark:text-slate-100 px-4 py-3">{{ old('descripcion') }}</textarea>
                    </div>

                    <button type="submit"
                        class="rounded-lg bg-[#0C67A3] px-5 py-3
                               text-white font-semibold
                               hover:bg-[#095c8f] transition">
                        Registrar tasa
                    </button>

                </form>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm
                    border border-slate-100 dark:border-slate-700 overflow-x-auto">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Productos financieros
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Las tasas se almacenarán con su vigencia para conservar el historial de cambios.
                    </p>
                </div>

                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">

                    <thead>
                        <tr>
                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Producto
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Tipo
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Tasa vigente
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Vigencia
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Estado
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Historial
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                        @foreach ($productos as $producto)
                            @php
                                $tasa = $producto->parametros->first();
                            @endphp

                            <tr>

                                <td class="px-3 py-4 font-medium text-slate-900 dark:text-slate-200">
                                    {{ $producto->nombre }}
                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">
                                    {{ ucfirst($producto->tipo) }}
                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">

                                    @if ($tasa)
                                        {{ $tasa->valor }} {{ $tasa->unidad }}
                                    @else
                                        <span class="text-amber-600 dark:text-amber-400">
                                            Sin tasa registrada
                                        </span>
                                    @endif

                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">

                                    @if ($tasa)
                                        {{ $tasa->fecha_inicio_vigencia->format('d/m/Y') }}

                                        @if ($tasa->fecha_fin_vigencia)
                                            -
                                            {{ $tasa->fecha_fin_vigencia->format('d/m/Y') }}
                                        @endif
                                    @else
                                        —
                                    @endif

                                </td>

                                <td class="px-3 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $producto->activo ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">

                                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}

                                    </span>

                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">
                                    {{ $producto->parametros->count() }} registro(s)
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
