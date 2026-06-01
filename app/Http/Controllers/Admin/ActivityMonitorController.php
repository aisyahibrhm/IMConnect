<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;

class ActivityMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = MentorshipRequest::with('student.user', 'alumni.user', 'student.course');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('student.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('alumni.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'    => MentorshipRequest::count(),
            'pending'  => MentorshipRequest::where('status', 'pending')->count(),
            'accepted' => MentorshipRequest::where('status', 'accepted')->count(),
            'rejected' => MentorshipRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.mentorship.index', compact('requests', 'stats'));
    }
}