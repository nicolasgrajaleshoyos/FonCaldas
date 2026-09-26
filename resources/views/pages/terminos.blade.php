@extends('layouts.app')

@section('title', 'Términos y Condiciones | Foncaldas')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-4xl mx-auto px-6">

        <x-page-header
            icon="document"
            title="Términos y Condiciones"
            subtitle="Condiciones de uso de este sitio web. Última actualización: {{ now()->format('d/m/Y') }}." />

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-10 space-y-8">

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">1. Aceptación de los términos</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Al acceder y utilizar el sitio web de Foncaldas (en adelante, "el sitio") usted acepta quedar
                    sujeto a los presentes Términos y Condiciones. Si no está de acuerdo con alguno de ellos, le
                    pedimos abstenerse de utilizar el sitio.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">2. Objeto del sitio</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Este sitio tiene un propósito informativo: dar a conocer los servicios de Foncaldas (ahorro,
                    crédito, afiliaciones, bienestar y convenios), publicar eventos y documentos de transparencia,
                    y facilitar el contacto con la entidad. La información aquí publicada no constituye una oferta
                    mercantil ni reemplaza los reglamentos, estatutos y demás documentos oficiales de Foncaldas, los
                    cuales prevalecen en caso de diferencia.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">3. Uso del sitio</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Usted se compromete a utilizar el sitio de forma lícita, sin afectar su disponibilidad ni
                    intentar acceder sin autorización a áreas restringidas (como el panel administrativo), y sin
                    introducir contenido malicioso o que infrinja derechos de terceros.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">4. Propiedad intelectual</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Los textos, logotipos, imágenes y demás contenidos del sitio son propiedad de Foncaldas o de
                    terceros que han autorizado su uso, y están protegidos por las normas de propiedad intelectual
                    vigentes. Su reproducción o uso comercial sin autorización previa y escrita está prohibido.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">5. Protección de datos personales</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Foncaldas trata los datos personales que usted suministra a través de este sitio (por ejemplo,
                    en formularios de contacto o afiliación) conforme a la Ley 1581 de 2012 y demás normas
                    concordantes sobre protección de datos personales en Colombia. Usted puede ejercer sus derechos
                    de acceso, actualización, rectificación y supresión de sus datos escribiéndonos a través de los
                    canales de contacto oficiales.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">6. Enlaces a sitios de terceros</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    El sitio puede contener enlaces a páginas de terceros (como redes sociales, WhatsApp, Villa
                    Beatriz o aliados de convenios). Foncaldas no controla ni se hace responsable por el contenido
                    o las políticas de privacidad de esos sitios externos.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">7. Limitación de responsabilidad</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Foncaldas procura mantener la información del sitio actualizada y correcta, pero no garantiza
                    la ausencia de errores u omisiones. El uso de la información publicada es responsabilidad del
                    usuario. Las condiciones específicas de productos de ahorro y crédito se rigen por los
                    reglamentos internos vigentes de Foncaldas, disponibles para los asociados.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">8. Modificaciones</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Foncaldas podrá actualizar estos Términos y Condiciones en cualquier momento. Los cambios
                    entrarán en vigencia desde su publicación en esta página.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">9. Legislación aplicable</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Estos Términos y Condiciones se rigen por las leyes de la República de Colombia.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">10. Contacto</h2>
                <p class="text-slate-600 dark:text-slate-400 text-[15px] leading-relaxed">
                    Para preguntas sobre estos Términos y Condiciones puede escribirnos a través de la
                    <a href="{{ route('contacto') }}" class="text-[#0B4870] font-medium hover:underline">página de contacto</a>
                    o por WhatsApp al 314 833 0328.
                </p>
            </section>

        </div>

    </div>

</div>

@endsection
