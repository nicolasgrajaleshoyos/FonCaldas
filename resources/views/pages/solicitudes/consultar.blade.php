@extends('layouts.app')

@section('title', 'Consultar solicitud | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="pensando" class="mb-6" sol />
            <x-page-header
                icon="search"
                title="Consulta el estado de tu solicitud"
                subtitle="Ingresa tu número de documento y el código que recibiste al radicar."
                center />
        </div>

        <x-formulario-consulta :accion="route('solicitudes.buscar')" mensaje-carga="Consultando tu solicitud…"
            prefijo="FC" campo-documento="asociado_documento" que="tu solicitud"
            :nueva-ruta="route('solicitudes.crear')" nueva-texto="Radica una solicitud" />
    </div>
</div>
@endsection
