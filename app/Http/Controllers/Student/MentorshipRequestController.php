<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;

class MentorshipRequestController extends Controller
{
    public function index()
    {
        $student = auth()->user()->studentProfile;

        $pending  = MentorshipRequest::with('alumni.user')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $accepted = MentorshipRequest::with('alumni.user')
            ->where('student_id', $student->id)
            ->where('status', 'accepted')
            ->latest()
            ->get();

        $rejected = MentorshipRequest::with('alumni.user')
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('student.requests.index', compact('pending', 'accepted', 'rejected'));
    }

    public function create(AlumniProfile $alumni)
    {
        abort_if($alumni->status !== 'approved', 404);

        $student = auth()->user()->studentProfile;

        // Block duplicate active requests
        $existing = MentorshipRequest::where('student_id', $student->id)
            ->where('alumni_id', $alumni->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($existing) {
            return redirect()
                ->route('student.alumni.show', $alumni)
                ->with('error', 'You have already submitted a request to this alumni. Please wait for their response.');
        }

        $alumni->load('user', 'course');
        return view('student.requests.create', compact('alumni'));
    }

    public function store(Request $request, AlumniProfile $alumni)
    {
        abort_if($alumni->status !== 'approved', 404);

        $student = auth()->user()->studentProfile;

        // Guard against duplicate submissions
        $existing = MentorshipRequest::where('student_id', $student->id)
            ->where('alumni_id', $alumni->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($existing) {
            return redirect()
                ->route('student.alumni.show', $alumni)
                ->with('error', 'You already have an active request with this alumni.');
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        MentorshipRequest::create([
            'student_id' => $student->id,
            'alumni_id'  => $alumni->id,
            'message'    => $validated['message'] ?? null,
            'status'     => 'pending',
        ]);

        return redirect()
            ->route('student.requests.index')
            ->with('success', 'Your mentorship request has been sent! You can track its status in My Requests.');
    }

    public function show(MentorshipRequest $request)
    {
        $student = auth()->user()->studentProfile;
        abort_if($request->student_id !== $student->id, 403);

        $request->load('alumni.user', 'alumni.course');
        return view('student.requests.show', compact('request'));
    }
}