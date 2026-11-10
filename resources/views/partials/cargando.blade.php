{{-- Pantalla de carga: se muestra al enviar cualquier formulario (mensaje opcional con data-cargando="Mensaje") y al navegar por enlaces internos. --}}
<div id="cargando-overlay" hidden role="status" aria-live="polite"
     class="fixed inset-0 z-[200] flex items-center justify-center bg-[#020617]/70 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-5 rounded-3xl border border-white/10 bg-[#0B4870]/95 px-10 py-9 shadow-2xl">
        <div class="relative h-16 w-16">
            <span class="absolute inset-0 rounded-full border-4 border-white/15"></span>
            <span class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-[#FACC15] border-r-[#38BDF8]"></span>
            <span class="absolute inset-3 rounded-full bg-white/10 animate-pulse"></span>
        </div>
        <p id="cargando-texto" class="text-center text-base font-semibold text-white">Procesando…</p>
        <p class="-mt-3 text-center text-xs text-white/60">Por favor no cierres esta ventana</p>
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
