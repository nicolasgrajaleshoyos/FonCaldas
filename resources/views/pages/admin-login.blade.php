@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-24">
    <div class="max-w-md mx-auto bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-10">
        <h1 class="text-3xl font-bold text-[#0F172A] dark:text-white mb-6 flex items-center gap-3"><span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="lock" class="w-6 h-6" /></span>Acceso Administrador</h1>

        @if(session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="username">Usuario</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                    required>
                @error('username')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="password">Contraseña</label>
                <input id="password" name="password" type="password"
                    class="w-full rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]"
                    required>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition inline-flex items-center justify-center gap-2">
                Ingresar como Admin <x-icon name="arrow-right" class="w-4 h-4" />
            </button>
        </form>
    </div>
</div>
@endsection
