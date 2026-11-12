{{-- Confirmación antes de una acción irreversible: añade data-confirm="mensaje" al <form> que deba confirmarse
     en lugar de onsubmit="confirm(...)". Centraliza el diálogo (mismo estilo que flash-modal, con tema claro/oscuro)
     para no repetirlo en cada formulario ni depender del confirm() nativo del navegador. --}}
<style>#confirm-modal::backdrop{background:rgba(2,6,23,.7);backdrop-filter:blur(4px)}</style>
<dialog id="confirm-modal" role="alertdialog" aria-labelledby="confirm-modal-texto"
    style="margin:auto;width:min(92vw,28rem);padding:0;border:0" class="rounded-3xl shadow-2xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
    <div class="flex flex-col items-center text-center" style="padding:2.5rem 2rem">
        <span class="mb-5 inline-flex h-16 w-16 items-center justify-center rounded-full text-3xl font-bold bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-300">?</span>
        <p id="confirm-modal-texto" class="text-base leading-relaxed text-slate-600 dark:text-slate-300"></p>
        <div class="mt-8 flex gap-3">
            <button type="button" data-confirm-cancelar class="rounded-full border border-slate-300 dark:border-slate-600 px-6 py-3 text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-700">Cancelar</button>
            <button type="button" data-confirm-aceptar autofocus class="rounded-full bg-[#0C67A3] px-6 py-3 text-base font-semibold text-white hover:opacity-90">Confirmar</button>
        </div>
    </div>
</dialog>
<script>
    (() => {
        const d = document.getElementById('confirm-modal');
        const texto = document.getElementById('confirm-modal-texto');
        let formPendiente = null;

        const cancelar = () => { d.close(); formPendiente = null; };

        // Se registra antes que el de "cargando" (ver layouts/app.blade.php) para poder frenar el envío a tiempo.
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (!form.dataset.confirm || form.dataset.confirmado) return;
            e.preventDefault();
            formPendiente = form;
            texto.textContent = form.dataset.confirm;
            d.showModal();
        });

        d.querySelector('[data-confirm-aceptar]').addEventListener('click', () => {
            d.close();
            if (!formPendiente) return;
            formPendiente.dataset.confirmado = '1';
            formPendiente.requestSubmit();
            delete formPendiente.dataset.confirmado;
            formPendiente = null;
        });
        d.querySelector('[data-confirm-cancelar]').addEventListener('click', cancelar);
        d.addEventListener('click', (e) => { if (e.target === d) cancelar(); });
    })();
</script>
