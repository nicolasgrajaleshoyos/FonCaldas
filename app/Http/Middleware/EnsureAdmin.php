<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            auth()->logout();

            return redirect()->route('admin')->with('error', 'Debes iniciar sesión.');
        }

        return $next($request);
    }
}
