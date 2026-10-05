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
            ->where('integration_type', 'gads')
            ->where('status', 'connected')
            ->get();

        if ($integrations->isEmpty()) {
            $this->info('No connected Google Ads integrations found.');
            return;
        }

        $startDate = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
        $endDate = \Carbon\Carbon::now()->format('Y-m-d');

        foreach ($integrations as $integration) {
            $this->info("Processing Website ID: {$integration->website_id}");

            try {
                $apiCredentials = $integration->api_credentials ?? [];
                $authCredentials = $integration->auth_credentials ?? [];
                
                $customerId = $integration->property_id ?? $authCredentials['property_id'] ?? null;
                if (!$customerId) {
                    $this->info("Skipping integration {$integration->id} - No Customer ID configured.");
                    continue;
                }

                $developerToken = $apiCredentials['developer_token'] ?? env('GOOGLE_ADS_DEVELOPER_TOKEN', '');
                if (empty($developerToken)) {
                    $this->error("Skipping integration {$integration->id} - Missing Developer Token.");
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

        Log::error('Google Ads Auth Error', $response->json());
        return null;
    }

    private function fetchGoogleAdsMetrics($token, $developerToken, $customerId, $startDate, $endDate)
    {
        $customerId = str_replace('-', '', $customerId);
        $url = "https://googleads.googleapis.com/v25/customers/{$customerId}/googleAds:search";
        
        $query = "SELECT campaign.name, metrics.clicks, metrics.impressions, metrics.cost_micros, metrics.conversions, segments.date FROM campaign WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}'";

        $http = Http::withToken($token);
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http
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
            
            $dailyTraffic = [];
            $campaigns = [];

            if (isset($data['results'])) {
                foreach ($data['results'] as $row) {
                    $metrics = $row['metrics'] ?? [];
                    $date = $row['segments']['date'] ?? null;
                    $campaignName = $row['campaign']['name'] ?? 'Unknown';
                    
                    $c = (int)($metrics['clicks'] ?? 0);
                    $i = (int)($metrics['impressions'] ?? 0);
                    $cost = (int)($metrics['costMicros'] ?? 0);
                    $conv = (float)($metrics['conversions'] ?? 0);

                    $clicks += $c;
                    $impressions += $i;
                    $costMicros += $cost;
                    $conversions += $conv;
                    
                    if ($date) {
                        if (!isset($dailyTraffic[$date])) {
                            $dailyTraffic[$date] = ['date' => $date, 'clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0];
                        }
                        $dailyTraffic[$date]['clicks'] += $c;
                        $dailyTraffic[$date]['impressions'] += $i;
                        $dailyTraffic[$date]['cost'] += round($cost / 1000000, 2);
                        $dailyTraffic[$date]['conversions'] += $conv;
                    }
                    
                    if (!isset($campaigns[$campaignName])) {
                        $campaigns[$campaignName] = ['name' => $campaignName, 'clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0];
                    }
                    $campaigns[$campaignName]['clicks'] += $c;
                    $campaigns[$campaignName]['impressions'] += $i;
                    $campaigns[$campaignName]['cost'] += round($cost / 1000000, 2);
                    $campaigns[$campaignName]['conversions'] += $conv;
                }
            }
            
            usort($campaigns, function($a, $b) {
                return $b['cost'] <=> $a['cost'];
            });

            return [
                'summary' => [
                    'clicks' => $clicks,
                    'impressions' => $impressions,
                    'cost' => round($costMicros / 1000000, 2),
                    'conversions' => $conversions,
                ],
                'daily_traffic' => array_values($dailyTraffic),
                'top_campaigns' => array_slice($campaigns, 0, 5),
                'raw' => $data
            ];
        }

        Log::error("Google Ads Metrics Fetch Error for Customer {$customerId}", (array) ($response->json() ?? ['body' => $response->body()]));
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

        $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gads/{$year}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = "{$dir}/{$monthStr}.json";
        file_put_contents($filePath, json_encode($metrics, JSON_PRETTY_PRINT));
    }
}
