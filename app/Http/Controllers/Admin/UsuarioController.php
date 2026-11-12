<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarUsuarioRequest;
use App\Models\TramiteTipo;
use App\Models\User;
use App\Services\Solicitudes\FlujoSolicitud;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function __construct(private FlujoSolicitud $flujo) {}

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

    private function autorizarEdicion(Request $request, User $usuario): void
    {
        abort_unless($request->user()->puedeEditar($usuario), 403, 'Solo el administrador principal original puede editar a otros administradores.');
    }

    public function edit(Request $request, User $usuario)
    {
        $this->autorizarEdicion($request, $usuario);

        return $this->formulario($usuario);
    }

    public function update(GuardarUsuarioRequest $request, User $usuario)
    {
        $this->autorizarEdicion($request, $usuario);

        $atributos = $this->atributos($request);

        // Se decide con el rol que realmente se va a guardar (un administrador secundario no puede cambiarlo).
        $quedaSinPrincipal = $usuario->is_super_admin && ! $atributos['is_super_admin']
            && User::where('is_super_admin', true)->where('is_active', true)->whereKeyNot($usuario->id)->doesntExist();

        if ($quedaSinPrincipal) {
            return back()->withInput()->withErrors(['is_super_admin' => 'Debe existir al menos un administrador principal activo; no puedes quitar este rol al último.']);
        }

        if (! $request->user()->puedeCambiarClaveDe($usuario)) {
            unset($atributos['password']);
        }

        $usuario->update($atributos);
        $usuario->tramiteTipos()->sync($request->input('tramite_tipos', []));

        // Si perdió acceso a algún tipo de trámite, sus solicitudes abiertas de ese tipo pasan a otro responsable.
        $movidas = $this->flujo->redistribuirAbiertasDe($usuario->refresh(), $request->user());

        return redirect()->route('admin.usuarios.index')->with('success', 'Funcionario actualizado correctamente.'.$this->avisoReparto($movidas));
    }

    public function toggleActive(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No puedes desactivar tu propia cuenta.']);
        }

        if ($usuario->is_super_admin) {
            return back()->withErrors(['usuario' => 'El administrador principal no se puede desactivar.']);
        }

        $usuario->update(['is_active' => ! $usuario->is_active]);

        if ($usuario->is_active) {
            return back()->with('success', 'Funcionario activado.');
        }

        $movidas = $this->flujo->redistribuirAbiertasDe($usuario, $request->user());

        return back()->with('success', 'Funcionario desactivado.'.$this->avisoReparto($movidas));
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->is($request->user()) || $usuario->is_super_admin) {
            return back()->withErrors(['usuario' => 'No se puede eliminar a un administrador principal ni a tu propia cuenta.']);
        }

        // Antes de borrar: sus solicitudes abiertas pasan a otro responsable en vez de quedar sin nadie.
        $movidas = $this->flujo->redistribuirAbiertasDe($usuario, $request->user(), saliente: true);
        $usuario->delete();

        return back()->with('success', 'Funcionario eliminado.'.$this->avisoReparto($movidas));
    }

    private function avisoReparto(int $movidas): string
    {
        return match ($movidas) {
            0 => '',
            1 => ' Su solicitud abierta se asignó a otro responsable.',
            default => " Sus {$movidas} solicitudes abiertas se asignaron a otros responsables.",
        };
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

        // Otorgar o quitar el rol de administrador es exclusivo del administrador principal original: si no,
        // un administrador secundario podría crear administradores y saltarse esa jerarquía.
        $usuario = $request->route('usuario');

        return $atributos + ['is_super_admin' => $request->user()->esPropietario()
            ? $request->boolean('is_super_admin')
            : (bool) $usuario?->is_super_admin];
    }
}
