@extends('layouts.app')

@section('content')
@php
    $input = 'w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C67A3]';
    $label = 'block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5';
    $card = 'rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200 dark:border-slate-700';
@endphp
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="usuarios" title="{{ $usuario->exists ? 'Editar funcionario' : 'Nuevo funcionario' }}" icon="users" />

        <a href="{{ route('admin.usuarios.index') }}" class="inline-block text-sm font-semibold text-[#0C67A3] dark:text-[#5EB3E4]">← Volver a usuarios</a>

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 px-4 py-3 text-sm text-rose-700 dark:text-rose-300">
                <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ $usuario->exists ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}" class="grid gap-6 lg:grid-cols-3 items-start">
            @csrf
            @if($usuario->exists) @method('PUT') @endif

            {{-- Datos de acceso --}}
            <div class="{{ $card }} lg:col-span-2 space-y-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Datos del funcionario</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Con el usuario y la contraseña inicia sesión en el panel; el correo recibe los avisos de nuevas solicitudes.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="name">Nombre completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $usuario->name) }}" required class="{{ $input }}">
                        @error('name')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="{{ $label }}" for="username">Usuario (para iniciar sesión)</label>
                        <input id="username" type="text" name="username" value="{{ old('username', $usuario->username) }}" required class="{{ $input }}">
                        @error('username')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="{{ $label }}" for="email">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="{{ $input }}">
                        @error('email')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    @if (! $usuario->exists || auth()->user()->puedeCambiarClaveDe($usuario))
                    <div>
                        <label class="{{ $label }}" for="password">Contraseña {{ $usuario->exists ? '(dejar en blanco para no cambiarla)' : '' }}</label>
                        <input id="password" type="password" name="password" class="{{ $input }}" {{ $usuario->exists ? '' : 'required' }}>
                        @error('password')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    @endif
                </div>

                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-4 text-sm text-slate-700 dark:text-slate-300">
                    <input type="checkbox" name="is_super_admin" value="1" class="mt-0.5" @checked(old('is_super_admin', $usuario->is_super_admin))>
                    <span><span class="block font-semibold text-slate-800 dark:text-slate-100">Administrador principal</span>Acceso total a todos los módulos y trámites.</span>
                </label>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 dark:border-slate-700 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">Cancelar</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#0B4870] px-8 py-2.5 text-sm font-semibold text-white hover:bg-[#0a3d60] transition">Guardar</button>
                </div>
            </div>

            {{-- Permisos por tipo de trámite --}}
            <div class="{{ $card }} space-y-4 lg:sticky lg:top-24">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Tipos de trámite que puede ver y gestionar</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No aplica si es administrador principal, quien siempre ve todo.</p>
                </div>

                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1 2xl:grid-cols-2">
                    @foreach($tramiteTipos as $tipo)
                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2.5">
                            <input type="checkbox" name="tramite_tipos[]" value="{{ $tipo->id }}"
                                @checked(collect(old('tramite_tipos', $usuario->tramiteTipos->pluck('id')->all()))->contains($tipo->id))>
                            {{ $tipo->nombre }}
                        </label>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
