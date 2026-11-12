<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarUsuarioRequest extends FormRequest
{
    /** Se decide antes de validar: quien no puede editar esta cuenta recibe 403, no errores de formulario. */
    public function authorize(): bool
    {
        $usuario = $this->route('usuario');

        return ! $usuario || $this->user()->puedeEditar($usuario);
    }

    /** SQLite compara con mayúsculas: sin esto "Ana@x.co" y "ana@x.co" pasarían como correos distintos. */
    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => strtolower(trim($this->email))]);
        }
    }

    public function rules(): array
    {
        /** @var User|null $usuario */
        $usuario = $this->route('usuario');

        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($usuario)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario)],
            'password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8'],
            'tramite_tipos' => 'nullable|array',
            'tramite_tipos.*' => 'exists:tramite_tipos,id',
        ];
    }
}
