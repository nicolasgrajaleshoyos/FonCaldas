<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarUsuarioRequest;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('pages.admin-usuarios-index', [
            'usuarios' => User::with('tramiteTipos')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return $this->formulario(new User);
    }

    public function store(GuardarUsuarioRequest $request)
    {
        $usuario = User::create($this->atributos($request) + ['is_active' => true]);
        $usuario->tramiteTipos()->sync($request->input('tramite_tipos', []));

        return redirect()->route('admin.usuarios.index')->with('success', 'Funcionario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return $this->formulario($usuario);
    }

    public function update(GuardarUsuarioRequest $request, User $usuario)
    {
        $usuario->update($this->atributos($request));
        $usuario->tramiteTipos()->sync($request->input('tramite_tipos', []));

        return redirect()->route('admin.usuarios.index')->with('success', 'Funcionario actualizado correctamente.');
    }

    public function toggleActive(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No puedes desactivar tu propia cuenta.']);
        }

        $usuario->update(['is_active' => ! $usuario->is_active]);

        return back()->with('success', $usuario->is_active ? 'Funcionario activado.' : 'Funcionario desactivado.');
    }

    private function formulario(User $usuario)
    {
        return view('pages.admin-usuarios-form', [
            'usuario' => $usuario,
            'tramiteTipos' => TramiteTipo::orderBy('nombre')->get(),
        ]);
    }

    /** Atributos del usuario; la contraseña (ya hasheada por el cast) solo se incluye si se envió. */
    private function atributos(GuardarUsuarioRequest $request): array
    {
        $atributos = $request->safe()->only(['name', 'username', 'email']);

        if ($request->filled('password')) {
            $atributos['password'] = $request->input('password');
        }

        return $atributos + ['is_super_admin' => $request->boolean('is_super_admin')];
    }
}
