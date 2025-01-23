<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expert_years' => 'required|integer|min:1|max:50',
            'bio' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'expert_years.required' => 'The number of expert years is required.',
            'expert_years.integer' => 'The number of expert years must be an integer.',
            'expert_years.min' => 'The number of expert years must be at least 1.',
            'expert_years.max' => 'The number of expert years cannot exceed 50.',

            'bio.required' => 'The bio is required.',
            'bio.string' => 'The bio must be a string.',
            'bio.max' => 'The bio cannot exceed 1000 characters.',
        ];
    }
}
