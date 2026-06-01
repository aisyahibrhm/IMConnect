<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\MentorshipRequest;
use App\Models\StudentProfile;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'      => StudentProfile::count(),
            'total_alumni'        => AlumniProfile::where('status', 'approved')->count(),
            'pending_approvals'   => AlumniProfile::where('status', 'pending')->count(),
            'total_requests'      => MentorshipRequest::count(),
            'accepted_requests'   => MentorshipRequest::where('status', 'accepted')->count(),
            'pending_requests'    => MentorshipRequest::where('status', 'pending')->count(),
        ];

        $recentApprovals = AlumniProfile::with('user', 'course')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentRequests = MentorshipRequest::with('student.user', 'alumni.user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentApprovals', 'recentRequests'));
    }
}