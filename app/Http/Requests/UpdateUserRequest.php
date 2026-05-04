<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * Reglas de validación.
     */
    // En UpdateUserRequest.php
    public function rules(): array
    {
        // Esto obtiene el ID ya sea que Laravel pase el objeto o solo el número
        $userRoute = $this->route('user');
        $userId = is_object($userRoute) ? $userRoute->id : $userRoute;

        return [
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users')->ignore($userId)],
            'alias'    => ['sometimes', 'string', Rule::unique('users', 'alias')->ignore($userId)],
            'role'     => 'sometimes|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    /**
     * Mensajes personalizados (Opcional)
     */
    public function messages(): array
    {
        return [
            'alias.unique' => 'Este alias ya está en uso por otro colaborador.',
            'email.unique' => 'El correo ya pertenece a otro usuario.',
            'role.in'      => 'El rol seleccionado no es válido.',
        ];
    }
}
