<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultarSolicitudRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'codigo' => 'required|string',
            'asociado_documento' => 'required|string',
        ];
    }
}
