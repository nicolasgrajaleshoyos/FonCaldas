<?php

use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function superActivo(): User
{
    return User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
}

test('los listados de documentos y eventos del panel cargan correctamente', function () {
    $super = superActivo();

    $this->actingAs($super)->get('/admin/documentos')->assertOk();
    $this->actingAs($super)->get('/admin/eventos')->assertOk();
});

test('el detalle de una PQRS en el panel carga correctamente', function () {
    $super = superActivo();
    $pqrs = Pqrs::create([
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123',
        'email' => 'ana@test.co', 'asunto' => 'x', 'descripcion' => 'x',
    ]);

    $this->actingAs($super)->get("/admin/pqrs/{$pqrs->id}")->assertOk();
});

test('los formularios de crear y editar funcionario cargan correctamente', function () {
    $super = superActivo();
    $otro = User::factory()->create();

    $this->actingAs($super)->get('/admin/usuarios/nuevo')->assertOk();
    $this->actingAs($super)->get("/admin/usuarios/{$otro->id}/editar")->assertOk();
});
