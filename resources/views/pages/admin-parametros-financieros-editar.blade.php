@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-admin-tabs active="parametros-financieros" title="Editar parámetro financiero" icon="card"
                subtitle="Actualiza el valor y las condiciones del parámetro seleccionado." />

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


                <form method="POST" action="{{ route('admin.parametros-financieros.tasas.actualizar', $tasa) }}"
                    class="space-y-6">

                    @csrf
                    @method('PUT')


                    {{-- Identificación --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Producto financiero
                            </label>

                            <div
                                class="w-full rounded-xl bg-slate-900/60
                                    border border-slate-700
                                    text-slate-300 px-4 py-3">

                                {{ $tasa->producto->nombre }}

                            </div>

                            <input type="hidden" name="producto_financiero_id" value="{{ $tasa->producto_financiero_id }}">

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Parámetro
                            </label>

                            <div
                                class="w-full rounded-xl bg-slate-900/60
                                    border border-slate-700
                                    text-slate-300 px-4 py-3">

                                {{ $definicion['nombre'] }}

                            </div>

                            <input type="hidden" name="codigo" value="{{ $tasa->codigo }}">

                        </div>

                    </div>


                    {{-- Valor --}}
                    @if (in_array($definicion['tipo_dato'], ['decimal', 'entero']))
                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Valor
                            </label>

                            <input type="number" name="valor" min="0"
                                step="{{ $definicion['tipo_dato'] === 'entero' ? '1' : '0.000001' }}"
                                value="{{ old(
                                    'valor',
                                    $definicion['tipo_dato'] === 'entero'
                                        ? (int) $tasa->valor
                                        : rtrim(rtrim(number_format((float) $tasa->valor, 6, '.', ''), '0'), '.'),
                                       ) }}"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3"
                                required>

                            @error('valor')
                                <p class="mt-2 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                    @elseif ($definicion['tipo_dato'] === 'booleano')
                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Valor
                            </label>

                            <select name="valor_texto"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3"
                                required>

                                <option value="true" @selected(old('valor_texto', $tasa->valor_texto) === 'true')>
                                    Sí
                                </option>

                                <option value="false" @selected(old('valor_texto', $tasa->valor_texto) === 'false')>
                                    No
                                </option>

                            </select>

                            @error('valor_texto')
                                <p class="mt-2 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                    @else
                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Valor
                            </label>

                            <input type="text" name="valor_texto" value="{{ old('valor_texto', $tasa->valor_texto) }}"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3"
                                required>

                            @error('valor_texto')
                                <p class="mt-2 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                    @endif


                    {{-- Unidad y origen --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Unidad
                            </label>

                            <input type="text" name="unidad" value="{{ old('unidad', $tasa->unidad) }}"
                                placeholder="{{ $definicion['unidad_sugerida'] ?? 'No aplica' }}"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3">

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Origen del dato
                            </label>

                            <select name="origen"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3"
                                required>

                                <option value="confirmado" @selected(old('origen', $tasa->origen) === 'confirmado')>
                                    Confirmado por FONCALDAS
                                </option>

                                <option value="referencia" @selected(old('origen', $tasa->origen) === 'referencia')>
                                    Referencia para desarrollo
                                </option>

                                <option value="pendiente" @selected(old('origen', $tasa->origen) === 'pendiente')>
                                    Pendiente de confirmación
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Vigencia --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Inicio de vigencia
                            </label>

                            <input type="date" name="fecha_inicio_vigencia"
                                value="{{ old('fecha_inicio_vigencia', $tasa->fecha_inicio_vigencia->format('Y-m-d')) }}"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3"
                                required>

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Fin de vigencia
                            </label>

                            <input type="date" name="fecha_fin_vigencia"
                                value="{{ old('fecha_fin_vigencia', $tasa->fecha_fin_vigencia?->format('Y-m-d')) }}"
                                class="w-full rounded-xl bg-slate-900
                                    border border-slate-600
                                    text-white px-4 py-3">

                            <p class="mt-1 text-xs text-slate-500">
                                Déjalo vacío si el parámetro no tiene una fecha final definida.
                            </p>

                        </div>

                    </div>


                    {{-- Editable --}}
                    <div>

                        <label class="flex items-center gap-3 cursor-pointer">

                            <input type="checkbox" name="editable_admin" value="1" @checked(old('editable_admin', $tasa->editable_admin))
                                class="h-4 w-4 rounded border-slate-500 text-sky-600">

                            <span class="text-sm font-medium text-slate-200">
                                Permitir modificación desde el panel administrativo
                            </span>

                        </label>

                    </div>


                    {{-- Observación --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-200 mb-2">
                            Observación
                        </label>

                        <textarea name="descripcion" rows="4"
                            class="w-full rounded-xl bg-slate-900
                                border border-slate-600
                                text-white px-4 py-3">{{ old('descripcion', $tasa->descripcion) }}</textarea>

                    </div>


                    {{-- Acciones --}}
                    <div class="flex flex-wrap gap-3">

                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg
                                bg-sky-700 hover:bg-sky-600
                                text-white font-medium transition">

                            Guardar cambios
                        </button>

                        <a href="{{ route('admin.parametros-financieros.historial', $tasa->producto_financiero_id) }}"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg
                                bg-slate-700 hover:bg-slate-600
                                text-white font-medium transition">

                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
