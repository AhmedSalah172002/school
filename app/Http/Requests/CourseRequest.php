<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends CustomFormRequest
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
            'course_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
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

            'day.required' => 'The course day is required.',
            'day.in' => 'The course day must be one of the following: Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday.',

            'time.required' => 'The course time is required.',
            'time.date_format' => 'The course time must be in the format HH:mm (24-hour format).',

            'price.required' => 'The course price is required.',
            'price.numeric' => 'The course price must be a number.',
            'price.min' => 'The course price must be a valid number.',
        ];
    }
}
