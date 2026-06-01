<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!auth()->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password. Please try again.');
        }

        $user = auth()->user();

        // Block deactivated accounts
        if (!$user->is_active) {
            auth()->logout();
            return back()
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        // Block pending/rejected alumni
        if ($user->isAlumni()) {
            $profile = $user->alumniProfile;

            if (!$profile || $profile->isPending()) {
                auth()->logout();
                return back()
                    ->with('error', 'Your account is pending administrator approval. Please wait for activation.');
            }

            if ($profile->status === 'rejected') {
                auth()->logout();
                return back()
                    ->with('error', 'Your registration was not approved. Please contact the administrator.');
            }
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    private function redirectByRole(string $role): \Illuminate\Http\RedirectResponse
    {
        return match ($role) {
            'admin'   => redirect()->route('admin.home'),
            'alumni'  => redirect()->route('alumni.home'),
            'student' => redirect()->route('student.home'),
            default   => redirect('/'),
        };
    }
}