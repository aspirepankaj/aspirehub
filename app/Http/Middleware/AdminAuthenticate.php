<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. If not logged in at all, redirect to admin login
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Allow access if user is currently being impersonated
        if (session()->has('impersonator_id')) {
            return $next($request);
        }

        // 2. If logged in but not an active admin, redirect to their correct dashboard
        if (!$user->admin || !$user->admin->is_active) {
            if ($user->staff && $user->staff->status === 'active') {
                return redirect()->route('staff.dashboard');
            }
            if ($user->client && $user->client->status === 'active') {
                return redirect()->route('client.dashboard');
            }

            // Fallback
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Access denied. You do not have administrator privileges.',
            ]);
        }

        return $next($request);
    }
}
