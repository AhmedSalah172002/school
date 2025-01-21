<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends CustomFormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            "course_id" => "required|exists:courses,id",
            "lesson_pdf" => "nullable|file|mimetypes:application/pdf",
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.string' => 'The course title must be a string.',
            'title.max' => 'The course title cannot be longer than 255 characters.',

            'description.required' => 'The course description is required.',
            'description.string' => 'The course description must be a string.',

        ];
    }
}
