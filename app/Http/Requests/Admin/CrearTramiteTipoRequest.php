<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CrearTramiteTipoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'es_certificacion' => 'nullable|boolean',
        ];
    }
}
