<?php

namespace App\Modules\Core\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class AdminDashboard extends Component
{
    // Define the static demo data
    public array $stats = [];
    public array $revenueData = [];
    public array $recentClients = [];
    public array $recentNotifications = [];
    public array $recentActivities = [];

    public function mount()
    {
        // Total Clients, Active, Inactive, New, Staff, Websites, Revenue, Tickets
        $this->stats = [
            'total_clients' => \App\Modules\CRM\Clients\Models\Client::count(),
            'active_clients' => \App\Modules\CRM\Clients\Models\Client::where('status', 'active')->count(),
            'inactive_clients' => \App\Modules\CRM\Clients\Models\Client::where('status', 'inactive')->count(),
            'new_clients' => \App\Modules\CRM\Clients\Models\Client::where('created_at', '>=', now()->subDays(30))->count(),
            'total_staff' => \App\Modules\CRM\Staff\Models\Staff::count(),
            'total_websites' => \App\Modules\CRM\Websites\Models\Website::count(),
            'monthly_revenue' => 145200,
            'open_tickets' => 14,
        ];

        // Revenue chart data
        $this->revenueData = [
            'Jan' => 95000,
            'Feb' => 102000,
            'Mar' => 115000,
            'Apr' => 108000,
            'May' => 125000,
            'Jun' => 138000,
            'Jul' => 145200,
        ];

        // Recent client signups
        $dbRecentClients = \App\Modules\CRM\Clients\Models\Client::with(['user', 'plans'])
            ->latest()
            ->limit(5)
            ->get();

        $this->recentClients = $dbRecentClients->map(fn($c) => [
            'name' => $c->user->name ?? 'Deleted User',
            'email' => $c->user->email ?? 'N/A',
            'status' => ucfirst($c->status),
            'plan' => $c->plans->pluck('name')->implode(', ') ?: 'No Plan',
            'date' => $c->created_at->diffForHumans()
        ])->toArray();

        // Recent notifications
        $this->recentNotifications = [
            ['title' => 'Server CPU usage peaked at 92%', 'time' => '10 mins ago', 'type' => 'warning'],
            ['title' => 'Client Acme Corp completed onboarding', 'time' => '2 hours ago', 'type' => 'info'],
            ['title' => 'Monthly billing invoices generated (312 total)', 'time' => '4 hours ago', 'type' => 'success'],
            ['title' => 'New support ticket received from Apex Retail', 'time' => '5 hours ago', 'type' => 'danger'],
        ];

        // Recent activities loaded dynamically from database (only latest 5 for dashboard preview)
        $dbActivities = \App\Modules\Core\Activity\Models\ActivityLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        if ($dbActivities->isNotEmpty()) {
            $this->recentActivities = $dbActivities->map(fn($log) => [
                'description' => $log->description,
                'user'        => $log->user->name ?? 'System',
                'time'        => $log->created_at->diffForHumans()
            ])->toArray();
        } else {
            $this->recentActivities = [
                ['description' => 'Administrator updated system settings', 'user' => 'Admin User', 'time' => '1 hour ago'],
                ['description' => 'Staff John Doe resolved ticket #2481', 'user' => 'John Doe', 'time' => '3 hours ago'],
                ['description' => 'Automatic payment received from Nova Tech', 'user' => 'Stripe Gateway', 'time' => '5 hours ago'],
                ['description' => 'Client Stellar Design changed domain settings', 'user' => 'Stellar Admin', 'time' => '1 day ago'],
            ];
        }
    }

    public function render()
    {
        return view('modules.core.dashboard.admin-dashboard')
            ->layoutData(['title' => 'Admin Dashboard - Aspire Hub']);
    }
}
