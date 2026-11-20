@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-admin-tabs
                active="parametros-financieros"
                title="Parámetros financieros"
                icon="card"
                subtitle="Consulta los parámetros registrados y sus periodos de vigencia." />

            <div class="mt-6 bg-slate-800 border border-slate-700 rounded-2xl shadow-xl overflow-hidden">

                {{-- Encabezado --}}
                <div class="p-6 border-b border-slate-700">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>

                            <h2 class="text-xl font-semibold text-white">
                                {{ $producto->nombre }}
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Tipo: {{ ucfirst($producto->tipo) }}
                            </p>

                        </div>

                        <a
                            href="{{ route('admin.parametros-financieros.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                bg-slate-700 hover:bg-slate-600
                                text-white text-sm font-medium transition">

                            Volver a parámetros financieros
                        </a>

                    </div>

                </div>


                {{-- Contenido --}}
                <div class="p-6">

                    @if ($parametros->isEmpty())

                        <div class="border border-dashed border-slate-600 rounded-xl p-8 text-center">

                            <p class="text-slate-300 font-medium">
                                Este producto todavía no tiene parámetros registrados.
                            </p>

                            <p class="text-sm text-slate-500 mt-2">
                                Cuando se registren nuevos parámetros aparecerán aquí
                                sin eliminar los registros anteriores.
                            </p>

                        </div>

                    @else

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm text-left">

                                <thead class="text-xs uppercase text-slate-400 border-b border-slate-700">

                                    <tr>

                                        <th class="px-4 py-3">
                                            Parámetro
                                        </th>

                                        <th class="px-4 py-3">
                                            Valor
                                        </th>

                                        <th class="px-4 py-3">
                                            Unidad
                                        </th>

                                        <th class="px-4 py-3">
                                            Categoría
                                        </th>

                                        <th class="px-4 py-3">
                                            Origen
                                        </th>

                                        <th class="px-4 py-3">
                                            Vigencia
                                        </th>

                                        <th class="px-4 py-3">
                                            Estado
                                        </th>

                                        <th class="px-4 py-3">
                                            Observación
                                        </th>

                                        <th class="px-4 py-3">
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-700">

                                    @foreach ($parametros as $parametro)

                                        @php
                                            $hoy = now()->toDateString();

                                            $esVigente =
                                                $parametro->activo
                                                && $parametro->fecha_inicio_vigencia->toDateString() <= $hoy
                                                && (
                                                    is_null($parametro->fecha_fin_vigencia)
                                                    || $parametro->fecha_fin_vigencia->toDateString() >= $hoy
                                                );

                                            $esFuturo =
                                                $parametro->activo
                                                && $parametro->fecha_inicio_vigencia->toDateString() > $hoy;

                                            if (in_array($parametro->tipo_dato, ['decimal', 'entero'])) {

                                                $valorMostrar = rtrim(
                                                    rtrim(
                                                        number_format(
                                                            (float) $parametro->valor,
                                                            6,
                                                            '.',
                                                            ''
                                                        ),
                                                        '0'
                                                    ),
                                                    '.'
                                                );

                                            } elseif ($parametro->tipo_dato === 'booleano') {

                                                $valorMostrar =
                                                    $parametro->valor_texto === 'true'
                                                        ? 'Sí'
                                                        : 'No';

                                            } else {

                                                $valorMostrar =
                                                    $parametro->valor_texto ?? '—';
                                            }
                                        @endphp


                                        <tr class="text-slate-200">

                                            {{-- Parámetro --}}
                                            <td class="px-4 py-4">

                                                <div class="font-semibold">
                                                    {{ $parametro->nombre }}
                                                </div>

                                                <div class="text-xs text-slate-500 mt-1">
                                                    {{ $parametro->codigo }}
                                                </div>

                                            </td>


                                            {{-- Valor --}}
                                            <td class="px-4 py-4 font-semibold">
                                                {{ $valorMostrar }}
                                            </td>


                                            {{-- Unidad --}}
                                            <td class="px-4 py-4">
                                                {{ $parametro->unidad ?? '—' }}
                                            </td>


                                            {{-- Categoría --}}
                                            <td class="px-4 py-4">
                                                {{ ucfirst($parametro->categoria ?? '—') }}
                                            </td>


                                            {{-- Origen --}}
                                            <td class="px-4 py-4">

                                                @if ($parametro->origen === 'confirmado')

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-emerald-900/50 text-emerald-300">

                                                        Confirmado
                                                    </span>

                                                @elseif ($parametro->origen === 'referencia')

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-blue-900/50 text-blue-300">

                                                        Referencia
                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-amber-900/50 text-amber-300">

                                                        Pendiente
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Vigencia --}}
                                            <td class="px-4 py-4">

                                                <div>
                                                    {{ $parametro->fecha_inicio_vigencia->format('d/m/Y') }}
                                                </div>

                                                <div class="text-xs text-slate-500 mt-1">

                                                    @if ($parametro->fecha_fin_vigencia)

                                                        hasta
                                                        {{ $parametro->fecha_fin_vigencia->format('d/m/Y') }}

                                                    @else

                                                        Sin fecha final

                                                    @endif

                                                </div>

                                            </td>


                                            {{-- Estado --}}
                                            <td class="px-4 py-4">

                                                @if (!$parametro->activo)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-slate-700 text-slate-300">

                                                        Inactivo
                                                    </span>

                                                @elseif ($esVigente)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-emerald-900/50 text-emerald-300">

                                                        Vigente
                                                    </span>

                                                @elseif ($esFuturo)

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-blue-900/50 text-blue-300">

                                                        Programado
                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                            bg-slate-700 text-slate-300">

                                                        Histórico
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Observación --}}
                                            <td class="px-4 py-4 text-slate-400">
                                                {{ $parametro->descripcion ?? '—' }}
                                            </td>


                                            {{-- Acciones --}}
                                            <td class="px-4 py-4">
                                                @if ($parametro->esSoloLecturaEnPanel())
                                                    <span class="text-xs text-slate-400">Solo lectura</span>
                                                @else

                                                    <div class="flex flex-wrap gap-2">

                                                        {{-- Editar --}}
                                                        <a
                                                            href="{{ route(
                                                                'admin.parametros-financieros.tasas.editar',
                                                                $parametro
                                                            ) }}"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-full
                                                                bg-sky-700 hover:bg-sky-600
                                                                text-white text-xs font-medium transition">

                                                            Editar
                                                        </a>


                                                        @if ($parametro->estaProtegido())
                                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-700 text-slate-300"
                                                                title="Para desactivarlo o eliminarlo, edítalo y marca «Permitir modificación desde el panel administrativo».">
                                                                Protegido
                                                            </span>
                                                        @else

                                                        {{-- Desactivar --}}
                                                        @if ($parametro->activo)

                                                            <form
                                                                method="POST"
                                                                action="{{ route(
                                                                    'admin.parametros-financieros.tasas.desactivar',
                                                                    $parametro
                                                                ) }}"
                                                                class="form-confirmacion"
                                                                data-accion="desactivar"
                                                                data-parametro="{{ $parametro->nombre }}">

                                                                @csrf
                                                                @method('PATCH')

                                                                <button
                                                                    type="submit"
                                                                    class="inline-flex items-center px-3 py-1.5 rounded-full
                                                                        bg-amber-700 hover:bg-amber-600
                                                                        text-white text-xs font-medium transition">

                                                                    Desactivar
                                                                </button>

                                                            </form>

                                                        @endif


                                                        {{-- Eliminar --}}
                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.parametros-financieros.parametros.eliminar',
                                                                $parametro
                                                            ) }}"
                                                            class="form-confirmacion"
                                                            data-accion="eliminar"
                                                            data-parametro="{{ $parametro->nombre }}">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 rounded-full
                                                                    bg-red-700 hover:bg-red-600
                                                                    text-white text-xs font-medium transition">

                                                                Eliminar
                                                            </button>

                                                        </form>

                                                        @endif

                                                    </div>
                                                @endif
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- MODAL DE CONFIRMACIÓN --}}
    <div
        id="modalConfirmacion"
        class="fixed inset-0 z-50 hidden items-center justify-center
            bg-black/70 backdrop-blur-sm px-4">

        <div
            class="w-full max-w-md overflow-hidden rounded-2xl
                border border-slate-700
                bg-slate-800 shadow-2xl">

            {{-- Contenido --}}
            <div class="p-6">

                <div class="flex items-start gap-4">

                    {{-- Icono --}}
                    <div
                        id="iconoModal"
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                            rounded-full">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />

                        </svg>

                    </div>


                    {{-- Texto --}}
                    <div class="flex-1">

                        <h3
                            id="tituloModal"
                            class="text-lg font-semibold text-white">
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-300">

                            <span id="mensajeModal"></span>

                            <span
                                id="nombreParametroModal"
                                class="font-semibold text-white">
                            </span>

                            <span>?</span>

                        </p>

                        <p
                            id="detalleModal"
                            class="mt-2 text-sm leading-5 text-slate-400">
                        </p>

                    </div>

                </div>

            </div>


            {{-- Botones --}}
            <div
                class="flex justify-end gap-3
                    border-t border-slate-700
                    bg-slate-900/40 px-6 py-4">

                <button
                    type="button"
                    id="cancelarConfirmacion"
                    class="rounded-full bg-slate-700 px-4 py-2
                        text-sm font-medium text-white
                        hover:bg-slate-600 transition">

                    Cancelar
                </button>

                <button
                    type="button"
                    id="confirmarAccion"
                    class="rounded-lg px-4 py-2
                        text-sm font-semibold text-white transition">

                    Confirmar
                </button>

            </div>

        </div>

    </div>


    {{-- CONTROL DEL MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modal =
                document.getElementById('modalConfirmacion');

            const titulo =
                document.getElementById('tituloModal');

            const mensaje =
                document.getElementById('mensajeModal');

            const nombreParametro =
                document.getElementById('nombreParametroModal');

            const detalle =
                document.getElementById('detalleModal');

            const icono =
                document.getElementById('iconoModal');

            const botonCancelar =
                document.getElementById('cancelarConfirmacion');

            const botonConfirmar =
                document.getElementById('confirmarAccion');

            let formularioActual = null;


            function abrirModal(formulario) {

                formularioActual = formulario;

                const accion =
                    formulario.dataset.accion;

                const nombre =
                    formulario.dataset.parametro || 'este parámetro';

                nombreParametro.textContent = nombre;


                if (accion === 'eliminar') {

                    titulo.textContent =
                        'Eliminar parámetro';

                    mensaje.textContent =
                        '¿Deseas eliminar definitivamente ';

                    detalle.textContent =
                        'Esta acción no se puede deshacer. El registro será eliminado y dejará de aparecer en el historial.';

                    icono.className =
                        'flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-500/15 text-red-400';

                    botonConfirmar.className =
                        'rounded-full bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition';

                    botonConfirmar.textContent =
                        'Sí, eliminar';

                } else {

                    titulo.textContent =
                        'Desactivar parámetro';

                    mensaje.textContent =
                        '¿Deseas desactivar ';

                    detalle.textContent =
                        'El registro no será eliminado y continuará disponible en el historial para futuras consultas.';

                    icono.className =
                        'flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-amber-400';

                    botonConfirmar.className =
                        'rounded-full bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500 transition';

                    botonConfirmar.textContent =
                        'Sí, desactivar';
                }


                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }


            function cerrarModal() {

                modal.classList.add('hidden');
                modal.classList.remove('flex');

                formularioActual = null;
            }


            document
                .querySelectorAll('.form-confirmacion')
                .forEach(function (formulario) {

                    formulario.addEventListener(
                        'submit',
                        function (event) {

                            event.preventDefault();

                            abrirModal(formulario);
                        }
                    );

                });


            botonCancelar.addEventListener(
                'click',
                cerrarModal
            );


            modal.addEventListener(
                'click',
                function (event) {

                    if (event.target === modal) {
                        cerrarModal();
                    }

                }
            );


            document.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Escape'
                        && !modal.classList.contains('hidden')
                    ) {
                        cerrarModal();
                    }

                }
            );


            botonConfirmar.addEventListener(
                'click',
                function () {

                    if (!formularioActual) {
                        return;
                    }

                    const formulario =
                        formularioActual;

                    formularioActual = null;

                    formulario.submit();
                }
            );

        });
    </script>

@endsection
