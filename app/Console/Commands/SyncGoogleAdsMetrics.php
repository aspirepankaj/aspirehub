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
                    $this->saveMetricsToJson($integration, $metrics);
                    
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
        $customerId = preg_replace('/[^0-9]/', '', $customerId);
        $loginCustomerId = $customerId;
        
        if (strlen($customerId) >= 20) {
            $loginCustomerId = substr($customerId, 0, 10);
            $customerId = substr($customerId, 10, 10);
        }

        $url = "https://googleads.googleapis.com/v25/customers/{$customerId}/googleAds:search";
        
        $query = "SELECT customer.descriptive_name, campaign.name, metrics.clicks, metrics.impressions, metrics.cost_micros, metrics.conversions, segments.date, segments.device FROM campaign WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}' AND campaign.status = 'ENABLED'";

        $http = Http::withToken($token);
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http
            ->withHeaders([
                'developer-token' => $developerToken,
                'login-customer-id' => $loginCustomerId
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
            $dailyCampaigns = [];
            $accountName = null;

            if (isset($data['results'])) {
                foreach ($data['results'] as $row) {
                    if (!$accountName && isset($row['customer']['descriptiveName'])) {
                        $accountName = $row['customer']['descriptiveName'];
                    }
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
                    
                    $device = $row['segments']['device'] ?? 'UNKNOWN';
                    if ($device === 'MOBILE') $deviceKey = 'mobile';
                    elseif ($device === 'TABLET') $deviceKey = 'tablet';
                    elseif ($device === 'DESKTOP') $deviceKey = 'desktop';
                    else $deviceKey = 'other';
                    
                    if ($date) {
                        if (!isset($dailyTraffic[$date])) {
                            $dailyTraffic[$date] = [
                                'date' => $date, 'clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0,
                                'devices' => [
                                    'mobile' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                                    'tablet' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                                    'desktop' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                                    'other' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                                ]
                            ];
                        }
                        $dailyTraffic[$date]['clicks'] += $c;
                        $dailyTraffic[$date]['impressions'] += $i;
                        $dailyTraffic[$date]['cost'] += round($cost / 1000000, 2);
                        $dailyTraffic[$date]['conversions'] += $conv;
                        
                        $dailyTraffic[$date]['devices'][$deviceKey]['clicks'] += $c;
                        $dailyTraffic[$date]['devices'][$deviceKey]['impressions'] += $i;
                        $dailyTraffic[$date]['devices'][$deviceKey]['cost'] += round($cost / 1000000, 2);
                        $dailyTraffic[$date]['devices'][$deviceKey]['conversions'] += $conv;
                    }
                    
                    if ($date) {
                        $cKey = $date . '_' . $campaignName;
                        if (!isset($dailyCampaigns[$cKey])) {
                            $dailyCampaigns[$cKey] = [
                                'date' => $date,
                                'name' => $campaignName,
                                'clicks' => 0,
                                'impressions' => 0,
                                'cost' => 0,
                                'conversions' => 0
                            ];
                        }
                        $dailyCampaigns[$cKey]['clicks'] += $c;
                        $dailyCampaigns[$cKey]['impressions'] += $i;
                        $dailyCampaigns[$cKey]['cost'] += round($cost / 1000000, 2);
                        $dailyCampaigns[$cKey]['conversions'] += $conv;
                    }
                }
            }

            $keywordQuery = "SELECT segments.date, ad_group_criterion.keyword.text, ad_group_criterion.keyword.match_type, metrics.clicks, metrics.impressions, metrics.cost_micros FROM keyword_view WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}' AND ad_group_criterion.status = 'ENABLED' AND metrics.clicks > 0 ORDER BY segments.date DESC";
            
            $keywordResponse = $http
                ->withHeaders([
                    'developer-token' => $developerToken,
                    'login-customer-id' => $loginCustomerId
                ])
                ->post($url, [
                    'query' => $keywordQuery
                ]);

            $dailyKeywords = [];
            if ($keywordResponse->successful()) {
                $kwData = $keywordResponse->json();
                if (isset($kwData['results'])) {
                    foreach ($kwData['results'] as $row) {
                        if (!isset($row['adGroupCriterion']['keyword']['text'])) continue;
                        $date = $row['segments']['date'] ?? null;
                        if (!$date) continue;

                        $text = $row['adGroupCriterion']['keyword']['text'];
                        $matchType = $row['adGroupCriterion']['keyword']['matchType'] ?? 'BROAD';
                        
                        if ($matchType === 'PHRASE') {
                            $text = '"' . $text . '"';
                        } elseif ($matchType === 'EXACT') {
                            $text = '[' . $text . ']';
                        }

                        $k_clicks = (int)($row['metrics']['clicks'] ?? 0);
                        $k_impressions = (int)($row['metrics']['impressions'] ?? 0);
                        $k_cost = round(((int)($row['metrics']['costMicros'] ?? 0)) / 1000000, 2);
                        
                        $dailyKeywords[] = [
                            'date' => $date,
                            'keyword' => $text,
                            'clicks' => $k_clicks,
                            'impressions' => $k_impressions,
                            'cost' => $k_cost
                        ];
                    }
                }
            }

            return [
                'account_name' => $accountName,
                'summary' => [
                    'clicks' => $clicks,
                    'impressions' => $impressions,
                    'cost' => round($costMicros / 1000000, 2),
                    'conversions' => $conversions,
                ],
                'daily_traffic' => array_values($dailyTraffic),
                'top_campaigns' => array_slice($campaigns, 0, 5),
                'daily_campaigns' => array_values($dailyCampaigns),
                'daily_keywords' => $dailyKeywords,
                'raw' => $data
            ];
        }

        Log::error("Google Ads Metrics Fetch Error for Customer {$customerId}", (array) ($response->json() ?? ['body' => $response->body()]));
        return null;
    }

    private function saveMetricsToJson($integration, $metrics)
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

        $dailyTraffic = $metrics['daily_traffic'] ?? [];
        if (empty($dailyTraffic)) {
            $year = now()->format('Y');
            $monthStr = Str::lower(now()->format('F'));
            $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gads/{$year}");
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents("{$dir}/{$monthStr}.json", json_encode($metrics, JSON_PRETTY_PRINT));
            \Illuminate\Support\Facades\Cache::flush();
            return;
        }

        $grouped = [];
        foreach ($dailyTraffic as $row) {
            $d = $row['date'] ?? null;
            if ($d && strlen($d) >= 8) {
                $dtStr = (strpos($d, '-') !== false) ? $d : substr($d, 0, 4) . '-' . substr($d, 4, 2) . '-' . substr($d, 6, 2);
                try {
                    $cDate = \Carbon\Carbon::parse($dtStr);
                    $y = $cDate->format('Y');
                    $m = Str::lower($cDate->format('F'));
                    $grouped["{$y}|{$m}"][] = $row;
                } catch (\Exception $e) {
                    $grouped[now()->format('Y') . '|' . Str::lower(now()->format('F'))][] = $row;
                }
            }
        }

        foreach ($grouped as $key => $rows) {
            list($y, $m) = explode('|', $key);
            $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gads/{$y}");
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filePath = "{$dir}/{$m}.json";

            $mReportData = $metrics;
            $mClicks = 0;
            $mImpressions = 0;
            $mCost = 0;
            $mConversions = 0;
            foreach ($rows as $r) {
                $mClicks += (int)($r['clicks'] ?? 0);
                $mImpressions += (int)($r['impressions'] ?? 0);
                $mCost += (float)($r['cost'] ?? 0);
                $mConversions += (float)($r['conversions'] ?? 0);
            }

            $mReportData['summary'] = [
                'clicks' => $mClicks,
                'impressions' => $mImpressions,
                'cost' => round($mCost, 2),
                'conversions' => round($mConversions, 2),
                'ctr' => $mImpressions > 0 ? round(($mClicks / $mImpressions) * 100, 2) : 0,
                'average_cpc' => $mClicks > 0 ? round($mCost / $mClicks, 2) : 0,
            ];
            $mReportData['daily_traffic'] = $rows;

            if (isset($metrics['daily_campaigns'])) {
                $mReportData['daily_campaigns'] = array_values(array_filter($metrics['daily_campaigns'], function($dc) use ($y, $m) {
                    $d = $dc['date'] ?? '';
                    if (!$d) return false;
                    try {
                        $cDate = \Carbon\Carbon::parse($d);
                        return $cDate->format('Y') == $y && Str::lower($cDate->format('F')) == $m;
                    } catch (\Exception $e) {
                        return false;
                    }
                }));
            }

            if (isset($metrics['daily_keywords'])) {
                $mReportData['daily_keywords'] = array_values(array_filter($metrics['daily_keywords'], function($dk) use ($y, $m) {
                    $d = $dk['date'] ?? '';
                    if (!$d) return false;
                    try {
                        $cDate = \Carbon\Carbon::parse($d);
                        return $cDate->format('Y') == $y && Str::lower($cDate->format('F')) == $m;
                    } catch (\Exception $e) {
                        return false;
                    }
                }));
            }

            file_put_contents($filePath, json_encode($mReportData, JSON_PRETTY_PRINT));
        }

        \Illuminate\Support\Facades\Cache::flush();
    }
}
