{{-- Página de error amable: número grande, mascota, explicación sencilla y caminos para seguir. Usada por errors/403, 404 y 419. --}}
@props(['codigo', 'titulo', 'mensaje', 'pose' => 'pensativo'])

<div class="min-h-screen page-bg flex items-center px-4 py-14 sm:px-6">
    <div class="hero-in mx-auto w-full max-w-3xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-[#0B4870]/15 dark:border-white/10 dark:bg-slate-800">
        <div class="relative flex flex-col items-center gap-6 overflow-hidden bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] px-8 py-10 text-center text-white sm:flex-row sm:text-left">
            <span class="hero-sun absolute -right-24 -top-24 h-80 w-80 opacity-60" aria-hidden="true"></span>
            <x-mascot :pose="$pose" loading="eager" size="w-32 h-32 sm:w-40 sm:h-40" />
            <div class="relative">
                <p class="hero-title text-7xl leading-none text-[#FACC15] sm:text-8xl">{{ $codigo }}</p>
                <h1 class="hero-title mt-2 text-2xl sm:text-3xl">{{ $titulo }}</h1>
            </div>
        </div>

        <div class="p-8 text-center sm:p-10">
            <p class="mx-auto max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-300">{{ $mensaje }}</p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                {{ $slot }}
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-3 rounded-full bg-[#0B4870] py-2 pl-6 pr-2 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3]">
                    Ir al inicio
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FACC15] text-[#0B4870] transition-transform group-hover:translate-x-1"><x-icon name="arrow-right" class="w-4 h-4" /></span>
                </a>
            </div>

            <div class="mt-10 border-t border-slate-200 pt-6 dark:border-white/10">
                <p class="text-sm font-semibold text-slate-500 dark:text-slate-300">O quizá buscabas:</p>
                <div class="mt-3 flex flex-wrap justify-center gap-2">
                    @foreach ([[route('servicios'), 'Servicios'], [route('solicitudes.crear'), 'Trámites'], [route('afiliaciones'), 'Afiliaciones'], [route('contacto'), 'Contacto']] as [$destino, $etiqueta])
                        <a href="{{ $destino }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#0B4870] hover:bg-[#0B4870] hover:text-white dark:border-white/15 dark:text-slate-200 dark:hover:bg-sky-400/15">{{ $etiqueta }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
