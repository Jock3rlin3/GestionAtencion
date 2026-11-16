<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsultaAtencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tratamiento_id' => ['required', 'integer', 'exists:tratamientos,id'],
            'paciente' => ['required', 'string', 'max:120'],
            'fecha_atencion' => ['required', 'date'],
            'motivo' => ['required', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'estado' => ['required', Rule::in(['pendiente', 'atendida', 'cancelada'])],
        ];
    }

    public function messages(): array
    {
        return [
            'tratamiento_id.required' => 'Selecciona un tratamiento.',
            'tratamiento_id.exists' => 'El tratamiento seleccionado no existe.',
            'paciente.required' => 'El nombre del paciente es obligatorio.',
            'fecha_atencion.required' => 'Indica la fecha y hora de atencion.',
            'fecha_atencion.date' => 'La fecha de atencion no es valida.',
            'motivo.required' => 'Indica el motivo de la consulta.',
            'estado.in' => 'Selecciona un estado valido para la consulta.',
        ];
    }
}
