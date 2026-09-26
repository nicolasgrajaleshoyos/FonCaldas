@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-6 py-14">
    <div class="max-w-5xl mx-auto space-y-6">
        <x-admin-tabs active="solicitudes" title="Solicitud {{ $solicitud->codigo }}" icon="clipboard"
            subtitle="{{ $solicitud->tramiteTipo->nombre }}" />

        <a href="{{ route('admin.solicitudes.index') }}" class="inline-block text-sm font-semibold text-[#0C67A3]">← Volver al listado</a>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white dark:text-white">Datos del asociado</h2>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-500 dark:text-slate-400">Nombre</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $solicitud->asociado_nombre }}</dd></div>
                        <div><dt class="text-slate-500 dark:text-slate-400">Documento</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $solicitud->asociado_documento }}</dd></div>
                        <div><dt class="text-slate-500 dark:text-slate-400">Correo</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $solicitud->asociado_email }}</dd></div>
                        <div><dt class="text-slate-500 dark:text-slate-400">Teléfono</dt><dd class="text-slate-900 dark:text-slate-200 font-medium">{{ $solicitud->asociado_telefono ?: '—' }}</dd></div>
                    </dl>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-sm">Descripción</dt>
                        <dd class="text-slate-900 dark:text-slate-200 mt-1 whitespace-pre-line">{{ $solicitud->descripcion }}</dd>
                    </div>

                    @if($solicitud->verificado_at)
                        <p class="text-sm text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> Identidad verificada por {{ $solicitud->verificadoPor?->name }} el {{ $solicitud->verificado_at->format('d/m/Y H:i') }}</p>
                    @else
                        <form method="POST" action="{{ route('admin.solicitudes.verificar', $solicitud) }}">
                            @csrf
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400">Compara nombre y documento con la información de asociados de FONCALDAS. Es una verificación manual: el portal no cruza los datos automáticamente.</p>
                            <button type="submit" class="rounded-lg bg-slate-800 dark:bg-slate-600 px-4 py-2 text-white text-sm font-semibold hover:bg-slate-900 dark:hover:bg-slate-500 transition">Verificar identidad</button>
                        </form>
                    @endif
                </div>

                <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Documentos</h2>
                    <ul class="divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        @forelse($solicitud->documentos as $documento)
                            <li class="py-2 flex items-center justify-between">
                                <span class="text-slate-900 dark:text-slate-200">{{ $documento->nombre_original }} <span class="text-slate-400">({{ $documento->origen === 'admin' ? 'publicado por FONCALDAS' : 'adjuntado por el asociado' }}@if($documento->origen === 'admin') · v{{ $documento->version }}@if($documento->version === $solicitud->documentos->where('origen', 'admin')->max('version')) · vigente @endif @endif)</span></span>
                                <a href="{{ route('admin.solicitudes.documentos.descargar', [$solicitud, $documento]) }}" target="_blank" class="text-[#0C67A3] font-semibold">Ver</a>
                            </li>
                        @empty
                            <li class="py-2 text-slate-500 dark:text-slate-400">No hay documentos adjuntos.</li>
                        @endforelse
                    </ul>

                    <form method="POST" action="{{ route('admin.solicitudes.documentos.store', $solicitud) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3 border-t border-slate-100 dark:border-slate-700 pt-4">
                        @csrf
                        <input type="file" name="documento" required class="text-sm text-slate-700 dark:text-slate-300 file:rounded-full file:border file:border-slate-300 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 file:font-medium">
                        <button type="submit" class="rounded-lg bg-[#0C67A3] px-4 py-2 text-white text-sm font-semibold hover:bg-[#095c8f] transition">Publicar documento / certificado</button>
                    </form>
                </div>

                <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Historial y auditoría</h2>
                    <ul class="space-y-3 text-sm">
                        @foreach($solicitud->eventos as $evento)
                            <li class="border-l-2 border-slate-200 dark:border-slate-600 pl-3">
                                <p class="text-slate-900 dark:text-slate-200 font-medium">{{ str_replace('_', ' ', $evento->accion) }}</p>
                                @if($evento->detalle)<p class="text-slate-600 dark:text-slate-400">{{ $evento->detalle }}</p>@endif
                                <p class="text-slate-400 text-xs">{{ $evento->actor() }} · {{ $evento->created_at->format('d/m/Y H:i') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Estado actual</h2>
                    <p class="text-2xl font-bold text-[#0C67A3]">{{ $solicitud->estadoLabel() }}</p>

                    @if($solicitud->estado === 'recibida')
                        <p class="text-sm text-slate-500 dark:text-slate-400">Verifica la identidad del asociado para poder aprobar o rechazar la solicitud.</p>
                    @endif

                    @if($solicitud->estado === 'en_revision')
                        <div class="flex flex-col gap-2 pt-2">
                            <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud) }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-white text-sm font-semibold hover:bg-emerald-700 transition">Aprobar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" class="space-y-2">
                                @csrf
                                <textarea name="justificacion" rows="2" required placeholder="Motivo del rechazo" class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-3 py-2 text-sm"></textarea>
                                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2 text-white text-sm font-semibold hover:bg-red-700 transition">Rechazar</button>
                            </form>
                        </div>
                    @endif

                    @if($solicitud->estado === 'aprobada')
                        <form method="POST" action="{{ route('admin.solicitudes.finalizar', $solicitud) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-[#0B4870] px-4 py-2 text-white text-sm font-semibold hover:bg-[#0a3d60] transition">Marcar como finalizada</button>
                        </form>
                    @endif

                    @if($solicitud->estado === 'rechazada' && $solicitud->justificacion)
                        <p class="text-sm text-slate-600 dark:text-slate-400"><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
                    @endif
                </div>

                <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Responsable</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $solicitud->asignadoA?->name ?? 'Sin asignar' }}</p>
                    <form method="POST" action="{{ route('admin.solicitudes.reasignar', $solicitud) }}" class="flex gap-2">
                        @csrf
                        <select name="asignado_a" required class="w-full rounded-2xl border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                            <option value="">Seleccionar funcionario</option>
                            @foreach($responsables as $responsable)
                                <option value="{{ $responsable->id }}" @selected($solicitud->asignado_a === $responsable->id)>{{ $responsable->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-lg bg-slate-800 dark:bg-slate-600 px-4 py-2 text-white text-sm font-semibold hover:bg-slate-900 dark:hover:bg-slate-500 transition whitespace-nowrap">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
