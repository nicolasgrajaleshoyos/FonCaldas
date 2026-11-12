@extends('layouts.app')

@section('title', 'Consultar PQRS | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="pensativo" class="mb-6" sol />
            <x-page-header
                icon="search"
                title="Consulta tu PQRS"
                subtitle="Ingresa tu número de documento y el código que recibiste al radicar."
                center />
        </div>

        <x-formulario-consulta :accion="route('pqrs.buscar')" mensaje-carga="Consultando tu PQRS…"
            prefijo="PQ" campo-documento="documento" que="tu PQRS"
            :nueva-ruta="route('pqrs.crear')" nueva-texto="Radica una PQRS" />
    </div>
</div>
@endsection
