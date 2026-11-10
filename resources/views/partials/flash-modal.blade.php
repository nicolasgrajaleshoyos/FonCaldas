@php
    $flashOk = session('success');
    $flashError = session('error');
    $flashMsg = $flashOk ?? $flashError;
@endphp
@if($flashMsg)
    <style>#flash-modal::backdrop{background:rgba(2,6,23,.7);backdrop-filter:blur(4px)}</style>
    {{-- Aviso emergente centrado sobre la misma página; se cierra con Esc, clic fuera o el botón --}}
    <dialog id="flash-modal" role="alertdialog" aria-labelledby="flash-title"
        style="margin:auto;width:min(92vw,32rem);padding:0;border:0" class="rounded-3xl shadow-2xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
        <div class="flex flex-col items-center text-center" style="padding:2.5rem 2rem">
            <span class="mb-5 inline-flex h-16 w-16 items-center justify-center rounded-full text-3xl font-bold {{ $flashOk ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-300' }}">{{ $flashOk ? '✓' : '!' }}</span>
            <h2 id="flash-title" class="text-2xl font-bold mb-3">{{ $flashOk ? '¡Listo!' : 'Algo salió mal' }}</h2>
            <p class="text-base leading-relaxed text-slate-600 dark:text-slate-300">{{ $flashMsg }}</p>
            <button type="button" autofocus style="margin-top:2rem" onclick="this.closest('dialog').close()"
                class="rounded-full bg-[#0C67A3] px-8 py-3 text-base font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-[#0C67A3]/30">Entendido</button>
        </div>
    </dialog>
    <script>
        (() => {
            const d = document.getElementById('flash-modal');
            d.showModal();
            d.addEventListener('click', e => { if (e.target === d) d.close(); });
        })();
    </script>
@endif
