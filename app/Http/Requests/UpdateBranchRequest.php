<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    /**
     * Solo el administrador puede editar sucursales.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user?->isAdmin() ?? false;
    }

    /**
     * Reglas de validación para la actualización.
     */
    public function rules(): array
    {
        // Obtenemos el código actual de la ruta (ej. /branches/CEON)
        $branchCode = $this->route('branch');

        return [
            // Validamos que sea único, pero IGNORANDO el registro actual
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('branches', 'code')->ignore($branchCode, 'code'),
            ],

            'company_id' => 'required|exists:companies,id',
            'country_id' => 'required|exists:countries,id',
            'estado'     => 'required|boolean',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'No puedes usar este código porque ya pertenece a otra sucursal.',
            'code.required' => 'El código de la sucursal es obligatorio.',
            'company_id.exists' => 'La empresa seleccionada no es válida.',
            'country_id.exists' => 'El país seleccionado no es válido.',
        ];
    }
}
