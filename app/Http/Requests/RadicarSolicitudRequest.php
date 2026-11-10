<?php

namespace App\Http\Requests;

use App\Models\SolicitudDocumento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RadicarSolicitudRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tramite_tipo_id' => ['required', Rule::exists('tramite_tipos', 'id')->where('activo', true)],
            'asociado_nombre' => 'required|string|max:255',
            'asociado_documento' => 'required|string|max:50',
            'asociado_email' => 'required|email|max:255',
            'asociado_telefono' => 'nullable|string|max:30',
            'descripcion' => 'required|string|max:5000',
            'documentos.*' => 'nullable|file|max:10240|mimes:'.SolicitudDocumento::TIPOS_PERMITIDOS,
        ];
    }

    /** Datos de la solicitud, sin los archivos adjuntos. */
    public function datosSolicitud(): array
    {
        return collect($this->validated())->except('documentos')->all();
    }
}
