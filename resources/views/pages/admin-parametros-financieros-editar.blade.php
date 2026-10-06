@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-admin-tabs
                active="parametros-financieros"
                title="Editar tasa"
                icon="card"
                subtitle="Corrige los datos de una tasa registrada." />

            <div class="mt-6 bg-slate-800 border border-slate-700 rounded-2xl shadow-xl p-6">

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-700 bg-red-950/40 p-4">
                        <p class="font-semibold text-red-300 mb-2">
                            Revisa la información ingresada:
                        </p>

                        <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.parametros-financieros.tasas.actualizar', $tasa) }}"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-slate-200 mb-2">
                            Producto financiero
                        </label>

                        <select
                            name="producto_financiero_id"
                            class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3"
                            required>
                            @foreach ($productos as $producto)
                                <option
                                    value="{{ $producto->id }}"
                                    @selected(old('producto_financiero_id', $tasa->producto_financiero_id) == $producto->id)>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Valor de la tasa
                            </label>

                            <input
                                type="number"
                                name="valor"
                                step="0.000001"
                                min="0"
                                value="{{ old('valor', $tasa->valor) }}"
                                class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Tipo / unidad de tasa
                            </label>

                            <input
                                type="text"
                                name="unidad"
                                value="{{ old('unidad', $tasa->unidad) }}"
                                class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3"
                                required>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Inicio de vigencia
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio_vigencia"
                                value="{{ old('fecha_inicio_vigencia', $tasa->fecha_inicio_vigencia->format('Y-m-d')) }}"
                                class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Fin de vigencia
                            </label>

                            <input
                                type="date"
                                name="fecha_fin_vigencia"
                                value="{{ old('fecha_fin_vigencia', $tasa->fecha_fin_vigencia?->format('Y-m-d')) }}"
                                class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3">
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-200 mb-2">
                            Observación
                        </label>

                        <textarea
                            name="descripcion"
                            rows="4"
                            class="w-full rounded-xl bg-slate-900 border border-slate-600 text-white px-4 py-3">{{ old('descripcion', $tasa->descripcion) }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-3">

                        <button
                            type="submit"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-600 text-white font-medium transition">
                            Guardar cambios
                        </button>

                        <a
                            href="{{ route('admin.parametros-financieros.historial', $tasa->producto_financiero_id) }}"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection
