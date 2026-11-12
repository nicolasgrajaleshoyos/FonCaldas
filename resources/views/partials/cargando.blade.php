{{-- Pantalla de carga: se muestra al enviar cualquier formulario (mensaje opcional con data-cargando="Mensaje") y al navegar por enlaces internos. --}}
<div id="cargando-overlay" hidden role="status" aria-live="polite"
     class="fixed inset-0 z-[200] flex items-center justify-center bg-[#020617]/70 backdrop-blur-md px-4">
    <div class="hero-in relative flex w-full max-w-xs flex-col items-center overflow-hidden rounded-[2rem] bg-linear-to-br from-[#0B4870] via-[#0C67A3] to-[#0B8B7B] px-8 pt-8 pb-7 shadow-2xl shadow-black/50 ring-1 ring-white/20">

        <!-- Escenario: el sol del logo gira detrás de la mascota y un anillo de color la rodea -->
        <div class="relative h-36 w-36">
            <span class="hero-sun absolute -inset-16 [animation-duration:10s]" aria-hidden="true"></span>
            <span class="absolute inset-2 rounded-full bg-[#FACC15]/40 blur-2xl" aria-hidden="true"></span>
            <span class="absolute inset-0 rounded-full border-4 border-white/15" aria-hidden="true"></span>
            <span class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-[#FACC15] border-r-[#38BDF8]" aria-hidden="true"></span>
            {{-- eager: la imagen debe estar ya descargada cuando aparezca la pantalla de carga --}}
            <x-mascot pose="hola" loading="eager" size="w-28 h-28" class="absolute inset-4" />
        </div>

        <p id="cargando-texto" class="hero-title relative mt-6 text-center text-xl text-white">Procesando…</p>
        <p class="relative mt-1 text-center text-sm text-white/80">Por favor no cierres esta ventana</p>

        <!-- Barra de progreso indeterminada -->
        <div class="relative mt-5 h-1.5 w-full overflow-hidden rounded-full bg-white/15" aria-hidden="true">
            <span class="cargando-barra absolute inset-y-0 w-2/5 rounded-full bg-linear-to-r from-[#FACC15] via-white to-[#38BDF8]"></span>
        </div>
    </div>
</div>

<script>
    const overlay = document.getElementById('cargando-overlay');
    let temporizador;

    const mostrar = (mensaje) => {
        document.getElementById('cargando-texto').textContent = mensaje;
        overlay.hidden = false;
    };

    // Todo formulario (salvo los que abren en otra pestaña o llevan data-sin-cargando) muestra la pantalla de carga.
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (e.defaultPrevented || form.target === '_blank' || 'sinCargando' in form.dataset) return;

        mostrar(form.dataset.cargando || 'Procesando…');
        // Se deshabilita después del envío para no perder el valor del botón ni enviar dos veces.
        setTimeout(() => form.querySelectorAll('button[type="submit"]').forEach((b) => b.disabled = true), 0);
    });

    // Los enlaces internos la muestran si la página tarda más de 300 ms (así no parpadea en cargas rápidas).
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if ((a.target && a.target !== '_self') || a.hasAttribute('download') || a.origin !== location.origin) return;
        if (!/^https?:$/.test(a.protocol) || (a.pathname === location.pathname && a.search === location.search)) return;
        if (/descargar|exportar/.test(a.pathname)) return; // descargas: la página no cambia y el spinner quedaría pegado

        clearTimeout(temporizador);
        temporizador = setTimeout(() => mostrar('Cargando…'), 300);
    });

    // Al volver con "atrás" el navegador restaura la página tal cual: se oculta la pantalla de carga.
    window.addEventListener('pageshow', (e) => {
        if (!e.persisted) return;
        clearTimeout(temporizador);
        overlay.hidden = true;
        document.querySelectorAll('form button[type="submit"]').forEach((b) => b.disabled = false);
    });
</script>
