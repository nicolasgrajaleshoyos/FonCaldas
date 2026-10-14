<?php

use App\Models\Pqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function crearPqrs(array $extra = []): Pqrs
{
    return Pqrs::create($extra + [
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'Demora', 'descripcion' => 'Detalle',
    ]);
}

test('nace en estado recibida por defecto', function () {
    expect(crearPqrs()->estado)->toBe(Pqrs::ESTADO_RECIBIDA);
});

test('tipoLabel y estadoLabel traducen el catálogo y caen al valor crudo si no lo reconocen', function () {
    $pqrs = crearPqrs();

    expect($pqrs->tipoLabel())->toBe('Queja')->and($pqrs->estadoLabel())->toBe('Recibida');

    $pqrs->forceFill(['tipo' => 'otro', 'estado' => 'x'])->save();
    $pqrs = $pqrs->fresh();
    expect($pqrs->tipoLabel())->toBe('otro')->and($pqrs->estadoLabel())->toBe('x');
});
