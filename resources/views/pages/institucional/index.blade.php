@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <x-page-header
            icon="compass"
            title="Institucional"
            subtitle="Misión, visión, órganos de dirección, personal y organigrama de Foncaldas."
            center />

        <!-- NAV DE SECCIONES -->
        <div class="flex flex-wrap justify-center gap-3 mb-14">
            <a href="#mision-vision" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Misión y Visión</a>
            <a href="#organos-direccion" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Órganos de Dirección</a>
            <a href="#organigrama" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Organigrama</a>
        </div>

        <!-- ================= MISIÓN Y VISIÓN ================= -->
        <section id="mision-vision" class="scroll-mt-24 mb-16 max-w-4xl mx-auto">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400 dark:text-slate-900 dark:shadow-lg dark:shadow-sky-400/30 shrink-0"><x-icon name="target" class="w-5 h-5 dark:w-7 dark:h-7" /></span>
                <x-section-title as="span">Misión y Visión</x-section-title>
            </h2>

            <div class="space-y-6">

                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                    <h3 class="text-lg font-semibold mb-4 text-[#0C67A3] dark:text-sky-200 flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 dark:h-14 dark:w-14 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-linear-to-br dark:from-sky-300 dark:to-blue-500 dark:text-white dark:ring-2 dark:ring-sky-200/60 shrink-0">
                            <x-icon name="target" class="w-5 h-5 dark:w-7 dark:h-7" />
                        </span>
                        Misión
                    </h3>
                    <p class="leading-relaxed text-[15px] text-slate-600 dark:text-slate-400">
                        Somos una entidad sin ánimo de lucro del sector solidario caracterizada por la acción ética,
                        la ayuda mutua y la corresponsabilidad, comprometida con el bienestar y las condiciones de vida
                        dignas, justas y empáticas de los asociados y sus beneficiarios a través del fomento del ahorro
                        y soluciones financieras accesibles, responsables, equitativas y oportunas; propendiendo por la
                        sustentabilidad de la vida y las relaciones cooperativas solidarias como aporte a la paz territorial.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                    <h3 class="text-lg font-semibold mb-4 text-[#0B8B7B] dark:text-teal-200 flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 dark:h-14 dark:w-14 items-center justify-center rounded-2xl bg-[#0B8B7B]/10 text-[#0B8B7B] dark:bg-linear-to-br dark:from-emerald-300 dark:to-teal-500 dark:text-white dark:ring-2 dark:ring-emerald-200/60 shrink-0">
                            <x-icon name="eye" class="w-5 h-5 dark:w-7 dark:h-7" />
                        </span>
                        Visión
                    </h3>
                    <p class="leading-relaxed text-[15px] text-slate-600 dark:text-slate-400">
                        En 2034, Foncaldas se consolidará como referente y generador de transformación del sector solidario
                        de las universidades del Eje Cafetero, promoviendo valores éticos como la solidaridad, la transparencia,
                        la democracia participativa y la responsabilidad; a través de la innovación y la adaptación constante a
                        nuevas tendencias y tecnologías, ofreceremos un ecosistema de servicios que potencie el bienestar,
                        el ocio y la recreación, siempre enfocados en el desarrollo integral de nuestros asociados.
                    </p>
                </div>

            </div>

        </section>

        <!-- ================= ÓRGANOS DE DIRECCIÓN Y PERSONAL ================= -->
        <section id="organos-direccion" class="scroll-mt-24 mb-16">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400 dark:text-slate-900 dark:shadow-lg dark:shadow-sky-400/30 shrink-0"><x-icon name="users" class="w-5 h-5" /></span>
                <x-section-title as="span">Órganos de Dirección y Personal</x-section-title>
            </h2>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-10 space-y-12">

                {{-- ================= JUNTA DIRECTIVA ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0C67A3]">
                        Junta Directiva (2025 - 2027)
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-4 items-start">

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Principales</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Juan Carlos Botero Soto</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Cesar Augusto Alzate Ospina</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Alberto Gómez Giraldo</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Sebastián Gutiérrez Patiño</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>María del Carmen Montoya Chalarca</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Diana Duque Salazar</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Rosa Marleny Montes Rincón</span></li>
                            </ul>
                        </div>

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Suplentes</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Carlos Federico Ayala Zuluaga</span></li>
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- ================= COMITÉ CONTROL SOCIAL ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0B8B7B]">
                        Comité de Control Social (2024 - 2025)
                    </h3>

                    <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                        <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Jairo Plata</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Diana Rocío Varón Serna</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>María de los Ángeles Núñez</span></li>
                        </ul>
                    </div>
                </div>

                {{-- ================= COMITÉ APELACIONES ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0C67A3]">
                        Comité de Apelaciones (2024 - 2026)
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-4 items-start">

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Principales</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Alba Lucía Jiménez Giraldo</span></li>
                            </ul>
                        </div>

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Suplentes</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Cecilia Osorio Dávila</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>German Gabriel Corredor Rengifo</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Orlando Castro Alarcón</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Monica Alexandra Hernández Palacio</span></li>
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- ================= COMITÉ DE RIESGOS ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0B8B7B]">
                        Comité de Riesgos
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-4 items-start">

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Riesgo de Liquidez</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Helmer Quintero (Presidente)</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Diana Duque Salazar (Representante Junta)</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Rubén Darío Cárdenas (Asociado)</span></li>
                            </ul>
                        </div>

                        <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-[#0C67A3] mb-3">Riesgos</h4>
                            <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>German Gabriel Corredor (Asociado)</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>José Daniel Correa (Oficial de Cumplimiento)</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Juan Carlos Botero Soto (Presidente-Representante Junta)</span></li>
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- ================= CRÉDITOS ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0C67A3]">
                        Comité de Créditos
                    </h3>

                    <div class="bg-[#F8FAFC] dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                        <ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Alberto Gómez Giraldo (Presidente-Representante Junta)</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>German Gabriel Corredor (Asociado)</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Aleida Duque Arias (Asociado)</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>Camilo Andrés Montoya Arias (Coordinador de Cartera)</span></li>
                            <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>José Daniel Correa (Oficial de Cumplimiento)</span></li>
                        </ul>
                    </div>
                </div>

                {{-- ================= PERSONAL ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0B8B7B]">
                        Planta de Personal
                    </h3>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                        <table class="w-full text-left">
                            <thead class="bg-[#0B4870] text-white">
                                <tr>
                                    <th class="p-3 text-sm font-semibold">Nombre</th>
                                    <th class="p-3 text-sm font-semibold">Cargo</th>
                                    <th class="p-3 text-sm font-semibold">Sede</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 dark:text-slate-300 text-[15px]">
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Germán Darío Correa Galvis</td>
                                    <td class="p-3">Gerente</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">José Daniel Correa</td>
                                    <td class="p-3">Oficial de Cumplimiento</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Luz María Gómez Vélez</td>
                                    <td class="p-3">Secretaria</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Viviana Bermúdez Salazar</td>
                                    <td class="p-3">Tesorería</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Jorge Trujillo</td>
                                    <td class="p-3">Auxiliar de Tesorería</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Yenny Viviana Valero Castrillón</td>
                                    <td class="p-3">Contadora</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Camilo Andrés Montoya Arias</td>
                                    <td class="p-3">Coordinador de cartera y créditos</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Santiago Granada</td>
                                    <td class="p-3">Auxiliar de créditos</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Laura Ríos Franco</td>
                                    <td class="p-3">Líder de bienestar</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Jesús David Fuquene</td>
                                    <td class="p-3">Analista de Comunicaciones</td>
                                    <td class="p-3">Administrativa</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= SEDE VILLA BEATRIZ ================= --}}
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-5 pl-4 border-l-4 border-[#0C67A3]">
                        Planta de Personal – Sede Villa Beatriz
                    </h3>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                        <table class="w-full text-left">
                            <thead class="bg-[#0B8B7B] text-white">
                                <tr>
                                    <th class="p-3 text-sm font-semibold">Nombre</th>
                                    <th class="p-3 text-sm font-semibold">Cargo</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 dark:text-slate-300 text-[15px]">
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Jessica Juliana Ríos Gañan</td>
                                    <td class="p-3">Administradora</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Carlos Alberto Pamplona Castaño</td>
                                    <td class="p-3">Oficios Varios</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Gabriel Andrés Vega</td>
                                    <td class="p-3">Oficios Varios</td>
                                </tr>
                                <tr class="odd:bg-white dark:odd:bg-slate-800 even:bg-[#F8FAFC] dark:even:bg-slate-700/40">
                                    <td class="p-3">Leticia Zapata Rincón</td>
                                    <td class="p-3">Camarera</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </section>

        <!-- ================= ORGANIGRAMA ================= -->
        <section id="organigrama" class="scroll-mt-24">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400 dark:text-slate-900 dark:shadow-lg dark:shadow-sky-400/30 shrink-0"><x-icon name="layers" class="w-5 h-5" /></span>
                <x-section-title as="span">Organigrama</x-section-title>
            </h2>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 overflow-x-auto">
                <img src="{{ asset('images/organigrama.png') }}"
                     alt="Organigrama de Foncaldas"
                     loading="lazy"
                     class="w-full h-auto rounded-2xl">
            </div>

        </section>

    </div>

</div>

@endsection
