@extends('layouts.app')

@section('title', 'Consultar solicitud | Foncaldas')

@section('content')
<div class="min-h-screen page-bg py-14">
    <div class="max-w-md mx-auto px-6">
        <div class="flex flex-col items-center mb-10">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="search"
                title="Consulta el estado de tu solicitud"
                subtitle="Ingresa tu número de documento y el código que recibiste al radicar."
                center />
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
            @if($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('solicitudes.buscar') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Código de solicitud</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" required placeholder="FC-26-XXXXXX" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de documento</label>
                    <input type="text" name="asociado_documento" value="{{ old('asociado_documento') }}" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 px-4 py-3 focus:ring-2 focus:ring-[#0C67A3]">
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-3 text-white font-semibold hover:bg-[#0a3d60] transition">Consultar</button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('solicitudes.crear') }}" class="text-[#0C67A3] font-semibold hover:underline">← Radicar una nueva solicitud</a>
        </p>
    </div>
</div>
@endsection
