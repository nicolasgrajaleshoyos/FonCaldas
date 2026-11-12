@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <div class="flex flex-col items-center">
            <x-mascot pose="formal" class="mb-4" />
            <x-page-header
                icon="compass"
                title="Institucional"
                subtitle="Misión, visión, órganos de dirección, personal y organigrama de Foncaldas."
                center />
        </div>

        <x-nav-secciones :secciones="[
            ['mision-vision', 'Misión y Visión'],
            ['organos-direccion', 'Órganos de Dirección'],
            ['organigrama', 'Organigrama'],
        ]" />

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

                <x-comite titulo="Junta Directiva (2025 - 2027)">
                    <div class="grid sm:grid-cols-2 gap-4 items-start">
                        <x-caja-integrantes titulo="Principales" :nombres="[
                            'Juan Carlos Botero Soto',
                            'Cesar Augusto Alzate Ospina',
                            'Alberto Gómez Giraldo',
                            'Sebastián Gutiérrez Patiño',
                            'María del Carmen Montoya Chalarca',
                            'Diana Duque Salazar',
                            'Rosa Marleny Montes Rincón',
                        ]" />
                        <x-caja-integrantes titulo="Suplentes" :nombres="['Carlos Federico Ayala Zuluaga']" />
                    </div>
                </x-comite>

                <x-comite titulo="Comité de Control Social (2024 - 2025)" borde="border-[#0B8B7B]">
                    <x-caja-integrantes :nombres="['Jairo Plata', 'Diana Rocío Varón Serna', 'María de los Ángeles Núñez']" />
                </x-comite>

                <x-comite titulo="Comité de Apelaciones (2024 - 2026)">
                    <div class="grid sm:grid-cols-2 gap-4 items-start">
                        <x-caja-integrantes titulo="Principales" :nombres="['Alba Lucía Jiménez Giraldo']" />
                        <x-caja-integrantes titulo="Suplentes" :nombres="[
                            'Cecilia Osorio Dávila',
                            'German Gabriel Corredor Rengifo',
                            'Orlando Castro Alarcón',
                            'Monica Alexandra Hernández Palacio',
                        ]" />
                    </div>
                </x-comite>

                <x-comite titulo="Comité de Riesgos" borde="border-[#0B8B7B]">
                    <div class="grid sm:grid-cols-2 gap-4 items-start">
                        <x-caja-integrantes titulo="Riesgo de Liquidez" :nombres="[
                            'Helmer Quintero (Presidente)',
                            'Diana Duque Salazar (Representante Junta)',
                            'Rubén Darío Cárdenas (Asociado)',
                        ]" />
                        <x-caja-integrantes titulo="Riesgos" :nombres="[
                            'German Gabriel Corredor (Asociado)',
                            'José Daniel Correa (Oficial de Cumplimiento)',
                            'Juan Carlos Botero Soto (Presidente-Representante Junta)',
                        ]" />
                    </div>
                </x-comite>

                <x-comite titulo="Comité de Créditos">
                    <x-caja-integrantes :nombres="[
                        'Alberto Gómez Giraldo (Presidente-Representante Junta)',
                        'German Gabriel Corredor (Asociado)',
                        'Aleida Duque Arias (Asociado)',
                        'Camilo Andrés Montoya Arias (Coordinador de Cartera)',
                        'José Daniel Correa (Oficial de Cumplimiento)',
                    ]" />
                </x-comite>

                <x-comite titulo="Planta de Personal" borde="border-[#0B8B7B]">
                    <x-tabla-personal :columnas="['Nombre', 'Cargo', 'Sede']" :filas="[
                        ['Germán Darío Correa Galvis', 'Gerente', 'Administrativa'],
                        ['José Daniel Correa', 'Oficial de Cumplimiento', 'Administrativa'],
                        ['Luz María Gómez Vélez', 'Secretaria', 'Administrativa'],
                        ['Viviana Bermúdez Salazar', 'Tesorería', 'Administrativa'],
                        ['Jorge Trujillo', 'Auxiliar de Tesorería', 'Administrativa'],
                        ['Yenny Viviana Valero Castrillón', 'Contadora', 'Administrativa'],
                        ['Camilo Andrés Montoya Arias', 'Coordinador de cartera y créditos', 'Administrativa'],
                        ['Santiago Granada', 'Auxiliar de créditos', 'Administrativa'],
                        ['Laura Ríos Franco', 'Líder de bienestar', 'Administrativa'],
                        ['Juan Felipe Arango', 'Analista de Comunicaciones', 'Administrativa'],
                    ]" />
                </x-comite>

                <x-comite titulo="Planta de Personal – Sede Villa Beatriz">
                    <x-tabla-personal fondo="bg-[#0B8B7B]" :columnas="['Nombre', 'Cargo']" :filas="[
                        ['Jessica Juliana Ríos Gañan', 'Administradora'],
                        ['Carlos Alberto Pamplona Castaño', 'Oficios Varios'],
                        ['Gabriel Andrés Vega', 'Oficios Varios'],
                        ['Leticia Zapata Rincón', 'Camarera'],
                    ]" />
                </x-comite>

            </div>

        </section>

        <!-- ================= ORGANIGRAMA ================= -->
        <section id="organigrama" class="scroll-mt-24">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400 dark:text-slate-900 dark:shadow-lg dark:shadow-sky-400/30 shrink-0"><x-icon name="layers" class="w-5 h-5" /></span>
                <x-section-title as="span">Organigrama</x-section-title>
            </h2>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 overflow-x-auto">
                <img src="{{ asset('images/organigrama.webp') }}"
                     alt="Organigrama de Foncaldas"
                     loading="lazy"
                     class="w-full h-auto rounded-2xl">
            </div>

        </section>

    </div>

</div>

@endsection
