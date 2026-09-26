<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disco de archivos predeterminado
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar el disco de archivos predeterminado que usará
    | el framework. El disco "local", así como varios discos en la nube,
    | están disponibles para el almacenamiento de archivos de tu aplicación.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Discos de archivos
    |--------------------------------------------------------------------------
    |
    | A continuación puedes configurar tantos discos de archivos como
    | necesites, e incluso varios discos para el mismo controlador. Aquí se
    | configuran ejemplos de referencia para la mayoría de los controladores.
    |
    | Controladores soportados: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enlaces simbólicos
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar los enlaces simbólicos que se crearán al
    | ejecutar el comando Artisan `storage:link`. Las claves del arreglo
    | deben ser las ubicaciones de los enlaces y los valores sus destinos.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
