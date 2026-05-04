<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'alias'    => 'required|string|unique:users,alias|max:255',
            'role'     => 'required|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
