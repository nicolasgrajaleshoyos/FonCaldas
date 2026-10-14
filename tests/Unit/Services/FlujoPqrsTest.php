<?php

use App\Models\Pqrs;
use App\Services\Pqrs\FlujoPqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('tramitar() no hace nada si la PQRS ya no está en estado recibida', function () {
    Mail::fake();
    $pqrs = Pqrs::create([
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123', 'email' => 'ana@test.co',
        'asunto' => 'x', 'descripcion' => 'x', 'estado' => Pqrs::ESTADO_RESPONDIDA,
    ]);

    app(FlujoPqrs::class)->tramitar($pqrs);

    expect($pqrs->fresh()->estado)->toBe(Pqrs::ESTADO_RESPONDIDA);
    Mail::assertNothingSent();
});
