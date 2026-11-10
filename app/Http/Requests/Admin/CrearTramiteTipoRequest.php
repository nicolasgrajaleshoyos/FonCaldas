<?php

namespace App\Http\Requests\Admin;

use App\Models\TramiteTipo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CrearTramiteTipoRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var TramiteTipo|null $actual */
        $actual = $this->route('tramiteTipo');

        return [
            'nombre' => ['required', 'string', 'max:255', function ($atributo, $valor, $fallar) use ($actual) {
                $slug = Str::slug($valor);

                if ($slug === '') {
                    $fallar('El nombre debe contener letras o números.');
                } elseif (TramiteTipo::where('slug', $slug)->when($actual, fn ($q) => $q->whereKeyNot($actual->id))->exists()
                    || TramiteTipo::where('nombre', $valor)->when($actual, fn ($q) => $q->whereKeyNot($actual->id))->exists()) {
                    $fallar('Ya existe un tipo de trámite con ese nombre.');
                }
            }],
            'descripcion' => 'nullable|string|max:255',
            'es_certificacion' => 'nullable|boolean',
        ];
    }
}
