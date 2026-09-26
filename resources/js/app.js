import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Skeleton de carga para imágenes: evita el "flash" en blanco mientras cargan
// los banners (informe UX 2.1). Se aplica a toda imagen que no esté ya en caché.
document.querySelectorAll('img').forEach((img) => {
    if (img.complete) return;
    img.classList.add('img-loading');
    img.addEventListener('load', () => img.classList.remove('img-loading'), { once: true });
    img.addEventListener('error', () => img.classList.remove('img-loading'), { once: true });
});
