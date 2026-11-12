@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="usuarios" title="Usuarios y permisos" icon="users"
            subtitle="Crea funcionarios y controla a qué tipos de trámite tiene acceso cada uno." />

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="px-1 text-[15px] font-semibold text-slate-600 dark:text-slate-300">
                {{ $usuarios->count() }} {{ $usuarios->count() === 1 ? 'persona' : 'personas' }} con acceso al panel
            </p>
            <a href="{{ route('admin.usuarios.create') }}" class="adm-btn"><span class="text-lg leading-none">+</span> Nuevo funcionario</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
            @foreach($usuarios as $usuario)
                <div class="flex min-w-0 flex-col rounded-3xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800 {{ $usuario->is_active ? '' : 'opacity-75' }}">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full text-xl font-bold shadow-lg {{ $usuario->is_super_admin ? 'bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-[#FACC15]/30' : 'bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-[#0C67A3]/30' }}">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($usuario->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="break-words text-lg font-bold leading-snug text-slate-900 dark:text-white">{{ $usuario->name }}</p>
                            <p class="break-all text-sm text-slate-500 dark:text-slate-300">Usuario: <span class="font-mono">{{ $usuario->username }}</span></p>
                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $usuario->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $usuario->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 rounded-2xl bg-slate-50 p-4 dark:bg-slate-900/60">
                        <div class="flex items-center gap-2 text-sm">
                            <x-icon name="shield" class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-500 dark:text-slate-300">Rol:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $usuario->is_super_admin ? 'Administrador principal' : 'Funcionario' }}</span>
                        </div>
                        <div>
                            <p class="mb-2 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-300"><x-icon name="briefcase" class="w-4 h-4 text-slate-400" /> Trámites que gestiona:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @if($usuario->is_super_admin)
                                    <span class="rounded-full bg-[#FACC15]/20 px-2.5 py-0.5 text-xs font-semibold text-[#8a6d00] dark:text-[#FACC15]">Todos los trámites</span>
                                @else
                                    @forelse($usuario->tramiteTipos as $tramite)
                                        <span class="rounded-full bg-[#0C67A3]/10 px-2.5 py-0.5 text-xs font-semibold text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300">{{ $tramite->nombre }}</span>
                                    @empty
                                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-300">Ninguno asignado todavía</span>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        @if (auth()->user()->puedeEditar($usuario))
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="adm-btn-sec"><x-icon name="wrench" class="w-4 h-4" /> Editar</a>
                        @endif
                        @unless ($usuario->is_super_admin)
                            <form method="POST" action="{{ route('admin.usuarios.toggle', $usuario) }}" data-confirm="{{ $usuario->is_active ? '¿Desactivar a este funcionario? No podrá iniciar sesión.' : '¿Activar a este funcionario?' }}">
                                @csrf
                                <button type="submit" class="adm-btn-sec">{{ $usuario->is_active ? 'Desactivar' : 'Activar' }}</button>
                            </form>
                            <x-admin-delete :action="route('admin.usuarios.delete', $usuario)" message="¿Eliminar a este funcionario? Esta acción no se puede deshacer." />
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
