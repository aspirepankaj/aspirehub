<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. If not logged in at all, redirect to client login
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. If logged in but not a client, redirect them to their respective portal dashboard
        if (!$user->client || $user->client->status !== 'active') {
            if ($user->admin && $user->admin->is_active) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->staff && $user->staff->status === 'active') {
                return redirect()->route('staff.dashboard');
            }

            // Fallback: log out if they are not active in any role
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Access denied. Your account is inactive or not registered as a client.',
            ]);
        }

        return $next($request);
    }
}
