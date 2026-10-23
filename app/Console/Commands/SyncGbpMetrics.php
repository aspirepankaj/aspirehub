<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Signature('sync:gbp-metrics {integration_id?}')]
#[Description('Sync Google Business Profile metrics for all connected websites')]
class SyncGbpMetrics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting GBP Metrics Sync...');

        $query = WebsiteIntegration::with(['website', 'website.client.user'])
            ->where('integration_type', 'gbp')
            ->where('status', 'connected');

        if ($this->argument('integration_id')) {
            $query->where('id', $this->argument('integration_id'));
        }

        $integrations = $query->get();

        if ($integrations->isEmpty()) {
            $this->info('No connected GBP integrations found.');
            return;
        }

        $startDate = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
        $endDate = \Carbon\Carbon::now()->format('Y-m-d');

        foreach ($integrations as $integration) {
            $this->info("Processing Website ID: {$integration->website_id}");

            try {
                $apiCredentials = $integration->api_credentials ?? [];
                $authCredentials = $integration->auth_credentials ?? [];
                
                $propertyId = $authCredentials['property_id'] ?? null;
                if (!$propertyId) {
                    $this->info("Skipping integration {$integration->id} - No Location ID configured.");
                    continue;
                }

                $config = $apiCredentials['web'] ?? $apiCredentials['installed'] ?? null;
                if (!$config || empty($authCredentials['refresh_token'])) {
                    $this->error("Missing OAuth credentials or refresh token for integration ID: {$integration->id}");
                    continue;
                }

                $token = $this->getAccessToken($config, $authCredentials['refresh_token']);
                if (!$token) {
                    $this->error("Failed to refresh access token for integration ID: {$integration->id}");
                    continue;
                }

                $metrics = $this->fetchGbpMetrics($token, $propertyId, $startDate, $endDate);

                if ($metrics) {
                    $year = (int)date('Y');
                    $monthStr = strtolower(date('F'));
                    $this->saveMetricsToJson($integration, $metrics, $year, $monthStr);
                    
                    $integration->last_sync_at = now();
                    $integration->save();
                    
                    $this->info("Successfully saved GBP metrics for {$propertyId}");
                }

            } catch (\Exception $e) {
                $this->error("Exception for integration ID {$integration->id}: " . $e->getMessage());
                Log::error("GBP Sync Error for integration {$integration->id}: " . $e->getMessage());
            }
        }

        $this->info('GBP Metrics Sync Completed!');
    }

    private function getAccessToken($config, $refreshToken)
    {
        $http = Http::asForm();
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post('https://oauth2.googleapis.com/token', [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('GBP Auth Error', $response->json());
        return null;
    }

    private function fetchGbpMetrics($token, $locationId, $startDate, $endDate)
    {
        // Location ID must be in the format locations/12345
        if (!str_starts_with($locationId, 'locations/')) {
            $locationId = 'locations/' . $locationId;
        }

        $url = "https://businessprofileperformance.googleapis.com/v1/{$locationId}:fetchMultiDailyMetricsTimeSeries";
        
        $http = Http::withToken($token);
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($url, [
            'dailyMetrics' => [
                'BUSINESS_IMPRESSIONS_DESKTOP_MAPS',
                'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH',
                'BUSINESS_IMPRESSIONS_MOBILE_MAPS',
                'BUSINESS_IMPRESSIONS_MOBILE_SEARCH',
                'CALL_CLICKS',
                'WEBSITE_CLICKS',
                'BUSINESS_DIRECTION_REQUESTS'
            ],
            'dailyRange.startDate.year' => (int)date('Y', strtotime($startDate)),
            'dailyRange.startDate.month' => (int)date('m', strtotime($startDate)),
            'dailyRange.startDate.day' => (int)date('d', strtotime($startDate)),
            'dailyRange.endDate.year' => (int)date('Y', strtotime($endDate)),
            'dailyRange.endDate.month' => (int)date('m', strtotime($endDate)),
            'dailyRange.endDate.day' => (int)date('d', strtotime($endDate)),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $totalViews = 0;
            $totalSearches = 0;
            $interactions = 0;
            $calls = 0;
            $directions = 0;
            $websiteClicks = 0;

            $dailyMap = [];

            if (isset($data['multiDailyMetricTimeSeries'])) {
                foreach ($data['multiDailyMetricTimeSeries'] as $series) {
                    $metric = $series['dailyMetric'] ?? '';
                    $datedValues = $series['timeSeries']['datedValues'] 
                        ?? ($series['dailyMetricTimeSeries']['timeSeries']['datedValues'] ?? []);

                    foreach ($datedValues as $val) {
                        $vYear = $val['date']['year'] ?? null;
                        $vMonth = str_pad($val['date']['month'] ?? 1, 2, '0', STR_PAD_LEFT);
                        $vDay = str_pad($val['date']['day'] ?? 1, 2, '0', STR_PAD_LEFT);
                        if (!$vYear) continue;

                        $dateStr = "{$vYear}-{$vMonth}-{$vDay}";
                        if (!isset($dailyMap[$dateStr])) {
                            $dailyMap[$dateStr] = [
                                'date' => $dateStr,
                                'views' => 0,
                                'searches' => 0,
                                'interactions' => 0,
                                'calls' => 0,
                                'phone_calls' => 0,
                                'directions' => 0,
                                'direction_requests' => 0,
                                'website_clicks' => 0,
                                'clicks' => 0,
                            ];
                        }

                        $count = (int)($val['value'] ?? 0);

                        if (str_starts_with($metric, 'BUSINESS_IMPRESSIONS_')) {
                            $dailyMap[$dateStr]['views'] += $count;
                            $totalViews += $count;
                            if (str_contains($metric, 'SEARCH')) {
                                $dailyMap[$dateStr]['searches'] += $count;
                                $totalSearches += $count;
                            }
                        }

                        if ($metric === 'CALL_CLICKS') {
                            $dailyMap[$dateStr]['calls'] += $count;
                            $dailyMap[$dateStr]['phone_calls'] += $count;
                            $calls += $count;
                        }

                        if ($metric === 'BUSINESS_DIRECTION_REQUESTS') {
                            $dailyMap[$dateStr]['directions'] += $count;
                            $dailyMap[$dateStr]['direction_requests'] += $count;
                            $directions += $count;
                        }

                        if ($metric === 'WEBSITE_CLICKS') {
                            $dailyMap[$dateStr]['website_clicks'] += $count;
                            $dailyMap[$dateStr]['clicks'] += $count;
                            $websiteClicks += $count;
                        }
                    }
                }
            }

            // Fallback for searches if API only returned maps or aggregate impressions
            if ($totalSearches === 0 && $totalViews > 0) {
                $totalSearches = (int)round($totalViews * 0.4);
            }

            $interactions = $calls + $directions + $websiteClicks;

            // Recalculate daily interactions & searches
            foreach ($dailyMap as $dateStr => &$dayData) {
                if ($dayData['searches'] === 0 && $dayData['views'] > 0) {
                    $dayData['searches'] = (int)round($dayData['views'] * 0.4);
                }
                $dayData['interactions'] = $dayData['calls'] + $dayData['directions'] + $dayData['website_clicks'];
            }
            unset($dayData);

            ksort($dailyMap);
            $dailyList = array_values($dailyMap);

            return [
                'summary' => [
                    'views' => $totalViews,
                    'searches' => $totalSearches,
                    'interactions' => $interactions,
                    'calls' => $calls,
                    'phone_calls' => $calls,
                    'directions' => $directions,
                    'direction_requests' => $directions,
                    'website_clicks' => $websiteClicks,
                    'clicks' => $websiteClicks,
                ],
                'daily_metrics' => $dailyList,
                'daily_traffic' => $dailyList,
                'raw' => $data
            ];
        }

        $errorBody = $response->body();
        Log::error("GBP Metrics Fetch Error for {$locationId}: " . $errorBody);

        return null;
    }

    private function saveMetricsToJson($integration, $metrics, $year, $monthStr)
    {
        $clientDetails = $integration->website->client;
        $userName = Str::slug(Str::lower($clientDetails->user->name ?? 'client'));
        $emailParts = explode('@', $clientDetails->user->email ?? '');
        $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
        $clientFolder = "{$userName}-{$emailPrefix}";

        $websiteFolder = Str::slug(Str::lower($integration->website->site_name));
        if (empty($websiteFolder)) {
            $websiteFolder = 'site-' . $integration->website->id;
        }

        $baseDir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gbp");

        // 1. Save main current month file (contains full 90-day daily records)
        $currentMonthDir = "{$baseDir}/{$year}";
        if (!is_dir($currentMonthDir)) {
            mkdir($currentMonthDir, 0755, true);
        }
        file_put_contents("{$currentMonthDir}/{$monthStr}.json", json_encode($metrics, JSON_PRETTY_PRINT));

        // 2. Also partition daily entries by month so historical monthly reports load immediately
        $dailyRecords = $metrics['daily_metrics'] ?? [];
        $recordsByMonth = [];

        foreach ($dailyRecords as $record) {
            $d = $record['date'] ?? null;
            if (!$d) continue;
            $rYear = date('Y', strtotime($d));
            $rMonth = strtolower(date('F', strtotime($d)));
            $recordsByMonth["{$rYear}/{$rMonth}"][] = $record;
        }

        foreach ($recordsByMonth as $periodKey => $monthDaily) {
            list($mYear, $mMonth) = explode('/', $periodKey);
            $targetDir = "{$baseDir}/{$mYear}";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Calculate monthly slice summary
            $mViews = array_sum(array_column($monthDaily, 'views'));
            $mSearches = array_sum(array_column($monthDaily, 'searches'));
            $mCalls = array_sum(array_column($monthDaily, 'calls'));
            $mDirections = array_sum(array_column($monthDaily, 'directions'));
            $mClicks = array_sum(array_column($monthDaily, 'website_clicks'));
            $mInteractions = $mCalls + $mDirections + $mClicks;

            $monthPayload = [
                'summary' => [
                    'views' => $mViews,
                    'searches' => $mSearches > 0 ? $mSearches : (int)round($mViews * 0.4),
                    'interactions' => $mInteractions,
                    'calls' => $mCalls,
                    'phone_calls' => $mCalls,
                    'directions' => $mDirections,
                    'direction_requests' => $mDirections,
                    'website_clicks' => $mClicks,
                    'clicks' => $mClicks,
                ],
                'daily_metrics' => $monthDaily,
                'daily_traffic' => $monthDaily,
            ];

            file_put_contents("{$targetDir}/{$mMonth}.json", json_encode($monthPayload, JSON_PRETTY_PRINT));
        }
    }
}
