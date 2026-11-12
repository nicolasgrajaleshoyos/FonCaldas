@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="solicitudes" title="Solicitud {{ $solicitud->codigo }}" icon="clipboard"
            subtitle="{{ $solicitud->tramiteTipo->nombre }}" />

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('admin.solicitudes.index') }}" class="adm-btn-sec">← Volver al listado</a>
            @if(auth()->user()->esPropietario())<x-admin-delete :action="route('admin.solicitudes.delete', $solicitud)" label="Eliminar solicitud" message="¿Eliminar esta solicitud del panel? Su historial y documentos se conservan para auditoría." />@endif
        </div>

        @php
            $pasos = ['recibida' => 'Recibida', 'en_revision' => 'En revisión', 'aprobada' => 'Aprobada', 'finalizada' => 'Finalizada'];
            $claves = array_keys($pasos);
            $indiceActual = array_search($solicitud->estado, $claves, true);
            $rechazada = $solicitud->estado === 'rechazada';
        @endphp
        <div class="adm-card p-5 space-y-4">
            <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                <p>Radicada: <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span></p>
                <p>Responsable: <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $solicitud->asignadoA?->name ?? 'Sin asignar' }}</span></p>
                <p>Identidad: <span class="font-semibold {{ $solicitud->verificado_at ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">{{ $solicitud->verificado_at ? 'Verificada' : 'Pendiente de verificar' }}</span></p>
            </div>
            @if($rechazada)
                <p class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-2 text-sm font-semibold text-red-700 dark:text-red-300">Solicitud rechazada. El proceso terminó.</p>
            @else
                <ol class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm">
                    @foreach($pasos as $clave => $etiqueta)
                        @php $i = array_search($clave, $claves, true); @endphp
                        <li class="flex items-center gap-2 rounded-2xl px-3 py-2 border {{ $i < $indiceActual ? 'border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : ($i === $indiceActual ? 'border-[#0C67A3] bg-[#0C67A3]/10 text-[#0C67A3] dark:text-[#5EB3E4] font-semibold' : 'border-slate-200 dark:border-white/10 text-slate-400') }}">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold border border-current">{{ $i < $indiceActual || $solicitud->estado === 'finalizada' ? '✓' : $i + 1 }}</span>
                            {{ $etiqueta }}
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="adm-card p-6 space-y-4">
                    <h2 class="adm-titulo text-lg"><span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md shadow-[#0C67A3]/30">1</span>Datos del asociado</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300">Nombre</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $solicitud->asociado_nombre }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300">Documento</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $solicitud->asociado_documento }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300">Correo</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $solicitud->asociado_email }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300">Teléfono</dt><dd class="text-slate-900 dark:text-slate-200 font-medium break-words">{{ $solicitud->asociado_telefono ?: '—' }}</dd></div>
                    </dl>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-300 text-sm">Descripción</dt>
                        <dd class="text-slate-900 dark:text-slate-200 mt-1 whitespace-pre-line">{{ $solicitud->descripcion }}</dd>
                    </div>

                    @if($solicitud->verificado_at)
                        <p class="text-sm text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> Identidad verificada por {{ $solicitud->verificadoPor?->name }} el {{ $solicitud->verificado_at->format('d/m/Y H:i') }}</p>
                    @else
                        <p class="text-sm text-amber-700 dark:text-amber-400">Identidad pendiente de verificar. La acción está en el panel «Siguiente paso».</p>
                    @endif
                </div>

                <div class="adm-card p-6 space-y-4">
                    <h2 class="adm-titulo text-lg"><span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md shadow-[#0C67A3]/30">2</span>Documentos</h2>
                    <ul class="divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        @forelse($solicitud->documentos as $documento)
                            <li class="py-2 flex items-center justify-between gap-3">
                                <span class="min-w-0 break-words text-slate-900 dark:text-slate-200">{{ $documento->nombre_original }} <span class="text-slate-400">({{ $documento->origen === 'admin' ? 'publicado por FONCALDAS' : 'adjuntado por el asociado' }}@if($documento->origen === 'admin') · v{{ $documento->version }}@if($documento->version === $solicitud->documentos->where('origen', 'admin')->max('version')) · vigente @endif @endif)</span></span>
                                <a href="{{ route('admin.solicitudes.documentos.descargar', [$solicitud, $documento]) }}" target="_blank" rel="noopener" class="shrink-0 py-2 text-[#0C67A3] dark:text-[#5EB3E4] font-semibold">Ver</a>
                            </li>
                        @empty
                            <li class="py-2 text-slate-500 dark:text-slate-300">No hay documentos adjuntos.</li>
                        @endforelse
                    </ul>

                    <form method="POST" action="{{ route('admin.solicitudes.documentos.store', $solicitud) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3 border-t border-slate-200 dark:border-white/10 pt-4">
                        @csrf
                        <label for="f-documento" class="sr-only">Archivo a publicar</label>
                        <input id="f-documento" type="file" name="documento" required class="adm-archivo">
                        <button type="submit" class="rounded-full bg-[#0C67A3] px-4 py-2 text-white text-sm font-semibold hover:bg-[#095c8f] transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5">Publicar documento / certificado</button>
                    </form>
                </div>

                <div class="adm-card p-6 space-y-3">
                    <h2 class="adm-titulo text-lg"><span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-sm font-bold text-white shadow-md shadow-[#0C67A3]/30">3</span>Historial y auditoría</h2>
                    <ul class="space-y-3 text-sm max-h-[28rem] overflow-y-auto pr-2">
                        @foreach($solicitud->eventos as $evento)
                            <li class="border-l-4 border-[#0C67A3]/40 pl-4 dark:border-sky-400/40">
                                <p class="text-slate-900 dark:text-slate-200 font-medium">{{ str_replace('_', ' ', $evento->accion) }}</p>
                                @if($evento->detalle)<p class="text-slate-600 dark:text-slate-300">{{ $evento->detalle }}</p>@endif
                                <p class="text-slate-400 text-xs">{{ $evento->actor() }} · {{ $evento->created_at->format('d/m/Y H:i') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="space-y-6 order-first lg:order-none lg:sticky lg:top-4 lg:self-start">
                <div class="adm-card p-6 space-y-3">
                    <h2 class="adm-titulo text-lg"><span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-md"><x-icon name="arrow-right" class="w-4 h-4" /></span>Siguiente paso</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-300">Estado actual: <span class="font-bold text-[#0C67A3] dark:text-[#5EB3E4]">{{ $solicitud->estadoLabel() }}</span></p>

                    @if($solicitud->estado === 'recibida')
                        <p class="text-sm text-slate-600 dark:text-slate-300"><strong>1.</strong> Compara nombre y documento del asociado con la información de FONCALDAS (verificación manual, el portal no cruza datos). <strong>2.</strong> Si coinciden, verifica. Si no, rechaza.</p>

                        <form method="POST" action="{{ route('admin.solicitudes.verificar', $solicitud) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-full bg-emerald-600 px-4 py-2.5 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5">Los datos coinciden: verificar identidad</button>
                        </form>

                        <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" class="space-y-2 border-t border-slate-200 dark:border-white/10 pt-4" data-confirm="¿Rechazar esta solicitud sin verificar la identidad? Se avisará al asociado por correo con el motivo.">
                            @csrf
                            <label for="f-justificacion-recibida" class="sr-only">Motivo del rechazo</label>
                            <textarea id="f-justificacion-recibida" name="justificacion" rows="2" required maxlength="5000" placeholder="Ej.: los datos no coinciden con la información de asociados" class="w-full rounded-2xl border border-slate-300 bg-slate-50 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:text-slate-100 px-3 py-2 text-sm">{{ old('justificacion') }}</textarea>
                            @error('justificacion')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            <button type="submit" class="w-full rounded-full border border-red-600 dark:border-red-500 px-4 py-2.5 text-red-600 dark:text-red-400 text-sm font-semibold hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition">Rechazar: identidad no coincide</button>
                        </form>
                    @endif

                    @if($solicitud->estado === 'en_revision')
                        <p class="text-sm text-slate-600 dark:text-slate-300">Identidad verificada. Revisa los documentos y decide: aprobar o rechazar con motivo.</p>
                        <div class="flex flex-col gap-2 pt-2">
                            <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud) }}" data-confirm="¿Aprobar esta solicitud? Se avisará al asociado por correo.">
                                @csrf
                                <button type="submit" class="w-full rounded-full bg-emerald-600 px-4 py-2.5 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5">Aprobar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" class="space-y-2 border-t border-slate-200 dark:border-white/10 pt-4" data-confirm="¿Rechazar esta solicitud? Se avisará al asociado por correo con el motivo.">
                                @csrf
                                <label for="f-justificacion" class="sr-only">Motivo del rechazo</label>
                                <textarea id="f-justificacion" name="justificacion" rows="2" required maxlength="5000" placeholder="Motivo del rechazo" class="w-full rounded-2xl border border-slate-300 bg-slate-50 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:text-slate-100 px-3 py-2 text-sm">{{ old('justificacion') }}</textarea>
                                @error('justificacion')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                <button type="submit" class="w-full rounded-full border border-red-600 dark:border-red-500 px-4 py-2.5 text-red-600 dark:text-red-400 text-sm font-semibold hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition">Rechazar</button>
                            </form>
                        </div>
                    @endif

                    @if($solicitud->estado === 'aprobada')
                        @php $faltaCertificado = $solicitud->faltaCertificado(); @endphp
                        @if($faltaCertificado)
                            <p class="rounded-2xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/40 px-4 py-2 text-sm font-semibold text-amber-800 dark:text-amber-300">Esta solicitud es una certificación. Publica el certificado en «Documentos» para poder finalizarla.</p>
                        @else
                            <p class="text-sm text-slate-600 dark:text-slate-300">Publica el documento o certificado en «Documentos» y luego marca la solicitud como finalizada.</p>
                        @endif
                        <form method="POST" action="{{ route('admin.solicitudes.finalizar', $solicitud) }}" data-confirm="¿Marcar la solicitud como finalizada? Se avisará al asociado por correo.">
                            @csrf
                            <button type="submit" @disabled($faltaCertificado) class="w-full rounded-full bg-[#0B4870] px-4 py-2 text-white text-sm font-semibold hover:bg-[#0a3d60] transition shadow-lg shadow-[#0B4870]/20 hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0">Marcar como finalizada</button>
                        </form>
                    @endif

                    @if($solicitud->estado === 'rechazada' && $solicitud->justificacion)
                        <p class="text-sm text-slate-600 dark:text-slate-300"><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
                    @endif

                    @if(in_array($solicitud->estado, ['rechazada', 'finalizada'], true))
                        <p class="text-sm text-slate-500 dark:text-slate-300">No hay más acciones: la solicitud ya está cerrada.</p>
                    @endif
                </div>

                <div class="adm-card p-6 space-y-3">
                    <h2 class="adm-titulo text-lg"><span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300"><x-icon name="user" class="w-4 h-4" /></span>Responsable</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $solicitud->asignadoA?->name ?? 'Sin asignar' }}</p>
                    @unless(in_array($solicitud->estado, ['rechazada', 'finalizada'], true))
                    <form method="POST" action="{{ route('admin.solicitudes.reasignar', $solicitud) }}" class="flex gap-2">
                        @csrf
                        <label for="f-asignado-a" class="sr-only">Funcionario responsable</label>
                        <select id="f-asignado-a" name="asignado_a" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 transition focus:border-[#0C67A3] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:focus:border-sky-400 dark:focus:bg-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                            <option value="">Seleccionar funcionario</option>
                            @foreach($responsables as $responsable)
                                <option value="{{ $responsable->id }}" @selected($solicitud->asignado_a === $responsable->id)>{{ $responsable->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-full bg-slate-800 dark:bg-slate-600 px-4 py-2 text-white text-sm font-semibold hover:bg-slate-900 dark:hover:bg-slate-500 transition whitespace-nowrap">Asignar</button>
                    </form>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
