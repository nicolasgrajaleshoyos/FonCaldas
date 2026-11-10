<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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

test('solo el administrador original edita a otros administradores y cambia contraseñas ajenas', function () {
    $original = User::factory()->create(['is_super_admin' => true, 'is_active' => true, 'username' => 'original']);
    $secundario = User::factory()->create(['is_super_admin' => true, 'is_active' => true, 'username' => 'secundario']);
    $funcionario = User::factory()->create(['is_super_admin' => false, 'is_active' => true, 'username' => 'funcionario']);
    $datos = fn (User $u, array $extra = []) => array_merge(['name' => $u->name, 'username' => $u->username, 'email' => $u->email], $extra);

    // El secundario no toca al original...
    $this->actingAs($secundario)->get("/admin/usuarios/{$original->id}/editar")->assertForbidden();
    $this->actingAs($secundario)->put("/admin/usuarios/{$original->id}", $datos($original, ['password' => 'hackeada123']))->assertForbidden();

    // ...ni cambia la contraseña de un funcionario, aunque sí edita sus demás datos.
    $clave = $funcionario->password;
    $this->actingAs($secundario)->put("/admin/usuarios/{$funcionario->id}", $datos($funcionario, ['name' => 'Nuevo nombre', 'password' => 'hackeada123']))->assertSessionHasNoErrors();
    expect($funcionario->fresh()->name)->toBe('Nuevo nombre')->and($funcionario->fresh()->password)->toBe($clave);

    // Sí puede cambiar la suya.
    $this->actingAs($secundario)->put("/admin/usuarios/{$secundario->id}", $datos($secundario, ['is_super_admin' => '1', 'password' => 'miclave1234']))->assertSessionHasNoErrors();
    expect(Hash::check('miclave1234', $secundario->fresh()->password))->toBeTrue();

    // El original puede con todos.
    $this->actingAs($original)->put("/admin/usuarios/{$funcionario->id}", $datos($funcionario->fresh(), ['password' => 'claveoriginal1']))->assertSessionHasNoErrors();
    expect(Hash::check('claveoriginal1', $funcionario->fresh()->password))->toBeTrue();
    $this->actingAs($original)->get("/admin/usuarios/{$secundario->id}/editar")->assertOk();
});

test('se puede eliminar a un funcionario, pero no a un administrador ni a uno mismo; un funcionario no puede eliminar', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $otroAdmin = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $funcionario = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    $this->actingAs($funcionario)->post("/admin/usuarios/{$otroAdmin->id}/delete")->assertForbidden();

    $this->actingAs($super)->post("/admin/usuarios/{$otroAdmin->id}/delete")->assertSessionHasErrors('usuario');
    $this->actingAs($super)->post("/admin/usuarios/{$super->id}/delete")->assertSessionHasErrors('usuario');
    expect(User::count())->toBe(3);

    $this->actingAs($super)->post("/admin/usuarios/{$funcionario->id}/delete")->assertSessionHasNoErrors();
    expect(User::find($funcionario->id))->toBeNull();
});

test('un funcionario no puede acceder a documentos, eventos ni auditoría', function () {
    $funcionario = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    foreach (['documentos', 'eventos', 'auditoria', 'usuarios', 'pqrs'] as $ruta) {
        $this->actingAs($funcionario)->get("/admin/{$ruta}")->assertForbidden();
    }
    $this->actingAs($funcionario)->get('/admin/dashboard')->assertOk()->assertDontSee('Documentos');
});

test('el 403 muestra una página con el diseño del sitio y el motivo', function () {
    $funcionario = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);

    $this->actingAs($funcionario)->get('/admin/usuarios')
        ->assertForbidden()->assertSee('Acceso restringido')->assertSee('Solo el administrador principal puede acceder a esta sección.');
});

test('un administrador secundario recibe 403 (y no errores de validación) al enviar el formulario de otro administrador', function () {
    $original = User::factory()->create(['is_super_admin' => true, 'is_active' => true, 'username' => 'original']);
    $secundario = User::factory()->create(['is_super_admin' => true, 'is_active' => true, 'username' => 'secundario']);

    $this->actingAs($secundario)->put("/admin/usuarios/{$original->id}", [])->assertForbidden();
});
