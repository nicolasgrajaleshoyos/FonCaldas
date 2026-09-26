<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor es el nombre de tu aplicación, que se usará cuando el
    | framework necesite mostrar el nombre de la aplicación en una
    | notificación u otros elementos de la interfaz.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Entorno de la aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor determina el "entorno" en el que se está ejecutando tu
    | aplicación. Puede influir en cómo prefieres configurar los distintos
    | servicios que utiliza. Defínelo en tu archivo ".env".
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Modo de depuración de la aplicación
    |--------------------------------------------------------------------------
    |
    | Cuando la aplicación está en modo de depuración, se muestran mensajes
    | de error detallados con la traza de la pila en cada error que ocurra.
    | Si está desactivado, se muestra una página de error genérica.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL de la aplicación
    |--------------------------------------------------------------------------
    |
    | Esta URL la usa la consola para generar URLs correctamente al utilizar
    | la herramienta de línea de comandos Artisan. Debes establecerla en la
    | raíz de la aplicación para que esté disponible en los comandos Artisan.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona horaria de la aplicación
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar la zona horaria predeterminada de tu aplicación,
    | que usarán las funciones de fecha y fecha-hora de PHP. Por defecto es
    | "UTC", ya que sirve para la mayoría de los casos.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Configuración de idioma de la aplicación
    |--------------------------------------------------------------------------
    |
    | El idioma de la aplicación determina el idioma predeterminado que usarán
    | los métodos de traducción / localización de Laravel. Puede ser cualquier
    | idioma para el que planees tener cadenas de traducción.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Clave de cifrado
    |--------------------------------------------------------------------------
    |
    | Esta clave la usan los servicios de cifrado de Laravel y debe ser una
    | cadena aleatoria de 32 caracteres para garantizar la seguridad de los
    | valores cifrados. Debes definirla antes de desplegar la aplicación.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Controlador del modo de mantenimiento
    |--------------------------------------------------------------------------
    |
    | Estas opciones determinan el controlador que se usa para gestionar el
    | estado de "modo de mantenimiento" de Laravel. El controlador "cache"
    | permite controlar el modo de mantenimiento entre varias máquinas.
    |
    | Controladores soportados: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
