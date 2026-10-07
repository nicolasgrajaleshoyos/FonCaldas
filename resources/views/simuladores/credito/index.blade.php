@extends('layouts.app')

@section('content')
    <div class="min-h-screen page-bg py-14">

        <div class="max-w-6xl mx-auto px-6">

            <x-page-header
                icon="card"
                title="Simulación de crédito"
                subtitle="Estimación orientativa. Las condiciones serán confirmadas por FONCALDAS." />

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-end">

                    <!-- COLUMNA IZQUIERDA -->
                    <div class="lg:col-span-2 space-y-5">

                        <div>
                            <label
                                for="producto_credito"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Selecciona un producto
                            </label>

                            <select
                                id="producto_credito"
                                name="producto_credito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-white dark:bg-slate-900
                                   text-slate-800 dark:text-slate-100
                                   px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">
                                <option value="">Línea de crédito</option>

                                @foreach ($productos as $producto)
                                    <option
                                        value="{{ $producto->codigo }}"
                                        data-tasa="{{ $producto->tasaVigente?->valor }}"
                                        data-unidad="{{ $producto->tasaVigente?->unidad }}">
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- INFORMACIÓN DEL PRODUCTO -->
                        <div
                            id="descripcion-producto"
                            class="hidden mt-4 rounded-xl border border-slate-200 dark:border-slate-700
                               bg-slate-50 dark:bg-slate-900/40 px-4 py-3">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-semibold text-slate-700 dark:text-slate-200">
                                    Producto seleccionado:
                                </span>

                                <span id="nombre-producto"></span>
                            </p>

                            <p
                                id="tasa-producto"
                                class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            </p>

                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Las condiciones específicas de esta línea están pendientes de confirmación por FONCALDAS.
                            </p>
                        </div>

                        <!-- MONTO -->
                        <div id="campo-monto">

                            <label
                                id="label-monto"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Monto solicitado
                            </label>

                            <input
                                id="monto-solicitado"
                                type="number"
                                disabled
                                placeholder="Selecciona una línea de crédito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-slate-100 dark:bg-slate-900
                                   text-slate-500 px-4 py-3">

                            <p
                                id="ayuda-monto"
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                        <!-- PLAZO -->
                        <div id="campo-plazo">

                            <label
                                id="label-plazo"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Plazo
                            </label>

                            <input
                                id="plazo-credito"
                                type="text"
                                disabled
                                placeholder="Selecciona una línea de crédito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-slate-100 dark:bg-slate-900
                                   text-slate-500 px-4 py-3">

                            <p
                                id="ayuda-plazo"
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                        <!-- DATO ESPECÍFICO -->
                        <div id="campo-especifico">

                            <label
                                id="label-especifico"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Información adicional
                            </label>

                            <input
                                id="dato-especifico"
                                type="text"
                                disabled
                                placeholder="Selecciona una línea de crédito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-slate-100 dark:bg-slate-900
                                   text-slate-500 px-4 py-3">

                            <p
                                id="ayuda-especifica"
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                    </div>

                    <!-- COLUMNA DERECHA -->
                    <div class="flex flex-col justify-end">

                        <button
                            type="button"
                            disabled
                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl
                               bg-[#0B4870]/60
                               text-white/70
                               font-semibold cursor-not-allowed">
                            Simular
                        </button>

                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">
                            Los campos varían según el producto.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        const selectorCredito = document.getElementById('producto_credito');
        const descripcionProducto = document.getElementById('descripcion-producto');
        const nombreProducto = document.getElementById('nombre-producto');
        const tasaProducto = document.getElementById('tasa-producto');

        const montoSolicitado = document.getElementById('monto-solicitado');
        const plazoCredito = document.getElementById('plazo-credito');
        const datoEspecifico = document.getElementById('dato-especifico');

        const labelMonto = document.getElementById('label-monto');
        const labelPlazo = document.getElementById('label-plazo');
        const labelEspecifico = document.getElementById('label-especifico');

        const ayudaMonto = document.getElementById('ayuda-monto');
        const ayudaPlazo = document.getElementById('ayuda-plazo');
        const ayudaEspecifica = document.getElementById('ayuda-especifica');

        const configuracionProductos = {

            credito_avances: {
                plazo: 'Hasta 3 meses',
                ayudaMonto: 'El monto solicitado no debe superar el 50 % del sueldo básico.',
                labelEspecifico: 'Sueldo básico del asociado',
                ayudaEspecifica: 'Este valor se utiliza para validar el monto máximo permitido.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_bonos: {
                plazo: 'Hasta 1 mes - cuota única',
                ayudaMonto: 'El monto está sujeto al límite definido para esta línea.',
                labelEspecifico: 'Referencia para validación del monto',
                ayudaEspecifica: 'El reglamento relaciona el límite con el SMMLV vigente.'
            },

            credito_bonificacion: {
                plazo: 'Hasta 6 meses - cuota única',
                ayudaMonto: 'El monto solicitado no debe superar el 90 % del valor elegible recibido el año anterior.',
                labelEspecifico: 'Valor elegible recibido el año anterior',
                ayudaEspecifica: 'Se utiliza como base para validar el límite del 90 %. Los deducibles legales están pendientes de confirmación por FONCALDAS.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_cuota_unica_junio: {
                plazo: 'Hasta 6 meses - pago único',
                ayudaMonto: 'El monto solicitado no debe superar el 100 % del ingreso básico mensual.',
                labelEspecifico: 'Ingreso básico mensual',
                ayudaEspecifica: 'Este valor se utiliza para validar el monto máximo de referencia.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_cuota_unica_diciembre: {
                plazo: 'Hasta 6 meses - pago único',
                ayudaMonto: 'El monto solicitado no debe superar el 100 % del ingreso básico mensual.',
                labelEspecifico: 'Ingreso básico mensual',
                ayudaEspecifica: 'Este valor se utiliza para validar el monto máximo de referencia.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_libre_inversion: {
                plazo: 'Hasta 72 meses',
                ayudaMonto: 'El monto está sujeto a capacidad de pago, endeudamiento y garantías.',
                labelEspecifico: 'Información para capacidad de pago',
                ayudaEspecifica: 'La metodología exacta de capacidad de pago está pendiente de confirmación.'
            },

            credito_compra_cartera: {
                plazo: 'Hasta 60 meses',
                ayudaMonto: 'El monto depende de capacidad de pago, endeudamiento y condiciones de la obligación.',
                labelEspecifico: 'Saldo de la obligación a comprar',
                ayudaEspecifica: 'El giro se realiza directamente a la entidad acreedora.'
            },

            credito_vehiculo: {
                plazo: 'Hasta 72 meses',
                ayudaMonto: 'El porcentaje financiable depende de si el vehículo es nuevo o usado y de las condiciones de la línea.',
                labelEspecifico: 'Valor comercial o asegurable del vehículo',
                ayudaEspecifica: 'Referencia: vehículo usado hasta 80 % y nuevo hasta 100 %, sujeto a capacidad de pago y garantías.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_motocicleta: {
                plazo: 'Hasta 72 meses',
                ayudaMonto: 'El monto está sujeto a capacidad de pago y a las condiciones de la línea.',
                labelEspecifico: 'Valor de la motocicleta',
                ayudaEspecifica: 'La referencia disponible contempla hasta el 100 % para motocicleta nueva, sujeto a capacidad y garantía.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_tecnologia: {
                plazo: 'Pendiente de confirmación',
                ayudaMonto: 'La página de FONCALDAS muestra una tasa de referencia, pero faltan reglas completas de cálculo.',
                labelEspecifico: 'Valor del artículo tecnológico',
                ayudaEspecifica: 'Pendiente de confirmar límites, plazo y demás condiciones.'
            }
        };

        selectorCredito.addEventListener('change', function() {

            const opcionSeleccionada = this.options[this.selectedIndex];
            const tasa = opcionSeleccionada.dataset.tasa;
            const unidad = opcionSeleccionada.dataset.unidad;
            const configuracion = configuracionProductos[this.value];

            montoSolicitado.value = '';
            plazoCredito.value = '';
            datoEspecifico.value = '';
            montoSolicitado.disabled = true;
            datoEspecifico.disabled = true;
            ayudaMonto.classList.remove('text-red-500');

            if (this.value !== '') {

                nombreProducto.textContent = opcionSeleccionada.text;
                descripcionProducto.classList.remove('hidden');

                if (tasa) {

                    const tasaFormateada = parseFloat(tasa).toString();

                    tasaProducto.textContent =
                        'Tasa de referencia: ' +
                        tasaFormateada +
                        ' ' +
                        (unidad || '');

                } else {

                    tasaProducto.textContent =
                        'Tasa pendiente de confirmación.';

                }

                labelMonto.textContent = 'Monto solicitado';
                montoSolicitado.placeholder =
                    'Pendiente de habilitación del cálculo';

                if (configuracion) {

                    labelPlazo.textContent = 'Plazo';
                    plazoCredito.placeholder = configuracion.plazo;

                    ayudaMonto.textContent =
                        configuracion.ayudaMonto;

                    ayudaPlazo.textContent =
                        'Condición de referencia para esta línea.';

                    labelEspecifico.textContent =
                        configuracion.labelEspecifico;

                    datoEspecifico.placeholder =
                        'Dato requerido para la línea';

                    ayudaEspecifica.textContent =
                        configuracion.ayudaEspecifica;

                    if (configuracion.habilitarMonto) {
                        montoSolicitado.disabled = false;
                    }

                    if (configuracion.habilitarEspecifico) {
                        datoEspecifico.disabled = false;
                    }

                } else {

                    plazoCredito.placeholder =
                        'Pendiente de confirmación';

                    labelEspecifico.textContent =
                        'Información adicional';

                    datoEspecifico.placeholder =
                        'Pendiente de confirmación';

                    ayudaMonto.textContent = '';
                    ayudaPlazo.textContent = '';

                    ayudaEspecifica.textContent =
                        'Las condiciones específicas están pendientes de confirmación.';

                }

            } else {

                nombreProducto.textContent = '';
                tasaProducto.textContent = '';
                descripcionProducto.classList.add('hidden');

                labelMonto.textContent = 'Monto solicitado';
                labelPlazo.textContent = 'Plazo';
                labelEspecifico.textContent = 'Información adicional';

                montoSolicitado.placeholder =
                    'Selecciona una línea de crédito';

                plazoCredito.placeholder =
                    'Selecciona una línea de crédito';

                datoEspecifico.placeholder =
                    'Selecciona una línea de crédito';

                ayudaMonto.textContent = '';
                ayudaPlazo.textContent = '';
                ayudaEspecifica.textContent = '';

            }

        });

        function validarAvances() {
            if (selectorCredito.value !== 'credito_avances') {
                ayudaMonto.classList.remove('text-red-500');
                return;
            }

            const monto = parseFloat(montoSolicitado.value);
            const sueldoBasico = parseFloat(datoEspecifico.value);

            if (isNaN(monto) || isNaN(sueldoBasico)) {
                ayudaMonto.textContent =
                    'El monto solicitado no debe superar el 50 % del sueldo básico.';
                ayudaMonto.classList.remove('text-red-500');
                return;
            }

            const montoMaximo = sueldoBasico * 0.5;

            if (monto > montoMaximo) {
                ayudaMonto.textContent =
                    'El monto solicitado supera el 50 % del sueldo básico permitido para esta línea.';
                ayudaMonto.classList.add('text-red-500');
            } else {
                ayudaMonto.textContent =
                    'El monto se encuentra dentro del límite de referencia permitido.';
                ayudaMonto.classList.remove('text-red-500');
            }
        }

        montoSolicitado.addEventListener('input', validarAvances);
        datoEspecifico.addEventListener('input', validarAvances);

        function validarBonificacion() {
            if (selectorCredito.value !== 'credito_bonificacion') {
                return;
            }

            const monto = parseFloat(montoSolicitado.value);
            const valorElegible = parseFloat(datoEspecifico.value);

            if (isNaN(monto) || isNaN(valorElegible)) {
                ayudaMonto.textContent =
                    'El monto solicitado no debe superar el 90 % del valor elegible recibido el año anterior.';

                ayudaMonto.classList.remove('text-red-500');
                return;
            }

            const montoMaximo = valorElegible * 0.90;

            if (monto > montoMaximo) {
                ayudaMonto.textContent =
                    'El monto solicitado supera el 90 % del valor elegible permitido para esta línea.';

                ayudaMonto.classList.add('text-red-500');
            } else {
                ayudaMonto.textContent =
                    'El monto se encuentra dentro del límite de referencia permitido.';

                ayudaMonto.classList.remove('text-red-500');
            }
        }

        montoSolicitado.addEventListener('input', validarBonificacion);
        datoEspecifico.addEventListener('input', validarBonificacion);

        function validarCuotaUnica() {
            const esCuotaUnica =
                selectorCredito.value === 'credito_cuota_unica_junio' ||
                selectorCredito.value === 'credito_cuota_unica_diciembre';

            if (!esCuotaUnica) {
                return;
            }

            const monto = parseFloat(montoSolicitado.value);
            const ingresoBasico = parseFloat(datoEspecifico.value);

            if (isNaN(monto) || isNaN(ingresoBasico)) {
                ayudaMonto.textContent =
                    'El monto solicitado no debe superar el 100 % del ingreso básico mensual.';
                return;
            }

            if (monto > ingresoBasico) {
                ayudaMonto.textContent =
                    'El monto solicitado supera el ingreso básico mensual permitido como referencia.';
                ayudaMonto.classList.add('text-red-500');
            } else {
                ayudaMonto.textContent =
                    'El monto se encuentra dentro del límite de referencia permitido.';
                ayudaMonto.classList.remove('text-red-500');
            }
        }

        montoSolicitado.addEventListener('input', validarCuotaUnica);
        datoEspecifico.addEventListener('input', validarCuotaUnica);
    </script>
@endsection
