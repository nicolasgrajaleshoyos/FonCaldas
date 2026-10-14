<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('el índice lista a los funcionarios existentes', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $otro = User::factory()->create(['name' => 'Carla']);

    $this->actingAs($super)->get('/admin/usuarios')->assertOk()->assertSee('Carla');
});

test('no se puede crear un funcionario con un username ya usado', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    User::factory()->create(['username' => 'luis']);

    $this->actingAs($super)->post('/admin/usuarios', [
        'name' => 'Otro', 'username' => 'luis', 'email' => 'otro@test.co', 'password' => 'secreto123',
    ])->assertSessionHasErrors('username');
});

test('no se puede crear un funcionario con contraseña de menos de 8 caracteres', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($super)->post('/admin/usuarios', [
        'name' => 'Otro', 'username' => 'otro', 'email' => 'otro@test.co', 'password' => 'corta',
    ])->assertSessionHasErrors('password');
});

test('toggleActive desactiva y reactiva a otro funcionario', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $otro = User::factory()->create(['is_active' => true]);

    $this->actingAs($super)->post("/admin/usuarios/{$otro->id}/toggle")->assertSessionHasNoErrors();
    expect($otro->fresh()->is_active)->toBeFalse();

    $this->actingAs($super)->post("/admin/usuarios/{$otro->id}/toggle")->assertSessionHasNoErrors();
    expect($otro->fresh()->is_active)->toBeTrue();
});

test('no se puede editar un funcionario con el email de otro ya existente', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    User::factory()->create(['email' => 'tomado@test.co']);
    $editable = User::factory()->create(['username' => 'editable', 'email' => 'libre@test.co']);

    $this->actingAs($super)->put("/admin/usuarios/{$editable->id}", [
        'name' => $editable->name, 'username' => 'editable', 'email' => 'tomado@test.co',
    ])->assertSessionHasErrors('email');
});
