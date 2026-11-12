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

@php
    // Paleta que se va alternando en las tarjetas: [ficha del ícono, halo, color de acento]
    $paleta = [
        ['bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-[#0C67A3]/40', 'bg-[#0C67A3]/20 dark:bg-sky-400/20', 'text-[#0C67A3] dark:text-sky-300'],
        ['bg-linear-to-br from-[#12A594] to-[#0B8B7B] text-white shadow-[#0B8B7B]/40', 'bg-[#0B8B7B]/20 dark:bg-teal-400/20', 'text-[#0B8B7B] dark:text-teal-300'],
        ['bg-linear-to-br from-[#FACC15] to-[#EAB308] text-[#0B4870] shadow-[#FACC15]/40', 'bg-[#FACC15]/30 dark:bg-amber-300/20', 'text-[#CA8A04] dark:text-amber-300'],
    ];
    $tituloSeccion = 'inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-white shadow-lg shadow-[#0C67A3]/30 shrink-0';
    $tarjeta = 'rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-800';
@endphp

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <div class="flex flex-col items-center">
            <x-mascot pose="pulgar" class="mb-6" sol />
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
        <section id="quien-puede-ser-socio" class="scroll-mt-24 mb-20">

            <h2 class="mb-8 flex items-center gap-4">
                <span class="{{ $tituloSeccion }}"><x-icon name="users" class="w-6 h-6" /></span>
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

            </div>

        </section>

        <!-- ================= CÓMO ASOCIARSE ================= -->
        <section id="como-asociarse" class="scroll-mt-24 mb-20">

            <h2 class="mb-8 flex items-center gap-4">
                <span class="{{ $tituloSeccion }}"><x-icon name="compass" class="w-6 h-6" /></span>
                <x-section-title as="span">¿Cómo asociarse?</x-section-title>
            </h2>

            <!-- PASOS 1 a 4: tarjetas numeradas -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($pasos as $indice => [$titulo, $detalle])
                    <div class="group relative overflow-hidden {{ $tarjeta }} p-7 transition duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#0B4870]/10">
                        <!-- Número gigante de fondo -->
                        <span class="hero-title pointer-events-none absolute -right-2 -top-6 text-[7rem] leading-none text-[#0C67A3]/10 transition-transform duration-500 group-hover:scale-110 dark:text-sky-300/10" aria-hidden="true">{{ $indice + 1 }}</span>
                        <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B8B7B] text-lg font-bold text-white shadow-lg shadow-[#0C67A3]/30">{{ $indice + 1 }}</span>
                        <h3 class="relative mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ $titulo }}</h3>
                        <p class="relative mt-2 text-[15px] leading-relaxed text-slate-600 dark:text-slate-300">{{ $detalle }}</p>
                    </div>
                @endforeach
            </div>

            <!-- PASO 5: bienvenida -->
            <div class="relative mt-6 flex flex-col items-center gap-6 overflow-hidden rounded-3xl bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] p-8 md:p-10 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/15 md:flex-row">
                <span class="hero-sun absolute -left-24 -top-24 h-80 w-80 md:left-auto md:-right-16" aria-hidden="true"></span>
                <span class="relative inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#FACC15] text-xl font-bold text-[#0B4870] shadow-lg">5</span>
                <div class="relative flex-1 text-center md:text-left">
                    <h3 class="hero-title text-2xl md:text-3xl">Aprobación y bienvenida</h3>
                    <p class="mt-2 max-w-2xl text-base leading-relaxed text-white/90">
                        Tu solicitud será revisada y aprobada. Bienvenido a la familia Foncaldas,
                        con acceso inmediato a todos los servicios.
                    </p>
                </div>
                <x-mascot pose="alegre" size="w-32 h-32 md:w-40 md:h-40" class="relative" />
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">

                <!-- ASESORA -->
                <div class="{{ $tarjeta }} flex flex-col gap-5 p-8">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-green-500 text-white shadow-lg shadow-green-500/30"><x-icon name="whatsapp" class="w-7 h-7" /></span>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Habla con nuestra asesora</h3>
                            <p class="mt-1 text-[15px] text-slate-600 dark:text-slate-300">Liliana Quintero Zuluaga te acompaña durante todo el proceso de afiliación.</p>
                        </div>
                    </div>
                    <a href="https://wa.me/573245048815" target="_blank" rel="noopener"
                       class="mt-auto inline-flex items-center justify-center gap-2 rounded-full bg-green-600 px-6 py-3 text-lg font-semibold text-white shadow-lg shadow-green-600/25 transition hover:-translate-y-0.5 hover:bg-green-700">
                        <x-icon name="whatsapp" class="w-5 h-5" />
                        314 504 8815
                    </a>
                </div>

                <!-- FORMULARIO PDF -->
                <div class="{{ $tarjeta }} flex flex-col gap-5 p-8">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30"><x-icon name="document" class="w-7 h-7" /></span>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Formulario de afiliación</h3>
                            <p class="mt-1 text-[15px] text-slate-600 dark:text-slate-300">Descarga o visualiza el documento oficial en PDF.</p>
                        </div>
                    </div>
                    <div class="mt-auto grid grid-cols-2 gap-3">
                        <a href="{{ asset('pdf/Formulariovinculacion.pdf') }}" target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center rounded-full bg-[#0B4870] px-6 py-3 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                            Ver PDF
                        </a>
                        <a href="{{ asset('pdf/Formulariovinculacion.pdf') }}" download
                           class="inline-flex items-center justify-center rounded-full border-2 border-[#0B4870] px-6 py-3 font-semibold text-[#0B4870] transition hover:-translate-y-0.5 hover:bg-[#0B4870] hover:text-white dark:border-sky-400 dark:text-sky-200 dark:hover:bg-sky-400/15 dark:hover:text-white">
                            Descargar
                        </a>
                    </div>
                </div>

            </div>

        </section>

        <!-- ================= BENEFICIOS ================= -->
        <section id="beneficios" class="scroll-mt-24">

            <h2 class="mb-8 flex items-center gap-4">
                <span class="{{ $tituloSeccion }}"><x-icon name="trophy" class="w-6 h-6" /></span>
                <x-section-title as="span">Beneficios de ser socio</x-section-title>
            </h2>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($beneficios as $indice => [$icono, $titulo, $detalle])
                    @php [$ficha, $halo, $acento] = $paleta[$indice % 3]; @endphp
                    {{-- La última tarjeta (queda sola en su fila) ocupa todo el ancho. --}}
                    <div class="group relative overflow-hidden {{ $tarjeta }} p-7 transition duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#0B4870]/10{{ $loop->last ? ' sm:col-span-2 lg:col-span-3' : '' }}">
                        <span class="absolute -right-10 -top-10 h-36 w-36 rounded-full {{ $halo }} blur-3xl" aria-hidden="true"></span>
                        <x-icon :name="$icono" class="pointer-events-none absolute -bottom-8 -right-6 h-32 w-32 {{ $acento }} opacity-[0.08] transition-transform duration-700 group-hover:-rotate-12 group-hover:scale-110" />
                        <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl {{ $ficha }} shadow-lg transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110"><x-icon :name="$icono" class="w-7 h-7" /></span>
                        <h3 class="relative mt-5 text-xl font-bold text-slate-900 dark:text-white">{{ $titulo }}</h3>
                        <p class="relative mt-2 text-base leading-relaxed text-slate-600 dark:text-slate-300">{{ $detalle }}</p>
                    </div>
                @endforeach

            </div>

            <!-- POR QUÉ ELEGIRNOS -->
            <div class="relative mt-6 overflow-hidden rounded-3xl bg-[#0B4870] p-8 md:p-10 text-white shadow-xl shadow-[#0B4870]/25 ring-1 ring-white/10">
                <span class="absolute -left-16 -top-16 h-56 w-56 rounded-full bg-[#0B8B7B]/40 blur-3xl" aria-hidden="true"></span>
                <span class="absolute -bottom-20 right-0 h-56 w-56 rounded-full bg-[#FACC15]/20 blur-3xl" aria-hidden="true"></span>

                <h3 class="hero-title relative text-2xl md:text-3xl">¿Por qué elegirnos?</h3>

                <div class="relative mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($porQueElegirnos as [$icono, $titulo, $detalle])
                        <div>
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-[#FACC15] ring-1 ring-white/20"><x-icon :name="$icono" class="w-6 h-6" /></span>
                            <p class="mt-4 text-lg font-bold">{{ $titulo }}</p>
                            <p class="mt-1 text-[15px] leading-relaxed text-white/85">{{ $detalle }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </section>

    </div>

</div>

@endsection