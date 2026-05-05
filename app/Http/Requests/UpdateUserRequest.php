<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
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

    public function messages(): array
    {
        return [
            'alias.unique' => 'Este alias ya está en uso por otro colaborador.',
            'email.unique' => 'El correo ya pertenece a otro usuario.',
            'role.in'      => 'El rol seleccionado no es válido.',
        ];
    }
}
