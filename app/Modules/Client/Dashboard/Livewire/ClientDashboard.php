<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
#[Layout('layouts.client-portal')]
class ClientDashboard extends Component
{
    /**
     * NOTE: This is a fresh, standalone Client Portal Dashboard module.
     * It does NOT read from or modify the existing CRM Staff/Clients
     * (admin-side) module in any way. Replace the demo arrays below
     * with real queries once your client-facing data models
     * (Website, MarketingReport, MaintenanceReport, SupportTicket) exist.
     */



    public function render()
    {
        $user = Auth::user();

        $client = DB::table('adspv_clients')
            ->where('user_id', $user->id)
            ->first();

        if (!$client) {
            abort(404, 'Client not found.');
        }

        $clientId = $client->id;

        $clientName        = $user->name;
        $clientCompanyName = $client->company_name;

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $websiteCount = DB::table('adspv_websites')
            ->where('client_id', $clientId)
            ->count();

        $activeServices = DB::table('adspv_client_plan')
            ->where('client_id', $clientId)
            ->count();

        $maintenanceReports = DB::table('adspv_maintenance_reports')
            ->where('client_id', $clientId)
            ->count();

        $marketingReports = DB::table('adspv_documents')
            ->where('client_id', $clientId)
            ->count();

        $lastReport = DB::table('adspv_maintenance_reports')
            ->where('client_id', $clientId)
            ->orderByDesc('maintenance_date')
            ->first();

        $stats = [
            'websites'             => $websiteCount,
            'active_services'      => $activeServices,
            'marketing_reports'    => $marketingReports,
            'maintenance_reports'  => $maintenanceReports,
            'last_report_month'    => $lastReport
                ? $lastReport->maintenance_month
                : '-',
        ];

        $lastReportParts = explode(' ', $stats['last_report_month']);

            /*
        |--------------------------------------------------------------------------
        | Dashboard Stat Cards
        |--------------------------------------------------------------------------
        */

        $statCards = [
            [
                'label' => 'My Websites',
                'value' => $stats['websites'],
                'sub'   => 'All operational',
                'icon'  => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'url'   => route('client.websites'),
            ],

            [
                'label' => 'Active Services',
                'value' => $stats['active_services'],
                'sub'   => 'Subscribed services',
                'icon'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                'url'   => '#',
            ],

            [
                'label' => 'Maintenance Reports',
                'value' => $stats['maintenance_reports'],
                'sub'   => 'Generated reports',
                'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94-1.543-.826-3.31-2.37-2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'url'   => '#',
            ],

            [
                'label' => 'Last Report',
                'value' => $lastReportParts[0] ?? '-',
                'sub'   => $lastReportParts[1] ?? '',
                'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'url'   => '#',
            ],
        ];

        return view('modules.client.dashboard.client-dashboard', [
            'clientName'        => $clientName,
            'clientCompanyName' => $clientCompanyName,
            'stats'             => $stats,
            'statCards'         => $statCards,
        ])->layoutData(['title' => 'Dashboard']);
    }
}
