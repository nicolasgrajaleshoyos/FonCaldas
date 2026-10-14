<?php

use App\Services\Archivos\AlmacenArchivosDiscoPublico;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

test('guardar() sanitiza el nombre y devuelve una ruta pública bajo storage/', function () {
    Storage::fake('public');
    $archivo = UploadedFile::fake()->create('foto raro@#!.png', 5);

    $ruta = (new AlmacenArchivosDiscoPublico)->guardar($archivo, 'uploads/eventos');

    expect($ruta)->toStartWith('storage/uploads/eventos/')
        ->and($ruta)->toMatch('#^storage/uploads/eventos/\d+_foto_raro___\.png$#');
    Storage::disk('public')->assertExists(str($ruta)->after('storage/')->toString());
});

test('eliminar() borra el archivo cuando la ruta viene del disco público', function () {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/eventos/archivo.png', 'contenido');

    (new AlmacenArchivosDiscoPublico)->eliminar('storage/uploads/eventos/archivo.png');

    Storage::disk('public')->assertMissing('uploads/eventos/archivo.png');
});

test('eliminar() también borra archivos heredados guardados directamente bajo public/', function () {
    $legado = public_path('tmp-legado-test.png');
    file_put_contents($legado, 'x');

    try {
        (new AlmacenArchivosDiscoPublico)->eliminar('tmp-legado-test.png');
        expect(is_file($legado))->toBeFalse();
    } finally {
        if (is_file($legado)) {
            unlink($legado);
        }
    }
});

test('eliminar() no hace nada si la ruta es null', function () {
    expect(fn () => (new AlmacenArchivosDiscoPublico)->eliminar(null))->not->toThrow(Throwable::class);
});
