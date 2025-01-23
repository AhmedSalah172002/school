<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            "course_id" => "required|exists:courses,id",
            "lesson_pdf" => "nullable|string",
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.string' => 'The course title must be a string.',
            'title.max' => 'The course title cannot be longer than 255 characters.',

            "lesson_pdf.string" => "The lesson_pdf must be a path or url.",

            'description.required' => 'The course description is required.',
            'description.string' => 'The course description must be a string.',

        ];
    }
}
