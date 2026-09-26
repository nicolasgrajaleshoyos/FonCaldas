<footer class="bg-[#0F172A] text-slate-300">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">

        <!-- MARCA -->
        <div class="md:col-span-2">
            <a href="{{ url('/') }}" class="inline-flex items-center mb-4">
                <img src="{{ asset('images/logo-lockup.png') }}" alt="Foncaldas — Fondo de Empleados" class="h-10 w-auto object-contain">
            </a>
            <p class="text-sm leading-relaxed max-w-sm text-slate-400">
                Fondo de empleados al servicio del bienestar de nuestros afiliados en Manizales: ahorro, crédito, afiliaciones y convenios.
            </p>
            <div class="flex items-center gap-3 mt-6">
                <a href="https://www.facebook.com/share/1LM7sKGQCM/?mibextid=wwXIfr" target="_blank" rel="noopener" aria-label="Facebook Foncaldas"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-[#1877F2] hover:text-white hover:scale-110 hover:-translate-y-0.5 transition duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://www.instagram.com/soyfoncaldas" target="_blank" rel="noopener" aria-label="Instagram Foncaldas"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-gradient-to-tr hover:from-[#FEDA75] hover:via-[#D62976] hover:to-[#4F5BD5] hover:text-white hover:scale-110 hover:-translate-y-0.5 transition duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                <a href="https://www.tiktok.com/@foncaldas" target="_blank" rel="noopener" aria-label="TikTok Foncaldas"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-black hover:text-[#25F4EE] hover:scale-110 hover:-translate-y-0.5 transition duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0115.54 3h-3.09v12.4a2.592 2.592 0 01-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 004.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg>
                </a>
                <a href="https://wa.me/573148330328" target="_blank" rel="noopener" aria-label="WhatsApp Foncaldas"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-[#25D366] hover:text-white hover:scale-110 hover:-translate-y-0.5 transition duration-200">
                    <x-icon name="whatsapp" class="w-5 h-5" />
                </a>
            </div>
        </div>

        <!-- ENLACES -->
        <div>
            <h3 class="text-white font-semibold text-sm tracking-wide uppercase mb-4">Enlaces</h3>
            <ul class="space-y-3 text-sm">
                <li><a href="{{ route('servicios') }}" class="hover:text-white transition">Servicios</a></li>
                <li><a href="{{ route('eventos') }}" class="hover:text-white transition">Eventos</a></li>
                <li><a href="{{ route('afiliaciones') }}" class="hover:text-white transition">Afiliaciones</a></li>
                <li><a href="{{ route('transparencia') }}" class="hover:text-white transition">Transparencia</a></li>
                <li><a href="{{ route('contacto') }}" class="hover:text-white transition">Contacto</a></li>
            </ul>
        </div>

        <!-- CONTACTO -->
        <div>
            <h3 class="text-white font-semibold text-sm tracking-wide uppercase mb-4">Contacto</h3>
            <ul class="space-y-3 text-sm text-slate-400">
                <li>Calle 60 #25-01, Manizales</li>
                <li>Lun - Vie: 8:00am - 4:00pm</li>
                <li><a href="https://wa.me/573148330328" target="_blank" rel="noopener" class="hover:text-white transition">WhatsApp: 314 833 0328</a></li>
            </ul>
        </div>

    </div>

    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-3 text-center text-xs text-slate-500 dark:text-slate-400">
            <p>&copy; {{ date('Y') }} Foncaldas. Todos los derechos reservados.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('pqrs.crear') }}" class="hover:text-white transition">PQRS</a>
                <a href="{{ route('terminos') }}" class="hover:text-white transition">Términos y condiciones</a>
                <a href="{{ route('admin') }}" class="hover:text-white transition">Administración</a>
            </div>
        </div>
    </div>
</footer>
