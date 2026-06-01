<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use App\Services\RecommendationService;

class DashboardController extends Controller
{
    public function __construct(private RecommendationService $recommender) {}

    public function index()
    {
        $student = auth()->user()->studentProfile;

        // Profile completeness check for recommendation fallback
        $profileComplete = $student &&
                           $student->career_interest &&
                           $student->industry_interest;

        // Recommended alumni (empty collection if profile incomplete)
        $recommendations = $profileComplete
            ? $this->recommender->recommend($student)
            : collect();

        // Request status counts
        $stats = [
            'pending'  => MentorshipRequest::where('student_id', $student->id)
                            ->where('status', 'pending')->count(),
            'accepted' => MentorshipRequest::where('student_id', $student->id)
                            ->where('status', 'accepted')->count(),
            'total'    => MentorshipRequest::where('student_id', $student->id)->count(),
        ];

        // Latest request activity
        $recentRequests = MentorshipRequest::with('alumni.user')
            ->where('student_id', $student->id)
            ->latest()
            ->take(4)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'recommendations',
            'profileComplete',
            'stats',
            'recentRequests'
        ));
    }
}