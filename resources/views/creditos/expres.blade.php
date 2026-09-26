@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-7xl mx-auto px-6">

        <x-page-header
            icon="bolt"
            title="Créditos Express"
            subtitle="Conozca nuestras líneas de crédito diseñadas para atender necesidades inmediatas de los asociados con procesos ágiles y flexibles."
            center />

        <!-- CONTENEDOR -->
        <div class="space-y-10">

            <!-- CRÉDITO AVANCES -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10">

                <div class="mb-8">
                    <x-section-title as="h2" class="mb-4">
                        Crédito Avances
                    </x-section-title>

                    <p class="text-slate-500 dark:text-slate-400">
                        Línea de crédito diseñada para atender necesidades inmediatas del asociado.
                    </p>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto">
                    <table class="w-full border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
                        <thead class="bg-[#0C67A3] text-white">
                            <tr>
                                <th class="px-5 py-4 text-left">Descripción</th>
                                <th class="px-5 py-4 text-left">Plazo</th>
                                <th class="px-5 py-4 text-left">Monto</th>
                                <th class="px-5 py-4 text-left">Otras especificaciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="border-t border-slate-200 dark:border-slate-700">
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Atender necesidades inmediatas del asociado.
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Hasta (3) meses
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Hasta el 50% del sueldo básico
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Se exonera del análisis de capacidad de pago y nivel de endeudamiento.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- REQUISITOS -->
                <div class="grid md:grid-cols-3 gap-8 mt-10">

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Requisitos y condiciones
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Diligenciar solicitud de crédito.</li>
                            <li>• Firmar el análisis de la solicitud.</li>
                            <li>• Firmar autorización de descuento, pagaré y carta de instrucciones.</li>
                            <li>• Diligenciar formato de asegurabilidad.</li>
                            <li>• Estar al día en las obligaciones con Foncaldas.</li>
                            <li>• Presentar último desprendible de pago o certificado laboral.</li>
                            <li>• Si no tiene vinculación laboral, aplica respaldo en ahorros y aportes.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Forma de pago
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Pago por caja</li>
                            
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Garantía
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Firma personal</li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- CRÉDITO BONOS -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10">

                <div class="mb-8">
                    <x-section-title as="h2" class="mb-4">
                        Crédito Bonos
                    </x-section-title>

                    <p class="text-slate-500 dark:text-slate-400">
                        Línea de crédito para atender necesidades inmediatas del asociado.
                    </p>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto">
                    <table class="w-full border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
                        <thead class="bg-[#0C67A3] text-white">
                            <tr>
                                <th class="px-5 py-4 text-left">Descripción</th>
                                <th class="px-5 py-4 text-left">Plazo</th>
                                <th class="px-5 py-4 text-left">Monto</th>
                                <th class="px-5 py-4 text-left">Otras especificaciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="border-t border-slate-200 dark:border-slate-700">
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Atender necesidades inmediatas del asociado.
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Hasta un (1) mes, cuota única
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Sin exceder 1 S.M.M.L.V
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Exonerado del análisis de capacidad de pago y endeudamiento.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- REQUISITOS -->
                <div class="grid md:grid-cols-3 gap-8 mt-10">

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Requisitos y condiciones
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Diligenciar solicitud de crédito.</li>
                            <li>• Firmar el análisis de la solicitud.</li>
                            <li>• Firmar autorización de descuento, pagaré y carta de instrucciones.</li>
                            <li>• Diligenciar formato de asegurabilidad.</li>
                            <li>• Estar al día en las obligaciones con Foncaldas.</li>
                            <li>• Presentar último desprendible de pago o certificado laboral.</li>
                            <li>• Si no tiene vinculación laboral, aplica respaldo en ahorros y aportes.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Forma de pago
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Pago por caja</li>
                            
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Garantía
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Firma personal</li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- CRÉDITO BONIFICACIÓN -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10">

                <div class="mb-8">
                    <x-section-title as="h2" class="mb-4">
                        Crédito Bonificación
                    </x-section-title>

                    <p class="text-slate-500 dark:text-slate-400">
                        Disponibilidad anticipada de recursos para los asociados de la universidad de caldas.
                    </p>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto">
                    <table class="w-full border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
                        <thead class="bg-[#0C67A3] text-white">
                            <tr>
                                <th class="px-5 py-4 text-left">Descripción</th>
                                <th class="px-5 py-4 text-left">Plazo</th>
                                <th class="px-5 py-4 text-left">Monto</th>
                                <th class="px-5 py-4 text-left">Otras especificaciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="border-t border-slate-200 dark:border-slate-700">
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Disponibilidad anticipada de recursos.
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Hasta seis (6) meses, cuota única
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Hasta el 90% de lo recibido el año anterior
                                </td>

                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                    Se descuentan los deducibles de ley.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- REQUISITOS -->
                <div class="grid md:grid-cols-3 gap-8 mt-10">

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Requisitos y condiciones
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Diligenciar solicitud de crédito.</li>
                            <li>• Firmar el análisis de la solicitud.</li>
                            <li>• Tener cuenta de nómina con Foncaldas o presentar deudor solidario.</li>
                            <li>• Estar al día en las obligaciones con Foncaldas.</li>
                            <li>• Diligenciar formato de asegurabilidad si se requiere.</li>
                            <li>• Presentar desprendible de pago correspondiente.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Forma de pago
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Por libranza</li>
                            <li>• Pago por caja</li>
                            
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                            Garantía
                        </h3>

                        <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                            <li>• Firma personal</li>
                            <li>• Deudor solidario</li>
                            <li>• Otra garantía si se considera necesario</li>
                        </ul>
                    </div>

                </div>

            </div>
 <!-- CRÉDITO CUOTA ÚNICA -->
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10">

    <div class="mb-8">
        <x-section-title as="h2" class="mb-4">
            Crédito Cuota Única (Junio y Diciembre)
        </x-section-title>

        <p class="text-slate-500 dark:text-slate-400">
            Disponibilidad anticipada de recursos mediante pago único para asociados.
        </p>
    </div>

    <!-- TABLA -->
    <div class="overflow-x-auto">
        <table class="w-full border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
            <thead class="bg-[#0C67A3] text-white">
                <tr>
                    <th class="px-5 py-4 text-left">Descripción</th>
                    <th class="px-5 py-4 text-left">Plazo</th>
                    <th class="px-5 py-4 text-left">Monto</th>
                    <th class="px-5 py-4 text-left">Otras especificaciones</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-t border-slate-200 dark:border-slate-700">
                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                        Disponibilidad anticipada de recursos.
                    </td>

                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                        Hasta seis (6) meses
                    </td>

                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                        Hasta el valor de la prima.
                    </td>

                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                        Pago único. Si se considera necesario, se solicitará codeudor solidario u otro tipo de garantía.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- REQUISITOS -->
    <div class="grid md:grid-cols-3 gap-8 mt-10">

        <div>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                Requisitos y condiciones
            </h3>

            <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                <li>• Tener la cuenta de nomina por Foncaldas (si no se pide coodeudor).</li>
                <li>• Estar al día en las obligaciones con Foncaldas.</li>
                <li>• Presentar último desprendible de pago o certificado laboral.</li>
                <li>• Firma de carta de autorización de descuento, pagaré y carta de instrucciones.</li>
                <li>• Diligenciar la solicitud de crédito.</li>
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                Forma de pago
            </h3>

            <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                <li>• Pago por caja</li>
                
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">
                Garantía
            </h3>

            <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                <li>• Firma personal</li>
                <li>• Codeudor solidario si se requiere</li>
            </ul>
        </div>

    </div>

</div>           

            <x-back-link :route="route('servicios.creditos')" label="← Volver a créditos" />

        </div>

    </div>

</div>

@endsection