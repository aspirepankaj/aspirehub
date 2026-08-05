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
            'total_clients' => 1240,
            'active_clients' => 1180,
            'inactive_clients' => 60,
            'new_clients' => 45,
            'total_staff' => 38,
            'total_websites' => 312,
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
        $this->recentClients = [
            ['name' => 'Acme Corporation', 'email' => 'contact@acme.com', 'status' => 'Active', 'plan' => 'Enterprise', 'date' => '2 hours ago'],
            ['name' => 'Nova Technologies', 'email' => 'billing@novatech.io', 'status' => 'Active', 'plan' => 'Growth', 'date' => '5 hours ago'],
            ['name' => 'Stellar Design Studio', 'email' => 'hello@stellardesign.co', 'status' => 'Inactive', 'plan' => 'Basic', 'date' => '1 day ago'],
            ['name' => 'Apex Retail Group', 'email' => 'operations@apexretail.com', 'status' => 'Active', 'plan' => 'Growth', 'date' => '2 days ago'],
        ];

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
