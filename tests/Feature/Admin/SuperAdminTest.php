<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un funcionario normal recibe 403 en las rutas exclusivas del administrador principal', function () {
    $normal = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    $this->actingAs($normal)->get('/admin/usuarios')->assertForbidden();
    $this->actingAs($normal)->get('/admin/usuarios/nuevo')->assertForbidden();
    $this->actingAs($normal)->get('/admin/tipos-tramite')->assertForbidden();
});

test('el administrador principal accede sin problema a esas rutas', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($super)->get('/admin/usuarios')->assertOk();
    $this->actingAs($super)->get('/admin/tipos-tramite')->assertOk();
});
