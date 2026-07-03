<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        $courses = Course::orderBy('name')->get();
        return view('auth.register', compact('courses'));
    }

    public function register(Request $request)
    {
        // Validate all fields including kpmim_id and type-specific rules
        $validated = $request->validate([
            'type'             => ['required', 'in:student,alumni'],
            'name'             => ['required', 'string', 'min:3', 'max:150'],
            'kpmim_id'         => ['required', 'string', 'unique:users,kpmim_id',
                                   'regex:/^[A-Z]{2,6}\d{2}-\d{2}-\d{3}$/'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'phone'            => ['required', 'regex:/^01[0-9]{8,9}$/'],
            'college_email'    => ['required', 'email',
                                   'ends_with:@inderamahkota.kpm.edu.my',
                                   'unique:student_profiles,college_email',
                                   'unique:alumni_profiles,college_email'],
            'course_id'        => ['required', 'exists:courses,id'],
            'graduation_year'  => ['required', 'integer', 'min:2000', 'max:' . ($request->input('type') === 'alumni' ? date('Y') : date('Y') + 5)],
            'password'         => ['required', 'confirmed', Password::min(6)],
            // Alumni-only optional professional fields
            'company'          => ['nullable', 'string', 'max:150'],
            'job_position'     => ['nullable', 'string', 'max:150'],
            'industry'         => ['nullable', 'string', 'max:100'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
        ], [
            'kpmim_id.regex'           => 'Invalid KPMIM ID. Please enter a valid matric number (e.g. ICS24-02-027).',
            'kpmim_id.unique'          => 'This KPMIM ID is already registered. Please contact the administrator if you believe this is an error.',
            'email.unique'             => 'This email address is already registered. Please log in or use a different email.',
            'college_email.ends_with'  => 'College email must end with @inderamahkota.kpm.edu.my.',
            'college_email.unique'     => 'This college email is already registered.',
            'phone.regex'              => 'Must be a valid Malaysian phone number starting with 01.',
            'graduation_year.max'      => $request->input('type') === 'alumni'
                ? 'Alumni graduation year cannot be in the future. Please enter the year you actually graduated.'
                : 'Graduation year cannot be more than 5 years ahead of the current year.',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Create user
            $user = User::create([
                'name'      => strtoupper($validated['name']),
                'kpmim_id'  => strtoupper($validated['kpmim_id']),
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'password'  => Hash::make($validated['password']),
                'role'      => $validated['type'],
            ]);

            $profileData = [
                'user_id'         => $user->id,
                'course_id'       => $validated['course_id'],
                'phone'           => $validated['phone'],
                'college_email'   => $validated['college_email'],
                'graduation_year' => $validated['graduation_year'],
            ];

            if ($validated['type'] === 'student') {
                StudentProfile::create($profileData);
                auth()->login($user);
            } else {
                // Merge optional professional fields for alumni
                AlumniProfile::create(array_merge($profileData, [
                    'status'           => 'pending',
                    'company'          => $validated['company']          ?? null,
                    'job_position'     => $validated['job_position']     ?? null,
                    'industry'         => $validated['industry']         ?? null,
                    'years_experience' => $validated['years_experience'] ?? null,
                ]));
            }
        });

        if ($validated['type'] === 'alumni') {
            return redirect()->route('login')
                ->with('success', 'Registration submitted! Your account will be activated after administrator approval.');
        }

        return redirect()->route('student.home')
            ->with('success', 'Welcome to IMConnect! Your account has been created.');
    }
}