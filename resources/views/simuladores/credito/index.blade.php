@extends('layouts.app')

@section('content')
    <div class="min-h-screen page-bg py-14">

        <div class="max-w-6xl mx-auto px-6">

            <x-page-header icon="card" title="Simulación de crédito"
                subtitle="Estimación orientativa. Las condiciones serán confirmadas por FONCALDAS." />

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-end">

                    <!-- COLUMNA IZQUIERDA -->
                    <div class="lg:col-span-2 space-y-5">

                        <div>
                            <label for="producto_credito"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Selecciona un producto
                            </label>

                            <select id="producto_credito" name="producto_credito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-white dark:bg-slate-900
                                   text-slate-800 dark:text-slate-100
                                   px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">
                                <option value="">Línea de crédito</option>

                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->codigo }}" data-tasa="{{ $producto->tasaVigente?->valor }}"
                                        data-unidad="{{ $producto->tasaVigente?->unidad }}"
                                        data-simulable="{{ $producto->simulable ? '1' : '0' }}">
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- INFORMACIÓN DEL PRODUCTO -->
                        <div id="descripcion-producto"
                            class="hidden mt-4 rounded-xl border border-slate-200 dark:border-slate-700
                               bg-slate-50 dark:bg-slate-900/40 px-4 py-3">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-semibold text-slate-700 dark:text-slate-200">
                                    Producto seleccionado:
                                </span>

                                <span id="nombre-producto"></span>
                            </p>

                            <p id="tasa-producto" class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            </p>

                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Las condiciones específicas de esta línea están pendientes de confirmación por FONCALDAS.
                            </p>


                        </div>

                        <div id="producto-informativo"
                            class="hidden mt-4 rounded-xl border border-sky-200 dark:border-sky-800
                                 bg-sky-50 dark:bg-sky-950/30 px-5 py-4">

                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                Producto informativo
                            </p>

                            <p id="texto-producto-informativo" class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                Este producto corresponde a una modalidad de pago único y no requiere simulación financiera.
                                Sus condiciones se presentan únicamente con carácter informativo.
                            </p>
                        </div>

                        <!-- MONTO -->
                        <div id="campo-monto">

                            <label id="label-monto"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Monto solicitado
                            </label>

                            <input id="monto-solicitado" type="number" disabled
                                placeholder="Selecciona una línea de crédito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                   bg-slate-100 dark:bg-slate-900
                                   text-slate-500 px-4 py-3">

                            <p id="ayuda-monto" class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                        <!-- PLAZO -->
                        <div id="campo-plazo">

                            <label id="label-plazo"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Plazo
                            </label>

                            <div
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                bg-slate-50 dark:bg-slate-900/40
                                px-4 py-3">
                                <span id="texto-plazo" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                    Selecciona una línea de crédito
                                </span>
                            </div>

                            <p id="ayuda-plazo" class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                        <!-- TIPO DE BIEN -->
                        <div id="campo-tipo-bien" class="hidden">

                            <label for="tipo-bien"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Estado del vehículo o motocicleta
                            </label>

                            <select id="tipo-bien"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
               bg-white dark:bg-slate-900
               text-slate-800 dark:text-slate-100
               px-4 py-3
               focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                                <option value="">Selecciona una opción</option>
                                <option value="nuevo">Nuevo</option>
                                <option value="usado">Usado</option>
                            </select>

                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Las condiciones y porcentajes aplicables a nuevo y usado están pendientes de confirmación
                                final por FONCALDAS.
                            </p>

                        </div>


                        <!-- DATO ESPECÍFICO -->
                        <div id="campo-especifico">

                            <label id="label-especifico"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                Información adicional
                            </label>

                            <input id="dato-especifico" type="text" disabled
                                placeholder="Selecciona una línea de crédito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                    bg-slate-100 dark:bg-slate-900
                                    text-slate-500 px-4 py-3">

                            <p id="ayuda-especifica" class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            </p>

                        </div>

                    </div>

                    <!-- COLUMNA DERECHA -->
                    <div class="flex flex-col justify-end">

                        <button id="boton-simular" type="button" disabled
                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl
                                bg-[#0B4870]/60
                                text-white/70
                                font-semibold cursor-not-allowed">
                            Simular
                        </button>

                        <p class="text-sm text-center text-slate-500 dark:text-slate-400 mt-3">
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
        const textoPlazo = document.getElementById('texto-plazo');
        const datoEspecifico = document.getElementById('dato-especifico');

        const campoMonto = document.getElementById('campo-monto');
        const campoPlazo = document.getElementById('campo-plazo');
        const campoEspecifico = document.getElementById('campo-especifico');

        const botonSimular = document.getElementById('boton-simular');

        const productoInformativo = document.getElementById('producto-informativo');
        const textoProductoInformativo = document.getElementById('texto-producto-informativo');

        const labelMonto = document.getElementById('label-monto');
        const labelPlazo = document.getElementById('label-plazo');
        const labelEspecifico = document.getElementById('label-especifico');

        const ayudaMonto = document.getElementById('ayuda-monto');
        const ayudaPlazo = document.getElementById('ayuda-plazo');
        const ayudaEspecifica = document.getElementById('ayuda-especifica');

        const campoTipoBien = document.getElementById('campo-tipo-bien');
        const tipoBien = document.getElementById('tipo-bien');

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
                ayudaMonto: 'El monto está sujeto a las condiciones y al estudio de crédito de FONCALDAS.',
                labelEspecifico: 'Antigüedad de afiliación (años)',
                ayudaEspecifica: 'La tasa depende de la antigüedad de afiliación. Para más de 15 años se informó una tasa de referencia del 1,35 %. Los demás rangos están pendientes de confirmación.',
                habilitarMonto: true,
                habilitarEspecifico: true,
                tipoEspecifico: 'number',
                placeholderEspecifico: 'Ej. 16'
            },

            credito_compra_cartera: {
                plazo: 'Hasta 60 meses',
                ayudaMonto: 'El monto depende de capacidad de pago, endeudamiento y condiciones de la obligación.',
                labelEspecifico: 'Saldo de la obligación a comprar',
                ayudaEspecifica: 'El giro se realiza directamente a la entidad acreedora.'
            },

            credito_vehiculo: {
                plazo: 'Pendiente de confirmación final',
                ayudaMonto: 'El monto financiable depende de las condiciones aplicables a vehículo nuevo o usado.',
                labelEspecifico: 'Valor comercial o asegurable del vehículo',
                ayudaEspecifica: 'Las condiciones de financiación para vehículo nuevo o usado están pendientes de confirmación final por FONCALDAS.',
                habilitarMonto: true,
                habilitarEspecifico: true,
                tipoEspecifico: 'number',
                placeholderEspecifico: 'Ingresa el valor del vehículo'
            },

            credito_motocicleta: {
                plazo: 'Pendiente de confirmación final',
                ayudaMonto: 'El monto financiable depende de las condiciones aplicables a motocicleta nueva o usada.',
                labelEspecifico: 'Valor comercial o asegurable de la motocicleta',
                ayudaEspecifica: 'Motocicleta comparte las condiciones generales de Vehículo. Los valores definitivos están pendientes de confirmación por FONCALDAS.',
                habilitarMonto: true,
                habilitarEspecifico: true,
                tipoEspecifico: 'number',
                placeholderEspecifico: 'Ingresa el valor de la motocicleta'
            },

            credito_tecnologia: {
                plazo: 'Hasta 48 meses',
                ayudaMonto: 'El monto máximo se encuentra pendiente de confirmación por FONCALDAS.',
                labelEspecifico: 'Valor de la cotización',
                ayudaEspecifica: 'Esta línea requiere cotización. El monto máximo exacto está pendiente de confirmación.',
                habilitarMonto: true,
                habilitarEspecifico: true
            },

            credito_turismo: {
                plazo: 'Hasta 48 meses',
                ayudaMonto: 'El monto máximo se encuentra pendiente de confirmación por FONCALDAS.',
                labelEspecifico: 'Valor de la cotización',
                ayudaEspecifica: 'Las condiciones complementarias de esta línea están pendientes de confirmación por FONCALDAS.',
                habilitarMonto: true,
                habilitarEspecifico: true
            }
        };

        selectorCredito.addEventListener('change', function() {

            const opcionSeleccionada = this.options[this.selectedIndex];
            const tasa = opcionSeleccionada.dataset.tasa;
            const unidad = opcionSeleccionada.dataset.unidad;
            const simulable = opcionSeleccionada.dataset.simulable === '1';
            const configuracion = configuracionProductos[this.value];

            const requiereTipoBien =
                this.value === 'credito_vehiculo' ||
                this.value === 'credito_motocicleta';

            montoSolicitado.value = '';
            datoEspecifico.value = '';
            montoSolicitado.disabled = true;
            datoEspecifico.disabled = true;
            ayudaMonto.classList.remove(
                'text-red-500',
                'dark:text-red-400'
            );

            ayudaMonto.classList.add(
                'text-slate-500',
                'dark:text-slate-400'
            );

            if (this.value !== '') {

                nombreProducto.textContent = opcionSeleccionada.text;
                descripcionProducto.classList.remove('hidden');

                if (!simulable) {
                    productoInformativo.classList.remove('hidden');

                    campoMonto.classList.add('hidden');
                    campoPlazo.classList.add('hidden');
                    campoEspecifico.classList.add('hidden');

                    campoTipoBien.classList.add('hidden');
                    tipoBien.value = '';

                    botonSimular.classList.add('hidden');

                    tasaProducto.textContent = '';

                    return;
                }

                productoInformativo.classList.add('hidden');

                campoMonto.classList.remove('hidden');
                campoPlazo.classList.remove('hidden');
                campoEspecifico.classList.remove('hidden');

                botonSimular.classList.remove('hidden');

                if (requiereTipoBien) {
                    campoTipoBien.classList.remove('hidden');
                } else {
                    campoTipoBien.classList.add('hidden');
                    tipoBien.value = '';
                }

                if (this.value === 'credito_libre_inversion') {

                    tasaProducto.textContent =
                        'La tasa depende de la antigüedad de afiliación. Para asociados con más de 15 años se informó una tasa de referencia del 1,35 %. Los demás rangos están pendientes de confirmación.';

                } else if (tasa) {

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
                    textoPlazo.textContent = configuracion.plazo;

                    ayudaMonto.textContent =
                        configuracion.ayudaMonto;

                    ayudaPlazo.textContent =
                        'Condición de referencia para esta línea.';

                    labelEspecifico.textContent =
                        configuracion.labelEspecifico;

                    datoEspecifico.type =
                        configuracion.tipoEspecifico || 'text';

                    datoEspecifico.placeholder =
                        configuracion.placeholderEspecifico || 'Dato requerido para la línea';

                    ayudaEspecifica.textContent =
                        configuracion.ayudaEspecifica;

                    if (configuracion.tipoEspecifico === 'number') {
                        datoEspecifico.min = '0';
                        datoEspecifico.step = '1';
                    } else {
                        datoEspecifico.removeAttribute('min');
                        datoEspecifico.removeAttribute('step');
                    }

                    if (configuracion.habilitarMonto) {
                        montoSolicitado.disabled = false;
                    }

                    if (configuracion.habilitarEspecifico) {
                        datoEspecifico.disabled = false;
                    }

                } else {

                    textoPlazo.textContent =
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

                productoInformativo.classList.add('hidden');

                campoMonto.classList.remove('hidden');
                campoPlazo.classList.remove('hidden');
                campoEspecifico.classList.remove('hidden');

                botonSimular.classList.remove('hidden');

                campoTipoBien.classList.add('hidden');
                tipoBien.value = '';

                labelMonto.textContent = 'Monto solicitado';
                labelPlazo.textContent = 'Plazo';
                labelEspecifico.textContent = 'Información adicional';

                datoEspecifico.type = 'text';
                datoEspecifico.removeAttribute('min');
                datoEspecifico.removeAttribute('step');

                montoSolicitado.placeholder =
                    'Selecciona una línea de crédito';

                textoPlazo.textContent =
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
                return;
            }

            const monto = parseFloat(montoSolicitado.value);
            const sueldoBasico = parseFloat(datoEspecifico.value);

            if (isNaN(monto) || isNaN(sueldoBasico)) {
                mostrarAyudaMonto(
                    'El monto solicitado no debe superar el 50 % del sueldo básico.'
                );
                return;
            }

            const montoMaximo = sueldoBasico * 0.5;

            if (monto > montoMaximo) {
                mostrarErrorMonto(
                    'El monto solicitado supera el 50 % del sueldo básico permitido para esta línea.'
                );
            } else {
                mostrarAyudaMonto(
                    'El monto se encuentra dentro del límite de referencia permitido.'
                );
            }
        }

        montoSolicitado.addEventListener('input', validarAvances);
        datoEspecifico.addEventListener('input', validarAvances);

        function mostrarErrorMonto(mensaje) {
            ayudaMonto.textContent = mensaje;

            ayudaMonto.classList.remove(
                'text-slate-500',
                'dark:text-slate-400'
            );

            ayudaMonto.classList.add(
                'text-red-500',
                'dark:text-red-400'
            );
        }

        function mostrarAyudaMonto(mensaje) {
            ayudaMonto.textContent = mensaje;

            ayudaMonto.classList.remove(
                'text-red-500',
                'dark:text-red-400'
            );

            ayudaMonto.classList.add(
                'text-slate-500',
                'dark:text-slate-400'
            );
        }
    </script>
@endsection
