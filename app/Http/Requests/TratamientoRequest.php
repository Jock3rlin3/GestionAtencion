<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120', Rule::unique('tratamientos', 'nombre')->ignore($this->route('tratamiento'))],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'duracion_minutos' => ['required', 'integer', 'min:1', 'max:1440'],
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del tratamiento es obligatorio.',
            'nombre.unique' => 'Ya existe un tratamiento con ese nombre.',
            'duracion_minutos.required' => 'Indica la duracion del tratamiento.',
            'duracion_minutos.integer' => 'La duracion debe expresarse en minutos enteros.',
            'duracion_minutos.min' => 'La duracion debe ser de al menos un minuto.',
            'precio.required' => 'Indica el precio del tratamiento.',
            'precio.numeric' => 'El precio debe ser un importe valido.',
            'precio.min' => 'El precio no puede ser negativo.',
        ];
    }
}
