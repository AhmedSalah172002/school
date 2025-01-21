<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expert_years' => 'required|integer|min:1|max:50',
            'bio' => 'required|string|max:1000',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
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
