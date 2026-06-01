<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;

class MentorshipController extends Controller
{
    public function index()
    {
        $alumniId = auth()->user()->alumniProfile->id;

        $pending  = MentorshipRequest::with('student.user', 'student.course')
            ->where('alumni_id', $alumniId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $responded = MentorshipRequest::with('student.user', 'student.course')
            ->where('alumni_id', $alumniId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->latest()
            ->paginate(10);

        return view('alumni.mentorship.index', compact('pending', 'responded'));
    }

    public function show(MentorshipRequest $request)
    {
        // Ensure this request belongs to the logged-in alumni
        abort_if($request->alumni_id !== auth()->user()->alumniProfile->id, 403);
        $request->load('student.user', 'student.course');
        return view('alumni.mentorship.show', compact('request'));
    }

    public function accept(MentorshipRequest $request)
    {
        abort_if($request->alumni_id !== auth()->user()->alumniProfile->id, 403);
        abort_if(!$request->isPending(), 422, 'This request has already been responded to.');

        $request->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Mentorship request accepted. The student\'s contact details are now visible.');
    }

    public function reject(Request $request, MentorshipRequest $mentorshipRequest)
    {
        abort_if($mentorshipRequest->alumni_id !== auth()->user()->alumniProfile->id, 403);
        abort_if(!$mentorshipRequest->isPending(), 422, 'This request has already been responded to.');

        $mentorshipRequest->update([
            'status'           => 'rejected',
            'responded_at'     => now(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return back()->with('success', 'Mentorship request has been declined.');
    }
}