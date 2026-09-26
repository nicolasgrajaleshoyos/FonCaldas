@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-6xl mx-auto space-y-8">
        <x-admin-tabs active="usuarios" title="Usuarios y permisos" icon="users"
            subtitle="Crea funcionarios y controla a qué tipos de trámite tiene acceso cada uno." />

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="flex justify-end">
            <a href="{{ route('admin.usuarios.create') }}" class="rounded-lg bg-[#0C67A3] px-5 py-2.5 text-white font-semibold hover:bg-[#095c8f] transition">+ Nuevo funcionario</a>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Nombre</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Usuario</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Rol</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Trámites asignados</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                        <th class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td class="px-4 py-4 text-slate-900 dark:text-slate-200">{{ $usuario->name }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $usuario->username }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $usuario->is_super_admin ? 'Administrador principal' : 'Funcionario' }}</td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">{{ $usuario->is_super_admin ? 'Todos' : ($usuario->tramiteTipos->pluck('nombre')->join(', ') ?: '—') }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $usuario->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">{{ $usuario->is_active ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="rounded-full border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Editar</a>
                                    <form method="POST" action="{{ route('admin.usuarios.toggle', $usuario) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">{{ $usuario->is_active ? 'Desactivar' : 'Activar' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
