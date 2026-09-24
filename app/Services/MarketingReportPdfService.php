<?php

namespace App\Services;

use App\Mail\MarketingReportMail;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use App\Modules\Core\Activity\Models\ActivityLog;
use App\Modules\Core\Activity\Models\EmailLog;
use App\Traits\LoadsMarketingReports;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MarketingReportPdfService
{
    use LoadsMarketingReports;

    /**
     * Get Base64 encoded Aspire Logo for DomPDF rendering.
     * Uses pure vector SVG paths so DomPDF renders crisp vectors with zero PHP GD extension dependencies.
     */
    public function getLogoBase64(): string
    {
        $svgPath = public_path('Aspire_Logo-01-White-2.svg');
        if (file_exists($svgPath)) {
            $data = file_get_contents($svgPath);
            return 'data:image/svg+xml;base64,' . base64_encode($data);
        }

        $fallbackSvg = public_path('aspire_logo.svg');
        if (file_exists($fallbackSvg)) {
            $data = file_get_contents($fallbackSvg);
            return 'data:image/svg+xml;base64,' . base64_encode($data);
        }

        return '';
    }

    /**
     * Prepare complete aggregated report data for all connected integrations.
     */
    public function prepareReportData(Client $client, Website $website, string $dateFrom, string $dateTo, ?string $compareDateFrom = null, ?string $compareDateTo = null): array
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->compareDateFrom = $compareDateFrom ?? '';
        $this->compareDateTo = $compareDateTo ?? '';

        $userName = Str::slug(Str::lower($client->user->name ?? 'client'));
        $emailParts = explode('@', $client->user->email ?? '');
        $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
        $clientFolder = "{$userName}-{$emailPrefix}";

        $websiteFolder = Str::slug(Str::lower($website->site_name));
        if (empty($websiteFolder)) {
            $websiteFolder = 'site-' . $website->id;
        }

        // Check which integrations are active
        $integrations = WebsiteIntegration::where('website_id', $website->id)
            ->get()
            ->keyBy('integration_type');

        $hasGa4 = $integrations->has('ga4');
        $hasGsc = $integrations->has('gsc');
        $hasKeyword = $integrations->has('keyword');
        $hasYoutube = $integrations->has('youtube');
        $hasGbp = $integrations->has('gbp');

        // Load data
        $ga4Data = $hasGa4 ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $dateFrom, $dateTo) : [];
        $gscData = $hasGsc ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $dateFrom, $dateTo) : [];
        $keywordData = $hasKeyword ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'keyword', $dateFrom, $dateTo) : [];
        $youtubeData = $hasYoutube ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'youtube', $dateFrom, $dateTo) : [];
        $gbpData = $hasGbp ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gbp', $dateFrom, $dateTo) : [];

        // Comparison Data (MoM / previous period)
        $compareGa4 = [];
        $compareGsc = [];
        $compareKeyword = [];
        $compareYoutube = [];

        // If comparison dates were not explicitly passed, compute prior period of same length for MoM
        if (empty($this->compareDateFrom) || empty($this->compareDateTo)) {
            try {
                $start = Carbon::parse($dateFrom);
                $end = Carbon::parse($dateTo);
                $diffDays = $start->diffInDays($end) + 1;
                $computedCompareTo = $start->copy()->subDay()->format('Y-m-d');
                $computedCompareFrom = $start->copy()->subDays($diffDays)->format('Y-m-d');
                
                $compareGa4 = $hasGa4 ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $computedCompareFrom, $computedCompareTo) : [];
                $compareGsc = $hasGsc ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $computedCompareFrom, $computedCompareTo) : [];
                $compareKeyword = $hasKeyword ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'keyword', $computedCompareFrom, $computedCompareTo) : [];
                $compareYoutube = $hasYoutube ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'youtube', $computedCompareFrom, $computedCompareTo) : [];
                $this->compareDateFrom = $computedCompareFrom;
                $this->compareDateTo = $computedCompareTo;
            } catch (\Exception $e) {
                // ignore
            }
        } else {
            $compareGa4 = $hasGa4 ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $this->compareDateFrom, $this->compareDateTo) : [];
            $compareGsc = $hasGsc ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $this->compareDateFrom, $this->compareDateTo) : [];
            $compareKeyword = $hasKeyword ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'keyword', $this->compareDateFrom, $this->compareDateTo) : [];
            $compareYoutube = $hasYoutube ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'youtube', $this->compareDateFrom, $this->compareDateTo) : [];
        }

        // Year-over-Year (YoY) Prior Period (1 year prior)
        $yoyGa4 = [];
        $yoyGsc = [];
        try {
            $yoyFrom = Carbon::parse($dateFrom)->subYear()->format('Y-m-d');
            $yoyTo = Carbon::parse($dateTo)->subYear()->format('Y-m-d');
            $yoyGa4 = $hasGa4 ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $yoyFrom, $yoyTo) : [];
            $yoyGsc = $hasGsc ? $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $yoyFrom, $yoyTo) : [];
        } catch (\Exception $e) {
            // ignore
        }

        // Generate Charts for PDF
        $donutChartSvg = '';
        $channelTimeSeriesSvg = '';
        if ($hasGa4 && !empty($ga4Data)) {
            $channelsList = $ga4Data['traffic_sources'] ?? [];
            $donutChartSvg = $this->generateDonutChartSvg($channelsList, 290, 125);
            $channelTimeSeriesSvg = $this->generateChannelTimeSeriesSvg($ga4Data['daily_traffic'] ?? [], $channelsList, 380, 125);
        }

        // GSC Metric Trend Lines
        $gscClicksMomSvg = '';
        $gscImpressionsMomSvg = '';
        $gscClicksYoySvg = '';
        $gscImpressionsYoySvg = '';
        if ($hasGsc && !empty($gscData)) {
            $gscClicksMomSvg = $this->generateMetricTrendLineSvg($gscData['daily_traffic'] ?? [], $compareGsc['daily_traffic'] ?? [], 'clicks', 400, 48, '#4ea6b7');
            $gscImpressionsMomSvg = $this->generateMetricTrendLineSvg($gscData['daily_traffic'] ?? [], $compareGsc['daily_traffic'] ?? [], 'impressions', 400, 48, '#4ea6b7');
            
            $yoyDailyGsc = !empty($yoyGsc['daily_traffic']) ? $yoyGsc['daily_traffic'] : ($compareGsc['daily_traffic'] ?? []);
            $gscClicksYoySvg = $this->generateMetricTrendLineSvg($gscData['daily_traffic'] ?? [], $yoyDailyGsc, 'clicks', 400, 48, '#4ea6b7');
            $gscImpressionsYoySvg = $this->generateMetricTrendLineSvg($gscData['daily_traffic'] ?? [], $yoyDailyGsc, 'impressions', 400, 48, '#4ea6b7');
        }

        $sparklineUpSvg = $this->generateSparklineSvg('up', 65, 28);
        $sparklineDownSvg = $this->generateSparklineSvg('down', 65, 28);

        // Format nice date strings
        try {
            $formattedDateRange = Carbon::parse($dateFrom)->format('M j, Y') . ' - ' . Carbon::parse($dateTo)->format('M j, Y');
            $formattedCompareRange = !empty($this->compareDateFrom) && !empty($this->compareDateTo) 
                ? Carbon::parse($this->compareDateFrom)->format('M j, Y') . ' - ' . Carbon::parse($this->compareDateTo)->format('M j, Y') 
                : '';
        } catch (\Exception $e) {
            $formattedDateRange = "{$dateFrom} - {$dateTo}";
            $formattedCompareRange = "{$this->compareDateFrom} - {$this->compareDateTo}";
        }

        return [
            'client' => $client,
            'website' => $website,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'compareDateFrom' => $this->compareDateFrom,
            'compareDateTo' => $this->compareDateTo,
            'formattedDateRange' => $formattedDateRange,
            'formattedCompareRange' => $formattedCompareRange,
            'hasGa4' => $hasGa4 && !empty($ga4Data),
            'hasGsc' => $hasGsc && !empty($gscData),
            'hasKeyword' => $hasKeyword && !empty($keywordData),
            'hasYoutube' => $hasYoutube && !empty($youtubeData),
            'hasGbp' => $hasGbp && !empty($gbpData),
            'ga4Data' => $ga4Data,
            'gscData' => $gscData,
            'keywordData' => $keywordData,
            'youtubeData' => $youtubeData,
            'gbpData' => $gbpData,
            'compareGa4' => $compareGa4,
            'compareGsc' => $compareGsc,
            'compareKeyword' => $compareKeyword,
            'compareYoutube' => $compareYoutube,
            'yoyGa4' => $yoyGa4,
            'yoyGsc' => $yoyGsc,
            'donutChartSvg' => $donutChartSvg,
            'channelTimeSeriesSvg' => $channelTimeSeriesSvg,
            'gscClicksMomSvg' => $gscClicksMomSvg,
            'gscImpressionsMomSvg' => $gscImpressionsMomSvg,
            'gscClicksYoySvg' => $gscClicksYoySvg,
            'gscImpressionsYoySvg' => $gscImpressionsYoySvg,
            'sparklineUpSvg' => $sparklineUpSvg,
            'sparklineDownSvg' => $sparklineDownSvg,
            'logoBase64' => $this->getLogoBase64(),
        ];
    }

    /**
     * Generate SVG Donut Chart with slice percentage labels and right-aligned legend.
     */
    public function generateDonutChartSvg(array $channels, int $width = 290, int $height = 125): string
    {
        $palette = ['#4ea6b7', '#f39c12', '#8e44ad', '#f1c40f', '#3498db', '#1abc9c', '#e67e22', '#e74c3c', '#95a5a6', '#bdc3c7'];
        $slices = [];
        $idx = 0;

        foreach ($channels as $ch) {
            $sCount = (int)($ch['sessions'] ?? 0);
            if ($sCount <= 0) continue;
            $rawName = $ch['channel'] ?? ($ch['source_medium'] ?? ($ch['name'] ?? 'Other'));
            
            // Format nice label
            if (str_contains($rawName, '(direct)')) $name = 'Direct';
            elseif (str_contains($rawName, 'google / organic')) $name = 'Organic Search';
            elseif (str_contains($rawName, 'bing / organic')) $name = 'Organic Search (Bing)';
            elseif (str_contains($rawName, '/ referral')) $name = 'Referral';
            elseif (str_contains($rawName, '/ cpc') || str_contains($rawName, '/ paid')) $name = 'Paid Search';
            elseif (str_contains($rawName, 'facebook') || str_contains($rawName, 'instagram')) $name = 'Social';
            else $name = ucwords(str_replace(['/', '_', '-'], ' ', $rawName));

            $slices[] = [
                'name' => $name,
                'value' => $sCount,
                'color' => $palette[$idx % count($palette)],
            ];
            $idx++;
            if (count($slices) >= 8) break;
        }

        if (empty($slices)) {
            $slices[] = ['name' => 'Organic Search', 'value' => 70, 'color' => '#4ea6b7'];
            $slices[] = ['name' => 'Direct', 'value' => 20, 'color' => '#f39c12'];
            $slices[] = ['name' => 'Referral', 'value' => 10, 'color' => '#3498db'];
        }

        $total = array_sum(array_column($slices, 'value')) ?: 1;
        $cx = 62;
        $cy = 62;
        $r = 54;
        $innerR = 29;

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\">\n";
        $currentAngle = -90;
        $percentageLabels = [];

        foreach ($slices as $slice) {
            $val = $slice['value'];
            if ($val <= 0) continue;
            $portion = $val / $total;
            $pct = round($portion * 100, 1);
            $angle = $portion * 360;
            if ($angle >= 360) $angle = 359.99;

            $startAngle = $currentAngle;
            $endAngle = $currentAngle + $angle;

            $x1 = $cx + $r * cos(deg2rad($startAngle));
            $y1 = $cy + $r * sin(deg2rad($startAngle));
            $x2 = $cx + $r * cos(deg2rad($endAngle));
            $y2 = $cy + $r * sin(deg2rad($endAngle));

            $ix1 = $cx + $innerR * cos(deg2rad($endAngle));
            $iy1 = $cy + $innerR * sin(deg2rad($endAngle));
            $ix2 = $cx + $innerR * cos(deg2rad($startAngle));
            $iy2 = $cy + $innerR * sin(deg2rad($startAngle));

            $largeArc = $angle > 180 ? 1 : 0;
            $color = $slice['color'];

            $path = "M {$x1} {$y1} A {$r} {$r} 0 {$largeArc} 1 {$x2} {$y2} L {$ix1} {$iy1} A {$innerR} {$innerR} 0 {$largeArc} 0 {$ix2} {$iy2} Z";
            $svg .= "  <path d=\"{$path}\" fill=\"{$color}\" stroke=\"#ffffff\" stroke-width=\"1.2\" />\n";

            // If slice is large enough, remember mid-angle to place text
            if ($pct >= 4.5) {
                $midAngle = ($startAngle + $endAngle) / 2;
                $labelR = ($r + $innerR) / 2;
                $lx = $cx + $labelR * cos(deg2rad($midAngle));
                $ly = $cy + $labelR * sin(deg2rad($midAngle)) + 2.5;
                $textColor = ($color === '#f1c40f' || $color === '#1abc9c') ? '#1e293b' : '#ffffff';
                $percentageLabels[] = "<text x=\"{$lx}\" y=\"{$ly}\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" font-weight=\"bold\" fill=\"{$textColor}\" text-anchor=\"middle\">{$pct}%</text>\n";
            }

            $currentAngle += $angle;
        }

        // Draw percentage labels on slices
        foreach ($percentageLabels as $txt) {
            $svg .= "  " . $txt;
        }

        // Draw Legend on the right
        $legX = 135;
        $legStartY = 14;
        $rowGap = 13;
        foreach ($slices as $i => $slice) {
            $ly = $legStartY + ($i * $rowGap);
            $c = $slice['color'];
            $n = htmlspecialchars($slice['name']);
            $svg .= "  <circle cx=\"{$legX}\" cy=\"{$ly}\" r=\"3\" fill=\"{$c}\" />\n";
            $svg .= "  <text x=\"" . ($legX + 8) . "\" y=\"" . ($ly + 3) . "\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"7.5\" font-weight=\"600\" fill=\"#334155\">{$n}</text>\n";
        }

        $svg .= "</svg>";
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate multi-line graph for daily traffic by channel.
     */
    public function generateChannelTimeSeriesSvg(array $dailyTraffic, array $channels, int $width = 380, int $height = 125): string
    {
        $padL = 28;
        $padR = 8;
        $padT = 24;
        $padB = 20;

        $plotW = $width - $padL - $padR;
        $plotH = $height - $padT - $padB;

        $palette = ['#4ea6b7', '#f39c12', '#8e44ad', '#3498db', '#e74c3c'];
        $seriesList = [];
        $xLabels = [];

        $dCount = count($dailyTraffic);
        if ($dCount < 2) return '';

        // Extract dates and total daily sessions
        $dailyTotals = [];
        foreach ($dailyTraffic as $d) {
            $rawDate = (string)($d['date'] ?? '');
            if (strlen($rawDate) === 8) {
                $monthNum = (int)substr($rawDate, 4, 2);
                $dayNum = (int)substr($rawDate, 6, 2);
                $monthName = date('M', mktime(0,0,0,$monthNum,1));
                $xLabels[] = "{$monthName} {$dayNum}";
            } else {
                $xLabels[] = substr($rawDate, 5);
            }
            $dailyTotals[] = (int)($d['sessions'] ?? ($d['pageviews'] ?? 0));
        }

        $totalSessions = array_sum(array_column($channels, 'sessions')) ?: array_sum($dailyTotals) ?: 1;
        $topCh = array_slice($channels, 0, 4);

        $maxVal = 1;
        foreach ($dailyTotals as $t) {
            if ($t > $maxVal) $maxVal = $t;
        }
        $maxVal = max(10, (int)ceil($maxVal * 1.15));

        $idx = 0;
        foreach ($topCh as $ch) {
            $chSessions = (int)($ch['sessions'] ?? 0);
            $ratio = $totalSessions > 0 ? ($chSessions / $totalSessions) : 0.25;
            $chPoints = [];
            foreach ($dailyTotals as $val) {
                $chPoints[] = max(0, round($val * $ratio));
            }

            $rawName = $ch['channel'] ?? ($ch['source_medium'] ?? ($ch['name'] ?? 'Channel'));
            if (str_contains($rawName, '(direct)')) $cleanName = 'Direct';
            elseif (str_contains($rawName, 'google / organic')) $cleanName = 'Organic Search';
            elseif (str_contains($rawName, 'bing / organic')) $cleanName = 'Bing Search';
            elseif (str_contains($rawName, '/ referral')) $cleanName = 'Referral';
            elseif (str_contains($rawName, '/ cpc') || str_contains($rawName, '/ paid')) $cleanName = 'Paid Search';
            else $cleanName = ucwords(str_replace(['/', '_', '-'], ' ', $rawName));

            $seriesList[] = [
                'name' => $cleanName,
                'color' => $palette[$idx % count($palette)],
                'data' => $chPoints,
            ];
            $idx++;
        }

        if (empty($seriesList)) {
            $seriesList[] = [
                'name' => 'Sessions',
                'color' => '#4ea6b7',
                'data' => $dailyTotals,
            ];
        }

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\">\n";

        // Top Legend (Horizontal)
        $curLegX = $padL;
        foreach ($seriesList as $s) {
            $col = $s['color'];
            $sName = htmlspecialchars($s['name']);
            $svg .= "  <line x1=\"{$curLegX}\" y1=\"10\" x2=\"" . ($curLegX + 12) . "\" y2=\"10\" stroke=\"{$col}\" stroke-width=\"2\" />\n";
            $svg .= "  <text x=\"" . ($curLegX + 16) . "\" y=\"12.5\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" font-weight=\"600\" fill=\"#64748b\">{$sName}</text>\n";
            $curLegX += strlen($sName) * 5.2 + 28;
        }

        // Grid lines (4 levels)
        for ($i = 0; $i <= 3; $i++) {
            $y = $padT + ($plotH / 3) * $i;
            $val = round($maxVal - ($maxVal / 3) * $i);
            $valFormatted = $val >= 1000 ? round($val / 1000, 1) . 'k' : $val;

            $svg .= "  <line x1=\"{$padL}\" y1=\"{$y}\" x2=\"" . ($width - $padR) . "\" y2=\"{$y}\" stroke=\"#edf2f7\" stroke-width=\"0.8\" />\n";
            $svg .= "  <text x=\"" . ($padL - 4) . "\" y=\"" . ($y + 2.5) . "\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" fill=\"#94a3b8\" text-anchor=\"end\">{$valFormatted}</text>\n";
        }

        // X-axis dates
        $step = max(1, (int)floor($dCount / 7));
        for ($j = 0; $j < $dCount; $j += $step) {
            $x = $padL + ($j / max(1, $dCount - 1)) * $plotW;
            $lbl = $xLabels[$j] ?? '';
            $svg .= "  <text x=\"{$x}\" y=\"" . ($height - 5) . "\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" fill=\"#94a3b8\" text-anchor=\"middle\">{$lbl}</text>\n";
        }

        // Series Polylines
        foreach ($seriesList as $s) {
            $data = $s['data'];
            $color = $s['color'];
            $points = [];
            for ($i = 0; $i < $dCount; $i++) {
                $x = $padL + ($i / max(1, $dCount - 1)) * $plotW;
                $y = $padT + $plotH - (($data[$i] / $maxVal) * $plotH);
                $points[] = round($x, 1) . ',' . round($y, 1);
            }
            $svg .= "  <polyline points=\"" . implode(' ', $points) . "\" fill=\"none\" stroke=\"{$color}\" stroke-width=\"1.6\" stroke-linecap=\"round\" stroke-linejoin=\"round\" />\n";
        }

        $svg .= "</svg>";
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate single metric trend line graph with prior comparison dashed line.
     */
    public function generateMetricTrendLineSvg(array $currentDaily, array $compareDaily, string $metricKey, int $width = 400, int $height = 48, string $lineColor = '#4ea6b7'): string
    {
        $padL = 28;
        $padR = 8;
        $padT = 8;
        $padB = 16;

        $plotW = $width - $padL - $padR;
        $plotH = $height - $padT - $padB;

        $currData = [];
        $xLabels = [];
        foreach ($currentDaily as $d) {
            $currData[] = (int)($d[$metricKey] ?? 0);
            $rawDate = (string)($d['date'] ?? '');
            if (strlen($rawDate) === 8) {
                $monthNum = (int)substr($rawDate, 4, 2);
                $dayNum = (int)substr($rawDate, 6, 2);
                $xLabels[] = date('M', mktime(0,0,0,$monthNum,1)) . ' ' . $dayNum;
            } elseif (strlen($rawDate) === 10) {
                $xLabels[] = date('M j', strtotime($rawDate));
            } else {
                $xLabels[] = $rawDate;
            }
        }

        $compData = [];
        foreach ($compareDaily as $cd) {
            $compData[] = (int)($cd[$metricKey] ?? 0);
        }

        $cCount = count($currData);
        if ($cCount < 2) return '';

        $maxVal = 1;
        foreach ($currData as $v) if ($v > $maxVal) $maxVal = $v;
        foreach ($compData as $cv) if ($cv > $maxVal) $maxVal = $cv;
        $maxVal = max(5, (int)ceil($maxVal * 1.15));

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\">\n";

        // Grid lines (3 levels)
        for ($i = 0; $i <= 2; $i++) {
            $y = $padT + ($plotH / 2) * $i;
            $val = round($maxVal - ($maxVal / 2) * $i);
            $valFormatted = $val >= 1000 ? round($val / 1000, 1) . 'k' : $val;

            $svg .= "  <line x1=\"{$padL}\" y1=\"{$y}\" x2=\"" . ($width - $padR) . "\" y2=\"{$y}\" stroke=\"#edf2f7\" stroke-width=\"0.8\" />\n";
            $svg .= "  <text x=\"" . ($padL - 4) . "\" y=\"" . ($y + 2.5) . "\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" fill=\"#94a3b8\" text-anchor=\"end\">{$valFormatted}</text>\n";
        }

        // X-axis dates
        $step = max(1, (int)floor($cCount / 6));
        for ($j = 0; $j < $cCount; $j += $step) {
            $x = $padL + ($j / max(1, $cCount - 1)) * $plotW;
            $lbl = $xLabels[$j] ?? '';
            $svg .= "  <text x=\"{$x}\" y=\"" . ($height - 3) . "\" font-family=\"DejaVu Sans, sans-serif\" font-size=\"6.5\" fill=\"#94a3b8\" text-anchor=\"middle\">{$lbl}</text>\n";
        }

        // Comparison line (dashed grey)
        if (!empty($compData) && count($compData) >= 2) {
            $cmpCount = count($compData);
            $cmpPoints = [];
            for ($k = 0; $k < $cmpCount; $k++) {
                $x = $padL + ($k / max(1, $cmpCount - 1)) * $plotW;
                $y = $padT + $plotH - (($compData[$k] / $maxVal) * $plotH);
                $cmpPoints[] = round($x, 1) . ',' . round($y, 1);
            }
            $svg .= "  <polyline points=\"" . implode(' ', $cmpPoints) . "\" fill=\"none\" stroke=\"#b0bec5\" stroke-width=\"1.3\" stroke-dasharray=\"3 3\" stroke-linecap=\"round\" stroke-linejoin=\"round\" />\n";
        }

        // Current line (solid cyan-teal)
        $currPoints = [];
        for ($i = 0; $i < $cCount; $i++) {
            $x = $padL + ($i / max(1, $cCount - 1)) * $plotW;
            $y = $padT + $plotH - (($currData[$i] / $maxVal) * $plotH);
            $currPoints[] = round($x, 1) . ',' . round($y, 1);
        }
        $svg .= "  <polyline points=\"" . implode(' ', $currPoints) . "\" fill=\"none\" stroke=\"{$lineColor}\" stroke-width=\"1.7\" stroke-linecap=\"round\" stroke-linejoin=\"round\" />\n";

        $svg .= "</svg>";
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate sparkline for keyword cards.
     */
    public function generateSparklineSvg(string $direction = 'up', int $width = 65, int $height = 28): string
    {
        $color = $direction === 'up' ? '#10b981' : '#ef4444';
        $d = $direction === 'up' 
            ? "M 4,22 C 18,22 26,16 38,12 C 48,9 56,5 62,3" 
            : "M 4,4 C 18,4 26,10 38,15 C 48,19 56,22 62,25";

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\">\n";
        $svg .= "  <path d=\"{$d}\" fill=\"none\" stroke=\"{$color}\" stroke-width=\"2\" stroke-linecap=\"round\" />\n";
        $svg .= "</svg>";

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate the DomPDF object for the report.
     */
    public function generatePdf(Client $client, Website $website, string $dateFrom, string $dateTo, ?string $compareDateFrom = null, ?string $compareDateTo = null): \Barryvdh\DomPDF\PDF
    {
        $reportData = $this->prepareReportData($client, $website, $dateFrom, $dateTo, $compareDateFrom, $compareDateTo);

        $pdf = Pdf::loadView('modules.crm.marketing.pdf-marketing-report', $reportData);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 120,
        ]);

        return $pdf;
    }

    /**
     * Send the report via email with in-memory attached PDF.
     */
    public function sendReportEmail(
        Client $client,
        Website $website,
        string $dateFrom,
        string $dateTo,
        string $recipientEmail,
        string $subject,
        ?string $personalMessage = null,
        ?string $compareDateFrom = null,
        ?string $compareDateTo = null
    ): array {
        try {
            $reportData = $this->prepareReportData($client, $website, $dateFrom, $dateTo, $compareDateFrom, $compareDateTo);
            
            $pdf = $this->generatePdf($client, $website, $dateFrom, $dateTo, $compareDateFrom, $compareDateTo);
            $pdfContent = $pdf->output();

            $siteSlug = Str::slug($website->site_name ?: 'Website');
            $dateSlug = Carbon::parse($dateFrom)->format('M-Y');
            $pdfFilename = "Monthly-SEO-Report-{$siteSlug}-{$dateSlug}.pdf";

            Mail::to($recipientEmail)->send(new MarketingReportMail(
                client: $client,
                website: $website,
                reportData: $reportData,
                subject: $subject,
                personalMessage: $personalMessage,
                pdfContent: $pdfContent,
                pdfFilename: $pdfFilename
            ));

            EmailLog::create([
                'sender_id' => Auth::id() ?? 1,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'status' => 'sent',
                'report_id' => null,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id() ?? 1,
                'action' => 'email_marketing_report',
                'description' => "Emailed Monthly Marketing & SEO Report for {$website->site_name} to {$recipientEmail}",
                'meta' => [
                    'client_id' => $client->id,
                    'website_id' => $website->id,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'recipient' => $recipientEmail,
                ],
            ]);

            return ['success' => true, 'message' => "Report sent successfully to {$recipientEmail}!"];
        } catch (\Throwable $e) {
            Log::error('MarketingReportPdfService: Failed to send email: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            EmailLog::create([
                'sender_id' => Auth::id() ?? 1,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'report_id' => null,
            ]);

            return ['success' => false, 'message' => 'Failed to send report email: ' . $e->getMessage()];
        }
    }
}
