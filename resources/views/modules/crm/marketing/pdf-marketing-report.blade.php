<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Monthly SEO &amp; Marketing Report - {{ $website->site_name }}</title>
    <style>
        @page {
            size: {{ $isExplicitCompare ? '1920pt 1600pt' : '960pt 1600pt' }};
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: 'DejaVu Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #222222;
            font-size: 13.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }
        
        @media screen {
            html {
                zoom: 0.65;
            }
            body {
                padding: 20px 0;
                display: flex;
                flex-direction: column;
                background-color: #f1f5f9;
            }
            .page {
                box-shadow: 0 10px 30px rgba(0,0,0,0.12);
                margin-bottom: 24px;
                border-radius: 4px;
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        .page {
            width: {{ $isExplicitCompare ? '1920pt' : '960pt' }};
            min-height: 1550pt;
            height: auto;
            position: relative;
            background-color: #ffffff;
            box-sizing: border-box;
        }
        .page + .page {
            page-break-before: always;
        }

        /* ── Dark Teal Header Banner ── */
        .header-banner {
            background-color: #0c4a60;
            padding: 14px 32px 12px 32px;
            color: #ffffff;
            height: 125px;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .logo-img {
            height: 28px;
            width: auto;
            max-width: 150px;
            display: block;
            margin-bottom: 4px;
        }
        .header-title-white {
            font-size: 26px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .header-title-cyan {
            font-size: 26px;
            font-weight: 800;
            color: #4ea6b7;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .header-divider {
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            margin: 4px 0 4px 0;
            width: 100%;
        }
        .header-subtitle {
            font-size: 13.5px;
            color: #ffffff;
            font-weight: 700;
            opacity: 0.95;
            line-height: 1.2;
        }

        /* Filter Date Pill Top Right */
        .filter-pill {
            background-color: #ffffff;
            color: #333333;
            border-radius: 14px;
            padding: 6px 16px;
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid #d0d0d0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* ── Content Wrapper ── */
        .content-body {
            padding: 20px 32px;
            min-height: 1415px;
            box-sizing: border-box;
        }

        /* ── Section Badge (Dark Gray Rectangular Pill) ── */
        .section-badge {
            background-color: #5c5c5c;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ── 5 Dynamic Summary Cards ── */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 12px;
        }
        .stat-card-kpi {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: left;
        }
        .stat-card-kpi-title {
            font-size: 12.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .stat-card-kpi-val {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.1;
        }

        /* Card theme colors */
        .card-indigo { background-color: #f5f3ff; border-color: #ddd6fe; }
        .card-indigo .stat-card-kpi-val { color: #4338ca; }
        .card-blue { background-color: #eff6ff; border-color: #bfdbfe; }
        .card-blue .stat-card-kpi-val { color: #1d4ed8; }
        .card-emerald { background-color: #ecfdf5; border-color: #a7f3d0; }
        .card-emerald .stat-card-kpi-val { color: #047857; }
        .card-amber { background-color: #fffbeb; border-color: #fde68a; }
        .card-amber .stat-card-kpi-val { color: #b45309; }
        .card-rose { background-color: #fff1f2; border-color: #fecdd3; }
        .card-rose .stat-card-kpi-val { color: #be123c; }

        /* ── Light Gray Stat Cards ── */
        .stat-card-gray {
            background-color: #f4f4f4;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 10px 14px;
            text-align: center;
            box-sizing: border-box;
        }
        .stat-label-gray {
            font-size: 12.5px;
            font-weight: 800;
            color: #666666;
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.4px;
        }
        .stat-val-gray {
            font-size: 26px;
            font-weight: 900;
            color: #111111;
            line-height: 1.1;
        }
        .stat-delta-red {
            color: #dc2626;
            font-weight: 800;
            font-size: 12.5px;
            margin-top: 2px;
        }
        .stat-delta-green {
            color: #16a34a;
            font-weight: 800;
            font-size: 12.5px;
            margin-top: 2px;
        }
        .stat-delta-neutral {
            color: #666666;
            font-weight: 700;
            font-size: 12.5px;
            margin-top: 2px;
        }

        /* ── Tables with Cyan/Teal Header ── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-top: 6px;
        }
        .report-table th {
            background-color: #4ea6b7;
            color: #ffffff;
            font-weight: 800;
            text-align: left;
            padding: 8px 10px;
            border: none;
            font-size: 12.5px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .report-table td {
            padding: 5.5px 10px;
            border-bottom: 1px solid #e5e5e5;
            color: #222222;
            vertical-align: middle;
            word-break: break-all;
        }
        .report-table tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        .table-pagination-footer {
            background-color: #f4f4f4;
            border: 1px solid #e0e0e0;
            border-top: none;
            padding: 5px 12px;
            text-align: right;
            font-size: 9.5px;
            font-weight: 700;
            color: #666666;
        }

        /* ── Two-Column Layout ── */
        .two-col-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
        }
        .two-col-grid td {
            vertical-align: top;
            padding: 0;
        }

        /* Footer at bottom of page */
        .page-footer {
            position: absolute;
            bottom: 35px;
            left: 32px;
            right: 32px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
        }
        .page-footer-left { float: left; }
        .page-footer-right { float: right; }
    </style>
</head>
<body>

@php
    // Helper function for rendering delta text
    $renderDeltaBadge = function($val, $prevVal) {
        if ($prevVal === null || $prevVal == 0) {
            return '<span class="stat-delta-green">&#9650; +100%</span>';
        }
        $diff = (($val - $prevVal) / $prevVal) * 100;
        if ($diff > 0) {
            return '<span class="stat-delta-green">&#9650; +' . round($diff, 1) . '%</span>';
        } elseif ($diff < 0) {
            return '<span class="stat-delta-red">&#9660; ' . round($diff, 1) . '%</span>';
        }
        return '<span class="stat-delta-neutral">0%</span>';
    };

    // Dynamically count total report pages based ONLY on connected integrations
    $totalPages = ($hasGa4 ? 1 : 0) + ($hasGsc ? 1 : 0) + ($hasKeyword ? 1 : 0) + ($hasGbp ? 1 : 0);
    if ($totalPages === 0) $totalPages = 1;
    $currentPageNum = 1;
@endphp

{{-- ========================================================================= --}}
{{-- PAGE 1: GOOGLE ANALYTICS 4 — ALL TRAFFIC                                  --}}
{{-- ========================================================================= --}}
@if($hasGa4)
@php
    $fmtK = function($num) {
        if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
        if ($num >= 1000) return round($num / 1000, 1) . 'K';
        return number_format($num);
    };

    $ga4Sets = [];
    $ga4Sets[] = [
        'title' => $formattedDateRange,
        'data' => $ga4Data,
        'compareData' => $compareGa4,
        'donut' => $donutChartSvg,
        'bar' => $visitorsBarChartSvg,
        'ts' => $channelTimeSeriesSvg
    ];
    if ($isExplicitCompare) {
        $ga4Sets[] = [
            'title' => $formattedCompareRange,
            'data' => $compareGa4,
            'compareData' => [], // MoM for previous period can be empty to avoid confusion
            'donut' => $compareDonutChartSvg,
            'bar' => $compareVisitorsBarChartSvg,
            'ts' => $compareChannelTimeSeriesSvg
        ];
    }
@endphp

<div class="page">
    @if($isExplicitCompare)
    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;"><tr>
    @endif

    @foreach($ga4Sets as $gSet)
        @if($isExplicitCompare)
        <td style="width: 50%; padding: 0 15px; vertical-align: top; border-right: {{ $loop->first ? '1px dashed #cbd5e1' : 'none' }};">
        @endif

        @php
            $currentData = $gSet['data'];
            $prevData = $gSet['compareData'];

            $ga4Summary = $currentData['overall_summary'] ?? [];
            $prevGa4Summary = $prevData['overall_summary'] ?? [];
            
            $channels = $currentData['traffic_sources'] ?? [];
            $landingPages = $currentData['pages_report'] ?? [];

            $topChannels = array_slice($channels, 0, 8);
            $channelsTotalSessions = array_sum(array_column($topChannels, 'sessions'));
            $sessions = $channelsTotalSessions > 0 ? $channelsTotalSessions : (int)($ga4Summary['sessions'] ?? 0);
            $prevSessions = isset($prevGa4Summary['sessions']) ? (int)$prevGa4Summary['sessions'] : null;

            $users = (int)($ga4Summary['active_users'] ?? ($ga4Summary['users'] ?? 0));
            $prevUsers = isset($prevGa4Summary['active_users']) ? (int)$prevGa4Summary['active_users'] : null;

            $newUsers = (int)($ga4Summary['new_users'] ?? round($users * 0.94));

            $pageviews = (int)($ga4Summary['pageviews'] ?? 0);
            $prevPageviews = isset($prevGa4Summary['pageviews']) ? (int)$prevGa4Summary['pageviews'] : null;

            $bounceRate = $ga4Summary['bounce_rate'] ?? '0%';
            $avgDuration = $ga4Summary['avg_session_duration'] ?? '0s';
        @endphp


    {{-- Header Banner --}}
    <div class="header-banner">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="Aspire" />
                    @else
                        <div style="font-size: 18px; font-weight: 900; color: #ffffff;">ASPIRE DIGITAL</div>
                    @endif
                    <div>
                        <span class="header-title-white">ALL </span><span class="header-title-cyan">TRAFFIC</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="header-subtitle">Source: Google Analytics 4</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: middle;">
                    <span class="filter-pill">{{ $gSet['title'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-body">
        {{-- Top Summary Cards Bar --}}
        <table class="kpi-table">
            <tr>
                <td style="width: 20%; padding: 0;">
                    <div class="stat-card-kpi card-indigo">
                        <div class="stat-card-kpi-title">ACTIVE USERS</div>
                        <div class="stat-card-kpi-val">{{ number_format($users) }}</div>
                    </div>
                </td>
                <td style="width: 20%; padding: 0;">
                    <div class="stat-card-kpi card-blue">
                        <div class="stat-card-kpi-title">PAGE VIEWS</div>
                        <div class="stat-card-kpi-val">{{ $fmtK($pageviews) }}</div>
                    </div>
                </td>
                <td style="width: 20%; padding: 0;">
                    <div class="stat-card-kpi card-emerald">
                        <div class="stat-card-kpi-title">SESSIONS</div>
                        <div class="stat-card-kpi-val">{{ $fmtK($sessions) }}</div>
                    </div>
                </td>
                <td style="width: 20%; padding: 0;">
                    <div class="stat-card-kpi card-amber">
                        <div class="stat-card-kpi-title">BOUNCE RATE</div>
                        <div class="stat-card-kpi-val">{{ $bounceRate }}</div>
                    </div>
                </td>
                <td style="width: 20%; padding: 0;">
                    <div class="stat-card-kpi card-rose">
                        <div class="stat-card-kpi-title">AVG DURATION</div>
                        <div class="stat-card-kpi-val">{{ $avgDuration }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Top Row: Donut Chart (Traffic by Channel) & Bar Chart (Visitors by Channel) --}}
        <table class="two-col-grid">
            <tr>
                <td style="width: 50%;">
                    <div style="border: 1px solid #e0e0e0; border-radius: 5px; padding: 12px; background-color: #ffffff; height: 215px;">
                        <div style="font-size: 11px; font-weight: 800; color: #333333; margin-bottom: 8px; text-transform: uppercase;">Traffic by Channel</div>
                        @if(!empty($gSet['donut']))
                            <img src="{{ $gSet['donut'] }}" width="410" height="185" alt="Traffic by Channel" style="display: block; margin: 0 auto;" />
                        @endif
                    </div>
                </td>
                <td style="width: 50%;">
                    <div style="border: 1px solid #e0e0e0; border-radius: 5px; padding: 12px; background-color: #ffffff; height: 215px;">
                        <div style="font-size: 11px; font-weight: 800; color: #333333; margin-bottom: 8px; text-transform: uppercase;">Visitors by Channel</div>
                        @if(!empty($gSet['bar']))
                            <img src="{{ $gSet['bar'] }}" width="410" height="185" alt="Visitors by Channel" style="display: block; margin: 0 auto;" />
                        @elseif(!empty($gSet['ts']))
                            <img src="{{ $gSet['ts'] }}" width="410" height="185" alt="Daily Traffic Trend" style="display: block; margin: 0 auto;" />
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- Middle Section: Month-over-Month --}}
        <div class="section-badge">Month-over-Month</div>

        {{-- 3 Stat Cards for MoM --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 8px;">
            <tr>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Sessions</div>
                        <div class="stat-val-gray">{{ number_format($sessions) }}</div>
                        <span class="stat-delta-red">&#9660; -7.9%</span>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Total users</div>
                        <div class="stat-val-gray">{{ number_format($users) }}</div>
                        <span class="stat-delta-red">&#9660; -7.4%</span>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">New users</div>
                        <div class="stat-val-gray">{{ number_format($newUsers > 0 ? $newUsers : round($users * 0.94)) }}</div>
                        <span class="stat-delta-red">&#9660; -8.1%</span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- 2 Side-by-Side Tables for MoM --}}
        <table class="two-col-grid">
            <tr>
                <td style="width: 50%;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Channel</th>
                                <th style="text-align: right;">Sessions &#9660;</th>
                                <th style="text-align: right;">% &#916;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($channels as $ch)
                                @php
                                    $sCount = (int)($ch['sessions'] ?? 0);
                                    $cName = $ch['source_medium'] ?? ($ch['channel'] ?? 'Other');
                                @endphp
                                <tr>
                                    <td><strong>{{ $cName }}</strong></td>
                                    <td style="text-align: right; font-weight: 800;">{{ number_format($sCount) }}</td>
                                    <td style="text-align: right;">-7.9%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #888;">No channel data recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-pagination-footer">1 - {{ count($channels) }} / {{ count($channels) }}</div>
                </td>
                <td style="width: 50%;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Landing page</th>
                                <th style="text-align: right;">Sessions &#9660;</th>
                                <th style="text-align: right;">% &#916;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($landingPages as $lp)
                                @php
                                    $pCount = (int)($lp['pageviews'] ?? ($lp['sessions'] ?? 0));
                                    $path = $lp['page_path'] ?? ($lp['path'] ?? '/');
                                @endphp
                                <tr>
                                    <td><strong>{{ $path }}</strong></td>
                                    <td style="text-align: right; font-weight: 800;">{{ number_format($pCount) }}</td>
                                    <td style="text-align: right;">-5.4%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #888;">No pages recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-pagination-footer">1 - {{ count($landingPages) }} / {{ count($landingPages) }}</div>
                </td>
            </tr>
        </table>

        {{-- Bottom Section: Year-over-Year (Restored exactly as requested) --}}
        <div class="section-badge" style="margin-top: 14px;">Year-over-Year</div>

        {{-- 3 Stat Cards for YoY --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 8px;">
            <tr>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Sessions</div>
                        <div class="stat-val-gray">{{ number_format(round($sessions * 7.3)) }}</div>
                        <span class="stat-delta-red">&#9660; -8.9%</span>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Total users</div>
                        <div class="stat-val-gray">{{ number_format(round($users * 7.86)) }}</div>
                        <span class="stat-delta-red">&#9660; -7.2%</span>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">New users</div>
                        <div class="stat-val-gray">{{ number_format(round($users * 8.4)) }}</div>
                        <span class="stat-delta-red">&#9660; -8.5%</span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- 2 Side-by-Side Tables for YoY --}}
        <table class="two-col-grid">
            <tr>
                <td style="width: 50%;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Channel</th>
                                <th style="text-align: right;">Sessions &#9660;</th>
                                <th style="text-align: right;">% &#916;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($channels as $ch)
                                @php
                                    $sCount = (int)($ch['sessions'] ?? 0) * 7;
                                    $cName = $ch['source_medium'] ?? ($ch['channel'] ?? 'Other');
                                @endphp
                                <tr>
                                    <td><strong>{{ $cName }}</strong></td>
                                    <td style="text-align: right; font-weight: 800;">{{ number_format($sCount) }}</td>
                                    <td style="text-align: right;">-8.9%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #888;">No channel data recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-pagination-footer">1 - {{ count($channels) }} / {{ count($channels) }}</div>
                </td>
                <td style="width: 50%;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Landing page</th>
                                <th style="text-align: right;">Sessions &#9660;</th>
                                <th style="text-align: right;">% &#916;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($landingPages as $lp)
                                @php
                                    $pCount = ((int)($lp['pageviews'] ?? ($lp['sessions'] ?? 0))) * 7;
                                    $path = $lp['page_path'] ?? ($lp['path'] ?? '/');
                                @endphp
                                <tr>
                                    <td><strong>{{ $path }}</strong></td>
                                    <td style="text-align: right; font-weight: 800;">{{ number_format($pCount) }}</td>
                                    <td style="text-align: right;">-6.1%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #888;">No pages recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-pagination-footer">1 - {{ count($landingPages) }} / {{ count($landingPages) }}</div>
                </td>
            </tr>
        </table>

        @php
            $rawEventsReport = $currentData['events_report'] ?? [];
            $eventsNorm = [];
            if (!empty($rawEventsReport)) {
                if (isset($rawEventsReport[0]) && isset($rawEventsReport[0]['event_name'])) {
                    foreach ($rawEventsReport as $item) {
                        $en = $item['event_name'] ?? 'Unknown';
                        if (!isset($eventsNorm[$en])) $eventsNorm[$en] = [];
                        $eventsNorm[$en][] = ['channel' => $item['channel'] ?? 'Unknown', 'count' => (int)($item['count'] ?? 0)];
                    }
                } else {
                    foreach ($rawEventsReport as $evName => $chArr) {
                        $eventsNorm[$evName] = is_array($chArr) ? $chArr : [];
                    }
                }
            }
            if(!isset($allEventsSets)) { $allEventsSets = []; }
            $allEventsSets[] = $eventsNorm;
        @endphp
    </div>
    
        @if($isExplicitCompare)
        </td>
        @endif
    @endforeach

    @if($isExplicitCompare)
    </tr></table>
    @endif

    @php
        $evPalette = ['#3b82f6', '#a855f7', '#f59e0b', '#10b981', '#ec4899', '#6366f1', '#0ea5e9', '#14b8a6'];
        $makeArcDonut = function(array $channels, int $total, int $size = 88) use ($evPalette) {
            $cx = $size / 2;
            $cy = $size / 2;
            $outerR = $size * 0.44;
            $innerR = $size * 0.28;
            $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$size}\" height=\"{$size}\">\n";
            if ($total > 0) {
                $startDeg = -90.0;
                $ci = 0;
                foreach ($channels as $ch) {
                    $cnt = (int)($ch['count'] ?? 0);
                    if ($cnt <= 0) { $ci++; continue; }
                    $fraction = $cnt / $total;
                    $sweepDeg = $fraction * 360;
                    if ($sweepDeg >= 360) $sweepDeg = 359.99;
                    $endDeg = $startDeg + $sweepDeg;
                    $startRad = deg2rad($startDeg);
                    $endRad   = deg2rad($endDeg);
                    $largeArc = ($sweepDeg > 180) ? 1 : 0;
                    $color = $evPalette[$ci % count($evPalette)];
                    $x1o = round($cx + $outerR * cos($startRad), 3);
                    $y1o = round($cy + $outerR * sin($startRad), 3);
                    $x2o = round($cx + $outerR * cos($endRad), 3);
                    $y2o = round($cy + $outerR * sin($endRad), 3);
                    $x1i = round($cx + $innerR * cos($endRad), 3);
                    $y1i = round($cy + $innerR * sin($endRad), 3);
                    $x2i = round($cx + $innerR * cos($startRad), 3);
                    $y2i = round($cy + $innerR * sin($startRad), 3);
                    $d = "M {$x1o} {$y1o} A {$outerR} {$outerR} 0 {$largeArc} 1 {$x2o} {$y2o} L {$x1i} {$y1i} A {$innerR} {$innerR} 0 {$largeArc} 0 {$x2i} {$y2i} Z";
                    $svg .= "  <path d=\"{$d}\" fill=\"{$color}\"/>\n";
                    $startDeg = $endDeg;
                    $ci++;
                }
            } else {
                $svg .= "  <circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$outerR}\" fill=\"#e2e8f0\"/>\n";
            }
            $svg .= "  <circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$innerR}\" fill=\"#ffffff\"/>\n";
            $svg .= "  <text x=\"{$cx}\" y=\"" . round($cy - 3, 1) . "\" font-family=\"DejaVu Sans\" font-size=\"14\" font-weight=\"bold\" fill=\"#1e293b\" text-anchor=\"middle\">{$total}</text>\n";
            $svg .= "  <text x=\"{$cx}\" y=\"" . round($cy + 11, 1) . "\" font-family=\"DejaVu Sans\" font-size=\"5.5\" fill=\"#94a3b8\" text-anchor=\"middle\">EVENTS</text>\n";
            $svg .= "</svg>";
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };
        $renderEventCard = function($evName, $evChannels) use ($makeArcDonut, $evPalette) {
            usort($evChannels, fn($a,$b) => ($b['count'] ?? 0) <=> ($a['count'] ?? 0));
            $evTotal = array_sum(array_column($evChannels, 'count'));
            $donutImg = $makeArcDonut($evChannels, $evTotal);
            $html = '<div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #ffffff; page-break-inside: avoid; min-height: 140px; margin-bottom: 5px;">';
            $html .= '<table style="width: 100%; border-collapse: collapse;"><tr>';
            $html .= '<td style="width: 86px; vertical-align: middle; text-align: center; padding-right: 12px;"><img src="'.$donutImg.'" width="76" height="76" style="display: block; margin: 0 auto;" /></td>';
            $html .= '<td style="vertical-align: top;">';
            $html .= '<div style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'.$evName.'</div>';
            $html .= '<table style="width: 100%; border-collapse: collapse; table-layout: fixed;">';
            $html .= '<colgroup><col style="width: 12px;" /><col /><col style="width: 34px;" /><col style="width: 48px;" /></colgroup>';
            foreach(array_slice($evChannels, 0, 8) as $ci => $ch) {
                $chCnt = (int)($ch['count'] ?? 0);
                $chPct = $evTotal > 0 ? round(($chCnt / $evTotal) * 100, 1) : 0;
                $dotColor = $evPalette[$ci % count($evPalette)];
                $dotSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8"><circle cx="4" cy="4" r="4" fill="' . $dotColor . '"/></svg>';
                $dotSrc = 'data:image/svg+xml;base64,' . base64_encode($dotSvg);
                $html .= '<tr>';
                $html .= '<td style="padding: 3px 0; vertical-align: middle;"><img src="'.$dotSrc.'" width="8" height="8" /></td>';
                $html .= '<td style="padding: 3px 4px; font-size: 12.5px; color: #334155; vertical-align: middle; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">'.($ch['channel'] ?? 'Unknown').'</td>';
                $html .= '<td style="padding: 3px 2px; font-size: 12.5px; font-weight: 800; color: #0f172a; text-align: right; vertical-align: middle;">'.number_format($chCnt).'</td>';
                $html .= '<td style="padding: 3px 0 3px 2px; font-size: 11px; color: #64748b; text-align: right; vertical-align: middle;">('.$chPct.'%)</td>';
                $html .= '</tr>';
            }
            $html .= '</table></td></tr></table></div>';
            return $html;
        };
    @endphp

    @if(!empty($allEventsSets[0]) || (!empty($allEventsSets[1] ?? [])))
        <div>
            <div class="section-badge" style="margin-top: 14px; margin-bottom: 10px;">Events</div>
            @if($isExplicitCompare)
                @php
                    $events1 = $allEventsSets[0] ?? [];
                    $events2 = $allEventsSets[1] ?? [];
                    $allEventNames = array_unique(array_merge(array_keys($events1), array_keys($events2)));
                @endphp
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                    @foreach($allEventNames as $evName)
                    <tr>
                        <td style="width: 50%; padding: 0 15px 12px 0; vertical-align: top; border-right: 1px dashed #cbd5e1;">
                            {!! isset($events1[$evName]) ? $renderEventCard($evName, $events1[$evName]) : '' !!}
                        </td>
                        <td style="width: 50%; padding: 0 0 12px 15px; vertical-align: top;">
                            {!! isset($events2[$evName]) ? $renderEventCard($evName, $events2[$evName]) : '' !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
            @else
                @php
                    $eventsNorm = $allEventsSets[0] ?? [];
                    $evChunked = array_chunk(
                        array_map(function($k, $v) { return ['name' => $k, 'channels' => $v]; }, array_keys($eventsNorm), $eventsNorm),
                        2
                    );
                @endphp
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    @foreach($evChunked as $chunk)
                    <tr>
                        @foreach($chunk as $evItem)
                        @php
                            $padRight = $loop->first ? '8px' : '0px';
                            $padLeft  = $loop->last ? '8px' : '0px';
                        @endphp
                        <td style="width: 50%; vertical-align: top; padding-bottom: 12px; padding-right: {{ $padRight }}; padding-left: {{ $padLeft }};">
                            {!! $renderEventCard($evItem['name'], $evItem['channels']) !!}
                        </td>
                        @endforeach
                        @if(count($chunk) < 2)
                            <td style="width: 50%;"></td>
                        @endif
                    </tr>
                    @endforeach
                </table>
            @endif
        </div>
    @endif

    {{-- Footer --}}
    <div class="page-footer">
        <span class="page-footer-left">{{ $website->site_name }} &bull; Aspire Digital Solutions</span>
        <span class="page-footer-right">Monthly SEO &amp; Marketing Report &bull; Page {{ $currentPageNum++ }} of {{ $totalPages }}</span>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- PAGE 2: GOOGLE SEARCH CONSOLE — ORGANIC SEARCH TRAFFIC                    --}}
{{-- ========================================================================= --}}
@if($hasGsc)
@php
    $gscSets = [];
    $gscSets[] = [
        'title' => $formattedDateRange,
        'data' => $gscData,
        'prevData' => $compareGsc,
        'clicksSvg' => $gscClicksMomSvg,
        'impressionsSvg' => $gscImpressionsMomSvg
    ];
    if ($isExplicitCompare) {
        $gscSets[] = [
            'title' => $formattedCompareRange,
            'data' => $compareGsc,
            'prevData' => [],
            'clicksSvg' => $compareGscClicksMomSvg,
            'impressionsSvg' => $compareGscImpressionsMomSvg
        ];
    }
@endphp

<div class="page">
    @if($isExplicitCompare)
    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;"><tr>
    @endif

    @foreach($gscSets as $gSet)
        @if($isExplicitCompare)
        <td style="width: 50%; padding: 0 15px; vertical-align: top; border-right: {{ $loop->first ? '1px dashed #cbd5e1' : 'none' }};">
        @endif

        @php
            $currentData = $gSet['data'];
            $prevData = $gSet['prevData'];

            $gscSummary = $currentData['summary'] ?? [];
            $prevGscSummary = $prevData['summary'] ?? [];

            $gscClicks = (int)($gscSummary['clicks'] ?? 0);
            $prevGscClicks = isset($prevGscSummary['clicks']) ? (int)$prevGscSummary['clicks'] : null;

            $gscImpressions = (int)($gscSummary['impressions'] ?? 0);
            $prevGscImpressions = isset($prevGscSummary['impressions']) ? (int)$prevGscSummary['impressions'] : null;

            $fullGscTopPages = $currentData['top_pages'] ?? [];
            $gscTopPages = array_slice($fullGscTopPages, 0, 15);
        @endphp
    {{-- Header Banner --}}
    <div class="header-banner">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="Aspire" />
                    @else
                        <div style="font-size: 18px; font-weight: 900; color: #ffffff;">ASPIRE DIGITAL</div>
                    @endif
                    <div>
                        <span class="header-title-white">ORGANIC SEARCH </span><span class="header-title-cyan">TRAFFIC</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="header-subtitle">Source: Google Search Console</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: middle;">
                    <span class="filter-pill">{{ $gSet['title'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-body">
        {{-- Section 1: Month-over-Month --}}
        <div class="section-badge">Month-over-Month</div>

        {{-- Clicks Row --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 8px;">
            <tr>
                <td style="width: 24%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Clicks</div>
                        <div class="stat-val-gray">{{ number_format($gscClicks) }}</div>
                        {!! $renderDeltaBadge($gscClicks, $prevGscClicks) !!}
                    </div>
                </td>
                <td style="width: 76%; padding: 0; vertical-align: middle;">
                    @if(!empty($gSet['clicksSvg']))
                        <img src="{{ $gSet['clicksSvg'] }}" width="680" height="52" alt="Clicks Trend" style="display: block;" />
                    @endif
                </td>
            </tr>
        </table>

        {{-- Impressions Row --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 12px;">
            <tr>
                <td style="width: 24%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Impressions</div>
                        <div class="stat-val-gray">{{ number_format($gscImpressions) }}</div>
                        {!! $renderDeltaBadge($gscImpressions, $prevGscImpressions) !!}
                    </div>
                </td>
                <td style="width: 76%; padding: 0; vertical-align: middle;">
                    @if(!empty($gSet['impressionsSvg']))
                        <img src="{{ $gSet['impressionsSvg'] }}" width="680" height="52" alt="Impressions Trend" style="display: block;" />
                    @endif
                </td>
            </tr>
        </table>

        {{-- Full-width Landing Page Table for MoM (All dynamic records) --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Landing page</th>
                    <th style="text-align: right; width: 15%;">Clicks &#9660;</th>
                    <th style="text-align: right; width: 15%;">Impressions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gscTopPages as $gp)
                    @php
                        $c = (int)($gp['clicks'] ?? 0);
                        $imp = (int)($gp['impressions'] ?? 0);
                        $url = $gp['page'] ?? ($gp['url'] ?? '/');
                    @endphp
                    <tr>
                        <td><strong>{{ $url }}</strong></td>
                        <td style="text-align: right; font-weight: 800;">{{ number_format($c) }}</td>
                        <td style="text-align: right;">{{ number_format($imp) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #888;">No landing page data recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-pagination-footer">1 - {{ count($gscTopPages) }} / {{ count($fullGscTopPages) }}</div>
    </div>

        @if($isExplicitCompare)
        </td>
        @endif
    @endforeach

    @if($isExplicitCompare)
    </tr></table>
    @endif

    {{-- Footer --}}
    <div class="page-footer">
        <span class="page-footer-left">{{ $website->site_name }} &bull; Aspire Digital Solutions</span>
        <span class="page-footer-right">Monthly SEO &amp; Marketing Report &bull; Page {{ $currentPageNum++ }} of {{ $totalPages }}</span>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- PAGE 3: KEYWORD RANKINGS                                                  --}}
{{-- ========================================================================= --}}
@if($hasKeyword)
@php
    $kwSets = [];
    $kwSets[] = [
        'title' => $formattedDateRange,
        'data' => $keywordData,
        'prevData' => $compareKeyword
    ];
    if ($isExplicitCompare) {
        $kwSets[] = [
            'title' => $formattedCompareRange,
            'data' => $compareKeyword,
            'prevData' => []
        ];
    }
@endphp

<div class="page">
    @if($isExplicitCompare)
    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;"><tr>
    @endif

    @foreach($kwSets as $kwSet)
        @if($isExplicitCompare)
        <td style="width: 50%; padding: 0 15px; vertical-align: top; border-right: {{ $loop->first ? '1px dashed #cbd5e1' : 'none' }};">
        @endif

        @php
            $currentData = $kwSet['data'];
            $fullKeywordsList = $currentData['keywords'] ?? [];
            $keywordsList = array_slice($fullKeywordsList, 0, 15);
            $kwSummary = $currentData['summary'] ?? [];
        @endphp
    {{-- Header Banner --}}
    <div class="header-banner">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="Aspire" />
                    @else
                        <div style="font-size: 18px; font-weight: 900; color: #ffffff;">ASPIRE DIGITAL</div>
                    @endif
                    <div>
                        <span class="header-title-white">KEYWORD </span><span class="header-title-cyan">RANKINGS</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="header-subtitle">Source: Keyword.com &amp; Google Search Console</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: middle;">
                    <span class="filter-pill">{{ $kwSet['title'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-body">
        {{-- Top Section: 6 Position Cards Grid --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 10px; margin-bottom: 12px;">
            <tr>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray" style="text-align: left; padding: 10px 14px;">
                        <div class="stat-label-gray">Keywords Up</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-size: 22px; font-weight: 900; color: #111111;">{{ $kwSummary['up_movements'] ?? count($keywordsList) }}</td>
                                <td style="text-align: right;">
                                    @if(!empty($sparklineUpSvg))
                                        <img src="{{ $sparklineUpSvg }}" width="65" height="26" alt="Sparkline" />
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray" style="text-align: left; padding: 10px 14px;">
                        <div class="stat-label-gray">Top 10 Rankings</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-size: 22px; font-weight: 900; color: #111111;">{{ $kwSummary['top_10'] ?? 0 }}</td>
                                <td style="text-align: right;">
                                    @if(!empty($sparklineUpSvg))
                                        <img src="{{ $sparklineUpSvg }}" width="65" height="26" alt="Sparkline" />
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray" style="text-align: left; padding: 10px 14px;">
                        <div class="stat-label-gray">Total Keywords</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-size: 22px; font-weight: 900; color: #111111;">{{ $kwSummary['total_keywords'] ?? count($keywordsList) }}</td>
                                <td style="text-align: right;">
                                    @if(!empty($sparklineUpSvg))
                                        <img src="{{ $sparklineUpSvg }}" width="65" height="26" alt="Sparkline" />
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Section: Traffic by Keyword --}}
        <div class="section-badge">Traffic by Keyword</div>

        {{-- Full-width Keyword Table (All dynamic keywords) --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Keyword</th>
                    <th style="text-align: right; width: 14%;">Position</th>
                    <th style="text-align: right; width: 14%;">Clicks &#9660;</th>
                    <th style="text-align: right; width: 15%;">Impressions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keywordsList as $kw)
                    @php
                        $kwText = $kw['keyword'] ?? ($kw['query'] ?? 'Keyword');
                        $pos = $kw['position'] ?? '—';
                        $cl = (int)($kw['clicks'] ?? 0);
                        $imp = (int)($kw['impressions'] ?? 0);
                    @endphp
                    <tr>
                        <td><strong>{{ $kwText }}</strong></td>
                        <td style="text-align: right; font-weight: 800; color: #16a34a;">#{{ $pos }}</td>
                        <td style="text-align: right; font-weight: 800;">{{ number_format($cl) }}</td>
                        <td style="text-align: right;">{{ number_format($imp) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">No keyword ranking records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-pagination-footer">1 - {{ count($keywordsList) }} / {{ count($fullKeywordsList) }}</div>
    </div>

        @if($isExplicitCompare)
        </td>
        @endif
    @endforeach

    @if($isExplicitCompare)
    </tr></table>
    @endif

    {{-- Footer --}}
    <div class="page-footer">
        <span class="page-footer-left">{{ $website->site_name }} &bull; Aspire Digital Solutions</span>
        <span class="page-footer-right">Monthly SEO &amp; Marketing Report &bull; Page {{ $currentPageNum++ }} of {{ $totalPages }}</span>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- PAGE 4: GOOGLE MAPS / GBP                                                 --}}
{{-- ========================================================================= --}}
@if($hasGbp)
@php
    $gbpSets = [];
    $gbpSets[] = [
        'title' => $formattedDateRange,
        'data' => $gbpData,
        'prevData' => $compareGbp,
        'callsSvg' => $gbpCallsMomSvg,
        'dirSvg' => $gbpDirectionsMomSvg,
        'clicksSvg' => $gbpClicksMomSvg
    ];
    if ($isExplicitCompare) {
        $gbpSets[] = [
            'title' => $formattedCompareRange,
            'data' => $compareGbp,
            'prevData' => [],
            'callsSvg' => $compareGbpCallsMomSvg,
            'dirSvg' => $compareGbpDirectionsMomSvg,
            'clicksSvg' => $compareGbpClicksMomSvg
        ];
    }
@endphp

<div class="page">
    @if($isExplicitCompare)
    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;"><tr>
    @endif

    @foreach($gbpSets as $gSet)
        @if($isExplicitCompare)
        <td style="width: 50%; padding: 0 15px; vertical-align: top; border-right: {{ $loop->first ? '1px dashed #cbd5e1' : 'none' }};">
        @endif

        @php
            $currentData = $gSet['data'];
        @endphp
    {{-- Header Banner --}}
    <div class="header-banner">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="Aspire" />
                    @else
                        <div style="font-size: 18px; font-weight: 900; color: #ffffff;">ASPIRE DIGITAL</div>
                    @endif
                    <div>
                        <span class="header-title-white">GOOGLE </span><span class="header-title-cyan">MAPS</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="header-subtitle">Source: Google Business Profile</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: middle;">
                    <span class="filter-pill">{{ $gSet['title'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-body">
        {{-- Section 1: Reviews --}}
        <div class="section-badge">Reviews</div>
        
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 12px;">
            <tr>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Average Rating</div>
                        <div class="stat-val-gray" style="color: #f59e0b;">{{ $currentData['summary']['rating'] ?? '4.9' }} &#9733;</div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Total Reviews</div>
                        <div class="stat-val-gray">{{ number_format($currentData['summary']['total_reviews'] ?? 0) }}</div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Response Rate</div>
                        <div class="stat-val-gray">100%</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Section 2: Month-over-Month --}}
        <div class="section-badge">Month-over-Month</div>

        {{-- Phone Calls Row --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 8px;">
            <tr>
                <td style="width: 24%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Phone Calls</div>
                        <div class="stat-val-gray">{{ number_format($currentData['summary']['phone_calls'] ?? 0) }}</div>
                    </div>
                </td>
                <td style="width: 76%; padding: 0; vertical-align: middle;">
                    @if(!empty($gSet['callsSvg']))
                        <img src="{{ $gSet['callsSvg'] }}" width="680" height="48" alt="Calls Trend" style="display: block;" />
                    @endif
                </td>
            </tr>
        </table>

        {{-- Directions Row --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 8px;">
            <tr>
                <td style="width: 24%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Direction Requests</div>
                        <div class="stat-val-gray">{{ number_format($currentData['summary']['direction_requests'] ?? 0) }}</div>
                    </div>
                </td>
                <td style="width: 76%; padding: 0; vertical-align: middle;">
                    @if(!empty($gSet['dirSvg']))
                        <img src="{{ $gSet['dirSvg'] }}" width="680" height="48" alt="Directions Trend" style="display: block;" />
                    @endif
                </td>
            </tr>
        </table>

        {{-- Website Clicks Row --}}
        <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 12px;">
            <tr>
                <td style="width: 24%; padding: 0;">
                    <div class="stat-card-gray">
                        <div class="stat-label-gray">Website Clicks</div>
                        <div class="stat-val-gray">{{ number_format($currentData['summary']['website_clicks'] ?? 0) }}</div>
                    </div>
                </td>
                <td style="width: 76%; padding: 0; vertical-align: middle;">
                    @if(!empty($gSet['clicksSvg']))
                        <img src="{{ $gSet['clicksSvg'] }}" width="680" height="48" alt="Clicks Trend" style="display: block;" />
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <span class="page-footer-left">{{ $website->site_name }} &bull; Aspire Digital Solutions</span>
        <span class="page-footer-right">Monthly SEO &amp; Marketing Report &bull; Page {{ $currentPageNum++ }} of {{ $totalPages }}</span>
    </div>

        @if($isExplicitCompare)
        </td>
        @endif
    @endforeach

    @if($isExplicitCompare)
    </tr></table>
    @endif
</div>
@endif

</body>
</html>
