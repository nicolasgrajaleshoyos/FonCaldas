@extends('layouts.app')

@section('title', 'PQRS | Foncaldas')

@section('content')
@php $input = 'w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]'; @endphp
<div class="min-h-screen page-bg py-14">
    <div class="max-w-3xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot pose="formal" class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="Peticiones, quejas, reclamos y sugerencias"
                subtitle="Cuéntanos tu caso. Te responderemos por correo y podrás consultar el estado con el código que recibas."
                center />
        </div>

        <div class="mb-8 flex flex-col items-center gap-4 rounded-2xl border border-[#0C67A3]/30 bg-[#0C67A3]/10 px-5 py-4 text-center sm:flex-row sm:justify-between sm:text-left">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0C67A3] text-white"><x-icon name="search" class="w-5 h-5" /></span>
                <div>
                    <p class="font-bold text-slate-900 dark:text-white">¿Ya radicaste una PQRS?</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Consulta en qué estado va con tu código y tu documento.</p>
                </div>
            </div>
            <a href="{{ route('pqrs.consultar') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-[#0C67A3] px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#095c8f]">Consultar estado</a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
            <form method="POST" action="{{ route('pqrs.store') }}" data-cargando="Enviando tu PQRS…" class="space-y-5">
                @csrf

                <div>
                    <label for="f-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">¿Qué quieres radicar?</label>
                    <select id="f-tipo" name="tipo" required class="{{ $input }}">
                        <option value="">Selecciona una opción</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" @selected(old('tipo') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Petición: solicitud de información o servicio · Queja: inconformidad con una persona o el servicio · Reclamo: exigir el cumplimiento de un derecho · Sugerencia: propuesta de mejora.</p>
                    @error('tipo')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="f-nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre completo</label>
                        <input type="text" id="f-nombre" name="nombre" value="{{ old('nombre') }}" required class="{{ $input }}">
                        @error('nombre')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-documento" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de documento</label>
                        <input type="text" id="f-documento" name="documento" value="{{ old('documento') }}" required class="{{ $input }}">
                        @error('documento')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo electrónico</label>
                        <input type="email" id="f-email" name="email" value="{{ old('email') }}" required class="{{ $input }}">
                        @error('email')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-telefono" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teléfono (opcional)</label>
                        <input type="text" id="f-telefono" name="telefono" value="{{ old('telefono') }}" class="{{ $input }}">
                        @error('telefono')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="f-asunto" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Asunto</label>
                    <input type="text" id="f-asunto" name="asunto" value="{{ old('asunto') }}" required maxlength="255" class="{{ $input }}">
                    @error('asunto')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="f-descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Describe tu caso</label>
                    <textarea id="f-descripcion" name="descripcion" rows="6" required maxlength="5000" class="{{ $input }}">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="acepta_datos" value="1" required @checked(old('acepta_datos')) class="mt-1 rounded border-slate-300">
                        <span>Autorizo el tratamiento de mis datos personales para gestionar esta solicitud, según los <a href="{{ route('terminos') }}" target="_blank" rel="noopener" class="text-[#0C67A3] font-semibold hover:underline">términos y condiciones</a>.</span>
                    </label>
                    @error('acepta_datos')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition inline-flex items-center justify-center gap-2">
                    Radicar PQRS
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
