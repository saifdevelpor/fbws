<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionVersionIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        $currentVersion = (int) ($user->session_version ?? 1);
        $sessionVersion = $request->session()->get('session_version');

        if ($sessionVersion === null) {
            $request->session()->put('session_version', $currentVersion);
            return $next($request);
        }

        if ((int) $sessionVersion !== $currentVersion) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'You have been logged out from all devices. Please sign in again.');
        }

        return $next($request);
    }
}
