<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Signature('sync:google-ads-metrics')]
#[Description('Sync Google Ads metrics for all connected websites')]
class SyncGoogleAdsMetrics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Google Ads Metrics Sync...');

        $integrations = WebsiteIntegration::with(['website', 'website.client.user'])
            ->where('integration_type', 'google_ads')
            ->where('status', 'connected')
            ->get();

        if ($integrations->isEmpty()) {
            $this->info('No connected Google Ads integrations found.');
            return;
        }

        $year = date('Y');
        $monthStr = Str::lower(date('F'));
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t'); // last day of current month
        
        $developerToken = env('GOOGLE_ADS_DEVELOPER_TOKEN', '');
        if (empty($developerToken)) {
            $this->error('GOOGLE_ADS_DEVELOPER_TOKEN is not set in .env');
            Log::error('Google Ads Sync Error: Developer Token is missing.');
            return;
        }

        foreach ($integrations as $integration) {
            $this->info("Processing Website ID: {$integration->website_id}");

            try {
                $apiCredentials = $integration->api_credentials ?? [];
                $authCredentials = $integration->auth_credentials ?? [];
                
                $customerId = $authCredentials['property_id'] ?? null;
                if (!$customerId) {
                    $this->info("Skipping integration {$integration->id} - No Customer ID configured.");
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

                $metrics = $this->fetchGoogleAdsMetrics($token, $developerToken, $customerId, $startDate, $endDate);

                if ($metrics) {
                    $this->saveMetricsToJson($integration, $metrics, $year, $monthStr);
                    
                    $integration->last_sync_at = now();
                    $integration->save();
                    
                    $this->info("Successfully saved metrics for Customer {$customerId}");
                }

            } catch (\Exception $e) {
                $this->error("Exception for integration ID {$integration->id}: " . $e->getMessage());
                Log::error("Google Ads Sync Error for integration {$integration->id}: " . $e->getMessage());
            }
        }

        $this->info('Google Ads Metrics Sync Completed!');
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

        Log::error('Google Ads Auth Error', $response->json());
        return null;
    }

    private function fetchGoogleAdsMetrics($token, $developerToken, $customerId, $startDate, $endDate)
    {
        $customerId = str_replace('-', '', $customerId);
        $url = "https://googleads.googleapis.com/v17/customers/{$customerId}/googleAds:search";
        
        $query = "SELECT metrics.clicks, metrics.impressions, metrics.cost_micros, metrics.conversions FROM campaign WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}'";

        $response = Http::withToken($token)
            ->withHeaders([
                'developer-token' => $developerToken,
                'login-customer-id' => $customerId // Assume direct access for simplicity, if MCC is used, this might need adjusting
            ])
            ->post($url, [
                'query' => $query
            ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $clicks = 0;
            $impressions = 0;
            $costMicros = 0;
            $conversions = 0;

            if (isset($data['results'])) {
                foreach ($data['results'] as $row) {
                    $metrics = $row['metrics'] ?? [];
                    $clicks += (int)($metrics['clicks'] ?? 0);
                    $impressions += (int)($metrics['impressions'] ?? 0);
                    $costMicros += (int)($metrics['costMicros'] ?? 0);
                    $conversions += (float)($metrics['conversions'] ?? 0);
                }
            }

            return [
                'summary' => [
                    'clicks' => $clicks,
                    'impressions' => $impressions,
                    'cost' => round($costMicros / 1000000, 2),
                    'conversions' => $conversions,
                ],
                'raw' => $data
            ];
        }

        Log::error("Google Ads Metrics Fetch Error for Customer {$customerId}", $response->json());
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

        $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/google_ads/{$year}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = "{$dir}/{$monthStr}.json";
        file_put_contents($filePath, json_encode($metrics, JSON_PRETTY_PRINT));
    }
}
