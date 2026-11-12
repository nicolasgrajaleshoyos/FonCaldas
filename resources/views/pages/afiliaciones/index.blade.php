@extends('layouts.app')

@section('content')

@php
    $requisitos = [
        'Anexar fotocopia de la cédula',
        'Diligenciar formulario de vinculación SARLAFT',
        'Cuota de admisión (10% SMMLV)',
        'Aporte mensual del 3% al 10% del salario',
    ];

    // Pasos 1 a 4; el paso 5 (bienvenida) tiene un diseño propio y se escribe aparte.
    $pasos = [
        ['Contacta a la asesora', 'Escríbele por WhatsApp para confirmar que cumples el vínculo laboral requerido y resolver tus dudas.'],
        ['Diligencia los formularios', 'Completa el formulario de vinculación y el formulario SARLAFT con tus datos personales.'],
        ['Presenta tu cédula', 'Anexa una fotocopia de tu cédula de ciudadanía a la solicitud.'],
        ['Realiza tus aportes', 'Paga la cuota de admisión (10% del SMMLV) e inicia tu aporte mensual, entre el 3% y el 10% de tu salario.'],
    ];

    // [icono, título, descripción]
    $beneficios = [
        ['money', 'Tasas Preferenciales', 'Acceso a tasas de interés especiales en créditos y productos de ahorro diseñados para socios.'],
        ['sparkle', 'Productos Exclusivos', 'Acceso a productos y servicios financieros diseñados exclusivamente para nuestros socios.'],
        ['briefcase', 'Convenios y Descuentos', 'Descuentos especiales en comercios aliados y servicios empresariales en toda la región.'],
        ['shield', 'Protección y Seguros', 'Coberturas de seguros especializados para proteger tu dinero y tus préstamos.'],
        ['graduation', 'Capacitación y Educación', 'Acceso a talleres y programas de educación financiera para mejorar tu gestión económica.'],
        ['ballot', 'Participación Democrática', 'Voz y voto en las asambleas generales y decisiones de la cooperativa.'],
        ['gift', 'Programas Especiales', 'Acceso a promociones especiales, sorteos y premios exclusivos para miembros.'],
    ];

    $porQueElegirnos = [
        ['heart', 'Organización solidaria', 'Fundada en principios de cooperación y ayuda mutua'],
        ['users', 'Atención personalizada', 'Servicio cercano y profesional para cada socio'],
        ['trending-up', 'Mejores tasas del mercado', 'Las mejores tasas de interés del mercado'],
        ['lock', 'Seguridad garantizada', 'Regulación y supervisión financiera completa'],
    ];
@endphp

<div class="min-h-screen page-bg py-14">

    <div class="max-w-4xl mx-auto px-6">

        <div class="flex flex-col items-center">
            <x-mascot pose="pulgar" class="mb-4" />
            <x-page-header
                icon="users"
                title="Afiliaciones"
                subtitle="Todo lo que necesitas para saber si puedes asociarte, cómo hacerlo y qué beneficios obtienes."
                center />
        </div>

        <x-nav-secciones :secciones="[
            ['quien-puede-ser-socio', 'Quién puede ser socio'],
            ['como-asociarse', 'Cómo asociarse'],
            ['beneficios', 'Beneficios'],
        ]" />

        <!-- ================= QUIÉN PUEDE SER SOCIO ================= -->
        <section id="quien-puede-ser-socio" class="scroll-mt-24 mb-16">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0"><x-icon name="users" class="w-5 h-5" /></span>
                <x-section-title as="span">¿Quién puede ser socio?</x-section-title>
            </h2>

            <div class="grid gap-6 lg:grid-cols-2">

                <!-- Vínculo requerido: tarjeta destacada -->
                <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-8 md:p-10 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15">
                    <x-icon name="users" class="pointer-events-none absolute -bottom-10 -right-8 h-52 w-52 text-white/10" />
                    <p class="relative text-xs font-semibold uppercase tracking-widest text-[#FACC15]">Vínculo requerido</p>
                    <p class="hero-title relative mt-3 text-2xl md:text-[1.75rem] leading-snug">
                        Personas con vínculo laboral con universidades del Eje Cafetero.
                    </p>
                    <p class="relative mt-4 text-base leading-relaxed text-white/85">
                        Pueden aspirar a ser asociados a Foncaldas las personas naturales que cumplan ese vínculo.
                    </p>
                </div>

                <!-- Requisitos -->
                <div class="{{ $tarjeta }} p-8 md:p-10">
                    <h3 class="hero-title text-2xl text-slate-900 dark:text-white">Requisitos de vinculación</h3>
                    <ul class="mt-6 space-y-4">
                        @foreach ($requisitos as $requisito)
                            <li class="flex items-center gap-4 text-base md:text-[17px] text-slate-700 dark:text-slate-200">
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0B8B7B]/15 text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300"><x-icon name="check" class="w-4 h-4" /></span>
                                {{ $requisito }}
                            </li>
                        @endforeach
                    </ul>
                </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-5">Requisitos de vinculación</h3>
                <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                    @foreach ($requisitos as $requisito)
                        <li class="flex items-start gap-2"><x-icon name="check" class="w-4 h-4 mt-1 text-[#0C67A3] shrink-0" /> {{ $requisito }}</li>
                    @endforeach
                </ul>
            </div>

        </section>

        <!-- ================= CÓMO ASOCIARSE ================= -->
        <section id="como-asociarse" class="scroll-mt-24 mb-16">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0"><x-icon name="compass" class="w-5 h-5" /></span>
                <x-section-title as="span">¿Cómo asociarse?</x-section-title>
            </h2>

            <!-- ASESORA -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Habla con nuestra asesora</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-1">
                        Liliana Quintero Zuluaga te acompaña durante todo el proceso de afiliación.
                    </p>
                </div>
                <a href="https://wa.me/573245048815" target="_blank" rel="noopener"
                   class="shrink-0 inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                    <x-icon name="whatsapp" class="w-4 h-4" />
                    314 504 8815
                </a>
            </div>

            <!-- STEPS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach ($pasos as $indice => [$titulo, $detalle])
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-10 h-10 rounded-full bg-[#0C67A3] text-white flex items-center justify-center font-bold shrink-0">{{ $indice + 1 }}</span>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $titulo }}</h3>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed">
                            {{ $detalle }}
                        </p>
                    </div>
                @endforeach

                <div class="md:col-span-2 bg-[#0B4870] rounded-3xl p-8 text-white shadow-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-10 h-10 rounded-full bg-white text-[#0B4870] flex items-center justify-center font-bold shrink-0">5</span>
                        <h3 class="text-lg font-semibold">Aprobación y bienvenida</h3>
                    </div>
                    <p class="text-white/80 text-[14px] leading-relaxed max-w-2xl">
                        Tu solicitud será revisada y aprobada. Bienvenido a la familia Foncaldas,
                        con acceso inmediato a todos los servicios.
                    </p>
                </div>

            </div>

            <!-- FORMULARIO PDF -->
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0">
                        <x-icon name="document" class="w-5 h-5" />
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Formulario de afiliación</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-1">Descarga o visualiza el documento oficial en PDF.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 shrink-0">
                    <a href="{{ asset('pdf/Formulariovinculacion.pdf') }}" target="_blank" rel="noopener"
                       class="px-6 py-3 bg-[#0B4870] text-white font-semibold rounded-lg hover:bg-[#0a3d60] transition">
                        Ver PDF
                    </a>
                    <a href="{{ asset('pdf/Formulariovinculacion.pdf') }}" download
                       class="px-6 py-3 border border-slate-300 text-slate-800 dark:text-slate-100 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                        Descargar
                    </a>
                </div>
            </div>

        </section>

        <!-- ================= BENEFICIOS ================= -->
        <section id="beneficios" class="scroll-mt-24">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0"><x-icon name="trophy" class="w-5 h-5" /></span>
                <x-section-title as="span">Beneficios de ser socio</x-section-title>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach ($beneficios as [$icono, $titulo, $detalle])
                    {{-- Con un número impar de tarjetas, la última ocupa todo el ancho. --}}
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700{{ $loop->last ? ' md:col-span-2' : '' }}">
                        <div class="inline-flex items-center gap-3 mb-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon :name="$icono" class="w-5 h-5" /></span>
                            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $titulo }}</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400">
                            {{ $detalle }}
                        </p>
                    </div>
                @endforeach

            </div>

            <!-- WHY US -->
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-3xl p-10 border border-slate-100 dark:border-slate-700 shadow-sm">

                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">¿Por qué elegirnos?</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-700 dark:text-slate-300">

                    @foreach ($porQueElegirnos as [$icono, $titulo, $detalle])
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon :name="$icono" class="w-5 h-5" /></span>
                            <div>
                                <p class="font-semibold">{{ $titulo }}</p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">{{ $detalle }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </section>

    </div>

</div>

@endsection
