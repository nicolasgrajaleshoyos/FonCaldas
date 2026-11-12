<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Foncaldas: ahorro, créditos, afiliaciones y bienestar para nuestros asociados en Manizales.')">
    <title>@yield('title', 'Foncaldas')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Las fuentes viven en el propio sitio (resources/css/app.css); se precargan para evitar el parpadeo de texto. --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin href="{{ Vite::asset('node_modules/@fontsource-variable/inter/files/inter-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="{{ Vite::asset('node_modules/@fontsource-variable/sora/files/sora-latin-wght-normal.woff2') }}">

    {{-- Navegación instantánea: al pasar el mouse (o tocar) un enlace, el navegador prepara la página de destino
         y al hacer clic ya está lista. Incluye el panel (sus enlaces solo muestran información; las acciones son POST).
         Se excluyen descargas, archivos, formularios con mensajes y enlaces que abren otra pestaña. --}}
    <script type="speculationrules">
    {
        "prerender": [{
            "where": { "and": [
                { "href_matches": "/*" },
                { "not": { "href_matches": ["/storage/*", "/build/*", "/images/*", "/pdf/*", "/tramites/documentos/*", "/tramites/*/estado", "/admin/solicitudes/*/documentos/*", "/admin/*/descargar*", "/admin", "/tramites", "/pqrs*"] } },
                { "not": { "selector_matches": "[download], [target=_blank]" } }
            ] },
            "eagerness": "moderate"
        }]
    }
    </script>
    <script>
        // Respaldo para navegadores sin "speculation rules" (Firefox, Safari): precarga el HTML al pasar el mouse.
        if (!(HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules'))) {
            const precargados = new Set();
            const precargar = (e) => {
                const enlace = e.target.closest && e.target.closest('a[href]');
                if (!enlace || enlace.origin !== location.origin || enlace.target === '_blank' || enlace.hasAttribute('download')) return;
                if (/^\/(tramites|pqrs)$/.test(enlace.pathname)) return;
                if (/^\/(storage|build|images|pdf|tramites\/documentos)\//.test(enlace.pathname) || /\/documentos\/\d+$/.test(enlace.pathname) || precargados.has(enlace.href)) return;
                precargados.add(enlace.href);
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = enlace.href;
                document.head.appendChild(link);
            };
            document.addEventListener('mouseover', precargar, { passive: true });
            document.addEventListener('touchstart', precargar, { passive: true });
        }
    </script>
</head>

<body class="font-[Inter] bg-white text-slate-800 antialiased transition-colors duration-300 dark:bg-slate-900 dark:text-slate-200">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] focus:bg-white dark:focus:bg-slate-800 focus:text-[#0C67A3] focus:px-4 focus:py-2 focus:rounded-full focus:shadow-lg">
        Saltar al contenido
    </a>

    @include('partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.whatsapp-fab')

    @include('partials.flash-modal')

    @include('partials.confirm-modal')

    @include('partials.cargando')

</body>
</html>