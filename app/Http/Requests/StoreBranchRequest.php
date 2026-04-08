<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    /**
     * Solo permitimos que el administrador cree sucursales.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user?->isAdmin() ?? false;
    }

    /**
     * Reglas de validación ajustadas a CorporateHRP.
     */
    public function rules(): array
    {
        return [
            'code'         => 'required|string|max:10|unique:branches,code',
            'company_name' => 'required|string|max:255', // El nombre "ORBE"
            'country_id'   => 'required|exists:countries,id',
            'estado'       => 'required|boolean',
        ];
    }

    /**
     * Mensajes en español para que el usuario entienda qué falló.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Este código de sucursal ya está registrado en el sistema.',
            'code.max' => 'El código no puede tener más de 10 caracteres.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'country_id.exists' => 'El país seleccionado no existe.',
            'estado.boolean' => 'El estado debe ser Activo o Inactivo.',
        ];
    }
}
