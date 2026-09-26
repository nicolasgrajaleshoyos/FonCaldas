<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foncaldas</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-[Poppins] bg-[#F5F7FA]">

    <div class="min-h-screen bg-gradient-to-r from-[#0C67A3] to-[#0B8B7B] p-6">

        <!-- NAVBAR -->
        <nav class="max-w-7xl mx-auto rounded-full border border-white/20 bg-white/10 backdrop-blur-md px-8 py-4 flex items-center justify-between">

            <!-- LOGO -->
            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-full bg-white/20 border border-white/20">
                </div>

                <h1 class="text-white text-2xl font-semibold">
                    Foncaldas
                </h1>

            </div>

            <!-- MENU -->
            <div class="flex items-center gap-4">

                <button class="px-5 py-2 rounded-full border border-white/20 text-white hover:bg-white/10 transition">
                    Inicio
                </button>

                <button class="px-5 py-2 rounded-full border border-white/20 text-white hover:bg-white/10 transition">
                    Servicios
                </button>

                <button class="px-5 py-2 rounded-full border border-white/20 text-white hover:bg-white/10 transition">
                    Contacto
                </button>

            </div>

        </nav>

        <!-- HERO -->
        <div class="max-w-7xl mx-auto mt-24 grid grid-cols-2 gap-12 items-center">

            <!-- TEXTO -->
            <div>

                <h2 class="text-6xl font-bold leading-tight text-white">
                    Tu futuro financiero comienza aquí
                </h2>

                <p class="mt-6 text-white/80 text-lg leading-relaxed">
                    Construimos bienestar financiero para nuestros afiliados con servicios modernos,
                    seguros y accesibles.
                </p>

                <div class="flex gap-4 mt-10">

                    <button class="bg-white text-[#0C67A3] px-8 py-4 rounded-full font-semibold hover:scale-105 transition">
                        Conocer más
                    </button>

                    <button class="border border-white/30 text-white px-8 py-4 rounded-full hover:bg-white/10 transition">
                        Contactar
                    </button>

                </div>

            </div>

            <!-- CARD DERECHA -->
            <div class="flex justify-center">

                <div class="bg-white rounded-[40px] p-10 shadow-2xl w-[500px] h-[500px] flex items-center justify-center">

                    <div class="w-72 h-72 rounded-full bg-gradient-to-r from-[#0C67A3] to-[#0B8B7B] opacity-80">
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>