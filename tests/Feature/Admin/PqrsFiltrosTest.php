<?php

use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function crearPqrsAdmin(array $extra = []): Pqrs
{
    return Pqrs::create($extra + [
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'x', 'descripcion' => 'x',
    ]);
}

test('el listado admin filtra por tipo', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    crearPqrsAdmin(['tipo' => 'queja']);
    crearPqrsAdmin(['tipo' => 'peticion']);

    $this->actingAs($super)->get('/admin/pqrs?tipo=peticion')
        ->assertOk()->assertViewHas('pqrs', fn ($p) => $p->total() === 1 && $p->first()->tipo === 'peticion');
});

test('el listado admin filtra por estado', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    crearPqrsAdmin(['estado' => Pqrs::ESTADO_RECIBIDA]);
    crearPqrsAdmin(['estado' => Pqrs::ESTADO_RESPONDIDA]);

    $this->actingAs($super)->get('/admin/pqrs?estado=respondida')
        ->assertOk()->assertViewHas('pqrs', fn ($p) => $p->total() === 1 && $p->first()->estado === Pqrs::ESTADO_RESPONDIDA);
});
