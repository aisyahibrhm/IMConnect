<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $base = [
            'name'                  => ['required', 'string', 'min:3', 'max:100'],
            'kpmim_id'              => ['required', 'regex:/^[A-Z]{2,6}\d{2}-\d{2}-\d{3}$/', 'unique:users,kpmim_id'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'college_email'         => ['required', 'email', 'unique:student_profiles,college_email', 'unique:alumni_profiles,college_email', 'ends_with:@inderamahkota.kpm.edu.my'],
            'course_id'             => ['required', 'exists:courses,id'],
            'graduation_year'       => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'phone'                 => ['required', 'regex:/^01[0-9]{8,9}$/'],
            'password'              => ['required', 'confirmed', Password::min(6)],
            'type'                  => ['required', 'in:student,alumni'],
        ];

        return $base;
    }

    public function messages(): array
    {
        return [
            'college_email.ends_with' => 'College email must end with @inderamahkota.kpm.edu.my.',
            'phone.regex'             => 'Phone must be a valid Malaysian number starting with 01.',
            'college_email.unique'    => 'This college email is already registered.',
            'email.unique'            => 'This email is already registered.',
            'kpmim_id.regex'         => 'Invalid KPMIM ID format. Expected format: ABC12-34-567.',
            'kpmim_id.unique'        => 'This KPMIM ID is already registered.',
            'graduation_year.max'     => 'Graduation year cannot be in the far future.',
            'graduation_year.min'     => 'Graduation year must be a valid year.',
            'password.min'           => 'Password must be at least 6 characters.',
            'password.confirmed'     => 'Password confirmation does not match.',
        ];
    }
}