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
            'description' => 'required|string',
            'category' => ['required', Rule::in($categorias->slugs())],
            'document' => 'required|mimes:pdf',
        ];
    }

    public function messages(): array
    {
        return ['document.mimes' => 'Solo se permiten archivos PDF.'];
    }
}
