<?php

namespace App\Http\Requests;

use App\Models\Pqrs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RadicarPqrsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(array_keys(Pqrs::TIPOS))],
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:30',
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string|max:5000',
            'acepta_datos' => 'accepted',
        ];
    }

    public function messages(): array
    {
        return ['acepta_datos.accepted' => 'Debes autorizar el tratamiento de tus datos personales.'];
    }

    public function datosPqrs(): array
    {
        return collect($this->validated())->except('acepta_datos')->all();
    }
}
