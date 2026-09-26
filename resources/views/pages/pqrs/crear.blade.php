@extends('layouts.app')

@section('title', 'PQRS | Foncaldas')

@section('content')
@php $input = 'w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]'; @endphp
<div class="min-h-screen page-bg py-14">
    <div class="max-w-3xl mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="clipboard"
                title="Peticiones, quejas, reclamos y sugerencias"
                subtitle="Cuéntanos tu caso. Te responderemos por correo y podrás consultar el estado con el código que recibas."
                center />
        </div>

        <div class="mb-6 text-center">
            <a href="{{ route('pqrs.consultar') }}" class="text-sm font-semibold text-[#0C67A3] hover:underline">¿Ya radicaste una PQRS? Consulta su estado aquí →</a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
            <form method="POST" action="{{ route('pqrs.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">¿Qué quieres radicar?</label>
                    <select name="tipo" required class="{{ $input }}">
                        <option value="">Selecciona una opción</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" @selected(old('tipo') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Petición: solicitud de información o servicio · Queja: inconformidad con una persona o el servicio · Reclamo: exigir el cumplimiento de un derecho · Sugerencia: propuesta de mejora.</p>
                    @error('tipo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre completo</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required class="{{ $input }}">
                        @error('nombre')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de documento</label>
                        <input type="text" name="documento" value="{{ old('documento') }}" required class="{{ $input }}">
                        @error('documento')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="{{ $input }}">
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teléfono (opcional)</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" class="{{ $input }}">
                        @error('telefono')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Asunto</label>
                    <input type="text" name="asunto" value="{{ old('asunto') }}" required maxlength="255" class="{{ $input }}">
                    @error('asunto')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Describe tu caso</label>
                    <textarea name="descripcion" rows="6" required maxlength="5000" class="{{ $input }}">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="acepta_datos" value="1" required @checked(old('acepta_datos')) class="mt-1 rounded border-slate-300">
                        <span>Autorizo el tratamiento de mis datos personales para gestionar esta solicitud, según los <a href="{{ route('terminos') }}" target="_blank" class="text-[#0C67A3] font-semibold hover:underline">términos y condiciones</a>.</span>
                    </label>
                    @error('acepta_datos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition inline-flex items-center justify-center gap-2">
                    Radicar PQRS <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
