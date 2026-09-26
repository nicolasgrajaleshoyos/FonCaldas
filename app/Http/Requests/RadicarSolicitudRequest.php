<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RadicarSolicitudRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tramite_tipo_id' => 'required|exists:tramite_tipos,id',
            'asociado_nombre' => 'required|string|max:255',
            'asociado_documento' => 'required|string|max:50',
            'asociado_email' => 'required|email|max:255',
            'asociado_telefono' => 'nullable|string|max:30',
            'descripcion' => 'required|string',
            'documentos.*' => 'nullable|file|max:10240',
        ];
    }

    /** Datos de la solicitud, sin los archivos adjuntos. */
    public function datosSolicitud(): array
    {
        return collect($this->validated())->except('documentos')->all();
    }
}
