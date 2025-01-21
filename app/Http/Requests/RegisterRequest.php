<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends CustomFormRequest
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
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => ['required','string','regex:/^(010|011|012|015)[0-9]{7}/','unique:users,phone'],
            'role' => 'required|string|in:teacher,student,admin',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'The username field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'The phone number must be valid.',
            'phone.unique' => 'The phone number is already taken.',
            'role.required' => 'The role field is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }
}
