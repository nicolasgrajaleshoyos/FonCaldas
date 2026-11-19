@extends('layouts.app')

@section('content')

<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<div class="min-h-screen page-bg py-14">
    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="coins"
            title="Simulación de ahorro"
            subtitle="Proyecta de forma orientativa el comportamiento de los productos de ahorro de FONCALDAS." />

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">

            @if ($errors->any())
                <div id="errores-simulacion"
                    class="mb-6 rounded-2xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-950/30 px-5 py-4">

                    <p class="font-semibold text-red-700 dark:text-red-300">
                        Revisa la información ingresada:
                    </p>

                    <ul class="mt-2 list-disc list-inside text-sm text-red-600 dark:text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                action="{{ route('simuladores.ahorro.simular') }}"
                id="form-simulador-ahorro">

                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-end">

                    <div class="lg:col-span-2 space-y-5">

                        {{-- PRODUCTO --}}
                        <div>
                            <label for="producto_ahorro"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Selecciona un producto
                                <span class="text-red-500">*</span>
                            </label>

                            <select id="producto_ahorro"
                                name="producto"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                                <option value="">
                                    Producto de ahorro
                                </option>

                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->codigo }}"
                                        data-parametros='@json($producto->parametros_simulador)'
                                        @selected(old('producto') === $producto->codigo)>
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- INFORMACIÓN --}}
                        <div id="descripcion-producto"
                            class="hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 px-5 py-4">

                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                <span class="font-semibold text-slate-700 dark:text-slate-200">
                                    Producto seleccionado:
                                </span>

                                <span id="nombre-producto"></span>
                            </p>

                            <p id="tasa-producto"
                                class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            </p>

                            <div class="mt-3">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    Condiciones de referencia:
                                </p>

                                <ul id="lista-condiciones"
                                    class="mt-2 space-y-1 list-disc list-inside text-sm text-slate-600 dark:text-slate-300">
                                </ul>
                            </div>

                            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                Los valores utilizados corresponden a los parámetros
                                configurados para la simulación y pueden ser actualizados
                                por FONCALDAS.
                            </p>
                        </div>

                        {{-- MONTO --}}
                        <div>
                            <label for="monto"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Monto inicial
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="monto"
                                name="monto"
                                type="text"
                                inputmode="numeric"
                                autocomplete="off"
                                disabled
                                required
                                value="{{ old('monto') }}"
                                placeholder="Selecciona un producto"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                            <p id="ayuda-monto"
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>
                        </div>

                        {{-- AHORRO ORDINARIO --}}
                        <div id="campos-ahorro-ordinario"
                            class="hidden space-y-5">

                            <div>
                                <label for="plazo-meses"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                    Plazo de la proyección en meses
                                    <span class="text-red-500">*</span>
                                </label>

                                <input id="plazo-meses"
                                    name="plazo_meses"
                                    type="number"
                                    min="1"
                                    step="1"
                                    value="{{ old('plazo_meses') }}"
                                    placeholder="Ej. 12"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                                <p id="ayuda-plazo"
                                    class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                </p>
                            </div>

                            <div>
                                <label for="aporte-mensual"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                    Aporte adicional mensual
                                    <span class="text-slate-400 font-normal">
                                        (opcional)
                                    </span>
                                </label>

                                <input id="aporte-mensual"
                                    name="aporte_mensual"
                                    type="text"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    value="{{ old('aporte_mensual') }}"
                                    placeholder="Ej. 20.000"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                                <p id="ayuda-aporte"
                                    class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                </p>
                            </div>
                        </div>

                        {{-- CDAT --}}
                        <div id="campos-cdat"
                            class="hidden">

                            <label for="plazo-dias"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Plazo del CDAT
                                <span class="text-red-500">*</span>
                            </label>

                            <select id="plazo-dias"
                                name="plazo_dias"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                                <option value="">
                                    Selecciona el plazo
                                </option>

                                <option value="90" @selected(old('plazo_dias') == 90)>
                                    90 días
                                </option>

                                <option value="180" @selected(old('plazo_dias') == 180)>
                                    180 días
                                </option>

                                <option value="360" @selected(old('plazo_dias') == 360)>
                                    360 días
                                </option>
                            </select>
                        </div>

                    </div>

                    {{-- BOTÓN --}}
                    <div class="flex flex-col justify-end">
                        <button id="boton-simular"
                            type="submit"
                            disabled
                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#0B4870]/60 text-white/70 font-semibold cursor-not-allowed">
                            Simular
                        </button>

                        <p class="mt-3 text-sm text-center text-slate-500 dark:text-slate-400">
                            Los campos cambian según el producto seleccionado.
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
            Simulación informativa. Los resultados corresponden a una proyección
            realizada con los parámetros configurados y no constituyen una oferta
            financiera definitiva.
        </p>
    </div>
</div>

<script>
    const selectorAhorro =
        document.getElementById('producto_ahorro');

    const descripcionProducto =
        document.getElementById('descripcion-producto');

    const nombreProducto =
        document.getElementById('nombre-producto');

    const tasaProducto =
        document.getElementById('tasa-producto');

    const listaCondiciones =
        document.getElementById('lista-condiciones');

    const montoInput =
        document.getElementById('monto');

    const ayudaMonto =
        document.getElementById('ayuda-monto');

    const camposOrdinario =
        document.getElementById('campos-ahorro-ordinario');

    const plazoMeses =
        document.getElementById('plazo-meses');

    const ayudaPlazo =
        document.getElementById('ayuda-plazo');

    const aporteMensual =
        document.getElementById('aporte-mensual');

    const ayudaAporte =
        document.getElementById('ayuda-aporte');

    const camposCdat =
        document.getElementById('campos-cdat');

    const plazoDias =
        document.getElementById('plazo-dias');

    const botonSimular =
        document.getElementById('boton-simular');

    const erroresSimulacion =
        document.getElementById('errores-simulacion');


    function obtenerParametros()
    {
        const opcion =
            selectorAhorro.options[
                selectorAhorro.selectedIndex
            ];

        if (
            !opcion
            || !opcion.dataset.parametros
        ) {
            return {};
        }

        try {
            return JSON.parse(
                opcion.dataset.parametros
            );
        } catch (error) {
            console.error(
                'No fue posible leer los parámetros del producto.',
                error
            );

            return {};
        }
    }


    function obtenerParametro(
        parametros,
        codigo
    ) {
        const parametro =
            parametros[codigo];

        if (!parametro) {
            return null;
        }

        if (
            parametro.tipo_dato === 'decimal'
            || parametro.tipo_dato === 'entero'
        ) {
            if (
                parametro.valor === null
                || parametro.valor === ''
            ) {
                return null;
            }

            const numero =
                Number(parametro.valor);

            return Number.isNaN(numero)
                ? null
                : numero;
        }

        return parametro.valor_texto;
    }


    function formatearPesos(valor)
    {
        if (
            valor === null
            || valor === undefined
            || valor === ''
        ) {
            return '';
        }

        const numero =
            Number(valor);

        if (
            Number.isNaN(numero)
        ) {
            return '';
        }

        return new Intl.NumberFormat(
            'es-CO',
            {
                maximumFractionDigits: 0
            }
        ).format(
            Math.trunc(numero)
        );
    }


    function formatearPesosEntrada(valor)
    {
        const limpio =
            String(valor || '')
                .replace(
                    /[^\d]/g,
                    ''
                );

        if (!limpio) {
            return '';
        }

        return new Intl.NumberFormat(
            'es-CO',
            {
                maximumFractionDigits: 0
            }
        ).format(
            Number(limpio)
        );
    }


    function configurarMoneda(campo)
    {
        campo.addEventListener(
            'input',
            function () {
                this.setCustomValidity('');

                this.value =
                    formatearPesosEntrada(
                        this.value
                    );
            }
        );

        if (campo.value) {
            campo.value =
                formatearPesosEntrada(
                    campo.value
                );
        }
    }


    function bloquearNumero(campo)
    {
        campo.addEventListener(
            'wheel',
            function () {
                if (
                    document.activeElement === this
                ) {
                    this.blur();
                }
            },
            {
                passive: true
            }
        );

        campo.addEventListener(
            'keydown',
            function (evento) {
                if (
                    [
                        '-',
                        '+',
                        'e',
                        'E'
                    ].includes(
                        evento.key
                    )
                ) {
                    evento.preventDefault();
                }
            }
        );
    }


    function agregarCondicion(texto)
    {
        if (!texto) {
            return;
        }

        const item =
            document.createElement('li');

        item.textContent =
            texto;

        listaCondiciones.appendChild(
            item
        );
    }


    function configurarProducto()
    {
        const codigo =
            selectorAhorro.value;

        const opcion =
            selectorAhorro.options[
                selectorAhorro.selectedIndex
            ];

        const parametros =
            obtenerParametros();


        listaCondiciones.innerHTML =
            '';

        camposOrdinario
            .classList
            .add('hidden');

        camposCdat
            .classList
            .add('hidden');

        montoInput.disabled =
            true;

        montoInput.placeholder =
            'Selecciona un producto';

        plazoMeses.required =
            false;

        plazoDias.required =
            false;

        botonSimular.disabled =
            true;

        plazoMeses.removeAttribute(
            'max'
        );

        ayudaPlazo.textContent =
            '';

        ayudaAporte.textContent =
            '';

        tasaProducto.textContent =
            '';


        botonSimular.className =
            'w-full inline-flex items-center justify-center '
            + 'px-6 py-3 rounded-xl '
            + 'bg-[#0B4870]/60 text-white/70 '
            + 'font-semibold cursor-not-allowed';


        if (!codigo) {
            descripcionProducto
                .classList
                .add('hidden');

            ayudaMonto.textContent =
                '';

            return;
        }


        descripcionProducto
            .classList
            .remove('hidden');

        nombreProducto.textContent =
            opcion.text.trim();

        montoInput.disabled =
            false;

        montoInput.placeholder =
            'Ej. 1.000.000';


        const minimo =
            obtenerParametro(
                parametros,
                'monto_minimo'
            );

        const maximo =
            obtenerParametro(
                parametros,
                'monto_maximo'
            );


        if (
            minimo !== null
            && maximo !== null
        ) {
            ayudaMonto.textContent =
                'Monto permitido para la simulación: $'
                + formatearPesos(minimo)
                + ' a $'
                + formatearPesos(maximo)
                + '.';

            agregarCondicion(
                'Monto mínimo: $'
                + formatearPesos(minimo)
                + '.'
            );

            agregarCondicion(
                'Monto máximo: $'
                + formatearPesos(maximo)
                + '.'
            );
        } else {
            ayudaMonto.textContent =
                'Ingresa el monto que deseas proyectar.';
        }


        if (
            codigo ===
            'ahorro_ordinario'
        ) {
            camposOrdinario
                .classList
                .remove('hidden');

            plazoMeses.required =
                true;


            const tasa =
                obtenerParametro(
                    parametros,
                    'tasa_interes_ea'
                );


            tasaProducto.textContent =
                tasa !== null
                    ? 'Tasa configurada: '
                        + tasa
                        + ' % E.A.'
                    : 'Tasa pendiente de configuración.';


            if (
                tasa !== null
            ) {
                agregarCondicion(
                    'Tasa de referencia: '
                    + tasa
                    + ' % E.A.'
                );
            }


            const aporteMinimo =
                obtenerParametro(
                    parametros,
                    'aporte_minimo'
                );


            if (
                aporteMinimo !== null
            ) {
                ayudaAporte.textContent =
                    'Si realizas aportes adicionales, el aporte mensual mínimo configurado es $'
                    + formatearPesos(
                        aporteMinimo
                    )
                    + '.';

                agregarCondicion(
                    'Aporte adicional mínimo: $'
                    + formatearPesos(
                        aporteMinimo
                    )
                    + '.'
                );
            } else {
                ayudaAporte.textContent =
                    'El aporte adicional es opcional.';
            }


            const plazoMaximoSimulacion =
                obtenerParametro(
                    parametros,
                    'plazo_maximo_simulacion'
                );


            if (
                plazoMaximoSimulacion !== null
            ) {
                const plazoMaximoEntero =
                    Math.trunc(
                        plazoMaximoSimulacion
                    );

                plazoMeses.max =
                    String(
                        plazoMaximoEntero
                    );

                ayudaPlazo.textContent =
                    'Puedes proyectar hasta '
                    + plazoMaximoEntero
                    + ' meses en el simulador.';

                agregarCondicion(
                    'Plazo máximo permitido por el simulador: '
                    + plazoMaximoEntero
                    + ' meses.'
                );
            } else {
                plazoMeses.removeAttribute(
                    'max'
                );

                ayudaPlazo.textContent =
                    'Ingresa el número de meses que deseas proyectar.';
            }


            agregarCondicion(
                'La proyección utiliza rendimientos calculados a partir de una tasa E.A.'
            );

        } else if (
            codigo ===
            'ahorro_cdat'
        ) {
            camposCdat
                .classList
                .remove('hidden');

            plazoDias.required =
                true;


            tasaProducto.textContent =
                'La tasa depende del plazo seleccionado.';


            const tasa90 =
                obtenerParametro(
                    parametros,
                    'tasa_interes_90_dias'
                );

            const tasa180 =
                obtenerParametro(
                    parametros,
                    'tasa_interes_180_dias'
                );

            const tasa360 =
                obtenerParametro(
                    parametros,
                    'tasa_interes_360_dias'
                );


            if (
                tasa90 !== null
            ) {
                agregarCondicion(
                    '90 días: '
                    + tasa90
                    + ' % E.A.'
                );
            }

            if (
                tasa180 !== null
            ) {
                agregarCondicion(
                    '180 días: '
                    + tasa180
                    + ' % E.A.'
                );
            }

            if (
                tasa360 !== null
            ) {
                agregarCondicion(
                    '360 días: '
                    + tasa360
                    + ' % E.A.'
                );
            }


            const retencion =
                obtenerParametro(
                    parametros,
                    'porcentaje_retencion'
                );


            if (
                retencion !== null
            ) {
                agregarCondicion(
                    'Retención de referencia sobre rendimientos: '
                    + retencion
                    + ' %.'
                );
            }


            agregarCondicion(
                'Depósito único.'
            );

            agregarCondicion(
                'El rendimiento se proyecta al vencimiento.'
            );

            agregarCondicion(
                'No se contempla retiro anticipado en esta simulación.'
            );
        }


        botonSimular.disabled =
            false;

        botonSimular.className =
            'w-full inline-flex items-center justify-center '
            + 'px-6 py-3 rounded-xl '
            + 'bg-[#0B4870] hover:bg-[#083653] '
            + 'text-white font-semibold transition';
    }


    configurarMoneda(
        montoInput
    );

    configurarMoneda(
        aporteMensual
    );

    bloquearNumero(
        plazoMeses
    );


    selectorAhorro.addEventListener(
        'change',
        function () {
            if (
                erroresSimulacion
            ) {
                erroresSimulacion
                    .classList
                    .add('hidden');
            }

            montoInput.value =
                '';

            plazoMeses.value =
                '';

            aporteMensual.value =
                '';

            plazoDias.value =
                '';

            configurarProducto();
        }
    );


    configurarProducto();
</script>

@endsection
