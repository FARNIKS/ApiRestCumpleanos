<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Importante importar esta clase
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                'exists:departments,id',
                Rule::unique('assignments')->where(function ($query) {
                    // CORRECCIÓN: Usar position_id (singular)
                    return $query->where('position_id', $this->position_id);
                }),
            ],
            'position_id' => 'required|exists:positions,id',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // CORRECCIÓN: El campo es department_id
            'department_id.unique' => 'Esta combinación de Departamento y Cargo ya existe.',
        ];
    }
}
