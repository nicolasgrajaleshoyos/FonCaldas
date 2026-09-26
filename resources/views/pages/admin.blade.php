@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] px-6 py-24">
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl p-10">
        <h1 class="text-3xl font-bold text-[#0F172A] mb-6 flex items-center gap-3"><span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="wrench" class="w-6 h-6" /></span>Admin</h1>

        <p class="text-gray-600 leading-relaxed mb-8">
            Portal administrativo de Foncaldas. Aquí puedes encontrar información interna, herramientas de gestión
            y las últimas novedades de la organización.
        </p>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="border border-slate-200 rounded-2xl p-6">
                <h2 class="text-2xl font-semibold mb-3 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="document" class="w-5 h-5" /></span>Noticias internas</h2>
                <p class="text-gray-600">Actualizaciones sobre procesos, reuniones de equipo y cambios en la gestión.</p>
            </div>
            <div class="border border-slate-200 rounded-2xl p-6">
                <h2 class="text-2xl font-semibold mb-3 inline-flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="folder" class="w-5 h-5" /></span>Recursos</h2>
                <p class="text-gray-600">Accede a documentos, protocolos y soporte para el personal autorizado.</p>
            </div>
        </div>
    </div>
</div>
@endsection
