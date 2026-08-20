<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Core\Activity\Models\ActivityLog;
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

        $targetRoleName = match (true) {
            $targetUser->staff !== null => 'Staff Member',
            $targetUser->client !== null => 'Client Portal',
            $targetUser->admin !== null => 'Administrator',
            default => 'User',
        };

        // Record Activity Log BEFORE changing session
        ActivityLog::create([
            'user_id'       => $currentUser->id,
            'action'        => 'impersonated',
            'loggable_type' => User::class,
            'loggable_id'   => $targetUser->id,
            'description'   => "{$currentUser->name} (Administrator) switched session to {$targetUser->name} ({$targetRoleName}).",
            'meta'          => [
                'ip'                => request()->ip(),
                'agent'             => request()->userAgent(),
                'impersonator_id'   => $currentUser->id,
                'impersonator_name' => $currentUser->name,
                'target_user_id'    => $targetUser->id,
                'target_user_name'  => $targetUser->name,
                'target_role'       => $targetRoleName,
            ]
        ]);

        // Store the original admin ID in the session
        session()->put('impersonator_id', $currentUser->id);

        // Log in as target user
        auth()->login($targetUser);

        // Update last_login_at on the client/staff record
        if ($targetUser->client) {
            $targetUser->client->update(['last_login_at' => now()]);
        }
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
            $impersonatedUser = auth()->user();
            session()->forget('impersonator_id');

            $originalAdmin = User::findOrFail($originalId);

            $impersonatedRoleName = match (true) {
                $impersonatedUser?->staff !== null => 'Staff Member',
                $impersonatedUser?->client !== null => 'Client Portal',
                default => 'User',
            };

            // Record Activity Log for switching back
            ActivityLog::create([
                'user_id'       => $originalAdmin->id,
                'action'        => 'switched_back',
                'loggable_type' => User::class,
                'loggable_id'   => $impersonatedUser?->id ?? $originalAdmin->id,
                'description'   => "{$originalAdmin->name} (Administrator) returned to administrator panel from {$impersonatedUser?->name} ({$impersonatedRoleName}).",
                'meta'          => [
                    'ip'                 => request()->ip(),
                    'agent'              => request()->userAgent(),
                    'admin_id'           => $originalAdmin->id,
                    'admin_name'         => $originalAdmin->name,
                    'previous_user_id'   => $impersonatedUser?->id,
                    'previous_user_name' => $impersonatedUser?->name,
                ]
            ]);

            auth()->login($originalAdmin);

            return redirect()->route('dashboard')->with('success', 'Returned to administrator panel.');
        }

        return redirect()->route('dashboard');
    }
}
