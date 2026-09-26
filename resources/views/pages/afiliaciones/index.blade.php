@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-4xl mx-auto px-6">

        <x-page-header
            icon="users"
            title="Afiliaciones"
            subtitle="Todo lo que necesitas para saber si puedes asociarte, cómo hacerlo y qué beneficios obtienes."
            center />

        <!-- NAV DE SECCIONES -->
        <div class="flex flex-wrap justify-center gap-3 mb-14">
            <a href="#quien-puede-ser-socio" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Quién puede ser socio</a>
            <a href="#como-asociarse" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Cómo asociarse</a>
            <a href="#beneficios" class="px-5 py-2.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-[#0C67A3]/40 hover:text-[#0C67A3] transition">Beneficios</a>
        </div>

        <!-- ================= QUIÉN PUEDE SER SOCIO ================= -->
        <section id="quien-puede-ser-socio" class="scroll-mt-24 mb-16">

            <h2 class="mb-6 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C67A3]/10 text-[#0C67A3] shrink-0"><x-icon name="users" class="w-5 h-5" /></span>
                <x-section-title as="span">¿Quién puede ser socio?</x-section-title>
            </h2>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 mb-6">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-3">Vínculo requerido</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                    Pueden aspirar a ser asociados a Foncaldas las personas naturales con vínculo laboral con
                    universidades del departamento de Caldas.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-5">Requisitos de vinculación</h3>
                <ul class="space-y-3 text-slate-600 dark:text-slate-400 text-[15px]">
                    <li class="flex items-start gap-2"><x-icon name="check" class="w-4 h-4 mt-1 text-[#0C67A3] shrink-0" /> Anexar fotocopia de la cédula</li>
                    <li class="flex items-start gap-2"><x-icon name="check" class="w-4 h-4 mt-1 text-[#0C67A3] shrink-0" /> Diligenciar formulario de vinculación SARLAFT</li>
                    <li class="flex items-start gap-2"><x-icon name="check" class="w-4 h-4 mt-1 text-[#0C67A3] shrink-0" /> Cuota de admisión (10% SMMLV)</li>
                    <li class="flex items-start gap-2"><x-icon name="check" class="w-4 h-4 mt-1 text-[#0C67A3] shrink-0" /> Aporte mensual del 3% al 10% del salario</li>
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
                <a href="https://wa.me/573245048815" target="_blank"
                   class="shrink-0 inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                    <x-icon name="whatsapp" class="w-4 h-4" />
                    314 504 8815
                </a>
            </div>

            <!-- STEPS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-10 h-10 rounded-full bg-[#0C67A3] text-white flex items-center justify-center font-bold shrink-0">1</span>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Contacta a la asesora</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed">
                        Escríbele por WhatsApp para confirmar que cumples el vínculo laboral requerido y resolver tus dudas.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-10 h-10 rounded-full bg-[#0C67A3] text-white flex items-center justify-center font-bold shrink-0">2</span>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Diligencia los formularios</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed">
                        Completa el formulario de vinculación y el formulario SARLAFT con tus datos personales.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-10 h-10 rounded-full bg-[#0C67A3] text-white flex items-center justify-center font-bold shrink-0">3</span>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Presenta tu cédula</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed">
                        Anexa una fotocopia de tu cédula de ciudadanía a la solicitud.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-10 h-10 rounded-full bg-[#0C67A3] text-white flex items-center justify-center font-bold shrink-0">4</span>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Realiza tus aportes</h3>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] leading-relaxed">
                        Paga la cuota de admisión (10% del SMMLV) e inicia tu aporte mensual, entre el 3% y el 10% de tu salario.
                    </p>
                </div>

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
                    <a href="{{ asset('pdf/Formulariovinculacion.pdf') }}" target="_blank"
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

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="money" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Tasas Preferenciales</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Acceso a tasas de interés especiales en créditos y productos de ahorro diseñados para socios.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="sparkle" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Productos Exclusivos</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Acceso a productos y servicios financieros diseñados exclusivamente para nuestros socios.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="briefcase" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Convenios y Descuentos</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Descuentos especiales en comercios aliados y servicios empresariales en toda la región.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="shield" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Protección y Seguros</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Coberturas de seguros especializados para proteger tu dinero y tus préstamos.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="graduation" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Capacitación y Educación</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Acceso a talleres y programas de educación financiera para mejorar tu gestión económica.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="ballot" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Participación Democrática</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Voz y voto en las asambleas generales y decisiones de la cooperativa.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700 md:col-span-2">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="gift" class="w-5 h-5" /></span>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Programas Especiales</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Acceso a promociones especiales, sorteos y premios exclusivos para miembros.
                    </p>
                </div>

            </div>

            <!-- WHY US -->
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-3xl p-10 border border-slate-100 dark:border-slate-700 shadow-sm">

                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">¿Por qué elegirnos?</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-700 dark:text-slate-300">

                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="heart" class="w-5 h-5" /></span>
                        <div>
                            <p class="font-semibold">Organización solidaria</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Fundada en principios de cooperación y ayuda mutua</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="users" class="w-5 h-5" /></span>
                        <div>
                            <p class="font-semibold">Atención personalizada</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Servicio cercano y profesional para cada socio</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="trending-up" class="w-5 h-5" /></span>
                        <div>
                            <p class="font-semibold">Mejores tasas del mercado</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Las mejores tasas de interés del mercado</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3]"><x-icon name="lock" class="w-5 h-5" /></span>
                        <div>
                            <p class="font-semibold">Seguridad garantizada</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Regulación y supervisión financiera completa</p>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

@endsection
