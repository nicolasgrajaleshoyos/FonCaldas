@extends('layouts.app')

@section('title', 'Acceso restringido · Foncaldas')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-24">
    <div class="max-w-md mx-auto bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-10 text-center">
        <span class="mx-auto mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="lock" class="w-7 h-7" /></span>
        <h1 class="text-2xl font-bold text-[#0F172A] dark:text-white mb-3">Acceso restringido</h1>
        <p class="text-slate-600 dark:text-slate-300 mb-8">{{ $exception->getMessage() ?: 'No tienes permiso para ver esta página.' }}</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-[#0B4870] px-5 py-3 text-white font-semibold hover:bg-[#0a3d60] transition">Volver al panel</a>
            @else
                <a href="{{ route('admin') }}" class="rounded-lg bg-[#0B4870] px-5 py-3 text-white font-semibold hover:bg-[#0a3d60] transition">Iniciar sesión</a>
            @endauth
            <a href="{{ url('/') }}" class="rounded-lg border border-slate-200 dark:border-slate-600 px-5 py-3 font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 transition">Ir al inicio</a>
        </div>
    </div>
</div>
@endsection
