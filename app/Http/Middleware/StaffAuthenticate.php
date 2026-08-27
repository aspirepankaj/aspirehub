<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. If not logged in at all, redirect to staff login
        if (!$user) {
            return redirect()->route('staff.login');
        }

        // 2. If logged in but not active staff, redirect to their correct dashboard
        if (!$user->staff || $user->staff->status !== 'active') {
            if ($user->admin && $user->admin->is_active) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->client && $user->client->status === 'active') {
                return redirect()->route('client.dashboard');
            }

            // Fallback
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('staff.login')->withErrors([
                'email' => 'Access denied. You do not have staff privileges or your account is deactivated.',
            ]);
        }

        return $next($request);
    }
}
