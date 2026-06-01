<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;

class AlumniApprovalController extends Controller
{
    public function index()
    {
        $pending = AlumniProfile::with('user', 'course')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $approved = AlumniProfile::with('user', 'course')
            ->where('status', 'approved')
            ->latest()
            ->paginate(15);

        $rejected = AlumniProfile::with('user', 'course')
            ->where('status', 'rejected')
            ->latest()
            ->paginate(15);

        return view('admin.alumni.index', compact('pending', 'approved', 'rejected'));
    }

    public function approve(AlumniProfile $alumni)
    {
        $alumni->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', "Alumni account for {$alumni->user->name} has been approved.");
    }

    public function reject(Request $request, AlumniProfile $alumni)
    {
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $alumni->update(['status' => 'rejected']);

        // Soft note: rejection reason stored on request if needed later
        return back()->with('success', "Alumni account for {$alumni->user->name} has been rejected.");
    }
}