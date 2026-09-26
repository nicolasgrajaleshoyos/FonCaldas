<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FiltrarSolicitudesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'estado' => 'nullable|string',
            'tramite_tipo_id' => 'nullable|integer',
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
        ];
    }
}
