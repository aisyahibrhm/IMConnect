<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumniApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isAlumni()) {
            $profile = $user->alumniProfile;

            if (!$profile || $profile->isPending()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Your alumni account is pending administrator approval. Please wait for activation.');
            }

            if ($profile->status === 'rejected') {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Your registration was not approved. Please contact the administrator.');
            }
        }

        return $next($request);
    }
}