<?php

namespace App\Providers;

use App\Contracts\AlmacenArchivos;
use App\Services\Archivos\AlmacenArchivosDiscoPublico;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios de la aplicación.
     */
    public function register(): void
    {
        $this->app->bind(AlmacenArchivos::class, AlmacenArchivosDiscoPublico::class);
    }

    /**
     * Inicializa los servicios de la aplicación.
     */
    public function boot(): void
    {
        //
    }
}
