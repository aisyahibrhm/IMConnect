<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // ── List all non-admin users ───────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::with('studentProfile.course', 'alumniProfile.course')
            ->where('role', '!=', 'admin');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name',      'like', "%{$term}%")
                  ->orWhere('email',    'like', "%{$term}%")
                  ->orWhere('kpmim_id', 'like', "%{$term}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'total'    => User::where('role', '!=', 'admin')->count(),
            'students' => User::where('role', 'student')->count(),
            'alumni'   => User::where('role', 'alumni')->count(),
            'inactive' => User::where('role', '!=', 'admin')->where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    // ── View single user ───────────────────────────────────────────────────
    public function show(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->load('studentProfile.course', 'alumniProfile.course');

        // Load mentorship stats
        if ($user->isStudent() && $user->studentProfile) {
            $requests = $user->studentProfile->mentorshipRequests()
                ->with('alumni.user')
                ->latest()
                ->get();
        } elseif ($user->isAlumni() && $user->alumniProfile) {
            $requests = $user->alumniProfile->mentorshipRequests()
                ->with('student.user')
                ->latest()
                ->get();
        } else {
            $requests = collect();
        }

        return view('admin.users.show', compact('user', 'requests'));
    }

    // ── Edit user form ─────────────────────────────────────────────────────
    public function edit(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->load('studentProfile.course', 'alumniProfile.course');
        return view('admin.users.edit', compact('user'));
    }

    // ── Update user ────────────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'regex:/^01[0-9]{8,9}$/'],
        ], [
            'phone.regex' => 'Must be a valid Malaysian phone number starting with 01.',
        ]);

        $user->update([
            'name'  => strtoupper($validated['name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User account for ' . $user->name . ' has been updated successfully.');
    }

    // ── Toggle active/inactive ─────────────────────────────────────────────
    public function toggleActive(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);
        abort_if($user->id === auth()->id(), 403);

        $newState = !$user->is_active;
        $user->update(['is_active' => $newState]);

        // Terminate all sessions for this user when deactivating
        if (!$newState) {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }

        $action = $newState ? 'reactivated' : 'deactivated';
        return back()->with('success', "{$user->name}'s account has been {$action}.");
    }

    // ── Reset user password ────────────────────────────────────────────────
    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);

        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return back()->with('success', "Password for {$user->name} has been reset successfully.");
    }
}