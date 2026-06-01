<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $student = auth()->user()->studentProfile;
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'career_interest'   => ['nullable', 'string', 'max:100'],
            'industry_interest' => ['nullable', 'string', 'max:100'],
        ]);

        auth()->user()->studentProfile->update($validated);

        return back()->with('success', 'Your profile has been updated. Recommendations will now reflect your interests.');
    }
}