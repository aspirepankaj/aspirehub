<?php

namespace App\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

trait LoadsMarketingReports
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $compareDateFrom = '';
    public string $compareDateTo = '';
    public bool $includeToday = false;
    public string $compareFormat = 'percentage';

    public function initDateRange()
    {
        if (empty($this->dateFrom)) {
            $this->dateFrom = Carbon::now()->subDays(28)->format('Y-m-d');
        }
        if (empty($this->dateTo)) {
            $this->dateTo = Carbon::now()->subDays(1)->format('Y-m-d');
        }
    }

    public function getMonthsForDateRange(string $startDate, string $endDate): array
    {
        try {
            $start = Carbon::parse($startDate)->startOfMonth();
            $end = Carbon::parse($endDate)->endOfMonth();
        } catch (\Exception $e) {
            $start = Carbon::now()->subDays(28)->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        $period = CarbonPeriod::create($start, '1 month', $end);
        $months = [];

        foreach ($period as $dt) {
            $months[] = [
                'year' => $dt->format('Y'),
                'month' => Str::lower($dt->format('F')),
            ];
        }

        return $months;
    }

    public function loadIntegrationJsonData(string $clientFolder, string $websiteFolder, string $integrationType, string $startDate, string $endDate): array
    {
        $months = $this->getMonthsForDateRange($startDate, $endDate);
        if (empty($months)) {
            return [];
        }

        $allData = [];
        $mergedDaily = [];

        foreach ($months as $m) {
            $path = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/{$integrationType}/{$m['year']}/{$m['month']}.json");
            if (!file_exists($path)) {
                continue;
            }

            $jsonData = json_decode(file_get_contents($path), true);
            if (!is_array($jsonData)) {
                continue;
            }

            if (empty($allData)) {
                $allData = $jsonData;
            }

            // Find daily metrics array key
            $dailyKey = isset($jsonData['daily_traffic']) ? 'daily_traffic' : (isset($jsonData['daily_performance']) ? 'daily_performance' : (isset($jsonData['daily_analytics']) ? 'daily_analytics' : null));

            if ($dailyKey && !empty($jsonData[$dailyKey])) {
                foreach ($jsonData[$dailyKey] as $item) {
                    $rawDate = (string)($item['date'] ?? '');
                    $normDate = strlen($rawDate) === 8 ? substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2) : $rawDate;
                    if ($normDate >= $startDate && $normDate <= $endDate) {
                        $mergedDaily[$normDate] = $item;
                    }
                }
            }
        }

        if (empty($allData)) {
            return [];
        }

        // Re-aggregate totals based on filtered mergedDaily
        if (!empty($mergedDaily)) {
            ksort($mergedDaily);
            $dailyKey = isset($allData['daily_traffic']) ? 'daily_traffic' : (isset($allData['daily_performance']) ? 'daily_performance' : (isset($allData['daily_analytics']) ? 'daily_analytics' : 'daily_traffic'));
            $allData[$dailyKey] = array_values($mergedDaily);

            // Re-calculate GA4 summary
            if ($integrationType === 'ga4') {
                $totalUsers = 0;
                $totalViews = 0;
                $totalSessions = 0;
                $weightedBounce = 0;
                $weightedDuration = 0;

                foreach ($mergedDaily as $d) {
                    $totalUsers += (int)($d['users'] ?? 0);
                    $totalViews += (int)($d['pageviews'] ?? 0);
                    
                    $s = (int)($d['sessions'] ?? 0);
                    $totalSessions += $s;
                    $weightedBounce += ((float)($d['bounce_rate'] ?? 0) * $s);
                    $weightedDuration += ((float)($d['avg_session_duration'] ?? 0) * $s);
                }
                
                // Calculate a deduplication ratio from the true 90-day data to estimate exact active users
                $origUsers = (int)($allData['overall_summary']['active_users'] ?? 0);
                $origSessions = (int)($allData['overall_summary']['sessions'] ?? 0);
                $dedupRatio = $origSessions > 0 ? ($origUsers / $origSessions) : ($origUsers > 0 ? 1 : 0.85);

                $allData['overall_summary']['pageviews'] = $totalViews;
                
                if ($totalSessions > 0) {
                    $allData['overall_summary']['active_users'] = (int)round($totalSessions * $dedupRatio);
                    $allData['overall_summary']['sessions'] = $totalSessions;
                    $finalBounce = $weightedBounce / $totalSessions;
                    $finalDuration = $weightedDuration / $totalSessions;
                    
                    $allData['overall_summary']['bounce_rate'] = number_format($finalBounce * 100, 2) . '%';
                    
                    $minutes = floor($finalDuration / 60);
                    $seconds = round($finalDuration - ($minutes * 60));
                    $allData['overall_summary']['avg_session_duration'] = "{$minutes}m {$seconds}s";
                } else {
                    $allData['overall_summary']['active_users'] = $totalUsers;
                    $allData['overall_summary']['sessions'] = (int)round($totalUsers * 1.15); // Fallback for old data without sessions
                }
            }
            // Re-calculate GSC summary
            elseif ($integrationType === 'gsc') {
                $totalClicks = 0;
                $totalImpressions = 0;
                $weightedPosition = 0;
                foreach ($mergedDaily as $d) {
                    $totalClicks += (int)($d['clicks'] ?? 0);
                    $totalImpressions += (int)($d['impressions'] ?? 0);
                    $weightedPosition += ((float)($d['position'] ?? 0) * (int)($d['impressions'] ?? 0));
                }
                $allData['summary']['clicks'] = $totalClicks;
                $allData['summary']['impressions'] = $totalImpressions;
                $allData['summary']['ctr'] = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;
                $allData['summary']['position'] = $totalImpressions > 0 ? round($weightedPosition / $totalImpressions, 1) : 0;
            }
            // Re-calculate YouTube summary
            elseif ($integrationType === 'youtube') {
                $totalViews = 0;
                $totalWatch = 0;
                $totalSubs = 0;
                foreach ($mergedDaily as $d) {
                    $totalViews += (int)($d['views'] ?? 0);
                    $totalWatch += (float)($d['watch_time'] ?? 0);
                    $totalSubs += (int)($d['subscribers'] ?? 0);
                }
                $allData['summary']['views'] = $totalViews;
                $allData['summary']['watch_time'] = $totalWatch;
                $allData['summary']['subscribers'] = $totalSubs;
            }
        }

        return $allData;
    }

    public function formatAbbreviated($number)
    {
        if (!is_numeric($number)) return $number;
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return number_format((float)$number);
    }
}
