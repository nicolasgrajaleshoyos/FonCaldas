@extends('layouts.app')

@section('content')

    <div class="min-h-screen page-bg py-14">

        <div class="max-w-6xl mx-auto px-6">


            <x-page-header
                icon="card"
                title="Simulación de crédito"
                subtitle="Realiza una estimación con las condiciones configuradas para las líneas de crédito de FONCALDAS." />


            <div
                class="bg-white dark:bg-slate-800
                    rounded-3xl shadow-sm
                    border border-slate-100
                    dark:border-slate-700
                    p-8">


                @if ($errors->any())

                    <div
                        class="mb-6 rounded-2xl
                            border border-red-200
                            dark:border-red-900
                            bg-red-50
                            dark:bg-red-950/30
                            px-5 py-4">

                        <p
                            class="font-semibold
                                text-red-700
                                dark:text-red-300">

                            Revisa la información ingresada:
                        </p>


                        <ul
                            class="mt-2 list-disc list-inside
                                text-sm
                                text-red-600
                                dark:text-red-300">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('simuladores.credito.simular') }}"
                    id="form-simulador-credito">

                    @csrf


                    <div
                        class="grid grid-cols-1
                            lg:grid-cols-3
                            gap-8 items-end">


                        <div
                            class="lg:col-span-2
                                space-y-5">


                            {{-- PRODUCTO --}}
                            <div>

                                <label
                                    for="producto_credito"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Selecciona un producto
                                    <span class="text-red-500">*</span>
                                </label>


                                <select
                                    id="producto_credito"
                                    name="producto"
                                    required
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-white
                                        dark:bg-slate-900
                                        text-slate-800
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">

                                    <option value="">
                                        Línea de crédito
                                    </option>


                                    @foreach ($productos as $producto)

                                        <option
                                            value="{{ $producto->codigo }}"
                                            data-simulable="{{ $producto->simulable ? '1' : '0' }}"
                                            data-parametros='@json($producto->parametros_simulador)'
                                            @selected(
                                                old('producto') ===
                                                $producto->codigo
                                            )>

                                            {{ $producto->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- INFORMACIÓN DEL PRODUCTO --}}
                            <div
                                id="descripcion-producto"
                                class="hidden mt-4
                                    rounded-xl
                                    border border-slate-200
                                    dark:border-slate-700
                                    bg-slate-50
                                    dark:bg-slate-900/40
                                    px-4 py-3">

                                <p
                                    class="text-sm
                                        text-slate-600
                                        dark:text-slate-400">

                                    <span
                                        class="font-semibold
                                            text-slate-700
                                            dark:text-slate-200">

                                        Producto seleccionado:
                                    </span>

                                    <span
                                        id="nombre-producto">
                                    </span>

                                </p>


                                <p
                                    id="tasa-producto"
                                    class="mt-2 text-sm
                                        text-slate-600
                                        dark:text-slate-300">
                                </p>


                                <p
                                    class="mt-1 text-sm
                                        text-slate-500
                                        dark:text-slate-400">

                                    La simulación utiliza los parámetros
                                    financieros vigentes configurados
                                    para esta línea.

                                </p>

                            </div>


                            {{-- PRODUCTO INFORMATIVO --}}
                            <div
                                id="producto-informativo"
                                class="hidden mt-4
                                    rounded-xl
                                    border border-sky-200
                                    dark:border-sky-800
                                    bg-sky-50
                                    dark:bg-sky-950/30
                                    px-5 py-4">

                                <p
                                    class="text-sm
                                        font-semibold
                                        text-slate-800
                                        dark:text-slate-100">

                                    Producto informativo
                                </p>


                                <p
                                    id="texto-producto-informativo"
                                    class="mt-2 text-sm
                                        text-slate-600
                                        dark:text-slate-300">

                                    Consulta las condiciones
                                    de esta línea directamente
                                    con el área de crédito
                                    de FONCALDAS.

                                </p>

                            </div>


                            {{-- MONTO --}}
                            <div id="campo-monto">

                                <label
                                    for="monto-solicitado"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Monto solicitado
                                    <span
                                        id="asterisco-monto"
                                        class="text-red-500">
                                        *
                                    </span>
                                </label>


                                <input
                                    id="monto-solicitado"
                                    name="monto"
                                    type="number"
                                    min="1"
                                    step="1"
                                    disabled
                                    value="{{ old('monto') }}"
                                    placeholder="Selecciona una línea de crédito"
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-slate-100
                                        dark:bg-slate-900
                                        text-slate-700
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">


                                <p
                                    id="ayuda-monto"
                                    class="mt-2 text-xs
                                        text-slate-500
                                        dark:text-slate-400">
                                </p>

                            </div>


                            {{-- PLAZO --}}
                            <div id="campo-plazo">

                                <label
                                    for="plazo"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Plazo en meses
                                    <span class="text-red-500">*</span>
                                </label>


                                <input
                                    id="plazo"
                                    name="plazo"
                                    type="number"
                                    min="1"
                                    step="1"
                                    disabled
                                    value="{{ old('plazo') }}"
                                    placeholder="Selecciona una línea de crédito"
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-slate-100
                                        dark:bg-slate-900
                                        text-slate-700
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">


                                <p
                                    id="ayuda-plazo"
                                    class="mt-2 text-xs
                                        text-slate-500
                                        dark:text-slate-400">
                                </p>

                            </div>


                            {{-- TIPO DE BIEN --}}
                            <div
                                id="campo-tipo-bien"
                                class="hidden">

                                <label
                                    for="tipo-bien"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Estado del vehículo
                                    o motocicleta
                                    <span class="text-red-500">*</span>
                                </label>


                                <select
                                    id="tipo-bien"
                                    name="tipo_bien"
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-white
                                        dark:bg-slate-900
                                        text-slate-800
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">

                                    <option value="">
                                        Selecciona una opción
                                    </option>


                                    <option
                                        value="nuevo"
                                        @selected(
                                            old('tipo_bien') ===
                                            'nuevo'
                                        )>

                                        Nuevo
                                    </option>


                                    <option
                                        value="usado"
                                        @selected(
                                            old('tipo_bien') ===
                                            'usado'
                                        )>

                                        Usado
                                    </option>

                                </select>


                                <p
                                    id="ayuda-tipo-bien"
                                    class="mt-2 text-xs
                                        text-slate-500
                                        dark:text-slate-400">

                                    El porcentaje máximo financiable
                                    depende de si el bien es nuevo
                                    o usado.

                                </p>

                            </div>


                            {{-- ANTIGÜEDAD DEL BIEN --}}
                            <div
                                id="campo-antiguedad-bien"
                                class="hidden">

                                <label
                                    for="antiguedad-bien"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Antigüedad del vehículo
                                    o motocicleta
                                    <span class="text-red-500">*</span>
                                </label>


                                <input
                                    id="antiguedad-bien"
                                    name="antiguedad_bien"
                                    type="number"
                                    min="0"
                                    step="1"
                                    value="{{ old('antiguedad_bien') }}"
                                    placeholder="Ej. 5"
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-white
                                        dark:bg-slate-900
                                        text-slate-700
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">


                                <p
                                    id="ayuda-antiguedad-bien"
                                    class="mt-2 text-xs
                                        text-slate-500
                                        dark:text-slate-400">
                                </p>

                            </div>


                            {{-- DATO ESPECÍFICO --}}
                            <div id="campo-especifico">

                                <label
                                    id="label-especifico"
                                    for="dato-especifico"
                                    class="block text-sm
                                        font-semibold
                                        text-slate-700
                                        dark:text-slate-200
                                        mb-2">

                                    Información adicional

                                    <span
                                        id="asterisco-especifico"
                                        class="text-red-500">
                                        *
                                    </span>
                                </label>


                                <input
                                    id="dato-especifico"
                                    name="dato_especifico"
                                    type="text"
                                    disabled
                                    value="{{ old('dato_especifico') }}"
                                    placeholder="Selecciona una línea de crédito"
                                    class="w-full rounded-xl
                                        border border-slate-300
                                        dark:border-slate-600
                                        bg-slate-100
                                        dark:bg-slate-900
                                        text-slate-700
                                        dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-[#0C67A3]">


                                <p
                                    id="ayuda-especifica"
                                    class="mt-2 text-xs
                                        text-slate-500
                                        dark:text-slate-400">
                                </p>

                            </div>

                        </div>


                        {{-- BOTÓN --}}
                        <div
                            class="flex flex-col
                                justify-end">

                            <button
                                id="boton-simular"
                                type="submit"
                                disabled
                                class="w-full inline-flex
                                    items-center justify-center
                                    px-6 py-3 rounded-xl
                                    bg-[#0B4870]/60
                                    text-white/70
                                    font-semibold
                                    cursor-not-allowed">

                                Simular
                            </button>


                            <p
                                class="mt-3 text-sm
                                    text-center
                                    text-slate-500
                                    dark:text-slate-400">

                                Los campos varían
                                según el producto.
                            </p>

                        </div>

                    </div>

                </form>

            </div>


            <p
                class="mt-4 text-center
                    text-xs
                    text-slate-500
                    dark:text-slate-400">

                Simulación informativa.
                Los resultados están sujetos
                a las condiciones, políticas
                y validaciones vigentes
                de FONCALDAS.

            </p>

        </div>

    </div>


    <script>

        const selectorCredito =
            document.getElementById(
                'producto_credito'
            );

        const descripcionProducto =
            document.getElementById(
                'descripcion-producto'
            );

        const nombreProducto =
            document.getElementById(
                'nombre-producto'
            );

        const tasaProducto =
            document.getElementById(
                'tasa-producto'
            );

        const montoSolicitado =
            document.getElementById(
                'monto-solicitado'
            );

        const plazoInput =
            document.getElementById(
                'plazo'
            );

        const datoEspecifico =
            document.getElementById(
                'dato-especifico'
            );

        const campoMonto =
            document.getElementById(
                'campo-monto'
            );

        const campoPlazo =
            document.getElementById(
                'campo-plazo'
            );

        const campoEspecifico =
            document.getElementById(
                'campo-especifico'
            );

        const botonSimular =
            document.getElementById(
                'boton-simular'
            );

        const productoInformativo =
            document.getElementById(
                'producto-informativo'
            );

        const textoProductoInformativo =
            document.getElementById(
                'texto-producto-informativo'
            );

        const labelEspecifico =
            document.getElementById(
                'label-especifico'
            );

        const ayudaMonto =
            document.getElementById(
                'ayuda-monto'
            );

        const ayudaPlazo =
            document.getElementById(
                'ayuda-plazo'
            );

        const ayudaEspecifica =
            document.getElementById(
                'ayuda-especifica'
            );

        const campoTipoBien =
            document.getElementById(
                'campo-tipo-bien'
            );

        const tipoBien =
            document.getElementById(
                'tipo-bien'
            );

        const campoAntiguedadBien =
            document.getElementById(
                'campo-antiguedad-bien'
            );

        const antiguedadBien =
            document.getElementById(
                'antiguedad-bien'
            );

        const ayudaAntiguedadBien =
            document.getElementById(
                'ayuda-antiguedad-bien'
            );


        const configuracionProductos = {

            credito_avances: {

                ayudaMonto:
                    'El monto está sujeto al porcentaje máximo configurado sobre el sueldo básico.',

                labelEspecifico:
                    'Sueldo básico del asociado',

                ayudaEspecifica:
                    'Este valor se utiliza para validar el monto máximo permitido.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el sueldo básico'
            },


            credito_libre_inversion: {

                ayudaMonto:
                    'El monto está sujeto a las condiciones y al estudio de crédito de FONCALDAS.',

                labelEspecifico:
                    'Antigüedad de afiliación (años)',

                ayudaEspecifica:
                    'La tasa se determina según la antigüedad de afiliación.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ej. 16'
            },


            credito_compra_cartera: {

                ayudaMonto:
                    'El monto depende de capacidad de pago, endeudamiento y condiciones de la obligación.',

                labelEspecifico:
                    'Saldo de la obligación a comprar',

                ayudaEspecifica:
                    'El giro se realiza directamente a la entidad acreedora.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el saldo de la obligación'
            },


            credito_vehiculo: {

                ayudaMonto:
                    'El monto financiable depende del porcentaje permitido sobre el valor comercial o asegurable.',

                labelEspecifico:
                    'Valor comercial o asegurable del vehículo',

                ayudaEspecifica:
                    'Este valor se utiliza para calcular el máximo financiable.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el valor del vehículo'
            },


            credito_motocicleta: {

                ayudaMonto:
                    'El monto financiable depende del porcentaje permitido sobre el valor comercial o asegurable.',

                labelEspecifico:
                    'Valor comercial o asegurable de la motocicleta',

                ayudaEspecifica:
                    'Este valor se utiliza para calcular el máximo financiable.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el valor de la motocicleta'
            },


            credito_tecnologia: {

                ayudaMonto:
                    'El monto está sujeto al límite configurado para esta línea.',

                labelEspecifico:
                    'Valor de la cotización',

                ayudaEspecifica:
                    'La cotización es requerida para esta línea.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el valor de la cotización'
            },


            credito_turismo: {

                ayudaMonto:
                    'El monto está sujeto al límite configurado para esta línea.',

                labelEspecifico:
                    'Valor de la cotización',

                ayudaEspecifica:
                    'La cotización es requerida para esta línea.',

                tipoEspecifico:
                    'number',

                placeholderEspecifico:
                    'Ingresa el valor de la cotización'
            }
        };


        function obtenerParametro(
            parametros,
            codigo
        ) {

            if (
                !parametros ||
                !parametros[codigo]
            ) {
                return null;
            }


            const parametro =
                parametros[codigo];


            if (
                parametro.tipo_dato ===
                    'decimal' ||
                parametro.tipo_dato ===
                    'entero'
            ) {

                return parametro.valor;
            }


            return parametro.valor_texto;
        }


        function obtenerParametrosSeleccionados()
        {
            const opcion =
                selectorCredito.options[
                    selectorCredito.selectedIndex
                ];


            if (
                !opcion ||
                !opcion.dataset.parametros
            ) {
                return {};
            }


            return JSON.parse(
                opcion.dataset.parametros
            );
        }


        function configurarProducto()
        {
            const opcionSeleccionada =
                selectorCredito.options[
                    selectorCredito.selectedIndex
                ];


            const codigoProducto =
                selectorCredito.value;


            const simulable =
                opcionSeleccionada
                    ?.dataset
                    .simulable === '1';


            const parametros =
                obtenerParametrosSeleccionados();


            const configuracion =
                configuracionProductos[
                    codigoProducto
                ];


            const requiereTipoBien =
                codigoProducto ===
                    'credito_vehiculo' ||
                codigoProducto ===
                    'credito_motocicleta';


            montoSolicitado.disabled =
                true;

            plazoInput.disabled =
                true;

            datoEspecifico.disabled =
                true;

            tipoBien.disabled =
                true;

            antiguedadBien.disabled =
                true;


            montoSolicitado.required =
                false;

            plazoInput.required =
                false;

            datoEspecifico.required =
                false;

            tipoBien.required =
                false;

            antiguedadBien.required =
                false;


            botonSimular.disabled =
                true;


            botonSimular.className =
                'w-full inline-flex items-center justify-center ' +
                'px-6 py-3 rounded-xl ' +
                'bg-[#0B4870]/60 text-white/70 ' +
                'font-semibold cursor-not-allowed';


            campoAntiguedadBien
                .classList
                .add('hidden');


            if (!codigoProducto) {

                descripcionProducto
                    .classList
                    .add('hidden');

                productoInformativo
                    .classList
                    .add('hidden');

                campoMonto
                    .classList
                    .remove('hidden');

                campoPlazo
                    .classList
                    .remove('hidden');

                campoEspecifico
                    .classList
                    .remove('hidden');

                campoTipoBien
                    .classList
                    .add('hidden');


                tasaProducto.textContent =
                    '';

                ayudaMonto.textContent =
                    '';

                ayudaPlazo.textContent =
                    '';

                ayudaEspecifica.textContent =
                    '';


                return;
            }


            nombreProducto.textContent =
                opcionSeleccionada
                    .text
                    .trim();


            descripcionProducto
                .classList
                .remove('hidden');


            if (!simulable) {

                productoInformativo
                    .classList
                    .remove('hidden');

                campoMonto
                    .classList
                    .add('hidden');

                campoPlazo
                    .classList
                    .add('hidden');

                campoEspecifico
                    .classList
                    .add('hidden');

                campoTipoBien
                    .classList
                    .add('hidden');

                campoAntiguedadBien
                    .classList
                    .add('hidden');

                botonSimular
                    .classList
                    .add('hidden');


                tasaProducto.textContent =
                    '';


                textoProductoInformativo
                    .textContent =
                    'Consulta las condiciones de esta línea directamente con el área de crédito de FONCALDAS.';


                return;
            }


            productoInformativo
                .classList
                .add('hidden');

            campoMonto
                .classList
                .remove('hidden');

            campoPlazo
                .classList
                .remove('hidden');

            botonSimular
                .classList
                .remove('hidden');


            montoSolicitado.disabled =
                false;

            plazoInput.disabled =
                false;

            montoSolicitado.required =
                true;

            plazoInput.required =
                true;


            if (requiereTipoBien) {

                campoTipoBien
                    .classList
                    .remove('hidden');

                tipoBien.disabled =
                    false;

                tipoBien.required =
                    true;

            } else {

                campoTipoBien
                    .classList
                    .add('hidden');

                tipoBien.disabled =
                    true;

                tipoBien.required =
                    false;
            }


            const tasaGeneral =
                obtenerParametro(
                    parametros,
                    'tasa_interes'
                );


            const unidadTasa =
                parametros
                    .tasa_interes
                    ?.unidad || '';


            if (
                codigoProducto ===
                'credito_libre_inversion'
            ) {

                tasaProducto.textContent =
                    'La tasa se determina según la antigüedad de afiliación.';

            } else if (
                tasaGeneral !== null
            ) {

                tasaProducto.textContent =
                    'Tasa configurada: ' +
                    parseFloat(
                        tasaGeneral
                    ).toString() +
                    ' ' +
                    unidadTasa;

            } else {

                tasaProducto.textContent =
                    'Tasa pendiente de configuración.';
            }


            const montoMinimo =
                obtenerParametro(
                    parametros,
                    'monto_minimo'
                );


            const montoMaximo =
                obtenerParametro(
                    parametros,
                    'monto_maximo'
                );


            montoSolicitado.min =
                montoMinimo !== null
                    ? montoMinimo
                    : 1;


            if (
                montoMaximo !== null
            ) {

                montoSolicitado.max =
                    montoMaximo;

            } else {

                montoSolicitado
                    .removeAttribute(
                        'max'
                    );
            }


            const plazoMinimo =
                obtenerParametro(
                    parametros,
                    'plazo_minimo'
                );


            const plazoMaximo =
                obtenerParametro(
                    parametros,
                    'plazo_maximo'
                );


            plazoInput.min =
                plazoMinimo !== null
                    ? plazoMinimo
                    : 1;


            if (
                plazoMaximo !== null
            ) {

                plazoInput.max =
                    plazoMaximo;

            } else {

                plazoInput
                    .removeAttribute(
                        'max'
                    );
            }


            if (
                plazoMinimo !== null &&
                plazoMaximo !== null
            ) {

                ayudaPlazo.textContent =
                    'Plazo permitido: ' +
                    plazoMinimo +
                    ' a ' +
                    plazoMaximo +
                    ' meses.';

            } else if (
                plazoMaximo !== null
            ) {

                ayudaPlazo.textContent =
                    'Plazo máximo configurado: ' +
                    plazoMaximo +
                    ' meses.';

            } else {

                ayudaPlazo.textContent =
                    'Ingresa el plazo de la simulación en meses.';
            }


            if (configuracion) {

                campoEspecifico
                    .classList
                    .remove('hidden');


                ayudaMonto.textContent =
                    configuracion
                        .ayudaMonto;


                labelEspecifico
                    .childNodes[0]
                    .textContent =
                    configuracion
                        .labelEspecifico +
                    ' ';


                ayudaEspecifica.textContent =
                    configuracion
                        .ayudaEspecifica;


                datoEspecifico.type =
                    configuracion
                        .tipoEspecifico ||
                    'text';


                datoEspecifico.placeholder =
                    configuracion
                        .placeholderEspecifico ||
                    'Dato adicional';


                datoEspecifico.disabled =
                    false;

                datoEspecifico.required =
                    true;

            } else {

                campoEspecifico
                    .classList
                    .add('hidden');

                datoEspecifico.disabled =
                    true;

                datoEspecifico.required =
                    false;
            }


            actualizarReglasTipoBien();


            botonSimular.disabled =
                false;


            botonSimular.className =
                'w-full inline-flex items-center justify-center ' +
                'px-6 py-3 rounded-xl ' +
                'bg-[#0B4870] hover:bg-[#083653] ' +
                'text-white font-semibold transition';
        }


        function actualizarReglasTipoBien()
        {
            const codigoProducto =
                selectorCredito.value;


            const esVehiculoMoto =
                codigoProducto ===
                    'credito_vehiculo' ||
                codigoProducto ===
                    'credito_motocicleta';


            if (!esVehiculoMoto) {

                campoAntiguedadBien
                    .classList
                    .add('hidden');

                antiguedadBien.disabled =
                    true;

                antiguedadBien.required =
                    false;

                return;
            }


            const parametros =
                obtenerParametrosSeleccionados();


            const tipo =
                tipoBien.value;


            if (tipo === 'usado') {

                campoAntiguedadBien
                    .classList
                    .remove('hidden');

                antiguedadBien.disabled =
                    false;

                antiguedadBien.required =
                    true;


                const maximo =
                    obtenerParametro(
                        parametros,
                        'antiguedad_maxima_usado'
                    );


                if (
                    maximo !== null
                ) {

                    antiguedadBien.max =
                        maximo;

                    ayudaAntiguedadBien
                        .textContent =
                        'Antigüedad máxima permitida: ' +
                        maximo +
                        ' años.';

                } else {

                    antiguedadBien
                        .removeAttribute(
                            'max'
                        );

                    ayudaAntiguedadBien
                        .textContent =
                        'La antigüedad máxima todavía no se encuentra configurada.';
                }

            } else {

                campoAntiguedadBien
                    .classList
                    .add('hidden');

                antiguedadBien.disabled =
                    true;

                antiguedadBien.required =
                    false;

                antiguedadBien.value =
                    '';
            }


            actualizarAyudaFinanciacion();
        }


        function actualizarAyudaFinanciacion()
        {
            const codigoProducto =
                selectorCredito.value;


            if (
                codigoProducto !==
                    'credito_vehiculo' &&
                codigoProducto !==
                    'credito_motocicleta'
            ) {
                return;
            }


            const parametros =
                obtenerParametrosSeleccionados();


            const tipo =
                tipoBien.value;


            if (!tipo) {

                ayudaMonto.textContent =
                    'Selecciona si el bien es nuevo o usado para conocer el porcentaje máximo financiable.';

                return;
            }


            const codigoPorcentaje =
                tipo === 'nuevo'
                    ? 'porcentaje_financiacion_nuevo'
                    : 'porcentaje_financiacion_usado';


            const porcentaje =
                obtenerParametro(
                    parametros,
                    codigoPorcentaje
                );


            if (
                porcentaje !== null
            ) {

                ayudaMonto.textContent =
                    'Financiación máxima configurada: ' +
                    porcentaje +
                    ' % del valor comercial o asegurable.';

            } else {

                ayudaMonto.textContent =
                    'El porcentaje máximo de financiación para bienes ' +
                    tipo +
                    ' todavía no se encuentra configurado.';
            }
        }


        selectorCredito.addEventListener(
            'change',
            function () {

                montoSolicitado.value =
                    '';

                plazoInput.value =
                    '';

                datoEspecifico.value =
                    '';

                tipoBien.value =
                    '';

                antiguedadBien.value =
                    '';


                configurarProducto();
            }
        );


        tipoBien.addEventListener(
            'change',
            actualizarReglasTipoBien
        );


        configurarProducto();

    </script>

@endsection
