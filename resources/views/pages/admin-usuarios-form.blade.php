@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-2xl mx-auto space-y-6">
        <x-admin-tabs active="usuarios" title="{{ $usuario->exists ? 'Editar funcionario' : 'Nuevo funcionario' }}" icon="users" />

        <a href="{{ route('admin.usuarios.index') }}" class="inline-block text-sm font-semibold text-[#0C67A3]">← Volver a usuarios</a>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <form method="POST" action="{{ $usuario->exists ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}" class="space-y-4">
                @csrf
                @if($usuario->exists) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Usuario (para iniciar sesión)</label>
                    <input type="text" name="username" value="{{ old('username', $usuario->username) }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contraseña {{ $usuario->exists ? '(dejar en blanco para no cambiarla)' : '' }}</label>
                    <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-4 py-3" {{ $usuario->exists ? '' : 'required' }}>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                    <input type="checkbox" name="is_super_admin" value="1" @checked(old('is_super_admin', $usuario->is_super_admin))>
                    Administrador principal (acceso total a todos los módulos y trámites)
                </label>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipos de trámite que puede ver y gestionar</label>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">No aplica si es administrador principal, quien siempre ve todo.</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($tramiteTipos as $tipo)
                            <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2">
                                <input type="checkbox" name="tramite_tipos[]" value="{{ $tipo->id }}"
                                    @checked(collect(old('tramite_tipos', $usuario->tramiteTipos->pluck('id')->all()))->contains($tipo->id))>
                                {{ $tipo->nombre }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection
