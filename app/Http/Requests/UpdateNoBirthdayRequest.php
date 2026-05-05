<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoBirthdayRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'intro_text'   => 'required|string',
            'main_body'    => 'required|string',
            'closing_text' => 'required|string',
            'sign_off'     => 'required|string|max:150',
        ];
    }
}
