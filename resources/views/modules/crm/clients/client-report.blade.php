<div>
    @if(!isset($hideHeader) || !$hideHeader)
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <h1 class="text-base font-bold text-slate-900 dark:text-white">
                        @if($activeReportIntegrationId === 'gsc')
                            Google Search Console &mdash; Detailed Report
                        @elseif($activeReportIntegrationId === 'youtube')
                            YouTube &mdash; Detailed Report
                        @elseif($activeReportIntegrationId === 'keyword')
                            Keyword.com &mdash; Detailed Report
                        @elseif($activeReportIntegrationId === 'gtm')
                            Google Tag Manager &mdash; Container Summary
                        @elseif($activeReportIntegrationId === 'gbp')
                            Google Business Profile &mdash; Detailed Report
                        @else
                            Google Analytics 4 &mdash; Detailed Report
                        @endif
                    </h1>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5 ml-7">
                    {{ $clientName ?? '' }} &bull; 
                    @if(!empty($websiteName)) {{ $websiteName }} &bull; @endif
                    Property ID: <span class="font-semibold text-slate-600 dark:text-slate-350">{{ $activeReportPropertyId ?? '—' }}</span>
                </p>
            </div>
        </div>
        {{-- Date Range Picker, Send Report & Back Button --}}
        <div class="flex items-center gap-3">
            @include('partials.marketing-report-send-modal')

            <a href="javascript:history.back()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
            
            <div>
                @php
                    $minDateBound = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
                    $maxDateBound = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                @include('partials.ga4-date-picker', ['minDateBound' => $minDateBound, 'maxDateBound' => $maxDateBound])
            </div>
        </div>
    </div>
    @endif

    {{-- Report Content --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm p-6">
        @if (empty($activeReportData))
            <div class="py-16 text-center">
                <div wire:loading class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <div wire:loading.remove>
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-400">No data available</p>
                    <p class="text-xs text-slate-400 mt-1">Try selecting a different date range or sync your integration.</p>
                </div>
            </div>
        @else
                @if ($activeReportIntegrationId === 'gsc')
@php
            $reportSets = [];
            $baseData = $activeReportData ?? [];
            if (!empty($baseData)) {
                $reportSets[] = ['title' => (!empty($this->dateFrom) && !empty($this->dateTo)) ? \Carbon\Carbon::parse($this->dateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->dateTo)->format('M d, Y') : 'Current Period', 'data' => $baseData];
            }
            if (!empty($baseData['compare_data'])) {
                $reportSets[] = ['title' => (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) ? \Carbon\Carbon::parse($this->compareDateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->compareDateTo)->format('M d, Y') : 'Previous Period', 'data' => $baseData['compare_data']];
            }
@endphp
            
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-1-multi" class="flex flex-col lg:flex-row gap-6 mb-6">
            @else
            <div wire:key="wrapper-1-single" class="flex flex-col gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-1-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Clicks</span>
                        <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ $this->formatAbbreviated($reportDataScope['summary']['clicks'] ?? 0) }}
                        </span>
                    </div>
                    <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Impressions</span>
                        <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                            {{ $this->formatAbbreviated($reportDataScope['summary']['impressions'] ?? 0) }}
                        </span>
                    </div>
                    <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg. CTR</span>
                        <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                            {{ $reportDataScope['summary']['ctr'] ?? 0 }}%
                        </span>
                    </div>
                    <div class="p-4 bg-amber-50/20 dark:bg-amber-950/10 border border-amber-100/30 dark:border-amber-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg. Position</span>
                        <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400">
                            {{ $reportDataScope['summary']['position'] ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            </div>

            
            @if(count($reportSets) == 1)
            <div class="grid grid-cols-1 gap-6 mb-6">
            @else
            <div class="grid grid-cols-1 gap-6 mb-6">
            @endif
            <div>
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-2-multi" class="flex flex-col lg:flex-row gap-6">
            @else
            <div wire:key="wrapper-2-single" class="flex flex-col gap-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-chart-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-white border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 w-full mb-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div> Daily Traffic
                        </div>
                    </div>
                    <div class="h-64" wire:ignore wire:key="line-gsc-traffic-{{ $idx }}-{{ md5(json_encode($activeReportData)) }}" x-data="{
                        chart: null,
                        init() {
                            if (!this.$refs.canvas) return;
                            if (this.chart) {
                                this.chart.destroy();
                            }
                            const ctx = this.$refs.canvas.getContext('2d');
                            let rawData = {{ json_encode($idx === 0 ? $activeReportData : ($activeReportData['compare_data'] ?? null)) }};
                            let traffic = (rawData && rawData.daily_traffic) ? rawData.daily_traffic : [];
                            let labels = traffic.map(t => t.date);
                            let clicks = traffic.map(t => t.clicks);
                            let impressions = traffic.map(t => t.impressions);
                            
                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: 'Clicks',
                                            data: clicks,
                                            borderColor: '#4f46e5',
                                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4
                                        },
                                        {
                                            label: 'Impressions',
                                            data: impressions,
                                            borderColor: '#0ea5e9',
                                            backgroundColor: 'rgba(14, 165, 233, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4,
                                            yAxisID: 'y1'
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { display: true, position: 'bottom' } },
                                    scales: {
                                        y: { beginAtZero: true, position: 'left' },
                                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } },
                                        x: { grid: { display: false } }
                                    }
                                }
                            });
                        }
                    }">
                        <canvas x-ref="canvas" id="line-gsc-traffic-{{ $idx }}"></canvas>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            </div>

            
            @if(count($reportSets) == 1)
            <div wire:key="wrapper-17-single" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @else
            <div wire:key="wrapper-17-multi" class="grid grid-cols-1 gap-6 mb-6">
            @endif
            
            <div>
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-3-multi" class="flex flex-col lg:flex-row gap-6">
            @else
            <div wire:key="wrapper-3-single" class="flex flex-col gap-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-2-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Search Queries</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">Query</th>
                                    <th class="py-2 text-right font-bold">Clicks</th>
                                    <th class="py-2 text-right font-bold">Imp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($reportDataScope['top_queries'] ?? [], 0, 10) as $query) <tr wire:key="query-{{ md5(json_encode($query)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                        <td class="py-2.5 font-bold truncate max-w-[150px]" title="{{ $query['query'] }}">{{ $query['query'] }}</td>
                                        <td class="py-2.5 text-right font-bold">{{ number_format($query['clicks'] ?? 0) }}</td>
                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($query['impressions'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            </div>

            
            <div>
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-4-multi" class="flex flex-col lg:flex-row gap-6">
            @else
            <div wire:key="wrapper-4-single" class="flex flex-col gap-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-3-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Ranking URLs</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">URL</th>
                                    <th class="py-2 text-right font-bold">Clicks</th>
                                    <th class="py-2 text-right font-bold">Imp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($reportDataScope['top_pages'] ?? [], 0, 10) as $page) <tr wire:key="page-{{ md5(json_encode($page)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                        <td class="py-2.5 font-bold truncate max-w-[150px] text-indigo-600" title="{{ $page['page'] }}">
                                            <a href="{{ $page['page'] }}" target="_blank" class="hover:underline">{{ str_replace('https://', '', $page['page']) }}</a>
                                        </td>
                                        <td class="py-2.5 text-right font-bold">{{ number_format($page['clicks'] ?? 0) }}</td>
                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($page['impressions'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            
            @if(count($reportSets) == 1)
            <div wire:key="wrapper-18-single" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @else
            <div wire:key="wrapper-18-multi" class="grid grid-cols-1 gap-6 mb-6">
            @endif
            
            <div>
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-5-multi" class="flex flex-col lg:flex-row gap-6">
            @else
            <div wire:key="wrapper-5-single" class="flex flex-col gap-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-4-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Device Breakdown</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">Device</th>
                                    <th class="py-2 text-right font-bold">Clicks</th>
                                    <th class="py-2 text-right font-bold">Imp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reportDataScope['devices'] ?? [] as $device)
                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                        <td class="py-2.5 font-bold capitalize">{{ strtolower($device['device']) }}</td>
                                        <td class="py-2.5 text-right font-bold">{{ number_format($device['clicks'] ?? 0) }}</td>
                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($device['impressions'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            </div>

            
            <div>
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-6-multi" class="flex flex-col lg:flex-row gap-6">
            @else
            <div wire:key="wrapper-6-single" class="flex flex-col gap-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="gsc-section-5-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Countries</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">Country</th>
                                    <th class="py-2 text-right font-bold">Clicks</th>
                                    <th class="py-2 text-right font-bold">Imp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($reportDataScope['countries'] ?? [], 0, 10) as $country)
                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                        <td class="py-2.5 font-bold capitalize">{{ strtolower(strlen($country['country']) === 3 ? $country['country'] : $country['country']) }}</td>
                                        <td class="py-2.5 text-right font-bold">{{ number_format($country['clicks'] ?? 0) }}</td>
                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($country['impressions'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>
        @elseif ($activeReportIntegrationId === 'gbp')
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Views</span>
                    <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                        {{ number_format($activeReportData['summary']['views'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/30 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Searches</span>
                    <span class="text-lg font-extrabold text-slate-700 dark:text-slate-200">
                        {{ number_format($activeReportData['summary']['searches'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/30 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Interactions</span>
                    <span class="text-lg font-extrabold text-slate-700 dark:text-slate-200">
                        {{ number_format($activeReportData['summary']['interactions'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-emerald-50/30 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Calls</span>
                    <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($activeReportData['summary']['calls'] ?? 0) }}
                    </span>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mb-6">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Google Business Profile Data Dump</h4>
                <div class="overflow-x-auto">
                    <pre class="text-[10px] text-slate-600 dark:text-slate-400 whitespace-pre-wrap">{{ json_encode($activeReportData, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @elseif ($activeReportIntegrationId === 'ga4' || $activeReportIntegrationId === 'overview')
@php
            
            $reportSets = [];
            $baseData = $activeReportData ?? [];
            
            $primaryTitle = (isset($dateFrom) && isset($dateTo)) 
                ? \Carbon\Carbon::parse($dateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($dateTo)->format('M d, Y') 
                : 'Primary Date Range';
                
            $reportSets[] = [
                'title' => $primaryTitle,
                'data' => $baseData,
            ];
            
            $hasCompare = !empty($baseData['compare_data']);
            
            if ($hasCompare) {
                $compareTitle = (isset($compareDateFrom) && isset($compareDateTo) && $compareDateFrom && $compareDateTo) 
                    ? \Carbon\Carbon::parse($compareDateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($compareDateTo)->format('M d, Y') 
                    : 'Compare Date Range';
                    
                $reportSets[] = [
                    'title' => $compareTitle,
                    'data' => $baseData['compare_data']
                ];
            }
            
            
            $calculateDelta = function($val1, $val2, $format) {
                $v1 = (float)$val1;
                $v2 = (float)$val2;
                if ($v2 == 0) return ['value' => $v1 > 0 ? '100%' : '0%', 'trend' => $v1 > 0 ? 'up' : 'flat'];
                
                $diff = $v1 - $v2;
                if ($diff == 0) return ['value' => $format === 'absolute' ? '0' : '0%', 'trend' => 'flat'];
                
                $trend = $diff > 0 ? 'up' : 'down';
                if ($format === 'absolute') {
                    return ['value' => number_format(abs($diff)), 'trend' => $trend];
                } else {
                    $pct = round((abs($diff) / $v2) * 100, 1);
                    return ['value' => $pct . '%', 'trend' => $trend];
                }
            };
            
            $findPreviousValue = function($array, $keyField, $keyValue, $valueField) {
                if (!is_array($array)) return 0;
                foreach ($array as $item) {
                    if (isset($item[$keyField]) && strtolower($item[$keyField]) == strtolower($keyValue)) {
                        return $item[$valueField] ?? 0;
                    }
                }
                return 0;
            };

            $renderBadge = function($currentVal, $previousVal, $invert = false) use ($calculateDelta) {
                $delta = $calculateDelta($currentVal, $previousVal, 'percentage');
                if ($delta['trend'] === 'flat') return '';
                
                $isUp = $delta['trend'] === 'up';
                $isGood = $invert ? !$isUp : $isUp;
                
                $colorClass = $isGood ? 'text-emerald-500' : 'text-rose-500';
                $iconClass = $isUp ? 'transform rotate-180' : '';
                
                return '<div class="text-[10px] ' . $colorClass . ' font-bold flex items-center justify-end gap-0.5 mt-0.5">
                            <svg class="w-3 h-3 ' . $iconClass . '" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            ' . $delta['value'] . '
                        </div>';
            };
$format = $compareFormat ?? 'percentage';
        @endphp


@if(count($reportSets) > 1)
<div wire:key="wrapper-7-multi" class="flex flex-col lg:flex-row gap-6">
@else
<div wire:key="wrapper-7-single" class="flex flex-col gap-6">
@endif
@foreach($reportSets as $idx => $rSet)
<div wire:key="ga4-section-1-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Active Users</span>
                <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                    {{ $this->formatAbbreviated($reportDataScope['overall_summary']['active_users'] ?? 0) }}
                </span>
            </div>
            <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Page Views</span>
                <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                    {{ $this->formatAbbreviated($reportDataScope['overall_summary']['pageviews'] ?? 0) }}
                </span>
            </div>
            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Sessions</span>
                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                    {{ $this->formatAbbreviated($reportDataScope['overall_summary']['sessions'] ?? 0) }}
                </span>
            </div>
            <div class="p-4 bg-amber-50/20 dark:bg-amber-950/10 border border-amber-100/30 dark:border-amber-900/20 rounded-2xl">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Bounce Rate</span>
                <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400">
                    {{ $reportDataScope['overall_summary']['bounce_rate'] ?? '—' }}
                </span>
            </div>
            <div class="p-4 bg-rose-50/20 dark:bg-rose-950/10 border border-rose-100/30 dark:border-rose-900/20 rounded-2xl col-span-2 md:col-span-1">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Session Duration</span>
                <span class="text-lg font-extrabold text-rose-600 dark:text-rose-400">
                    {{ $reportDataScope['overall_summary']['avg_session_duration'] ?? '—' }}
                </span>
            </div>
        </div>
</div>
        @endforeach
</div>


@if(count($reportSets) == 1)
<div wire:key="wrapper-19-single" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
@else
<div wire:key="wrapper-19-multi" class="grid grid-cols-1 gap-6 mb-6">
@endif

    <!-- Left: Traffic by Channel / Key Events Distribution -->
    <div>
        @if(count($reportSets) > 1)
        <div wire:key="wrapper-8-multi" class="flex flex-col lg:flex-row gap-6">
        @else
        <div wire:key="wrapper-8-single" class="flex flex-col gap-6">
        @endif
        @foreach($reportSets as $idx => $rSet)
        <div wire:key="ga4-section-2-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
            <div class="bg-white border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 w-full mb-6 rounded-2xl">
                @php
                    $donutTitle = !empty($reportDataScope['key_events']) ? 'Key Events Distribution' : 'Traffic by Channel';
                    $donutItems = !empty($reportDataScope['key_events'])
                        ? array_map(fn($e) => ['label' => str_replace('_', ' ', $e['event_name']), 'value' => $e['event_count']], array_slice($reportDataScope['key_events'], 0, 8))
                        : array_map(fn($s) => ['label' => $s['source_medium'], 'value' => $s['sessions']], array_slice($reportDataScope['traffic_sources'] ?? [], 0, 8));
                    $donutTotal = array_sum(array_column($donutItems, 'value'));
                    $donutTotalLabel = !empty($reportDataScope['key_events']) ? 'Key Events' : 'Sessions';
                @endphp
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-400"></div> {{ $donutTitle }}
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-8">
                    <div wire:key="stable-donut-{{ $idx }}" class="flex justify-center w-full">
                        <div class="relative w-48 h-48 shrink-0" wire:ignore wire:key="donut-key-events-{{ $idx }}-{{ md5(json_encode($activeReportData)) }}" x-data="{
                            chart: null,
                            init() {
                                if (!this.$refs.donutCanvas) return;
                                if (this.chart) {
                                    this.chart.destroy();
                                }
                                const ctx = this.$refs.donutCanvas.getContext('2d');
                                let rawData = {{ json_encode($idx === 0 ? $activeReportData : ($activeReportData['compare_data'] ?? null)) }};
                                let items = [];
                                let usingFallback = false;
                                if (rawData && rawData.key_events && rawData.key_events.length > 0) {
                                    items = rawData.key_events.map(e => ({ label: e.event_name, value: e.event_count }));
                                } else if (rawData && rawData.traffic_sources && rawData.traffic_sources.length > 0) {
                                    items = rawData.traffic_sources.slice(0, 8).map(e => ({ label: e.source_medium, value: e.sessions }));
                                    usingFallback = true;
                                } else {
                                    items = [{ label: 'No Data', value: 1 }];
                                }
                                let labels = items.map(e => e.label);
                                let data = items.map(e => e.value);
                                let colors = ['#3b82f6','#10b981','#f59e0b','#ec4899','#6366f1','#94a3b8','#8b5cf6','#0ea5e9'];
                                this.chart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: labels,
                                        datasets: [{ data: data, backgroundColor: colors.slice(0, data.length), borderWidth: 0 }]
                                    },
                                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
                                });
                            }
                        }">
                            <canvas x-ref="donutCanvas" id="donut-key-events-{{ $idx }}"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-3xl font-black text-slate-800">
                                    {{ number_format($donutTotal) }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $donutTotalLabel }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 text-xs font-medium text-slate-500 w-full pr-4">
                        @php
                            $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-yellow-500', 'bg-pink-500', 'bg-indigo-500', 'bg-slate-400', 'bg-purple-500', 'bg-sky-500'];
                        @endphp
                        @foreach($donutItems as $index => $item)
                            @php $percent = $donutTotal > 0 ? round(($item['value'] / $donutTotal) * 100, 1) : 0; @endphp
                            <div class="flex items-center justify-between w-full p-2 hover:bg-slate-50 rounded-lg transition-colors">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 {{ $colors[$index % count($colors)] }} rounded-full"></div>
                                    <span class="capitalize text-slate-600">{{ $item['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-800">{{ number_format($item['value']) }}</span>
                                    <span class="text-slate-400 text-[10px] w-8 text-right">{{ $percent }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>

    <!-- Right: Visitors by Channel Bar Chart -->
    <div>
        @if(count($reportSets) > 1)
        <div wire:key="wrapper-9-multi" class="flex flex-col lg:flex-row gap-6">
        @else
        <div wire:key="wrapper-9-single" class="flex flex-col gap-6">
        @endif
        @foreach($reportSets as $idx => $rSet)
        <div wire:key="ga4-section-3-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
            <div class="bg-white border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 w-full mb-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-400"></div> Visitors by Channel
                    </div>
                </div>
                <div wire:key="stable-bar-{{ $idx }}" class="w-full">
                    <div class="h-64" wire:ignore wire:key="bar-channels-{{ $idx }}-{{ md5(json_encode($activeReportData)) }}" x-data="{
                        chart: null,
                        init() {
                            if (!this.$refs.barCanvas) return;
                            if (this.chart) {
                                this.chart.destroy();
                            }
                            const ctx = this.$refs.barCanvas.getContext('2d');
                            let rawData = {{ json_encode($idx === 0 ? $activeReportData : ($activeReportData['compare_data'] ?? null)) }};
                            let sources = (rawData && rawData.traffic_sources) ? rawData.traffic_sources : [];
                            let labels = sources.map(c => c.source_medium).slice(0, 6);
                            let data = sources.map(c => c.sessions).slice(0, 6);
                            
                            if (labels.length === 0) {
                                labels = ['Organic Search', 'Paid Search', 'Direct', 'Unassigned', 'Organic Social', 'Referral'];
                                data = [620, 480, 310, 180, 40, 20];
                            }

                            this.chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        data: data,
                                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#93c5fd', '#f472b6', '#a78bfa'],
                                        borderWidth: 0,
                                        barPercentage: 0.5,
                                        categoryPercentage: 0.5
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { dash: [4, 4] } },
                                        x: { grid: { display: false } }
                                    }
                                }
                            });
                        }
                    }">
                        <canvas x-ref="barCanvas" id="bar-channels-{{ $idx }}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>


@if(count($reportSets) == 1)
<div wire:key="wrapper-20-single" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
@else
<div wire:key="wrapper-20-multi" class="grid grid-cols-1 gap-6 mb-6">
@endif

<div>
@if(count($reportSets) > 1)
<div wire:key="wrapper-10-multi" class="flex flex-col lg:flex-row gap-6">
@else
<div wire:key="wrapper-10-single" class="flex flex-col gap-6">
@endif
@foreach($reportSets as $idx => $rSet)
<div wire:key="ga4-section-4-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
<div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50" x-data="{ showAllPages: false }">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Viewed Pages</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold w-1/2">Page Path</th>
                                <th class="py-2 text-right font-bold w-1/4">Views</th>
                                <th class="py-2 text-right font-bold w-1/4">Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_slice($reportDataScope['pages_report'] ?? [], 0, 6) as $page) <tr wire:key="page-{{ md5(json_encode($page)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                    <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($page['pageviews'] ?? 0) }}</div>

</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($page['users'] ?? 0) }}</div>

</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($reportDataScope['pages_report'] ?? []) > 6)
                    <div class="transition-all duration-500 ease-in-out overflow-hidden"
                         :style="showAllPages ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <tbody>
                                    @foreach (array_slice($reportDataScope['pages_report'] ?? [], 6) as $page) <tr wire:key="page-{{ md5(json_encode($page)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                            <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                            <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($page['pageviews'] ?? 0) }}</div>

</td>
                                            <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($page['users'] ?? 0) }}</div>

</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                        <button type="button" @click="showAllPages = !showAllPages" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                            <span x-text="showAllPages ? 'Show Less' : 'View Full'"></span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllPages ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
</div>
        @endforeach
</div>
</div>

<div>
@if(count($reportSets) > 1)
<div wire:key="wrapper-11-multi" class="flex flex-col lg:flex-row gap-6">
@else
<div wire:key="wrapper-11-single" class="flex flex-col gap-6">
@endif
@foreach($reportSets as $idx => $rSet)
<div wire:key="ga4-section-5-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
<div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50" x-data="{ showAllSources: false }">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Traffic Sources / Mediums</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold w-1/2">Source / Medium</th>
                                <th class="py-2 text-right font-bold w-1/4">Sessions</th>
                                <th class="py-2 text-right font-bold w-1/4">Bounce Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_slice($reportDataScope['traffic_sources'] ?? [], 0, 6) as $source)
                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                    <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                    <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($source['sessions'] ?? 0) }}</div>

</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ $source['bounce_rate'] ?? '—' }}</div>

</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($reportDataScope['traffic_sources'] ?? []) > 6)
                    <div class="transition-all duration-500 ease-in-out overflow-hidden"
                         :style="showAllSources ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <tbody>
                                    @foreach (array_slice($reportDataScope['traffic_sources'] ?? [], 6) as $source)
                                        <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                            <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                            <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($source['sessions'] ?? 0) }}</div>

</td>
                                            <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ $source['bounce_rate'] ?? '—' }}</div>

</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                        <button type="button" @click="showAllSources = !showAllSources" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                            <span x-text="showAllSources ? 'Show Less' : 'View Full'"></span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllSources ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        
</div>
        @endforeach
</div>
</div>
</div>


@if(count($reportSets) == 1)
<div wire:key="wrapper-21-single" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
@else
<div wire:key="wrapper-21-multi" class="grid grid-cols-1 gap-6 mb-6">
@endif

<div>
@if(count($reportSets) > 1)
<div wire:key="wrapper-12-multi" class="flex flex-col lg:flex-row gap-6">
@else
<div wire:key="wrapper-12-single" class="flex flex-col gap-6">
@endif
@foreach($reportSets as $idx => $rSet)
<div wire:key="ga4-section-6-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
<div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Device Breakdowns</h4>
                <div class="space-y-4">
                    @foreach ($reportDataScope['device_demographics'] ?? [] as $device)
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $device['device'] }}</span>
                                <span class="text-slate-400 dark:text-slate-500 font-bold">
{{ $device['percentage'] }}
@if($hasCompare)
                                    @php
                                        $keyToUse = isset($baseData['device_demographics'][0]['active_users']) ? 'active_users' : 'sessions';
                                        $myVal = $device[$keyToUse] ?? 0;
                                        $compareVal = 0;
                                        $otherArr = $idx == 0 ? ($baseData['compare_data']['device_demographics'] ?? []) : ($baseData['device_demographics'] ?? []);
                                        foreach($otherArr as $od) {
                                            if($od['device'] === $device['device']) { $compareVal = $od[$keyToUse] ?? 0; break; }
                                        }
                                        $delta = $calculateDelta($myVal, $compareVal, $format);
                                    @endphp
                                    <span class="text-[10px] {{ $delta['trend'] === 'up' ? 'text-emerald-500' : ($delta['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }} ml-1.5 inline-flex items-center">
                                        @if($delta['trend'] !== 'flat')
                                            <svg class="w-2.5 h-2.5 {{ $delta['trend'] === 'down' ? 'transform rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                                        @endif
                                        {{ $delta['value'] }}
                                    </span>
                                @endif
</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 dark:bg-indigo-400 h-1.5 rounded-full" style="width: {{ $device['percentage'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
</div>
        @endforeach
</div>
</div>
            
<div>
@if(count($reportSets) > 1)
<div wire:key="wrapper-13-multi" class="flex flex-col lg:flex-row gap-6">
@else
<div wire:key="wrapper-13-single" class="flex flex-col gap-6">
@endif
@foreach($reportSets as $idx => $rSet)
<div wire:key="ga4-section-7-{{ $idx }}" class="flex-1 w-full overflow-hidden">
            @php $reportDataScope = $rSet['data']; @endphp
            @if(count($reportSets) > 1)
                <div class="col-span-full mb-3 mt-4">
                    <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                        {{ $rSet['title'] }}
                    </span>
                </div>
            @endif
<div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 col-span-2" x-data="{ showAllGeo: false }">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Geographic Audience</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold w-1/2">Country</th>
                                <th class="py-2 text-right font-bold w-1/4">Active Users</th>
                                <th class="py-2 text-right font-bold w-1/4">Sessions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_slice($reportDataScope['geographic_sources'] ?? [], 0, 6) as $geo) <tr wire:key="geo-{{ md5(json_encode($geo)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                    <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                    <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($geo['active_users'] ?? 0) }}</div>

</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($geo['sessions'] ?? 0) }}</div>

</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($reportDataScope['geographic_sources'] ?? []) > 6)
                    <div class="transition-all duration-500 ease-in-out overflow-hidden"
                         :style="showAllGeo ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <tbody>
                                    @foreach (array_slice($reportDataScope['geographic_sources'] ?? [], 6) as $geo) <tr wire:key="geo-{{ md5(json_encode($geo)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                            <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                            <td class="py-2.5 text-right font-bold w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($geo['active_users'] ?? 0) }}</div>

</td>
                                            <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4"><div class="text-slate-800 dark:text-slate-200">{{ number_format($geo['sessions'] ?? 0) }}</div>

</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                        <button type="button" @click="showAllGeo = !showAllGeo" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                            <span x-text="showAllGeo ? 'Show Less' : 'View Full'"></span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllGeo ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
</div>
        @endforeach
</div>
</div>
</div>

        @elseif ($activeReportIntegrationId === 'youtube')
@php
            $reportSets = [];
            $baseData = $activeReportData ?? [];
            if (!empty($baseData)) {
                $reportSets[] = ['title' => (!empty($this->dateFrom) && !empty($this->dateTo)) ? \Carbon\Carbon::parse($this->dateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->dateTo)->format('M d, Y') : 'Current Period', 'data' => $baseData];
            }
            if (!empty($baseData['compare_data'])) {
                $reportSets[] = ['title' => (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) ? \Carbon\Carbon::parse($this->compareDateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->compareDateTo)->format('M d, Y') : 'Previous Period', 'data' => $baseData['compare_data']];
            }
@endphp
            
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-14-multi" class="flex flex-col lg:flex-row gap-6 mb-6">
            @else
            <div wire:key="wrapper-14-single" class="flex flex-col gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="youtube-section-1-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-red-50/20 dark:bg-red-950/10 border border-red-100/30 dark:border-red-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Views</span>
                        <span class="text-lg font-extrabold text-red-600 dark:text-red-400">
                            {{ number_format($reportDataScope['summary']['views'] ?? 0) }}
                        </span>
                    </div>
                    <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Watch Time (hrs)</span>
                        <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                            {{ number_format($reportDataScope['summary']['watch_time'] ?? 0, 1) }}
                        </span>
                    </div>
                    <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Subscribers</span>
                        <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                            {{ number_format($reportDataScope['summary']['subscribers'] ?? 0) }}
                        </span>
                    </div>
                    <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Duration</span>
                        <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ $reportDataScope['summary']['avg_view_duration'] ?? '0s' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            </div>

            
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-15-multi" class="flex flex-col lg:flex-row gap-6 mb-6">
            @else
            <div wire:key="wrapper-15-single" class="flex flex-col gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="youtube-section-chart-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-white border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 w-full mb-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Daily Views
                        </div>
                    </div>
                    @if(empty($reportDataScope['daily_traffic']))
                    <div class="h-64 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500">
                        <svg class="w-10 h-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2.625c.66 0 1.255.434 1.442 1.066l1.266 4.266c.187.632.782 1.068 1.442 1.068h4.45c.66 0 1.255-.436 1.442-1.068l1.266-4.266A1.51 1.51 0 0118.375 13H21M3 13v6a2 2 0 002 2h14a2 2 0 002-2v-6M12 9V3m0 0L9 6m3-3l3 3" />
                        </svg>
                        <span class="text-xs font-medium">No traffic data available for this period.</span>
                    </div>
                    @else
                    <div wire:key="stable-yt-{{ $idx }}" class="w-full"><div class="h-64 relative" wire:ignore wire:key="line-youtube-traffic-{{ $idx }}-{{ md5(json_encode($activeReportData)) }}" x-data="{
                        chart: null,
                        init() {
                            setTimeout(() => {
                                let canvas = this.$refs.canvas;
                                if (!canvas) return;
                                if (this.chart) {
                                    this.chart.destroy();
                                }
                                const ctx = canvas.getContext('2d');
                                let rawData = {{ json_encode($idx === 0 ? $activeReportData : ($activeReportData['compare_data'] ?? null)) }};
                                let traffic = (rawData && rawData.daily_traffic) ? rawData.daily_traffic : [];
                                let labels = traffic.map(t => t.date);
                                let views = traffic.map(t => t.views);
                                
                                this.chart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [
                                            {
                                                label: 'Views',
                                                data: views,
                                                borderColor: '#dc2626',
                                                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                                borderWidth: 2,
                                                fill: true,
                                                tension: 0.4
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true, maintainAspectRatio: false,
                                        plugins: { legend: { display: true, position: 'bottom' } },
                                        scales: {
                                            y: { beginAtZero: true, position: 'left' },
                                            x: { grid: { display: false } }
                                        }
                                    }
                                });
                            }, 50);
                        }
                    }">
                        <canvas x-ref="canvas" id="line-youtube-traffic-{{ $idx }}"></canvas>
                    </div>
                    </div>@endif
                </div>
            </div>
            @endforeach
            </div>

            
            @if(count($reportSets) > 1)
            <div wire:key="wrapper-16-multi" class="flex flex-col lg:flex-row gap-6 mb-6">
            @else
            <div wire:key="wrapper-16-single" class="flex flex-col gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="youtube-section-2-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Performing Videos</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold w-3/5">Video</th>
                                    <th class="py-2 text-right font-bold">Views</th>
                                    <th class="py-2 text-right font-bold">Watch Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reportDataScope['top_videos'] ?? [] as $video) <tr wire:key="vid-{{ md5(json_encode($video)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                        <td class="py-3 font-bold">
                                            <div class="flex items-center gap-3">
                                                @if(!empty($video['thumbnail']))
                                                <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}" class="w-16 h-10 object-cover rounded shadow-sm">
                                                @else
                                                <div class="w-16 h-10 bg-slate-200 dark:bg-slate-700 rounded shadow-sm flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm14 0H4v8h12V6z"/><path d="M8 8l5 2.5L8 13V8z"/></svg>
                                                </div>
                                                @endif
                                                <div class="flex flex-col truncate max-w-[200px]" title="{{ $video['title'] }}">
                                                    <span class="truncate">{{ $video['title'] }}</span>
                                                    <a href="https://youtube.com/watch?v={{ $video['id'] ?? '' }}" target="_blank" class="text-[9px] text-red-500 hover:underline">Watch Video</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-right font-bold">{{ number_format($video['views'] ?? 0) }}</td>
                                        <td class="py-3 text-right text-slate-400 dark:text-slate-500">{{ number_format($video['watch_time'] ?? 0, 1) }} hrs</td>
                                    </tr>
                                @empty
                                    <tr wire:key="vid-empty">
                                        <td colspan="3" class="py-4 text-center text-slate-500">No video data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        @elseif($activeReportIntegrationId === 'keyword')
            @php
                $baseData = $activeReportData ?? [];
                $reportSets = [];
                if (!empty($baseData)) {
                    $reportSets[] = ['title' => (!empty($this->dateFrom) && !empty($this->dateTo)) ? \Carbon\Carbon::parse($this->dateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->dateTo)->format('M d, Y') : 'Current Period', 'data' => $baseData];
                }
                if (isset($baseData['compare_data'])) {
                    $reportSets[] = ['title' => (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) ? \Carbon\Carbon::parse($this->compareDateFrom)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->compareDateTo)->format('M d, Y') : 'Previous Period', 'data' => $baseData['compare_data']];
                }
            @endphp
            
            @if(count($reportSets) == 1)
            <div class="grid grid-cols-1 gap-6 mb-6">
            @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="keyword-summary-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="p-4 bg-purple-50/20 dark:bg-purple-950/10 border border-purple-100/30 dark:border-purple-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Keywords</span>
                    <span class="text-lg font-extrabold text-purple-600 dark:text-purple-400">
                        {{ number_format($reportDataScope['summary']['total_keywords'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10</span>
                    <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                        {{ number_format($reportDataScope['summary']['top_10'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 15</span>
                    <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                        {{ number_format($reportDataScope['summary']['top_15'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10 Pages</span>
                    <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                        {{ number_format($reportDataScope['summary']['top_10_pages'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-pink-50/20 dark:bg-pink-950/10 border border-pink-100/30 dark:border-pink-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 15 Pages</span>
                    <span class="text-lg font-extrabold text-pink-600 dark:text-pink-400">
                        {{ number_format($reportDataScope['summary']['top_15_pages'] ?? 0) }}
                    </span>
                </div>
            </div>
            </div>
            @endforeach
            </div>

            
            @if(count($reportSets) == 1)
            <div class="grid grid-cols-1 gap-6 mb-6">
            @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @endif
            @foreach($reportSets as $idx => $rSet)
            <div wire:key="keyword-chart-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                @php $reportDataScope = $rSet['data']; @endphp
                @if(count($reportSets) > 1)
                    <div class="col-span-full mb-3 mt-4">
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                            {{ $rSet['title'] }}
                        </span>
                    </div>
                @endif
            <div class="grid grid-cols-1 mb-6">
                <div class="bg-white border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 w-full rounded-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div> Ranking Distribution
                        </div>
                    </div>
                    <div wire:key="stable-kw-{{ $idx }}" class="w-full"><div class="h-64 relative" wire:ignore wire:key="bar-keyword-dist-{{ $idx }}-{{ md5(json_encode($activeReportData)) }}" x-data="{
                        chart: null,
                        init() {
                            setTimeout(() => {
                                let canvas = this.$refs.canvas;
                                if (!canvas) return;
                                if (this.chart) {
                                    this.chart.destroy();
                                }
                                const ctx = canvas.getContext('2d');
                                let rawData = {{ json_encode($idx === 0 ? $activeReportData : ($activeReportData['compare_data'] ?? null)) }};
                                let history = (rawData && rawData.daily_traffic) ? rawData.daily_traffic : [];
                                
                                if (history.length === 0 && rawData && rawData.summary) {
                                    history = [{
                                        date: '{{ now()->format('Y-m-d') }}',
                                        top_10: rawData.summary.top_10 || 0,
                                        top_15: rawData.summary.top_15 || 0,
                                        top_50: rawData.summary.top_50 || 0
                                    }];
                                }
                                let labels = history.map(h => h.date);
                                let dTop10 = history.map(h => (h.top_10 !== undefined && h.top_10 !== null) ? h.top_10 : ((h.top_1_3 !== undefined && h.top_1_3 !== null) ? h.top_1_3 : null));
                                let dTop15 = history.map(h => (h.top_15 !== undefined && h.top_15 !== null) ? h.top_15 : ((h.top_10 !== undefined && h.top_10 !== null) ? h.top_10 : null));
                                let dTop50 = history.map(h => {
                                    if (h.top_50 !== undefined && h.top_50 !== null) return h.top_50;
                                    if (h.top_1_3 !== undefined && h.top_1_3 !== null) return (h.top_1_3 || 0) + (h.top_4_10 || 0) + (h.top_11_50 || 0);
                                    return null;
                                });
                                
                                this.chart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [
                                            { label: 'Top 10', data: dTop10, backgroundColor: '#10b981', borderRadius: 4 },
                                            { label: 'Top 15', data: dTop15, backgroundColor: '#3b82f6', borderRadius: 4 },
                                            { label: 'Top 50', data: dTop50, backgroundColor: '#f59e0b', borderRadius: 4 }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: { legend: { display: true, position: 'bottom' } },
                                        scales: {
                                            y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f1f5f9' }, border: { display: false } },
                                            x: { grid: { display: false }, border: { display: false } }
                                        }
                                    }
                                });
                            }, 100);
                        }
                    }">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div></div>
            </div>

            </div>
            @endforeach
            </div>

            
            @if(count($reportSets) == 1)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @php $rSet = $reportSets[0]; $reportDataScope = $rSet['data']; @endphp
                
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Keyword Rankings</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">Keyword</th>
                                    <th class="py-2 text-right font-bold">Position</th>
                                    <th class="py-2 text-right font-bold">Change</th>
                                    <th class="py-2 text-right font-bold">Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($reportDataScope['keywords'] ?? [], 0, 10) as $kw) <tr wire:key="kw-{{ md5(json_encode($kw)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-bold truncate">{{ $kw['keyword'] ?? 'Unknown' }}</td>
                                    <td class="py-2.5 text-right font-medium">{{ $kw['position'] ?? 0 }}</td>
                                    <td class="py-2.5 text-right font-medium text-emerald-500">{{ $kw['change'] ?? 0 }}</td>
                                    <td class="py-2.5 text-right font-medium">{{ number_format($kw['volume'] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Pages</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                    <th class="py-2 font-bold">URL</th>
                                    <th class="py-2 text-right font-bold">Keywords</th>
                                    <th class="py-2 text-right font-bold">Avg Rank</th>
                                    <th class="py-2 text-right font-bold">Total Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($reportDataScope['pages'] ?? [], 0, 10) as $page) <tr wire:key="page-{{ md5(json_encode($page)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-medium truncate max-w-[200px]" title="{{ $page['url'] ?? '' }}">
                                        {{ $page['path'] ?? '/' }}
                                    </td>
                                    <td class="py-2.5 text-right font-medium">{{ number_format($page['keyword_count'] ?? 0) }}</td>
                                    <td class="py-2.5 text-right font-medium">{{ $page['avg_rank'] ?? 0 }}</td>
                                    <td class="py-2.5 text-right font-medium">{{ number_format($page['total_volume'] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    @foreach($reportSets as $idx => $rSet)
                    <div wire:key="keyword-table-rankings-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                        @php $reportDataScope = $rSet['data']; @endphp
                        @if(count($reportSets) > 1)
                            <div class="col-span-full mb-3 mt-4">
                                <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                                    {{ $rSet['title'] }}
                                </span>
                            </div>
                        @endif
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Keyword Rankings</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                            <th class="py-2 font-bold">Keyword</th>
                                            <th class="py-2 text-right font-bold">Position</th>
                                            <th class="py-2 text-right font-bold">Change</th>
                                            <th class="py-2 text-right font-bold">Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (array_slice($reportDataScope['keywords'] ?? [], 0, 10) as $kw) <tr wire:key="kw-{{ md5(json_encode($kw)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                            <td class="py-2.5 font-bold truncate">{{ $kw['keyword'] ?? 'Unknown' }}</td>
                                            <td class="py-2.5 text-right font-medium">{{ $kw['position'] ?? 0 }}</td>
                                            <td class="py-2.5 text-right font-medium text-emerald-500">{{ $kw['change'] ?? 0 }}</td>
                                            <td class="py-2.5 text-right font-medium">{{ number_format($kw['volume'] ?? 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    @foreach($reportSets as $idx => $rSet)
                    <div wire:key="keyword-table-pages-{{ $idx }}" class="flex-1 w-full overflow-hidden">
                        @php $reportDataScope = $rSet['data']; @endphp
                        @if(count($reportSets) > 1)
                            <div class="col-span-full mb-3 mt-4">
                                <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-widest px-2 py-1 rounded">
                                    {{ $rSet['title'] }}
                                </span>
                            </div>
                        @endif
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Pages</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                            <th class="py-2 font-bold">URL</th>
                                            <th class="py-2 text-right font-bold">Keywords</th>
                                            <th class="py-2 text-right font-bold">Avg Rank</th>
                                            <th class="py-2 text-right font-bold">Total Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (array_slice($reportDataScope['pages'] ?? [], 0, 10) as $page) <tr wire:key="page-{{ md5(json_encode($page)) }}" class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                            <td class="py-2.5 font-medium truncate max-w-[200px]" title="{{ $page['url'] ?? '' }}">
                                                {{ $page['path'] ?? '/' }}
                                            </td>
                                            <td class="py-2.5 text-right font-medium">{{ number_format($page['keyword_count'] ?? 0) }}</td>
                                            <td class="py-2.5 text-right font-medium">{{ $page['avg_rank'] ?? 0 }}</td>
                                            <td class="py-2.5 text-right font-medium">{{ number_format($page['total_volume'] ?? 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @elseif($activeReportIntegrationId === 'gtm')
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-purple-50/20 dark:bg-purple-950/10 border border-purple-100/30 dark:border-purple-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Tags</span>
                    <span class="text-lg font-extrabold text-purple-600 dark:text-purple-400">
                        {{ number_format($activeReportData['summary']['tags_count'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Triggers</span>
                    <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                        {{ number_format($activeReportData['summary']['triggers_count'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Variables</span>
                    <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                        {{ number_format($activeReportData['summary']['variables_count'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Folders</span>
                    <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                        {{ number_format($activeReportData['summary']['folders_count'] ?? 0) }}
                    </span>
                </div>
            </div>

            
            @if(!empty($activeReportData['tags_list']))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-6">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">All Tags</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold">Tag Name</th>
                                <th class="py-2 text-right font-bold">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeReportData['tags_list'] as $tag)
                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-bold truncate" title="{{ $tag['name'] }}">{{ $tag['name'] }}</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ $tag['type'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            
            @if(!empty($activeReportData['triggers_list']))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-6">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">All Triggers</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold">Trigger Name</th>
                                <th class="py-2 text-right font-bold">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeReportData['triggers_list'] as $trigger)
                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-bold truncate" title="{{ $trigger['name'] ?? '' }}">{{ $trigger['name'] ?? 'Unknown' }}</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ $trigger['type'] ?? 'Unknown' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(!empty($activeReportData['variables_list']))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-6">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">All Variables</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-bold">Variable Name</th>
                                <th class="py-2 text-right font-bold">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeReportData['variables_list'] as $variable)
                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                    <td class="py-2.5 font-bold truncate" title="{{ $variable['name'] ?? '' }}">{{ $variable['name'] ?? 'Unknown' }}</td>
                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ $variable['type'] ?? 'Unknown' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endif
    @endif
    </div>
</div>

