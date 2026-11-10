<?php

use App\Http\Middleware\CabecerasSeguridad;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Detrás de un proxy o balanceador: TRUSTED_PROXIES="*" (o lista de IP separadas por coma) para detectar HTTPS e IP reales.
        $middleware->trustProxies(at: env('TRUSTED_PROXIES') === '*' ? '*' : (env('TRUSTED_PROXIES') ? explode(',', env('TRUSTED_PROXIES')) : null));

        $middleware->web(append: [CabecerasSeguridad::class]);

        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'super_admin' => EnsureSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
