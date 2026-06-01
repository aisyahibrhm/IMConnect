<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;

class AlumniSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = AlumniProfile::with('user', 'course')
            ->where('status', 'approved');

        // Keyword search — name, job position, industry, company
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function ($q) use ($kw) {
                $q->whereHas('user', fn ($u) =>
                        $u->where('name', 'like', "%{$kw}%"))
                  ->orWhere('job_position', 'like', "%{$kw}%")
                  ->orWhere('industry',     'like', "%{$kw}%")
                  ->orWhere('company',      'like', "%{$kw}%");
            });
        }

        // Filter: course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter: industry
        if ($request->filled('industry')) {
            $query->where('industry', 'like', '%' . $request->industry . '%');
        }

        $alumni  = $query->latest()->paginate(12)->withQueryString();
        $courses = Course::orderBy('name')->get();

        // Distinct industries for filter dropdown
        $industries = AlumniProfile::where('status', 'approved')
            ->whereNotNull('industry')
            ->distinct()
            ->pluck('industry')
            ->sort()
            ->values();

        return view('student.search.index', compact('alumni', 'courses', 'industries'));
    }

    public function show(AlumniProfile $alumni)
    {
        // Only show approved alumni
        abort_if($alumni->status !== 'approved', 404);
        $alumni->load('user', 'course');

        $student = auth()->user()->studentProfile;

        // Check if student already has an active request to this alumni
        $existingRequest = MentorshipRequest::where('student_id', $student->id)
            ->where('alumni_id', $alumni->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        return view('student.search.show', compact('alumni', 'existingRequest'));
    }
}