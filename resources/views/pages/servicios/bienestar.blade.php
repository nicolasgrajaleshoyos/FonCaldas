@extends('layouts.app')

@section('content')
<div class="min-h-screen page-bg px-6 py-16">

    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col items-center mb-4">
            <x-mascot class="mb-4" />
            <x-page-header
                icon="heart"
                title="Área de Bienestar"
                subtitle="Espacios, actividades y beneficios diseñados para promover el bienestar integral de nuestros asociados y sus familias."
                center />
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden">

        <!-- CONTENIDO -->
        <div class="p-10">

            <div class="space-y-8 text-slate-600 dark:text-slate-400 leading-relaxed text-[16px]">

                <p>
                    El Área de Bienestar de 
                    <span class="font-semibold text-[#0C67A3]">
                        FONCALDAS
                    </span>
                    trabaja para generar espacios, actividades y beneficios que contribuyan al bienestar integral
                    de nuestros asociados y sus familias.
                </p>

                <p>
                    A través de programas recreativos, culturales, educativos, deportivos y sociales,
                    buscamos fortalecer la calidad de vida, la integración y el sentido de pertenencia
                    hacia nuestra gran familia FONCALDAS.
                </p>

                <p>
                    Nuestro propósito es acompañar a los asociados con experiencias, convenios y servicios
                    que promuevan el crecimiento personal, la sana convivencia y el disfrute de momentos especiales,
                    reafirmando así el compromiso solidario que nos caracteriza.
                </p>

            </div>

            <!-- BOTONES -->
            <div class="flex flex-wrap gap-4 mt-12">

                <!-- WHATSAPP -->
                <a href="https://wa.me/573225947308"
                   target="_blank"
                   class="inline-flex items-center gap-3 px-6 py-4 rounded-lg bg-[#25D366] text-white font-medium shadow-md hover:scale-[1.02] transition">

                    <x-icon name="whatsapp" class="w-5 h-5" />
                    WhatsApp Bienestar
                </a>

                <!-- EVENTOS -->
                <a href="{{ route('eventos') }}"
                   class="inline-flex items-center gap-3 px-6 py-4 rounded-lg bg-[#0B4870] text-white font-medium shadow-md hover:bg-[#0a3d60] transition">

                    <x-icon name="calendar" class="w-5 h-5" />
                    Ver eventos
                </a>

                <!-- VOLVER -->
                <a href="{{ route('servicios') }}"
                   class="inline-flex items-center gap-3 px-6 py-4 rounded-lg border border-slate-300 text-slate-800 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700/40 transition">

                    <x-icon name="arrow-right" class="w-4 h-4 rotate-180" />
                    Volver a servicios
                </a>

            </div>

            <!-- SECCIÓN OPCIONES -->
            <div class="grid md:grid-cols-3 gap-6 mt-14">

                <a href="{{ route('eventos') }}"
                   class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] mb-4">
                        <x-icon name="graduation" class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Cursos</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                        Participa en cursos y capacitaciones para nuestros asociados.
                    </p>
                </a>

                <a href="{{ route('eventos') }}"
                   class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] mb-4">
                        <x-icon name="sparkle" class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Actividades</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                        Disfruta actividades recreativas, deportivas y culturales.
                    </p>
                </a>

                <a href="{{ route('servicios.convenios') }}"
                   class="group block rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#0C67A3]/20">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] mb-4">
                        <x-icon name="briefcase" class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Convenios</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[14px] mt-2 leading-relaxed">
                        Conoce los beneficios y alianzas disponibles para asociados.
                    </p>
                </a>

            </div>

        </div>

        </div>

    </div>

</div>
@endsection