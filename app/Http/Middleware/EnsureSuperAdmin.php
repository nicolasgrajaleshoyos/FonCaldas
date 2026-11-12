<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /** Con "super_admin:propietario" exige además ser el administrador principal original (acciones que borran rastro). */
    public function handle(Request $request, Closure $next, ?string $nivel = null): Response
    {
        if (! $request->user()?->is_super_admin) {
            abort(403, 'Solo el administrador principal puede acceder a esta sección.');
        }

        if ($nivel === 'propietario' && ! $request->user()->esPropietario()) {
            abort(403, 'Solo el administrador principal original puede realizar esta acción.');
        }

        return $next($request);
    }
}
