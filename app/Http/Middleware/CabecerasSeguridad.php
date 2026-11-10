<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Cabeceras HTTP defensivas para todas las respuestas web. */
class CabecerasSeguridad
{
    public function handle(Request $request, Closure $next): Response
    {
        $respuesta = $next($request);

        $respuesta->headers->add([
            // Evita que el navegador "adivine" el tipo de un archivo subido y lo ejecute como HTML/JS.
            'X-Content-Type-Options' => 'nosniff',
            // Impide incrustar el sitio en un iframe de otro dominio (clickjacking).
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
            // Directivas que no afectan a los scripts ni estilos en línea de las vistas: solo cierran vías de
            // incrustación, de <base> manipulado y de envío de formularios a otros dominios.
            'Content-Security-Policy' => "frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'",
        ]);

        // HSTS solo en producción: en desarrollo fijaría el HTTPS de un dominio local en el navegador.
        if ($request->isSecure() && app()->isProduction()) {
            $respuesta->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $respuesta;
    }
}
