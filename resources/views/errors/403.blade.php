@extends('layouts.app')

@section('title', 'Acceso restringido · Foncaldas')

@section('content')
<x-pagina-error codigo="403" titulo="Acceso restringido" pose="formal"
    :mensaje="$exception->getMessage() ?: 'No tienes permiso para ver esta página.'">
    @auth
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200">Volver al panel</a>
    @else
        <a href="{{ route('admin') }}" class="inline-flex items-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200">Iniciar sesión</a>
    @endauth
</x-pagina-error>
@endsection
