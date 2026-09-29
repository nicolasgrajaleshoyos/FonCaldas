@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">
    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="card"
            title="Simulación de crédito"
            subtitle="Estimación orientativa. Las condiciones serán confirmadas por FONCALDAS." />

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-end">

                <!-- COLUMNA IZQUIERDA -->
                <div class="lg:col-span-2 space-y-5">

                    <div>
                        <label for="producto_credito"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            Selecciona un producto
                        </label>

                        <select id="producto_credito"
                                name="producto_credito"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                        bg-white dark:bg-slate-900
                                        text-slate-800 dark:text-slate-100
                                        px-4 py-3
                                        focus:outline-none focus:ring-2 focus:ring-[#0C67A3]">

                            <option value="">Línea de crédito</option>
                            <option value="avances">Crédito Avances</option>
                            <option value="bonos">Crédito Bonos</option>
                            <option value="bonificacion">Crédito Bonificación</option>
                            <option value="cuota_unica_junio">Crédito Cuota Única - junio</option>
                            <option value="cuota_unica_diciembre">Crédito Cuota Única - diciembre</option>
                            <option value="vehiculo">Crédito de Vehículo</option>
                            <option value="libre_inversion">Crédito Libre Inversión</option>
                            <option value="compra_cartera">Compra de Cartera</option>
                            <option value="motocicleta">Crédito de Motocicleta</option>
                            <option value="tecnologia">Crédito de Tecnología</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            Monto solicitado
                        </label>

                        <input type="number"
                                disabled
                                placeholder="Pendiente de confirmación"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                        bg-slate-100 dark:bg-slate-900
                                        text-slate-500 px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            Plazo
                        </label>

                        <input type="text"
                                disabled
                                placeholder="Pendiente de confirmación"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                        bg-slate-100 dark:bg-slate-900
                                        text-slate-500 px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            Dato específico de la línea
                        </label>

                        <input type="text"
                                disabled
                                placeholder="Pendiente de confirmación"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-600
                                        bg-slate-100 dark:bg-slate-900
                                        text-slate-500 px-4 py-3">
                    </div>

                </div>

                <!-- COLUMNA DERECHA -->
                <div class="flex flex-col justify-end">

                    <button type="button"
                            disabled
                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl
                                    bg-[#0B4870]/60
                                    text-white/70
                                    font-semibold cursor-not-allowed">
                        Simular
                    </button>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">
                        Los campos varían según el producto.
                    </p>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection
