<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $alumni = auth()->user()->alumniProfile;

        $stats = [
            'pending'  => MentorshipRequest::where('alumni_id', $alumni->id)->where('status', 'pending')->count(),
            'accepted' => MentorshipRequest::where('alumni_id', $alumni->id)->where('status', 'accepted')->count(),
            'rejected' => MentorshipRequest::where('alumni_id', $alumni->id)->where('status', 'rejected')->count(),
        ];

        $recentRequests = MentorshipRequest::with('student.user', 'student.course')
            ->where('alumni_id', $alumni->id)
            ->latest()
            ->take(5)
            ->get();

        $profileComplete = $alumni->company && $alumni->job_position && $alumni->industry;

        return view('alumni.dashboard', compact('alumni', 'stats', 'recentRequests', 'profileComplete'));
    }
}