<?php

namespace App\Http\Requests\Admin;

use App\Services\Transparencia\CategoriasTransparencia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarDocumentoRequest extends FormRequest
{
    public function rules(CategoriasTransparencia $categorias): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category' => ['required', Rule::in($categorias->slugs())],
            // Al editar (PUT) el PDF es opcional: sin archivo nuevo se conserva el actual.
            'document' => [$this->isMethod('PUT') ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'document.mimes' => 'Solo se permiten archivos PDF.',
            'document.max' => 'El PDF es demasiado grande. Máximo 20MB.',
        ];
    }
}
