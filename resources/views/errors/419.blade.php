@extends('layouts.app')

@section('title', 'La página expiró · Foncaldas')

@section('content')
<x-pagina-error codigo="419" titulo="La página expiró" pose="pc"
    mensaje="Pasó mucho tiempo desde que abriste el formulario y, por seguridad, caducó. Vuelve atrás, recarga la página y envíalo de nuevo.">
    <button type="button" onclick="history.back()" class="inline-flex items-center gap-2 rounded-full border-2 border-[#0B4870] px-6 py-2.5 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200">← Volver atrás</button>
</x-pagina-error>
@endsection
