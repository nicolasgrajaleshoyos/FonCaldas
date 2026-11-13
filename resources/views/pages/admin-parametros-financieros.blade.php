@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">

        <div class="max-w-6xl mx-auto space-y-6">

            <x-admin-tabs active="parametros-financieros" title="Parámetros financieros" icon="card"
                subtitle="Administra las tasas y parámetros utilizados por los simuladores." />

            @if (session('success'))
                <div
                    class="rounded-2xl border border-emerald-200 dark:border-emerald-800
                        bg-emerald-50 dark:bg-emerald-950/40
                        px-4 py-3 text-sm
                        text-emerald-700 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FORMULARIO DE PARÁMETROS --}}
            <div
                class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm
                    border border-slate-100 dark:border-slate-700">

                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Nuevo parámetro financiero
                </h2>

                <p class="mt-1 mb-6 text-sm text-slate-500 dark:text-slate-400">
                    Selecciona el producto y el parámetro que deseas configurar.
                    El sistema asignará automáticamente su código, categoría y tipo de dato.
                </p>

                <form method="POST" action="{{ route('admin.parametros-financieros.parametros.guardar') }}"
                    class="space-y-5">

                    @csrf

                    {{-- Producto --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Producto financiero
                        </label>

                        <select name="producto_financiero_id" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-900
                                text-slate-900 dark:text-slate-100 px-4 py-3">

                            <option value="">Selecciona un producto</option>

                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}" data-codigo="{{ $producto->codigo }}"
                                    @selected(old('producto_financiero_id') == $producto->id)>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('producto_financiero_id')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Parámetro --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Parámetro a configurar
                        </label>

                        <select name="codigo" id="codigo_parametro" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                            bg-white dark:bg-slate-900
                            text-slate-900 dark:text-slate-100 px-4 py-3">

                            <option value="">
                                Primero selecciona un producto
                            </option>

                        </select>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            No es necesario ingresar códigos técnicos manualmente.
                        </p>

                        @error('codigo')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- VALOR NUMÉRICO --}}
                    <div id="contenedor_valor_numerico" class="hidden">

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Valor
                        </label>

                        <input type="number" name="valor" id="valor" value="{{ old('valor') }}" min="0"
                            step="0.000001" placeholder="Ingresa el valor" disabled
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-900
                                text-slate-900 dark:text-slate-100 px-4 py-3">

                        @error('valor')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- VALOR TEXTO --}}
                    <div id="contenedor_valor_texto" class="hidden">

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Valor
                        </label>

                        <input type="text" name="valor_texto" id="valor_texto" value="{{ old('valor_texto') }}"
                            placeholder="Ingresa el valor" disabled
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-900
                                text-slate-900 dark:text-slate-100 px-4 py-3">

                        @error('valor_texto')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- VALOR BOOLEANO --}}
                    <div id="contenedor_valor_booleano" class="hidden">

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Valor
                        </label>

                        <select name="valor_texto" id="valor_booleano" disabled
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-900
                                text-slate-900 dark:text-slate-100 px-4 py-3">

                            <option value="">Selecciona una opción</option>

                            <option value="true" @selected(old('valor_texto') === 'true')}>
                                Sí
                            </option>

                            <option value="false" @selected(old('valor_texto') === 'false')}>
                                No
                            </option>
                        </select>

                        @error('valor_texto')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Unidad y origen --}}
                    <div class="grid gap-5 md:grid-cols-2">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Unidad
                            </label>

                            <input type="text" name="unidad" id="unidad" value="{{ old('unidad') }}"
                                placeholder="Se completa automáticamente cuando aplica"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900
                                    text-slate-900 dark:text-slate-100 px-4 py-3">

                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Puedes modificarla cuando sea necesario.
                            </p>

                            @error('unidad')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Origen del dato
                            </label>

                            <select name="origen" required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900
                                    text-slate-900 dark:text-slate-100 px-4 py-3">

                                <option value="">
                                    Selecciona el origen
                                </option>

                                <option value="confirmado" @selected(old('origen') === 'confirmado')}>
                                    Confirmado por FONCALDAS
                                </option>

                                <option value="referencia" @selected(old('origen', 'referencia') === 'referencia')}>
                                    Referencia para desarrollo
                                </option>

                                <option value="pendiente" @selected(old('origen') === 'pendiente')}>
                                    Pendiente de confirmación
                                </option>

                            </select>

                            @error('origen')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Vigencias --}}
                    <div class="grid gap-5 md:grid-cols-2">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Inicio de vigencia
                            </label>

                            <input type="date" name="fecha_inicio_vigencia" value="{{ old('fecha_inicio_vigencia') }}"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900
                                    text-slate-900 dark:text-slate-100 px-4 py-3">

                            @error('fecha_inicio_vigencia')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Fin de vigencia
                            </label>

                            <input type="date" name="fecha_fin_vigencia" value="{{ old('fecha_fin_vigencia') }}"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900
                                    text-slate-900 dark:text-slate-100 px-4 py-3">

                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Déjalo vacío si no existe una fecha de finalización definida.
                            </p>

                            @error('fecha_fin_vigencia')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Editable --}}
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">

                            <input type="checkbox" name="editable_admin" value="1" @checked(old('editable_admin', true))
                                class="h-4 w-4 rounded border-slate-300 text-[#0C67A3]">

                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Permitir modificación desde el panel administrativo
                            </span>

                        </label>
                    </div>

                    {{-- Observación --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Observación (opcional)
                        </label>

                        <textarea name="descripcion" rows="3"
                            placeholder="Información adicional sobre el parámetro, su fuente o condición de uso."
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-900
                                text-slate-900 dark:text-slate-100 px-4 py-3">{{ old('descripcion') }}</textarea>

                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="rounded-lg bg-[#0C67A3] px-5 py-3
                            text-white font-semibold
                            hover:bg-[#095c8f] transition">

                        Registrar parámetro
                    </button>

                </form>

            </div>

            {{-- RESUMEN DE PARÁMETROS POR PRODUCTO --}}
            <div
                class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm
        border border-slate-100 dark:border-slate-700 overflow-x-auto">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Productos y parámetros financieros
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Consulta los parámetros registrados para cada producto y conserva
                        el historial de sus configuraciones y vigencias.
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
                                Parámetros registrados
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Parámetros activos
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Estado
                            </th>

                            <th class="px-3 py-3 font-medium text-slate-700 dark:text-slate-300">
                                Gestión
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                        @foreach ($productos as $producto)
                            @php
                                $totalParametros = $producto->parametros->count();

                                $parametrosActivos = $producto->parametros->where('activo', true)->count();
                            @endphp

                            <tr>

                                <td class="px-3 py-4 font-medium text-slate-900 dark:text-slate-200">
                                    {{ $producto->nombre }}
                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">
                                    {{ ucfirst($producto->tipo) }}
                                </td>

                                <td class="px-3 py-4 text-slate-600 dark:text-slate-400">

                                    @if ($totalParametros > 0)
                                        <span class="font-medium text-slate-800 dark:text-slate-200">
                                            {{ $totalParametros }}
                                        </span>

                                        <span class="text-slate-500 dark:text-slate-400">
                                            registro(s)
                                        </span>
                                    @else
                                        <span class="text-amber-600 dark:text-amber-400">
                                            Sin parámetros registrados
                                        </span>
                                    @endif

                                </td>

                                <td class="px-3 py-4">

                                    @if ($parametrosActivos > 0)
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold
                                    bg-sky-100 dark:bg-sky-900/40
                                    text-sky-700 dark:text-sky-300">

                                            {{ $parametrosActivos }} activo(s)

                                        </span>
                                    @else
                                        <span class="text-slate-400">
                                            —
                                        </span>
                                    @endif

                                </td>

                                <td class="px-3 py-4">

                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $producto->activo
                                    ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300'
                                    : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">

                                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}

                                    </span>

                                </td>

                                <td class="px-3 py-4">

                                    <a href="{{ route('admin.parametros-financieros.historial', $producto) }}"
                                        class="text-sky-500 hover:text-sky-400 font-medium">

                                        Ver parámetros

                                    </a>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const producto =
                document.querySelector('[name="producto_financiero_id"]');

            const parametro =
                document.getElementById('codigo_parametro');

            const contenedorNumerico =
                document.getElementById('contenedor_valor_numerico');

            const contenedorTexto =
                document.getElementById('contenedor_valor_texto');

            const contenedorBooleano =
                document.getElementById('contenedor_valor_booleano');

            const valorNumerico =
                document.getElementById('valor');

            const valorTexto =
                document.getElementById('valor_texto');

            const valorBooleano =
                document.getElementById('valor_booleano');

            const unidad =
                document.getElementById('unidad');

            const catalogo =
                @json($tiposParametro);

            const parametrosPermitidos =
                @json($parametrosPermitidos);

            const codigoAnterior =
                @json(old('codigo'));


            function ocultarCamposValor() {

                contenedorNumerico.classList.add('hidden');
                contenedorTexto.classList.add('hidden');
                contenedorBooleano.classList.add('hidden');

                valorNumerico.disabled = true;
                valorTexto.disabled = true;
                valorBooleano.disabled = true;
            }


            function cargarParametros() {

                const opcionProducto =
                    producto.options[producto.selectedIndex];

                const codigoProducto =
                    opcionProducto?.dataset?.codigo || '';

                const permitidos =
                    parametrosPermitidos[codigoProducto] || [];

                parametro.innerHTML = '';


                if (!codigoProducto) {

                    parametro.innerHTML =
                        '<option value="">Primero selecciona un producto</option>';

                    parametro.disabled = true;

                    ocultarCamposValor();

                    unidad.value = '';

                    return;
                }


                if (permitidos.length === 0) {

                    parametro.innerHTML =
                        '<option value="">Producto informativo - no requiere parámetros de simulación</option>';

                    parametro.disabled = true;

                    ocultarCamposValor();

                    unidad.value = '';

                    return;
                }


                parametro.disabled = false;

                const opcionInicial =
                    document.createElement('option');

                opcionInicial.value = '';
                opcionInicial.textContent =
                    'Selecciona un parámetro';

                parametro.appendChild(opcionInicial);


                permitidos.forEach(function(codigo) {

                    const definicion =
                        catalogo[codigo];

                    if (!definicion) {
                        return;
                    }

                    const opcion =
                        document.createElement('option');

                    opcion.value = codigo;
                    opcion.textContent =
                        definicion.nombre;

                    opcion.dataset.tipo =
                        definicion.tipo_dato;

                    opcion.dataset.unidad =
                        definicion.unidad_sugerida || '';

                    if (codigoAnterior === codigo) {
                        opcion.selected = true;
                    }

                    parametro.appendChild(opcion);
                });


                actualizarFormulario(false);
            }


            function actualizarFormulario(cambiarUnidad = true) {

                ocultarCamposValor();

                const opcion =
                    parametro.options[parametro.selectedIndex];

                const tipo =
                    opcion?.dataset?.tipo || '';

                const unidadSugerida =
                    opcion?.dataset?.unidad || '';


                if (tipo === 'decimal') {

                    contenedorNumerico.classList.remove('hidden');

                    valorNumerico.disabled = false;
                    valorNumerico.step = '0.000001';
                }


                if (tipo === 'entero') {

                    contenedorNumerico.classList.remove('hidden');

                    valorNumerico.disabled = false;
                    valorNumerico.step = '1';
                }


                if (tipo === 'texto') {

                    contenedorTexto.classList.remove('hidden');

                    valorTexto.disabled = false;
                }


                if (tipo === 'booleano') {

                    contenedorBooleano.classList.remove('hidden');

                    valorBooleano.disabled = false;
                }


                if (cambiarUnidad) {
                    unidad.value = unidadSugerida;
                }
            }


            producto.addEventListener('change', function() {

                valorNumerico.value = '';
                valorTexto.value = '';
                valorBooleano.value = '';
                unidad.value = '';

                cargarParametros();
            });


            parametro.addEventListener('change', function() {

                valorNumerico.value = '';
                valorTexto.value = '';
                valorBooleano.value = '';

                actualizarFormulario(true);
            });


            cargarParametros();


            if (
                codigoAnterior &&
                unidad.value === ''
            ) {
                actualizarFormulario(true);
            }

        });
    </script>
@endsection
