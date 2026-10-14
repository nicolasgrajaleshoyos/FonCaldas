<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function funcionario(array $extra = []): User
{
    return User::factory()->create($extra + ['username' => 'luis', 'password' => 'secreto123', 'is_active' => true]);
}

test('un funcionario activo inicia sesión y llega al dashboard', function () {
    funcionario();

    $this->post('/admin/login', ['username' => 'luis', 'password' => 'secreto123'])
        ->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticated();
});

test('credenciales incorrectas no autentican', function () {
    funcionario();

    $this->post('/admin/login', ['username' => 'luis', 'password' => 'mala'])
        ->assertRedirect(route('admin'))
        ->assertSessionHas('error');
    $this->assertGuest();
});

test('un usuario inactivo no puede iniciar sesión aunque la contraseña sea correcta', function () {
    funcionario(['is_active' => false]);

    $this->post('/admin/login', ['username' => 'luis', 'password' => 'secreto123'])
        ->assertRedirect(route('admin'));
    $this->assertGuest();
});

test('un funcionario ya autenticado que visita el login es redirigido al dashboard', function () {
    $this->actingAs(funcionario())->get('/admin')->assertRedirect(route('admin.dashboard'));
});

test('un invitado que intenta entrar al panel es redirigido al login', function () {
    $this->get('/admin/dashboard')->assertRedirect(route('admin'));
});

test('un invitado ve el formulario de login', function () {
    $this->get('/admin')->assertOk();
});

test('logout invalida la sesión', function () {
    $this->actingAs(funcionario())->post('/admin/logout')->assertRedirect(route('admin'));
    $this->assertGuest();
    $this->get('/admin/dashboard')->assertRedirect(route('admin'));
});

test('si desactivan al usuario durante su sesión, la siguiente petición lo desloguea', function () {
    $user = funcionario();

    $this->actingAs($user)->get('/admin/dashboard')->assertOk();

    $user->update(['is_active' => false]);

    $this->get('/admin/dashboard')->assertRedirect(route('admin'));
    $this->assertGuest();
});

test('el login público tiene límite de intentos', function () {
    funcionario();

    foreach (range(1, 5) as $_) {
        $this->post('/admin/login', ['username' => 'luis', 'password' => 'mala'])->assertRedirect(route('admin'));
    }

    $this->post('/admin/login', ['username' => 'luis', 'password' => 'mala'])->assertStatus(429);
});
