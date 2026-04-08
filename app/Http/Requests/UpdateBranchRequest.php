<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    /**
     * Solo permitimos que el administrador actualice sucursales.
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
        // Obtenemos el parámetro de la ruta ('ATI', 'CEO', etc.)
        $branchCode = $this->route('branch');

        // Si pasas el objeto modelo, extraemos el valor de la llave primaria
        $ignoreKey = is_object($branchCode) ? $branchCode->code : $branchCode;

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                // Ignoramos el registro actual usando su código único
                Rule::unique('branches', 'code')->ignore($ignoreKey, 'code'),
            ],
            'company_name' => 'sometimes|string|max:255',
            'country_id'   => 'sometimes|exists:countries,id',
            'estado'       => 'sometimes|boolean',
        ];
    }
    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'code.unique'       => 'Este código de sucursal ya pertenece a otra sede.',
            'code.max'          => 'El código no puede tener más de 10 caracteres.',
            'country_id.exists' => 'El país seleccionado no es válido.',
            'estado.boolean'    => 'El valor del estado es incorrecto.',
        ];
    }
}
