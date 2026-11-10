@extends('layouts.app')

@section('content')

@php
    // Requisitos y formas de pago que comparten varias líneas.
    $requisitosBase = [
        'Diligenciar solicitud de crédito.',
        'Firmar el análisis de la solicitud.',
        'Firmar autorización de descuento, pagaré y carta de instrucciones.',
        'Diligenciar formato de asegurabilidad.',
        'Estar al día en las obligaciones con Foncaldas.',
        'Presentar último desprendible de pago o certificado laboral.',
        'Si no tiene vinculación laboral, aplica respaldo en ahorros y aportes.',
    ];
    $pagoPorCaja = ['Pago por caja'];
    $firmaPersonal = ['Firma personal'];
@endphp

<div class="min-h-screen page-bg py-14">

    <div class="max-w-7xl mx-auto px-6">

        <x-back-link :route="route('servicios.creditos')" label="Volver a créditos" />

        <x-page-header
            icon="bolt"
            title="Créditos Express"
            subtitle="Conozca nuestras líneas de crédito diseñadas para atender necesidades inmediatas de los asociados con procesos ágiles y flexibles."
            center />

        <div class="space-y-10">

            <x-credito-ficha
                titulo="Crédito Avances"
                resumen="Línea de crédito diseñada para atender necesidades inmediatas del asociado."
                descripcion="Atender necesidades inmediatas del asociado."
                plazo="Hasta (3) meses"
                monto="Hasta el 50% del sueldo básico"
                otras="Se exonera del análisis de capacidad de pago y nivel de endeudamiento."
                :requisitos="$requisitosBase"
                :pago="$pagoPorCaja"
                :garantia="$firmaPersonal" />

            <x-credito-ficha
                titulo="Crédito Bonos"
                resumen="Línea de crédito para atender necesidades inmediatas del asociado."
                descripcion="Atender necesidades inmediatas del asociado."
                plazo="Hasta un (1) mes, cuota única"
                monto="Sin exceder 1 S.M.M.L.V"
                otras="Exonerado del análisis de capacidad de pago y endeudamiento."
                :requisitos="$requisitosBase"
                :pago="$pagoPorCaja"
                :garantia="$firmaPersonal" />

            <x-credito-ficha
                titulo="Crédito Bonificación"
                resumen="Disponibilidad anticipada de recursos para los asociados de la universidad de caldas."
                descripcion="Disponibilidad anticipada de recursos."
                plazo="Hasta seis (6) meses, cuota única"
                monto="Hasta el 90% de lo recibido el año anterior"
                otras="Se descuentan los deducibles de ley."
                :requisitos="[
                    'Diligenciar solicitud de crédito.',
                    'Firmar el análisis de la solicitud.',
                    'Tener cuenta de nómina con Foncaldas o presentar deudor solidario.',
                    'Estar al día en las obligaciones con Foncaldas.',
                    'Diligenciar formato de asegurabilidad si se requiere.',
                    'Presentar desprendible de pago correspondiente.',
                ]"
                :pago="['Por libranza', 'Pago por caja']"
                :garantia="['Firma personal', 'Deudor solidario', 'Otra garantía si se considera necesario']" />

            <x-credito-ficha
                titulo="Crédito Cuota Única (Junio y Diciembre)"
                resumen="Disponibilidad anticipada de recursos mediante pago único para asociados."
                descripcion="Disponibilidad anticipada de recursos."
                plazo="Hasta seis (6) meses"
                monto="Hasta el valor de la prima."
                otras="Pago único. Si se considera necesario, se solicitará codeudor solidario u otro tipo de garantía."
                :requisitos="[
                    'Tener la cuenta de nomina por Foncaldas (si no se pide coodeudor).',
                    'Estar al día en las obligaciones con Foncaldas.',
                    'Presentar último desprendible de pago o certificado laboral.',
                    'Firma de carta de autorización de descuento, pagaré y carta de instrucciones.',
                    'Diligenciar la solicitud de crédito.',
                ]"
                :pago="$pagoPorCaja"
                :garantia="['Firma personal', 'Codeudor solidario si se requiere']" />

        </div>

    </div>

</div>

@endsection
