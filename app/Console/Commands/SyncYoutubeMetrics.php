<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Signature('sync:youtube-metrics {integration_id?}')]
#[Description('Sync YouTube Analytics and Data metrics for all connected websites')]
class SyncYoutubeMetrics extends Command
{
    public function handle()
    {
        $this->info('Starting YouTube Metrics Sync...');

        $query = WebsiteIntegration::with(['website', 'website.client.user'])
            ->where('integration_type', 'youtube')
            ->where('status', 'connected');
            
        if ($this->argument('integration_id')) {
            $query->where('id', $this->argument('integration_id'));
        }
        
        $integrations = $query->get();

        if ($integrations->isEmpty()) {
            $this->info('No connected YouTube integrations found.');
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
                    $this->info("Skipping integration {$integration->id} - No Channel ID configured.");
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

                $metrics = $this->fetchYoutubeMetrics($token, $propertyId, $startDate, $endDate);

                if ($metrics) {
                    $this->saveMetricsToJson($integration, $metrics, (int)date('Y'), strtolower(date('F')));
                    
                    $integration->last_sync_at = now();
                    $integration->save();
                    
                    $this->info("Successfully saved YouTube metrics for {$propertyId}");
                }

            } catch (\Exception $e) {
                $this->error("Exception for integration ID {$integration->id}: " . $e->getMessage());
                Log::error("YouTube Sync Error for integration {$integration->id}: " . $e->getMessage());
            }
        }

        $this->info('YouTube Metrics Sync Completed!');
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

        Log::error('YouTube Auth Error', $response->json());
        return null;
    }

    private function fetchYoutubeMetrics($token, $channelId, $startDate, $endDate)
    {
        // 1. Fetch Timeline (Daily Traffic)
        $timelineResponse = Http::withToken($token)->get('https://youtubeanalytics.googleapis.com/v2/reports', [
            'ids' => "channel=={$channelId}",
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'views,estimatedMinutesWatched,subscribersGained,averageViewDuration',
            'dimensions' => 'day',
            'sort' => 'day'
        ]);

        if (!$timelineResponse->successful()) {
            Log::error("YouTube Analytics Timeline Fetch Error for {$channelId}", $timelineResponse->json());
            return null;
        }

        $timelineData = $timelineResponse->json();
        $dailyTraffic = [];
        $totalViews = 0;
        $totalMinutes = 0;
        $totalSubscribers = 0;
        $totalAvgViewDuration = 0;

        if (isset($timelineData['rows'])) {
            foreach ($timelineData['rows'] as $row) {
                $dailyTraffic[] = [
                    'date' => $row[0],
                    'views' => $row[1],
                    'watch_time' => round($row[2] / 60, 2), // convert minutes to hours
                    'subscribers' => $row[3],
                    'avg_view_duration' => $row[4]
                ];
                $totalViews += $row[1];
                $totalMinutes += $row[2];
                $totalSubscribers += $row[3];
            }
        }
        
        $totalWatchTime = round($totalMinutes / 60, 2);
        $totalAvgDurationSeconds = count($dailyTraffic) > 0 ? round(array_sum(array_column($dailyTraffic, 'avg_view_duration')) / count($dailyTraffic)) : 0;
        $formattedDuration = gmdate("H:i:s", $totalAvgDurationSeconds);
        // Clean up format like 00:00:22 to 22s
        if (str_starts_with($formattedDuration, '00:00:')) $formattedDuration = (int)substr($formattedDuration, 6) . 's';
        elseif (str_starts_with($formattedDuration, '00:')) $formattedDuration = (int)substr($formattedDuration, 3, 2) . 'm ' . (int)substr($formattedDuration, 6) . 's';

        // 2. Fetch Top Videos
        $topVideosResponse = Http::withToken($token)->get('https://youtubeanalytics.googleapis.com/v2/reports', [
            'ids' => "channel=={$channelId}",
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'views,estimatedMinutesWatched,averageViewDuration',
            'dimensions' => 'video',
            'sort' => '-views',
            'maxResults' => 10
        ]);

        $topVideos = [];
        if ($topVideosResponse->successful()) {
            $topData = $topVideosResponse->json();
            $videoIds = [];
            $videoStats = [];

            if (isset($topData['rows'])) {
                foreach ($topData['rows'] as $row) {
                    $vid = $row[0];
                    $videoIds[] = $vid;
                    $videoStats[$vid] = [
                        'views' => $row[1],
                        'watch_time' => round($row[2] / 60, 2),
                    ];
                }
            }

            // 3. Fetch Video Details (Thumbnails & Titles) from Data API
            if (!empty($videoIds)) {
                $videoDetailsResponse = Http::withToken($token)->get('https://www.googleapis.com/youtube/v3/videos', [
                    'id' => implode(',', $videoIds),
                    'part' => 'snippet'
                ]);

                if ($videoDetailsResponse->successful()) {
                    $detailsData = $videoDetailsResponse->json();
                    if (isset($detailsData['items'])) {
                        foreach ($detailsData['items'] as $item) {
                            $vid = $item['id'];
                            $topVideos[] = [
                                'id' => $vid,
                                'title' => $item['snippet']['title'] ?? 'Unknown Title',
                                'thumbnail' => $item['snippet']['thumbnails']['medium']['url'] ?? $item['snippet']['thumbnails']['default']['url'] ?? '',
                                'views' => $videoStats[$vid]['views'] ?? 0,
                                'watch_time' => $videoStats[$vid]['watch_time'] ?? 0
                            ];
                        }
                    }
                }
            }
        }
        
        // Sort top videos by views descending just in case Data API changed order
        usort($topVideos, function($a, $b) { return $b['views'] <=> $a['views']; });

        return [
            'summary' => [
                'views' => $totalViews,
                'subscribers' => $totalSubscribers,
                'video_count' => count($topVideos),
                'watch_time' => $totalWatchTime,
                'avg_view_duration' => $formattedDuration
            ],
            'daily_traffic' => $dailyTraffic,
            'top_videos' => $topVideos,
        ];
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

        $dir = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/youtube/{$year}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = "{$dir}/{$monthStr}.json";
        file_put_contents($filePath, json_encode($metrics, JSON_PRETTY_PRINT));
    }
}

