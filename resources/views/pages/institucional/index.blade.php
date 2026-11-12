@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <div class="flex flex-col items-center">
            <x-mascot pose="formal" class="mb-6" sol />
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
        <section id="mision-vision" class="scroll-mt-24 mb-20">

            <h2 class="mb-8 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30 shrink-0"><x-icon name="target" class="w-6 h-6" /></span>
                <x-section-title as="span">Misión y Visión</x-section-title>
            </h2>

            <div class="grid gap-6 lg:grid-cols-2">

                @foreach ([
                    ['target', 'Nuestra razón de ser', 'Misión',
                     'Somos una entidad sin ánimo de lucro del sector solidario caracterizada por la acción ética, la ayuda mutua y la corresponsabilidad, comprometida con el bienestar y las condiciones de vida dignas, justas y empáticas de los asociados y sus beneficiarios a través del fomento del ahorro y soluciones financieras accesibles, responsables, equitativas y oportunas; propendiendo por la sustentabilidad de la vida y las relaciones cooperativas solidarias como aporte a la paz territorial.',
                     'bg-linear-to-br from-[#0C67A3] to-[#0B4870] shadow-[#0C67A3]/40', 'text-[#0C67A3] dark:text-sky-300', 'bg-[#0C67A3]/15 dark:bg-sky-400/20', 'bg-linear-to-r from-[#0C67A3] to-sky-400'],
                    ['eye', 'Hacia dónde vamos', 'Visión',
                     'En 2034, Foncaldas se consolidará como referente y generador de transformación del sector solidario de las universidades del Eje Cafetero, promoviendo valores éticos como la solidaridad, la transparencia, la democracia participativa y la responsabilidad; a través de la innovación y la adaptación constante a nuevas tendencias y tecnologías, ofreceremos un ecosistema de servicios que potencie el bienestar, el ocio y la recreación, siempre enfocados en el desarrollo integral de nuestros asociados.',
                     'bg-linear-to-br from-[#12A594] to-[#0B8B7B] shadow-[#0B8B7B]/40', 'text-[#0B8B7B] dark:text-teal-300', 'bg-[#0B8B7B]/15 dark:bg-teal-400/20', 'bg-linear-to-r from-[#0B8B7B] to-emerald-400'],
                ] as [$icono, $antetitulo, $titulo, $texto, $ficha, $acento, $halo, $barra])
                    <div class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 md:p-9 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10 dark:border-white/10 dark:bg-slate-800">
                        <span class="absolute inset-x-0 top-0 h-1.5 {{ $barra }}" aria-hidden="true"></span>
                        <span class="absolute -right-10 -top-10 h-40 w-40 rounded-full {{ $halo }} blur-3xl" aria-hidden="true"></span>
                        <x-icon :name="$icono" class="pointer-events-none absolute -bottom-10 -right-8 h-44 w-44 {{ $acento }} opacity-[0.07] transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110" />

                        <div class="relative flex items-center gap-4">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $ficha }} text-white shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                <x-icon :name="$icono" class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest {{ $acento }}">{{ $antetitulo }}</p>
                                <h3 class="hero-title text-2xl md:text-3xl text-slate-900 dark:text-white">{{ $titulo }}</h3>
                            </div>
                        </div>

                        <p class="relative mt-6 text-base md:text-[17px] leading-relaxed text-slate-700 dark:text-slate-200">{{ $texto }}</p>
                    </div>
                @endforeach

            </div>

        </section>

        <!-- ================= ÓRGANOS DE DIRECCIÓN Y PERSONAL ================= -->
        <section id="organos-direccion" class="scroll-mt-24 mb-20">

            <h2 class="mb-8 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30 shrink-0"><x-icon name="users" class="w-6 h-6" /></span>
                <x-section-title as="span">Órganos de Dirección y Personal</x-section-title>
            </h2>

            <div class="grid gap-6 lg:grid-cols-2 lg:items-start">

                <x-comite titulo="Junta Directiva (2025 - 2027)" class="lg:col-span-2">
                    <div class="grid sm:grid-cols-2 gap-6 items-start">
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
                    <div class="grid sm:grid-cols-2 gap-6 items-start">
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
                    <div class="grid sm:grid-cols-2 gap-6 items-start">
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

                <x-comite titulo="Planta de Personal" borde="border-[#0B8B7B]" class="lg:col-span-2">
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

                <x-comite titulo="Planta de Personal – Sede Villa Beatriz" class="lg:col-span-2">
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

            <h2 class="mb-8 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30 shrink-0"><x-icon name="layers" class="w-6 h-6" /></span>
                <x-section-title as="span">Organigrama</x-section-title>
            </h2>

            <div class="rounded-3xl border border-slate-200 bg-white p-4 sm:p-8 shadow-sm overflow-x-auto dark:border-white/10 dark:bg-slate-800">
                <img src="{{ asset('images/organigrama.webp') }}"
                     alt="Organigrama de Foncaldas"
                     loading="lazy"
                     class="w-full h-auto rounded-2xl">
            </div>

        </section>

    </div>

</div>

@endsection
