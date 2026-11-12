@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg text-slate-900 dark:text-slate-100 px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-[1920px] mx-auto space-y-6">
        <x-admin-tabs active="tramite-tipos" title="Tipos de trámite" icon="briefcase"
            subtitle="Agrega nuevas categorías de trámite o certificación sin afectar la operación actual." />

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] xl:items-start">

            {{-- Crear --}}
            <div class="adm-card xl:sticky xl:top-28">
                <h2 class="adm-titulo mb-6"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-lg shadow-[#0B8B7B]/30"><x-icon name="sparkle" class="w-6 h-6" /></span>Nuevo tipo de trámite</h2>
                <form method="POST" action="{{ route('admin.tramite-tipos.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="f-nombre" class="adm-etiqueta">Nombre</label>
                        <input type="text" id="f-nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej.: Certificado de saldo" class="adm-campo">
                        @error('nombre')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="f-descripcion" class="adm-etiqueta">Descripción <span class="font-normal text-slate-500 dark:text-slate-400">(opcional)</span></label>
                        <input type="text" id="f-descripcion" name="descripcion" value="{{ old('descripcion') }}" class="adm-campo">
                        @error('descripcion')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-[15px] text-slate-700 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200">
                        <input type="checkbox" name="es_certificacion" value="1" @checked(old('es_certificacion')) class="mt-0.5 h-5 w-5 shrink-0 rounded accent-[#0C67A3]">
                        <span><strong class="font-semibold">Es una certificación</strong><br><span class="text-sm text-slate-500 dark:text-slate-300">El asociado descarga un documento generado por FONCALDAS.</span></span>
                    </label>
                    <button type="submit" class="adm-btn w-full"><span class="text-lg leading-none">+</span> Crear tipo de trámite</button>
                </form>
            </div>

            {{-- Existentes --}}
            <div class="adm-card">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="adm-titulo"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="briefcase" class="w-6 h-6" /></span>Tipos existentes</h2>
                    <span class="adm-chip font-semibold">{{ $tramiteTipos->where('activo', true)->count() }} activos de {{ $tramiteTipos->count() }}</span>
                </div>

                <div class="space-y-3">
                    @foreach($tramiteTipos as $tipo)
                        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-white hover:shadow-md sm:flex-row sm:items-center dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900 {{ $tipo->activo ? '' : 'opacity-70' }}">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $tipo->es_certificacion ? 'bg-[#FACC15]/20 text-[#8a6d00] dark:text-[#FACC15]' : 'bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300' }}">
                                <x-icon :name="$tipo->es_certificacion ? 'document' : 'clipboard'" class="w-6 h-6" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $tipo->nombre }}</p>
                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $tipo->activo ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $tipo->activo ? 'Activo' : 'Inactivo' }}</span>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-300">{{ $tipo->es_certificacion ? 'Certificación' : 'Trámite' }}</span>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('admin.tramite-tipos.toggle', $tipo) }}" data-confirm="{{ $tipo->activo ? '¿Desactivar este tipo de trámite? Dejará de aparecer en el formulario público.' : '¿Activar este tipo de trámite?' }}">
                                    @csrf
                                    <button type="submit" class="adm-btn-sec">{{ $tipo->activo ? 'Desactivar' : 'Activar' }}</button>
                                </form>
                                <a href="{{ route('admin.tramite-tipos.edit', $tipo) }}" class="adm-btn-sec"><x-icon name="wrench" class="w-4 h-4" /> Editar</a>
                                <x-admin-delete :action="route('admin.tramite-tipos.delete', $tipo)" message="¿Eliminar el tipo de trámite «{{ $tipo->nombre }}»? Solo es posible si no tiene solicitudes." />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
