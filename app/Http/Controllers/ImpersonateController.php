<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function start($userId)
    {
        $currentUser = auth()->user();

        // Security check: Only active administrators can initiate impersonation
        if (!$currentUser || !$currentUser->admin || !$currentUser->admin->is_active) {
            abort(403, 'Unauthorized action.');
        }

        $targetUser = User::findOrFail($userId);

        // Prevent self-impersonation
        if ($currentUser->id === $targetUser->id) {
            return redirect()->back()->with('error', 'You cannot impersonate yourself.');
        }

        // Store the original admin ID in the session
        session()->put('impersonator_id', $currentUser->id);

        // Log in as target user
        auth()->login($targetUser);

        // Update last_login_at on the staff record (if this is a staff user)
        if ($targetUser->staff) {
            $targetUser->staff->update(['last_login_at' => now()]);
        }

        // Redirect to dashboard (routes/web.php handles role redirection)
        return redirect()->route('dashboard')->with('success', "Logged in as {$targetUser->name}");
    }

    public function stop()
    {
        if (session()->has('impersonator_id')) {
            $originalId = session()->get('impersonator_id');
            session()->forget('impersonator_id');

            $originalAdmin = User::findOrFail($originalId);
            auth()->login($originalAdmin);

            return redirect()->route('dashboard')->with('success', 'Returned to administrator panel.');
        }

        return redirect()->route('dashboard');
    }
}
