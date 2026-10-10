<?php

namespace App\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

use Livewire\Attributes\Url;

trait LoadsMarketingReports
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $compareDateFrom = '';
    public string $compareDateTo = '';
    #[\Livewire\Attributes\Url(except: false)]
    public bool $compareEnabled = false;
    #[\Livewire\Attributes\Url(except: false)]
    public bool $includeToday = false;
    #[\Livewire\Attributes\Url(except: 'percentage')]
    public string $compareFormat = 'percentage';

    public bool $showSendReportModal = false;
    public string $reportRecipientEmail = '';
    public string $reportEmailSubject = '';
    public string $reportEmailMessage = '';
    public bool $isSendingReport = false;
    public string $reportModalSuccessMessage = '';
    public string $reportModalErrorMessage = '';
    #[\Livewire\Attributes\Url(except: 'last_30')]
    public string $datePreset = 'last_30';


    public function initDateRange()
    {
        if (empty($this->dateFrom)) {
            $this->dateFrom = session('marketing_date_from', Carbon::now()->subDays(29)->format('Y-m-d'));
        }
        if (empty($this->dateTo)) {
            $this->dateTo = session('marketing_date_to', Carbon::now()->format('Y-m-d'));
        }
        if ($this->datePreset === 'last_30') {
            $this->datePreset = session('marketing_date_preset', 'last_30');
        }
        
        // Ensure format and other compare states persist if not set
        if (empty($this->compareDateFrom)) {
            $this->compareDateFrom = session('marketing_compare_date_from', '');
            $this->compareDateTo = session('marketing_compare_date_to', '');
            $this->compareFormat = session('marketing_compare_format', 'percentage');
        }

        if ($this->dateFrom && $this->dateTo) {
            $d1 = Carbon::parse($this->dateFrom);
            $d2 = Carbon::parse($this->dateTo);
            $diff = $d1->diffInDays($d2) + 1;
            
            if ($diff == 7) $this->datePreset = 'last_7';
            elseif ($diff == 28) $this->datePreset = 'last_28';
            elseif ($diff == 30) $this->datePreset = 'last_30';
            elseif ($diff == 90) $this->datePreset = 'last_90';
            else $this->datePreset = 'custom';
            
            session(['marketing_date_preset' => $this->datePreset]);
        }
    }

    public function applyDateFilter($dateFrom, $dateTo, $compareFrom, $compareTo, $includeToday, $format, $preset = 'custom')
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->compareDateFrom = $compareFrom;
        $this->compareDateTo = $compareTo;
        $this->includeToday = filter_var($includeToday, FILTER_VALIDATE_BOOLEAN);
        $this->compareFormat = $format;
        $this->datePreset = $preset;

        session([
            'marketing_date_from' => $this->dateFrom,
            'marketing_date_to' => $this->dateTo,
            'marketing_compare_date_from' => $this->compareDateFrom,
            'marketing_compare_date_to' => $this->compareDateTo,
            'marketing_compare_format' => $this->compareFormat,
            'marketing_date_preset' => $this->datePreset,
        ]);
        
        // Call whichever data loading method exists in the component
        if (method_exists($this, 'loadReport')) {
            $this->loadReport();
        } elseif (method_exists($this, 'loadReportData')) {
            $this->loadReportData();
        } elseif (method_exists($this, 'loadReportDataForActiveModal')) {
            $this->loadReportDataForActiveModal();
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

    public function loadIntegrationJsonData(string $clientFolder, string $websiteFolder, string $integrationType, string $startDate, string $endDate, bool $fetchDefaultCompare = true): array
    {
        $months = $this->getMonthsForDateRange($startDate, $endDate);
        if (empty($months)) {
            return [];
        }

        $allData = [];
        $mergedDaily = [];
        $allMonthlyJsons = [];

        foreach ($months as $m) {
            $path = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/{$integrationType}/{$m['year']}/{$m['month']}.json");
            if (!file_exists($path)) {
                // Fallback: newly synced integrations fetch 90 days of data but only save to the current month's file.
                $currentMonthPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/{$integrationType}/" . date('Y') . "/" . strtolower(date('F')) . ".json");
                if (file_exists($currentMonthPath)) {
                    $path = $currentMonthPath;
                } else {
                    continue;
                }
            }

            // Prevent processing the exact same file twice in the same loop if fallback was used
            if (isset($allMonthlyJsons[$path])) {
                continue;
            }
            $allMonthlyJsons[$path] = true;

            static $fileCache = [];
            if (!isset($fileCache[$path])) {
                $fileCache[$path] = json_decode(file_get_contents($path), true);
            }
            $jsonData = $fileCache[$path];
            
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
            
            if (!empty($jsonData['daily_keywords'])) {
                if (!isset($mergedDailyKeywords)) $mergedDailyKeywords = [];
                foreach ($jsonData['daily_keywords'] as $item) {
                    $rawDate = (string)($item['date'] ?? '');
                    $normDate = strlen($rawDate) === 8 ? substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2) : $rawDate;
                    if ($normDate >= $startDate && $normDate <= $endDate) {
                        $mergedDailyKeywords[] = $item;
                    }
                }
            }

            if (!empty($jsonData['daily_campaigns'])) {
                if (!isset($mergedDailyCampaigns)) $mergedDailyCampaigns = [];
                foreach ($jsonData['daily_campaigns'] as $item) {
                    $rawDate = (string)($item['date'] ?? '');
                    $normDate = strlen($rawDate) === 8 ? substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2) : $rawDate;
                    if ($normDate >= $startDate && $normDate <= $endDate) {
                        $mergedDailyCampaigns[] = $item;
                    }
                }
            }

            if (!empty($jsonData['top_posts'])) {
                if (!isset($mergedTopPosts)) $mergedTopPosts = [];
                foreach ($jsonData['top_posts'] as $post) {
                    $rawDate = (string)($post['created_time'] ?? '');
                    if ($rawDate) {
                        $normDate = substr($rawDate, 0, 10);
                        if ($normDate >= $startDate && $normDate <= $endDate) {
                            $mergedTopPosts[$post['id']] = $post;
                        }
                    }
                }
            }
        }

        if (empty($allData)) {
            return [];
        }

        if (isset($mergedTopPosts)) {
            $allData['top_posts'] = array_values($mergedTopPosts);
            usort($allData['top_posts'], function($a, $b) {
                return ($b['views'] ?? 0) <=> ($a['views'] ?? 0);
            });
            $allData['top_posts'] = array_slice($allData['top_posts'], 0, 15);
        }

        // Fill in missing dates for the selected date range
        $filledDaily = [];
        try {
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $d = $date->format('Y-m-d');
                if (isset($mergedDaily[$d])) {
                    $filledDaily[$d] = $mergedDaily[$d];
                } else {
                    $filledDaily[$d] = [
                        'date' => $d,
                        'pageviews' => 0,
                        'users' => 0,
                        'sessions' => 0,
                        'bounce_rate' => 0,
                        'avg_session_duration' => 0,
                        'clicks' => 0,
                        'impressions' => 0,
                        'position' => 0,
                        'views' => 0,
                        'watch_time' => 0,
                        'subscribers' => 0,
                        'avg_view_duration' => 0,
                        'top_10' => null,
                        'top_15' => null,
                        'top_50' => null
                    ];
                }
            }
            $mergedDaily = $filledDaily;
        } catch (\Exception $e) {
            // fallback
        }

        // ── Proportionally scale aggregate arrays based on filtered date range ──
        // The monthly JSON files contain identical aggregate data (from the same GA4 API call).
        // We scale key_events, traffic_sources, pages_report, etc. proportionally based on
        // the ratio of sessions in the selected date range vs. total sessions in the full data.
        if ($integrationType === 'ga4' && !empty($mergedDaily)) {
            // Calculate total sessions from the filtered daily data
            $filteredSessions = 0;
            foreach ($mergedDaily as $d) {
                $filteredSessions += (int)($d['sessions'] ?? 0);
            }

            // Get total sessions from the original overall_summary (full period)
            $originalTotalSessions = (int)($allData['overall_summary']['sessions'] ?? 0);

            // Calculate the scale factor
            $scaleFactor = ($originalTotalSessions > 0) ? ($filteredSessions / $originalTotalSessions) : 1;

            // Scale key_events
            if (!empty($allData['key_events'])) {
                foreach ($allData['key_events'] as &$ev) {
                    $ev['event_count'] = (int)round(($ev['event_count'] ?? 0) * $scaleFactor);
                }
                unset($ev);
            }

            // Scale traffic_sources
            if (!empty($allData['traffic_sources'])) {
                foreach ($allData['traffic_sources'] as &$src) {
                    $src['sessions'] = (int)round(($src['sessions'] ?? 0) * $scaleFactor);
                }
                unset($src);
            }

            // Scale events_report
            if (!empty($allData['events_report'])) {
                foreach ($allData['events_report'] as $eventName => &$channels) {
                    foreach ($channels as &$channel) {
                        $channel['count'] = (int)round(($channel['count'] ?? 0) * $scaleFactor);
                    }
                }
            }

            // Scale pages_report
            if (!empty($allData['pages_report'])) {
                foreach ($allData['pages_report'] as &$pg) {
                    $pg['pageviews'] = (int)round(($pg['pageviews'] ?? 0) * $scaleFactor);
                    $pg['users'] = (int)round(($pg['users'] ?? 0) * $scaleFactor);
                }
                unset($pg);
            }

            // Scale device_demographics
            if (!empty($allData['device_demographics'])) {
                foreach ($allData['device_demographics'] as &$dev) {
                    if (isset($dev['active_users'])) {
                        $dev['active_users'] = (int)round($dev['active_users'] * $scaleFactor);
                    } elseif (isset($dev['sessions'])) {
                        $dev['sessions'] = (int)round($dev['sessions'] * $scaleFactor);
                    }
                }
                unset($dev);
                // Recalculate percentages
                $keyToUse = isset($allData['device_demographics'][0]['active_users']) ? 'active_users' : 'sessions';
                $totalDev = array_sum(array_column($allData['device_demographics'], $keyToUse));
                foreach ($allData['device_demographics'] as &$dev) {
                    $val = $dev[$keyToUse] ?? 0;
                    $dev['percentage'] = $totalDev > 0 ? round(($val / $totalDev) * 100, 1) . '%' : '0%';
                }
                unset($dev);
            }

            // Scale geographic_sources
            if (!empty($allData['geographic_sources'])) {
                foreach ($allData['geographic_sources'] as &$geo) {
                    $geo['active_users'] = (int)round(($geo['active_users'] ?? 0) * $scaleFactor);
                    $geo['sessions'] = (int)round(($geo['sessions'] ?? 0) * $scaleFactor);
                }
                unset($geo);
            }
        } elseif ($integrationType === 'youtube' && !empty($mergedDaily)) {
            // Proportionally scale youtube top videos
            $filteredViews = 0;
            foreach ($mergedDaily as $d) {
                $filteredViews += (int)($d['views'] ?? 0);
            }
            $originalTotalViews = (int)($allData['summary']['views'] ?? 0);
            $scaleFactor = ($originalTotalViews > 0) ? ($filteredViews / $originalTotalViews) : 1;

            if (!empty($allData['top_videos'])) {
                foreach ($allData['top_videos'] as &$vid) {
                    $vid['views'] = (int)round(($vid['views'] ?? 0) * $scaleFactor);
                    $vid['watch_time'] = round(($vid['watch_time'] ?? 0) * $scaleFactor, 2);
                }
                unset($vid);
            }
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
                $totalAvgViewDuration = 0;
                $validDays = 0;

                foreach ($mergedDaily as $d) {
                    $totalViews += (int)($d['views'] ?? 0);
                    $totalWatch += (float)($d['watch_time'] ?? 0);
                    $totalSubs += (int)($d['subscribers'] ?? 0);
                    
                    if (isset($d['avg_view_duration']) && $d['avg_view_duration'] > 0) {
                        $totalAvgViewDuration += (int)$d['avg_view_duration'];
                        $validDays++;
                    }
                }
                
                $allData['summary']['views'] = $totalViews;
                $allData['summary']['watch_time'] = $totalWatch;
                $allData['summary']['subscribers'] = $totalSubs;

                if ($validDays > 0) {
                    $finalAvgViewDuration = round($totalAvgViewDuration / $validDays);
                    $formattedDuration = gmdate("H:i:s", $finalAvgViewDuration);
                    if (str_starts_with($formattedDuration, '00:00:')) {
                        $formattedDuration = (int)substr($formattedDuration, 6) . 's';
                    } elseif (str_starts_with($formattedDuration, '00:')) {
                        $formattedDuration = (int)substr($formattedDuration, 3, 2) . 'm ' . (int)substr($formattedDuration, 6) . 's';
                    }
                    $allData['summary']['avg_view_duration'] = $formattedDuration;
                } else {
                    $allData['summary']['avg_view_duration'] = '0s';
                }
            }
            // Re-calculate Facebook/LinkedIn summary
            elseif ($integrationType === 'facebook' || $integrationType === 'linkedin') {
                $totalReach = 0;
                $totalViews = 0;
                $totalEngaged = 0;
                
                foreach ($mergedDaily as $d) {
                    $totalReach += (int)($d['reach'] ?? ($d['impressions'] ?? 0));
                    $totalViews += (int)($d['views'] ?? 0);
                    $totalEngaged += (int)($d['engaged'] ?? ($d['engagements'] ?? 0));
                }
                
                $allData['summary']['reach'] = $totalReach;
                $allData['summary']['views'] = $totalViews;
                $allData['summary']['engaged'] = $totalEngaged;
                if (isset($allData['top_posts'])) {
                    $allData['summary']['posts'] = count($allData['top_posts']);
                }
            }
            // Re-calculate Gads summary
            elseif ($integrationType === 'gads') {
                $totalClicks = 0;
                $totalImpressions = 0;
                $totalCost = 0;
                $totalConversions = 0;
                
                $deviceSummary = [
                    'mobile' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                    'desktop' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                    'tablet' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                    'other' => ['clicks' => 0, 'impressions' => 0, 'cost' => 0, 'conversions' => 0],
                ];
                
                foreach ($mergedDaily as $d) {
                    $totalClicks += (int)($d['clicks'] ?? 0);
                    $totalImpressions += (int)($d['impressions'] ?? 0);
                    $totalCost += (float)($d['cost'] ?? 0);
                    $totalConversions += (float)($d['conversions'] ?? 0);
                    
                    if (isset($d['devices']) && is_array($d['devices'])) {
                        foreach (['mobile', 'desktop', 'tablet', 'other'] as $dev) {
                            if (isset($d['devices'][$dev])) {
                                $deviceSummary[$dev]['clicks'] += (int)($d['devices'][$dev]['clicks'] ?? 0);
                                $deviceSummary[$dev]['impressions'] += (int)($d['devices'][$dev]['impressions'] ?? 0);
                                $deviceSummary[$dev]['cost'] += (float)($d['devices'][$dev]['cost'] ?? 0);
                                $deviceSummary[$dev]['conversions'] += (float)($d['devices'][$dev]['conversions'] ?? 0);
                            }
                        }
                    }
                }
                
                $allData['summary']['clicks'] = $totalClicks;
                $allData['summary']['impressions'] = $totalImpressions;
                $allData['summary']['cost'] = round($totalCost, 2);
                $allData['summary']['conversions'] = round($totalConversions, 2);
                
                foreach ($deviceSummary as $k => &$v) {
                    $v['cost'] = round($v['cost'], 2);
                    $v['conversions'] = round($v['conversions'], 2);
                }
                $allData['device_summary'] = $deviceSummary;

                if (!empty($mergedDailyKeywords)) {
                    $aggregatedKeywords = [];
                    $uniqueDaily = [];
                    foreach ($mergedDailyKeywords as $dk) {
                        $date = $dk['date'] ?? '';
                        $kw = $dk['keyword'];
                        $uniqueKey = $date . '_' . $kw;
                        // Deduplicate exact same keyword on same date across overlapping JSON files
                        $uniqueDaily[$uniqueKey] = $dk;
                    }

                    foreach ($uniqueDaily as $dk) {
                        $kw = $dk['keyword'];
                        if (!isset($aggregatedKeywords[$kw])) {
                            $aggregatedKeywords[$kw] = [
                                'keyword' => $kw,
                                'clicks' => 0,
                                'impressions' => 0,
                                'cost' => 0
                            ];
                        }
                        $aggregatedKeywords[$kw]['clicks'] += (int)($dk['clicks'] ?? 0);
                        $aggregatedKeywords[$kw]['impressions'] += (int)($dk['impressions'] ?? 0);
                        $aggregatedKeywords[$kw]['cost'] += (float)($dk['cost'] ?? 0);
                    }

                    $topKwList = [];
                    foreach ($aggregatedKeywords as $kwData) {
                        if ($kwData['clicks'] > 0 || $kwData['impressions'] > 0) {
                            $c = $kwData['clicks'];
                            $i = $kwData['impressions'];
                            // Google Ads CTR is normally stored as a decimal (e.g. 0.0676 for 6.76%), because the blade file multiplies by 100
                            $kwData['ctr'] = $i > 0 ? ($c / $i) : 0;
                            $kwData['avg_cpc'] = $c > 0 ? round($kwData['cost'] / $c, 2) : 0;
                            $kwData['cost'] = round($kwData['cost'], 2);
                            $topKwList[] = $kwData;
                        }
                    }

                    usort($topKwList, function($a, $b) {
                        return $b['cost'] <=> $a['cost'];
                    });

                    $allData['top_keywords'] = array_slice($topKwList, 0, 10);
                }

                if (!empty($mergedDailyCampaigns)) {
                    $aggregatedCampaigns = [];
                    $uniqueDailyCamp = [];
                    foreach ($mergedDailyCampaigns as $dc) {
                        $date = $dc['date'] ?? '';
                        $campName = $dc['name'] ?? 'Unknown';
                        $uniqueKey = $date . '_' . $campName;
                        // Deduplicate same campaign on same date
                        $uniqueDailyCamp[$uniqueKey] = $dc;
                    }

                    foreach ($uniqueDailyCamp as $dc) {
                        $campName = $dc['name'] ?? 'Unknown';
                        if (!isset($aggregatedCampaigns[$campName])) {
                            $aggregatedCampaigns[$campName] = [
                                'name' => $campName,
                                'clicks' => 0,
                                'impressions' => 0,
                                'cost' => 0,
                                'conversions' => 0
                            ];
                        }
                        $aggregatedCampaigns[$campName]['clicks'] += (int)($dc['clicks'] ?? 0);
                        $aggregatedCampaigns[$campName]['impressions'] += (int)($dc['impressions'] ?? 0);
                        $aggregatedCampaigns[$campName]['cost'] += (float)($dc['cost'] ?? 0);
                        $aggregatedCampaigns[$campName]['conversions'] += (float)($dc['conversions'] ?? 0);
                    }

                    $topCampList = array_values($aggregatedCampaigns);
                    usort($topCampList, function($a, $b) {
                        return $b['cost'] <=> $a['cost'];
                    });

                    $allData['top_campaigns'] = array_slice($topCampList, 0, 5);
                }
            }
        }

        if ($fetchDefaultCompare && !empty($startDate) && !empty($endDate)) {
            try {
                $start = \Carbon\Carbon::parse($startDate);
                $end = \Carbon\Carbon::parse($endDate);
                $diffDays = $start->diffInDays($end) + 1;
                $cEnd = $start->copy()->subDay();
                $cStart = $cEnd->copy()->subDays($diffDays - 1);
                
                $defaultCompare = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $integrationType, $cStart->format('Y-m-d'), $cEnd->format('Y-m-d'), false);
                $allData['default_compare_data'] = $defaultCompare;
            } catch (\Exception $e) {}
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

    public function getActiveClientAndWebsite(): array
    {
        $client = null;
        $website = null;

        if (!empty($this->client) && is_object($this->client)) {
            $client = $this->client;
        } elseif (!empty($this->clientId)) {
            $client = \App\Modules\CRM\Clients\Models\Client::with('user')->find($this->clientId);
        } elseif (!empty($this->selectedClientDetailId)) {
            $client = \App\Modules\CRM\Clients\Models\Client::with('user')->find($this->selectedClientDetailId);
        } elseif (!empty($this->selectedClientId)) {
            $client = \App\Modules\CRM\Clients\Models\Client::with('user')->find($this->selectedClientId);
        } elseif (\Illuminate\Support\Facades\Auth::check()) {
            $client = \App\Modules\CRM\Clients\Models\Client::with('user')->where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        }

        if (!empty($this->website) && is_object($this->website)) {
            $website = $this->website;
        } else {
            $wId = $this->websiteId ?? ($this->selectedWebsiteId ?? null);
            if ($wId) {
                $website = \App\Modules\CRM\Websites\Models\Website::find($wId);
            } elseif ($client) {
                $website = \App\Modules\CRM\Websites\Models\Website::where('client_id', $client->id)->first();
            }
        }

        return [$client, $website];
    }

    public function syncDefaultReportModalData(): void
    {
        [$client, $website] = $this->getActiveClientAndWebsite();
        if ($client && $client->user && empty($this->reportRecipientEmail)) {
            $this->reportRecipientEmail = $client->user->email ?? '';
        }
        if ($website && empty($this->reportEmailSubject)) {
            $monthName = \Carbon\Carbon::parse(!empty($this->dateFrom) ? $this->dateFrom : now())->format('F Y');
            $this->reportEmailSubject = "Monthly SEO & Marketing Report - {$monthName} - {$website->site_name}";
        }
    }

    public function openSendReportModal(): void
    {
        [$client, $website] = $this->getActiveClientAndWebsite();
        if (!$client || !$website) {
            session()->flash('error', 'Client or website details could not be found.');
            return;
        }

        $this->reportRecipientEmail = $client->user->email ?? '';
        
        $monthName = \Carbon\Carbon::parse($this->dateFrom ?: now())->format('F Y');
        $this->reportEmailSubject = "Monthly SEO & Marketing Report - {$monthName} - {$website->site_name}";
        $this->reportEmailMessage = "";
        $this->reportModalSuccessMessage = '';
        $this->reportModalErrorMessage = '';
        $this->showSendReportModal = true;
    }

    public function closeSendReportModal(): void
    {
        $this->showSendReportModal = false;
        $this->reportModalSuccessMessage = '';
        $this->reportModalErrorMessage = '';
    }

    public function sendMarketingReport(): void
    {
        [$client, $website] = $this->getActiveClientAndWebsite();
        if (empty($this->reportRecipientEmail) && $client && $client->user) {
            $this->reportRecipientEmail = $client->user->email ?? '';
        }
        if (empty($this->reportEmailSubject) && $website) {
            $monthName = \Carbon\Carbon::parse($this->dateFrom ?: now())->format('F Y');
            $subject = "Monthly SEO & Marketing Report - {$monthName}";
            if (!empty($this->compareDateFrom)) {
                $compareMonthName = \Carbon\Carbon::parse($this->compareDateFrom)->format('F Y');
                $subject .= " vs {$compareMonthName}";
            }
            $subject .= " - {$website->site_name}";
            $this->reportEmailSubject = $subject;
        }

        $this->validate([
            'reportRecipientEmail' => 'required|email',
            'reportEmailSubject' => 'required|string|max:255',
        ]);

        $this->isSendingReport = true;
        $this->reportModalSuccessMessage = '';
        $this->reportModalErrorMessage = '';

        try {
            if (!$client || !$website) {
                throw new \Exception('Client or website information could not be resolved.');
            }

            $activeFrom = !empty($this->dateFrom) ? $this->dateFrom : Carbon::now()->subDays(28)->format('Y-m-d');
            $activeTo = !empty($this->dateTo) ? $this->dateTo : Carbon::now()->subDays(1)->format('Y-m-d');

            $service = app(\App\Services\MarketingReportPdfService::class);
            $result = $service->sendReportEmail(
                client: $client,
                website: $website,
                dateFrom: $activeFrom,
                dateTo: $activeTo,
                recipientEmail: $this->reportRecipientEmail,
                subject: $this->reportEmailSubject,
                personalMessage: $this->reportEmailMessage,
                compareDateFrom: $this->compareDateFrom,
                compareDateTo: $this->compareDateTo
            );

            if ($result['success']) {
                $this->reportModalSuccessMessage = $result['message'];
                session()->flash('success', $result['message']);
            } else {
                $this->reportModalErrorMessage = $result['message'];
                session()->flash('error', $result['message']);
            }
        } catch (\Throwable $e) {
            $this->reportModalErrorMessage = 'Failed to send report: ' . $e->getMessage();
        } finally {
            $this->isSendingReport = false;
        }
    }
}
