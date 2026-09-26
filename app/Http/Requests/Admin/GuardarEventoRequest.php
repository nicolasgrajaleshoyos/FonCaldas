<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GuardarEventoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'media' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,svg,mp4,mov,avi,webm,mkv|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'media.mimes' => 'Solo se permiten imágenes o videos (MP4, MOV, AVI, WEBM, MKV).',
            'media.max' => 'El archivo es demasiado grande. Máximo 50MB.',
            'media.file' => 'El archivo enviado no es válido.',
            'media.uploaded' => 'El archivo no se pudo subir. Verifica que no supere upload_max_filesize/post_max_size de php.ini.',
        ];
    }
}
