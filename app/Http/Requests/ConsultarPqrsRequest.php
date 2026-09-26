<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultarPqrsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'codigo' => 'required|string',
            'documento' => 'required|string',
        ];
    }
}
