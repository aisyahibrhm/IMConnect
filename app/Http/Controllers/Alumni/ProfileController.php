<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $alumni = auth()->user()->alumniProfile;
        return view('alumni.profile.edit', compact('alumni'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company'          => ['nullable', 'string', 'max:150'],
            'job_position'     => ['nullable', 'string', 'max:150'],
            'industry'         => ['nullable', 'string', 'max:100'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'linkedin_url'     => ['nullable', 'url', 'max:300'],
            'instagram_url'    => ['nullable', 'url', 'max:300'],
            'facebook_url'     => ['nullable', 'url', 'max:300'],
        ], [
            'linkedin_url.url'  => 'Please enter a valid LinkedIn URL (e.g. https://linkedin.com/in/yourname).',
            'instagram_url.url' => 'Please enter a valid Instagram URL (e.g. https://instagram.com/yourname).',
            'facebook_url.url'  => 'Please enter a valid Facebook URL (e.g. https://facebook.com/yourname).',
        ]);

        auth()->user()->alumniProfile->update($validated);

        return back()->with('success', 'Your profile has been updated successfully.');
    }
}