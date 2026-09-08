<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Signature('sync:gbp-metrics')]
#[Description('Sync Google Business Profile metrics for all connected websites')]
class SyncGbpMetrics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting GBP Metrics Sync...');

        $integrations = WebsiteIntegration::with(['website', 'website.client.user'])
            ->where('integration_type', 'gbp')
            ->where('status', 'connected')
            ->get();

        if ($integrations->isEmpty()) {
            $this->info('No connected GBP integrations found.');
            return;
        }

        $year = date('Y');
        $monthStr = Str::lower(date('F'));
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t'); // last day of current month

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
                    $this->saveMetricsToJson($integration, $metrics, $year, $monthStr);
                    
                    $integration->last_sync_at = now();
                    $integration->save();
                    
                    $this->info("Successfully saved metrics for {$propertyId}");
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
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
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
        
        $response = Http::withToken($token)->get($url, [
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
            
            // Format the data for the UI
            $totalViews = 0;
            $interactions = 0;
            $calls = 0;

            if (isset($data['multiDailyMetricTimeSeries'])) {
                foreach ($data['multiDailyMetricTimeSeries'] as $series) {
                    $metric = $series['dailyMetric'];
                    $count = 0;
                    
                    if (isset($series['timeSeries']['datedValues'])) {
                        foreach ($series['timeSeries']['datedValues'] as $value) {
                            $count += (int)($value['value'] ?? 0);
                        }
                    }

                    if (str_starts_with($metric, 'BUSINESS_IMPRESSIONS_')) {
                        $totalViews += $count;
                    }
                    
                    if (in_array($metric, ['CALL_CLICKS', 'WEBSITE_CLICKS', 'BUSINESS_DIRECTION_REQUESTS'])) {
                        $interactions += $count;
                    }

                    if ($metric === 'CALL_CLICKS') {
                        $calls += $count;
                    }
                }
            }

            return [
                'summary' => [
                    'views' => $totalViews,
                    'searches' => (int)($totalViews * 0.4), // GBP API doesn't return searches directly in v1 without insights, using a proxy
                    'interactions' => $interactions,
                    'calls' => $calls,
                ],
                'raw' => $data
            ];
        }

        Log::error("GBP Metrics Fetch Error for {$locationId}", $response->json());
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

        $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gbp/{$year}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = "{$dir}/{$monthStr}.json";
        file_put_contents($filePath, json_encode($metrics, JSON_PRETTY_PRINT));
    }
}
