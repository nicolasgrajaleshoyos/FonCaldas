<?php

use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

afterEach(fn () => Str::createRandomStringsNormally());

test('el código generado sigue el formato PREFIJO-AA-XXXXXX', function () {
    $pqrs = Pqrs::create([
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'x', 'descripcion' => 'x',
    ]);

    expect($pqrs->codigo)->toMatch('/^PQ-\d{2}-[A-Z0-9]{6}$/');
});

test('si el sufijo aleatorio choca con uno existente, se reintenta hasta obtener uno único', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $datos = fn () => [
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ];

    Str::createRandomStringsUsing(fn () => 'AAAAAA');
    $primero = Solicitud::create($datos());

    $secuencia = ['AAAAAA', 'BBBBBB'];
    $i = 0;
    Str::createRandomStringsUsing(function () use (&$secuencia, &$i) {
        return $secuencia[$i++];
    });
    $segundo = Solicitud::create($datos());

    expect($segundo->codigo)->not->toBe($primero->codigo)->and($segundo->codigo)->toEndWith('BBBBBB');
});
