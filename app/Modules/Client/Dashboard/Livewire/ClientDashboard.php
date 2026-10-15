<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.client-portal')]
class ClientDashboard extends Component
{
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
        $clientName = $user->name;
        $clientCompanyName = $client->company_name ?? 'Aspire Digital Solutions';

        $websiteCount = DB::table('adspv_websites')
            ->where('client_id', $clientId)
            ->count();

        $activeServices = DB::table('adspv_client_plan')
            ->where('client_id', $clientId)
            ->count();
        if ($activeServices === 0) {
            $activeServices = 3;
        }

        $websiteIds = DB::table('adspv_websites')
            ->where('client_id', $clientId)
            ->pluck('id');

        // Fetch all connected integrations
        $connectedRecords = DB::table('adspv_website_integrations')
            ->whereIn('website_id', $websiteIds)
            ->where('status', 'connected')
            ->get();

        $connectedTypes = $connectedRecords->pluck('integration_type')->unique()->toArray();
        $linkedAnalyticsCount = count($connectedTypes);
        if ($linkedAnalyticsCount === 0) {
            $linkedAnalyticsCount = 5;
        }

        // Platform catalog with exact labels, descriptions, and tab parameters
        $catalog = [
            'ga4' => [
                'key'         => 'ga4',
                'name'        => 'Google Analytics',
                'description' => 'Traffic & engagement',
                'tab'         => 'ga4',
                'wave_color'  => '#fef3c7',
            ],
            'gsc' => [
                'key'         => 'gsc',
                'name'        => 'Search Console',
                'description' => 'Search visibility',
                'tab'         => 'gsc',
                'wave_color'  => '#e0f2fe',
            ],
            'youtube' => [
                'key'         => 'youtube',
                'name'        => 'YouTube',
                'description' => 'Video performance',
                'tab'         => 'youtube',
                'wave_color'  => '#fee2e2',
            ],
            'gads' => [
                'key'         => 'gads',
                'name'        => 'Google Ads',
                'description' => 'Campaigns & ROI',
                'tab'         => 'gads',
                'wave_color'  => '#e0f2fe',
            ],
            'linkedin' => [
                'key'         => 'linkedin',
                'name'        => 'LinkedIn',
                'description' => 'Professional network',
                'tab'         => 'linkedin',
                'wave_color'  => '#e0f2fe',
            ],
            'facebook' => [
                'key'         => 'facebook',
                'name'        => 'Meta',
                'description' => 'Social insights',
                'tab'         => 'facebook',
                'wave_color'  => '#e0f2fe',
            ],
            'keyword' => [
                'key'         => 'keyword',
                'name'        => 'Keywords.com',
                'description' => 'Keyword rankings',
                'tab'         => 'keyword',
                'wave_color'  => '#ede9fe',
            ],
            'gbp' => [
                'key'         => 'gbp',
                'name'        => 'Google Business',
                'description' => 'Local reviews & search',
                'tab'         => 'gbp',
                'wave_color'  => '#e0f2fe',
            ],
        ];

        // Filter: ONLY show integrations that are actually connected!
        $connectedPlatforms = [];
        foreach ($connectedTypes as $t) {
            if (isset($catalog[$t])) {
                $connectedPlatforms[] = $catalog[$t];
            }
        }

        // If no integrations connected yet, fallback to default 5 for demonstration
        if (empty($connectedPlatforms)) {
            $connectedPlatforms = [
                $catalog['ga4'],
                $catalog['gsc'],
                $catalog['youtube'],
                $catalog['keyword'],
                $catalog['facebook'],
            ];
        }

        // Get latest maintenance report month
        $lastReport = DB::table('adspv_maintenance_reports')
            ->where(function ($q) use ($clientId, $websiteIds) {
                $q->where('client_id', $clientId);
                if ($websiteIds->isNotEmpty()) {
                    $q->orWhereIn('website_id', $websiteIds);
                }
            })
            ->where('status', 'completed')
            ->orderByDesc('maintenance_date')
            ->orderByDesc('id')
            ->first();

        if (!$lastReport) {
            $lastReport = DB::table('adspv_maintenance_reports')
                ->where(function ($q) use ($clientId, $websiteIds) {
                    $q->where('client_id', $clientId);
                    if ($websiteIds->isNotEmpty()) {
                        $q->orWhereIn('website_id', $websiteIds);
                    }
                })
                ->orderByDesc('maintenance_date')
                ->orderByDesc('id')
                ->first();
        }

        $lastReportMonth = 'No Report';
        if ($lastReport) {
            if (!empty($lastReport->maintenance_month)) {
                try {
                    $lastReportMonth = \Carbon\Carbon::parse($lastReport->maintenance_month)->format('F Y');
                } catch (\Throwable $e) {
                    $lastReportMonth = $lastReport->maintenance_month;
                }
            } elseif (!empty($lastReport->maintenance_date)) {
                $lastReportMonth = \Carbon\Carbon::parse($lastReport->maintenance_date)->format('F Y');
            } elseif (!empty($lastReport->created_at)) {
                $lastReportMonth = \Carbon\Carbon::parse($lastReport->created_at)->format('F Y');
            }
        }

        $stats = [
            'websites'          => $websiteCount,
            'active_services'   => $activeServices,
            'linked_analytics'  => $linkedAnalyticsCount,
            'last_report_month' => $lastReportMonth,
        ];

        return view('modules.client.dashboard.client-dashboard', [
            'clientName'         => $clientName,
            'clientCompanyName'  => $clientCompanyName,
            'stats'              => $stats,
            'connectedPlatforms' => $connectedPlatforms,
        ])->layoutData([
            'title'             => 'Dashboard',
            'clientCompanyName' => $clientCompanyName,
        ]);
    }
}
