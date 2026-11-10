<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Se muestra siempre el formulario: si ya hay una sesión abierta (p. ej. la del administrador) y se
        // redirigiera al panel, no habría forma de entrar con otra cuenta sin cerrar sesión antes.
        return view('pages.admin-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Límite por cuenta (sin la IP), además del throttle por IP de la ruta: frena la fuerza bruta distribuida
        // contra un usuario concreto. Es holgado y dura 1 minuto para que no sirva para dejar fuera al administrador.
        $clave = 'login:'.strtolower($data['username']);

        if (RateLimiter::tooManyAttempts($clave, 10)) {
            $segundos = RateLimiter::availableIn($clave);

            return redirect()->route('admin')->with('error', "Demasiados intentos. Intenta de nuevo en {$segundos} segundos.");
        }

        if (! Auth::attempt(['username' => $data['username'], 'password' => $data['password'], 'is_active' => true])) {
            RateLimiter::hit($clave, 60);

            return redirect()->route('admin')->with('error', 'Usuario o contraseña incorrecta');
        }

        RateLimiter::clear($clave);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin');
    }
}
