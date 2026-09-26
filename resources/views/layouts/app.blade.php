<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Foncaldas: ahorro, créditos, afiliaciones y bienestar para nuestros asociados en Manizales.')">
    <title>@yield('title', 'Foncaldas')</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
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

</body>
</html>