<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $assignmentId = $this->route('assignment') ?? $this->route('id');

        return [
            'department_id' => [
                'required',
                'exists:departments,id', // CORRECCIÓN: Tabla departments (plural)
                Rule::unique('assignments', 'department_id')
                    ->where(function ($query) {
                        // CORRECCIÓN: position_id (singular)
                        return $query->where('position_id', $this->position_id);
                    })
                    ->ignore($assignmentId),
            ],
            'position_id' => 'required|exists:positions,id',
            'estado' => 'sometimes|boolean',
        ];
    }
}
