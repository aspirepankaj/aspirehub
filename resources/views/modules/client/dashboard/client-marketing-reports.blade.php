<div class="space-y-6">
    @php
        $hasGa4 = !empty($ga4Data) && !isset($ga4Data['error']);
        $hasGsc = !empty($gscData) && !isset($gscData['error']);
        $hasYoutube = !empty($youtubeData) && !isset($youtubeData['error']);
        $hasKeyword = !empty($keywordData) && !isset($keywordData['error']);
    @endphp

    <!-- Header Controls Widget -->
    <div class="bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-100 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Website Selector -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Select Website</label>
                @if(count($websites) > 0)
                    <div class="relative w-64">
                        <select wire:model.live="selectedWebsiteId" style="background-image: none;" class="appearance-none w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 pr-10 cursor-pointer">
                            @foreach($websites as $site)
                                <option value="{{ $site->id }}">
                                    {{ $site->site_name }}
                                    @if(!empty($site->site_url))
                                        ({{ rtrim(str_replace(['https://', 'http://'], '', $site->site_url), '/') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-550">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                @else
                    <span class="text-xs text-slate-400">No registered websites found.</span>
                @endif
            </div>

            <!-- Integrations Status List -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-2">Integrations Status</span>
                
                <!-- GA4 Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ isset($integrations['ga4']) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Google Analytics 4</span>
                </div>

                <!-- GSC Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ isset($integrations['gsc']) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Search Console</span>
                </div>

                <!-- YouTube Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ isset($integrations['youtube']) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">YouTube</span>
                </div>

                <!-- Keyword Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ isset($integrations['keyword']) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Keyword.com</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Selector Tabs / Date Filter Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Integration Tabs -->
        @if($hasGa4 || $hasGsc || $hasYoutube || $hasKeyword)
            <div class="flex items-center p-1 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur border border-slate-200/30 dark:border-slate-700/30 rounded-xl max-w-max">
                <button wire:click="selectIntegration('overview')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $activeReportIntegrationId === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                    Overview Dashboard
                </button>
                @if($hasGa4)
                    <button wire:click="selectIntegration('ga4')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $activeReportIntegrationId === 'ga4' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        Google Analytics 4
                    </button>
                @endif
                @if($hasGsc)
                    <button wire:click="selectIntegration('gsc')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $activeReportIntegrationId === 'gsc' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        Search Console
                    </button>
                @endif
                @if($hasYoutube)
                    <button wire:click="selectIntegration('youtube')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $activeReportIntegrationId === 'youtube' ? 'bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        YouTube
                    </button>
                @endif
                @if($hasKeyword)
                    <button wire:click="selectIntegration('keyword')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $activeReportIntegrationId === 'keyword' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        Keyword.com
                    </button>
                @endif
            </div>
        @endif

        <!-- Month Filter Selector -->
        @if(!empty($availableMonths))
            <div class="relative w-48">
                <select wire:model.live="selectedMonth" style="background-image: none;" class="appearance-none w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 pr-10 cursor-pointer shadow-sm">
                    @foreach($availableMonths as $monthOpt)
                        <option value="{{ $monthOpt['value'] }}">{{ $monthOpt['label'] }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-550">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        @endif
    </div>

    <!-- ==================== OVERVIEW DASHBOARD VIEW ==================== -->
    @if($activeReportIntegrationId === 'overview')
        @if($hasGa4 || $hasGsc || $hasYoutube || $hasKeyword)
            <!-- Dynamic Metric Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- GA4 Metrics -->
                @if($hasGa4)
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Users</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($ga4Data['overall_summary']['active_users'] ?? 0) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Sessions</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($ga4Data['overall_summary']['sessions'] ?? 0) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Pageviews</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($ga4Data['overall_summary']['pageviews'] ?? 0) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Avg Duration</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ $ga4Data['overall_summary']['avg_session_duration'] ?? '0s' }}
                        </div>
                    </div>
                @endif

                <!-- GSC Metrics -->
                @if($hasGsc)
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Total Clicks</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($gscData['summary']['clicks'] ?? 0) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Impressions</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($gscData['summary']['impressions'] ?? 0) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Average CTR</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ isset($gscData['summary']['ctr']) ? number_format($gscData['summary']['ctr'], 1) . '%' : '0.0%' }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Avg Position</div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($gscData['summary']['position'] ?? 0.0, 1) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Charts Grid (Line & Donut Charts) -->
            @if($hasGa4 && (!empty($ga4Data['daily_traffic']) || !empty($ga4Data['traffic_sources'])))
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Weekly Trend Line Chart -->
                    @if(!empty($ga4Data['daily_traffic']))
                        <div class="lg:col-span-2 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-6 rounded-2xl shadow-sm">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-4">Traffic & Pageviews Trend</h3>
                            <div wire:ignore
                                 x-data="{
                                     month: @entangle('selectedMonth'),
                                     users: @js(array_column($ga4Data['daily_traffic'] ?? [], 'users')),
                                     pageviews: @js(array_column($ga4Data['daily_traffic'] ?? [], 'pageviews')),
                                     chart: null,
                                     initChart() {
                                         if (this.chart) {
                                             this.chart.destroy();
                                         }
                                         const ctx = document.getElementById('weeklyTrendChart').getContext('2d');
                                         
                                         let uData = this.users;
                                         let pData = this.pageviews;
                                         let labels = this.users.map((_, i) => 'Day ' + (i+1));
                                         
                                         if (uData.length > 20) {
                                             let chunkedU = [];
                                             let chunkedP = [];
                                             let chunkedLabels = [];
                                             for (let i = 0; i < uData.length; i += 7) {
                                                 let uSum = uData.slice(i, i+7).reduce((a, b) => a + b, 0);
                                                 let pSum = pData.slice(i, i+7).reduce((a, b) => a + b, 0);
                                                 chunkedU.push(uSum);
                                                 chunkedP.push(pSum);
                                                 chunkedLabels.push('W' + (Math.floor(i/7) + 1));
                                             }
                                             uData = chunkedU;
                                             pData = chunkedP;
                                             labels = chunkedLabels;
                                         }

                                         this.chart = new Chart(ctx, {
                                             type: 'line',
                                             data: {
                                                 labels: labels,
                                                 datasets: [
                                                     {
                                                         label: 'Users',
                                                         data: uData,
                                                         borderColor: '#3b82f6',
                                                         backgroundColor: 'rgba(59, 130, 246, 0.04)',
                                                         borderWidth: 2,
                                                         pointBackgroundColor: '#3b82f6',
                                                         tension: 0.35,
                                                         fill: true
                                                     },
                                                     {
                                                         label: 'Pageviews',
                                                         data: pData,
                                                         borderColor: '#6366f1',
                                                         backgroundColor: 'rgba(99, 102, 241, 0.04)',
                                                         borderWidth: 2,
                                                         pointBackgroundColor: '#6366f1',
                                                         tension: 0.35,
                                                         fill: true
                                                     }
                                                 ]
                                             },
                                             options: {
                                                 responsive: true,
                                                 maintainAspectRatio: false,
                                                 plugins: {
                                                     legend: {
                                                         position: 'bottom',
                                                         labels: { color: '#64748b', boxWidth: 10, usePointStyle: true }
                                                     }
                                                 },
                                                 scales: {
                                                     x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
                                                     y: { grid: { color: 'rgba(148, 163, 184, 0.08)' }, ticks: { color: '#64748b', font: { size: 10 } } }
                                                 }
                                             }
                                         });
                                     }
                                 }"
                                 x-init="initChart(); $watch('month', () => { setTimeout(() => initChart(), 100); })"
                                 class="h-64">
                                <canvas id="weeklyTrendChart"></canvas>
                            </div>
                        </div>
                    @endif

                    <!-- Channel Mix Donut Chart -->
                    @if(!empty($ga4Data['traffic_sources']))
                        <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-6 rounded-2xl shadow-sm">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-4">Traffic Channel Mix</h3>
                            <div wire:ignore
                                 x-data="{
                                     month: @entangle('selectedMonth'),
                                     channels: @js($ga4Data['traffic_sources'] ?? []),
                                     chart: null,
                                     initChart() {
                                         if (this.chart) {
                                             this.chart.destroy();
                                         }
                                         const ctx = document.getElementById('channelMixChart').getContext('2d');
                                         
                                         let labels = this.channels.map(c => c.source_medium);
                                         let data = this.channels.map(c => c.sessions);
                                         
                                         if (labels.length > 5) {
                                             labels = labels.slice(0, 5);
                                             data = data.slice(0, 5);
                                         }

                                         this.chart = new Chart(ctx, {
                                             type: 'doughnut',
                                             data: {
                                                 labels: labels.map(l => {
                                                      let clean = (l || 'unknown').trim();
                                                      clean = clean.replace(/\s*\/\s*/g, ' ');
                                                      clean = clean.replace(/\s*\([^)]*\)$/, '');
                                                      return clean;
                                                  }),
                                                 datasets: [{
                                                     data: data,
                                                     backgroundColor: [
                                                         '#6366f1',
                                                         '#3b82f6',
                                                         '#10b981',
                                                         '#ef4444',
                                                         '#f59e0b'
                                                     ],
                                                     borderWidth: 0
                                                 }]
                                             },
                                             options: {
                                                 responsive: true,
                                                 maintainAspectRatio: false,
                                                 cutout: '70%',
                                                 plugins: {
                                                     legend: {
                                                         position: 'bottom',
                                                         labels: { color: '#64748b', boxWidth: 10, usePointStyle: true }
                                                     }
                                                 }
                                             }
                                         });
                                     }
                                 }"
                                 x-init="initChart(); $watch('month', () => { setTimeout(() => initChart(), 100); })"
                                 class="h-64">
                                <canvas id="channelMixChart"></canvas>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tables Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top Viewed Pages -->
                @if($hasGa4 && !empty($ga4Data['pages_report']))
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Viewed Pages</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                        <th class="px-6 py-3.5">Page Path</th>
                                        <th class="px-6 py-3.5 text-right">Pageviews</th>
                                        <th class="px-6 py-3.5 text-right">Users</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                    @foreach(array_slice($ga4Data['pages_report'], 0, 5) as $p)
                                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                            <td class="px-6 py-3.5 font-mono text-[11px] truncate max-w-xs">{{ $p['page_path'] }}</td>
                                            <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($p['pageviews']) }}</td>
                                            <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($p['users']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Top Keywords / Search Queries -->
                @if(($hasGsc && !empty($gscData['top_queries'])) || ($hasGa4 && !empty($ga4Data['top_keywords'])))
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Keywords / Search Queries</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                        <th class="px-6 py-3.5">Keyword</th>
                                        <th class="px-6 py-3.5 text-right">Position</th>
                                        <th class="px-6 py-3.5 text-right">Clicks / Users</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                    @if($hasGsc && !empty($gscData['top_queries']))
                                        @foreach(array_slice($gscData['top_queries'], 0, 5) as $q)
                                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                                <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $q['query'] }}</td>
                                                <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($q['position'], 1) }}</td>
                                                <td class="px-6 py-3.5 text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($q['clicks']) }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach(array_slice($ga4Data['top_keywords'], 0, 5) as $k)
                                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                                <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $k['keyword'] }}</td>
                                                <td class="px-6 py-3.5 text-right font-medium text-slate-500">-</td>
                                                <td class="px-6 py-3.5 text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($k['active_users']) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Error / Empty State -->
            <div class="bg-white/40 dark:bg-slate-900/10 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-12 text-center">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">No Connected Reports</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Please ensure that your Google Analytics 4, Search Console, YouTube or Keyword.com is integrated and synced for this month.
                </p>
            </div>
        @endif
    @endif

    <!-- ==================== GOOGLE ANALYTICS 4 VIEW ==================== -->
    @if($activeReportIntegrationId === 'ga4')
        @if($hasGa4)
            <!-- GA4 Top Metrics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Active Users</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($ga4Data['overall_summary']['active_users'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Pageviews</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($ga4Data['overall_summary']['pageviews'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Sessions</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($ga4Data['overall_summary']['sessions'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Bounce Rate</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ $ga4Data['overall_summary']['bounce_rate'] ?? '0.0%' }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Avg Session Duration</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ $ga4Data['overall_summary']['avg_session_duration'] ?? '0s' }}
                    </div>
                </div>
            </div>

            <!-- Page Views & Traffic Sources Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Viewed Pages -->
                <div x-data="{ expanded: false }" class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Viewed Pages</h3>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                    <th class="px-6 py-3.5">Page Path</th>
                                    <th class="px-6 py-3.5 text-right">Pageviews</th>
                                    <th class="px-6 py-3.5 text-right">Users</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                @foreach(array_slice($ga4Data['pages_report'] ?? [], 0, 5) as $page)
                                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                        <td class="px-6 py-3.5 font-mono text-[11px] truncate max-w-xs">{{ $page['page_path'] }}</td>
                                        <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($page['pageviews']) }}</td>
                                        <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($page['users']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if(count($ga4Data['pages_report'] ?? []) > 5)
                            <div x-show="expanded" 
                                 x-transition:enter="transition-all ease-out duration-300"
                                 x-transition:enter-start="max-h-0 opacity-0"
                                 x-transition:enter-end="max-h-[500px] opacity-100"
                                 x-transition:leave="transition-all ease-in duration-200"
                                 x-transition:leave-start="max-h-[500px] opacity-100"
                                 x-transition:leave-end="max-h-0 opacity-0"
                                 class="overflow-hidden border-t border-slate-50 dark:border-slate-850">
                                <table class="w-full text-left border-collapse">
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                        @foreach(array_slice($ga4Data['pages_report'], 5) as $page)
                                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                                <td class="px-6 py-3.5 font-mono text-[11px] truncate max-w-xs">{{ $page['page_path'] }}</td>
                                                <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($page['pageviews']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($page['users']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if(count($ga4Data['pages_report'] ?? []) > 5)
                        <div class="px-6 py-3.5 border-t border-slate-50 dark:border-slate-800/60 flex justify-center bg-slate-50/20 dark:bg-slate-900/5">
                            <button @click="expanded = !expanded" class="flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                <span x-text="expanded ? 'View Less' : 'View Full'"></span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Traffic Sources -->
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Traffic Sources / Mediums</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                    <th class="px-6 py-3.5">Source / Medium</th>
                                    <th class="px-6 py-3.5 text-right">Sessions</th>
                                    <th class="px-6 py-3.5 text-right">Bounce Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                @foreach($ga4Data['traffic_sources'] ?? [] as $src)
                                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                        <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $src['source_medium'] }}</td>
                                        <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($src['sessions']) }}</td>
                                        <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ $src['bounce_rate'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Device Breakdowns & Geographic Location Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Device Breakdowns -->
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-6">Device Breakdowns</h3>
                    <div class="space-y-4">
                        @foreach($ga4Data['device_demographics'] ?? [] as $device)
                            <div>
                                <div class="flex items-center justify-between text-xs font-semibold mb-1.5 text-slate-700 dark:text-slate-300">
                                    <span>{{ $device['device'] }}</span>
                                    <span>{{ $device['percentage'] }} <span class="text-[10px] font-normal text-slate-400">({{ number_format($device['active_users']) }} Users)</span></span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $device['percentage'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Geographic Audience -->
                <div x-data="{ expanded: false }" class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Geographic Audience</h3>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                    <th class="px-6 py-3.5">Country</th>
                                    <th class="px-6 py-3.5 text-right">Active Users</th>
                                    <th class="px-6 py-3.5 text-right">Sessions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                @foreach(array_slice($ga4Data['geographic_sources'] ?? [], 0, 5) as $geo)
                                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                        <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $geo['country'] }}</td>
                                        <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($geo['active_users']) }}</td>
                                        <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($geo['sessions']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if(count($ga4Data['geographic_sources'] ?? []) > 5)
                            <div x-show="expanded" 
                                 x-transition:enter="transition-all ease-out duration-300"
                                 x-transition:enter-start="max-h-0 opacity-0"
                                 x-transition:enter-end="max-h-[500px] opacity-100"
                                 x-transition:leave="transition-all ease-in duration-200"
                                 x-transition:leave-start="max-h-[500px] opacity-100"
                                 x-transition:leave-end="max-h-0 opacity-0"
                                 class="overflow-hidden border-t border-slate-50 dark:border-slate-850">
                                <table class="w-full text-left border-collapse">
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                        @foreach(array_slice($ga4Data['geographic_sources'], 5) as $geo)
                                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                                <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $geo['country'] }}</td>
                                                <td class="px-6 py-3.5 text-right font-semibold">{{ number_format($geo['active_users']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($geo['sessions']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if(count($ga4Data['geographic_sources'] ?? []) > 5)
                        <div class="px-6 py-3.5 border-t border-slate-50 dark:border-slate-800/60 flex justify-center bg-slate-50/20 dark:bg-slate-900/5">
                            <button @click="expanded = !expanded" class="flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                <span x-text="expanded ? 'View Less' : 'View Full'"></span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="bg-white/40 dark:bg-slate-900/10 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-12 text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Google Analytics 4 Data Unavailable</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Please ensure GA4 has been synced for this month.</p>
            </div>
        @endif
    @endif

    <!-- ==================== SEARCH CONSOLE VIEW ==================== -->
    @if($activeReportIntegrationId === 'gsc')
        @if($hasGsc)
            <!-- GSC Top Metrics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Clicks</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($gscData['summary']['clicks'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Impressions</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($gscData['summary']['impressions'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Average CTR</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ isset($gscData['summary']['ctr']) ? number_format($gscData['summary']['ctr'], 1) . '%' : '0.0%' }}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Average Position</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ number_format($gscData['summary']['position'] ?? 0.0, 1) }}
                    </div>
                </div>
            </div>

            <!-- Top Search Queries & Top Search Pages Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Queries -->
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Search Queries</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                    <th class="px-6 py-3.5">Keyword / Query</th>
                                    <th class="px-6 py-3.5 text-right">Position</th>
                                    <th class="px-6 py-3.5 text-right">Clicks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                @foreach($gscData['top_queries'] ?? [] as $query)
                                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                        <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $query['query'] }}</td>
                                        <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($query['position'], 1) }}</td>
                                        <td class="px-6 py-3.5 text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($query['clicks']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Pages -->
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Search Impression Pages</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                    <th class="px-6 py-3.5">Page Path</th>
                                    <th class="px-6 py-3.5 text-right">Impressions</th>
                                    <th class="px-6 py-3.5 text-right">Clicks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                @foreach($gscData['top_pages'] ?? [] as $p)
                                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                        <td class="px-6 py-3.5 font-mono text-[11px] truncate max-w-xs">{{ $p['page'] }}</td>
                                        <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($p['impressions']) }}</td>
                                        <td class="px-6 py-3.5 text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($p['clicks']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="bg-white/40 dark:bg-slate-900/10 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-12 text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Search Console Data Unavailable</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Please ensure GSC has been synced for this month.</p>
            </div>
        @endif
    @elseif ($activeReportIntegrationId === 'youtube')
        @if($hasYoutube)
            <!-- 1. Stats Summary Widgets (YouTube) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-red-50/20 dark:bg-red-950/10 border border-red-100/30 dark:border-red-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Views</span>
                    <span class="text-lg font-extrabold text-red-600 dark:text-red-400">
                        {{ number_format($activeReportData['summary']['views'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Watch Time (hrs)</span>
                    <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                        {{ number_format($activeReportData['summary']['watch_time'] ?? 0, 1) }}
                    </span>
                </div>
                <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Subscribers</span>
                    <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($activeReportData['summary']['subscribers'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Duration</span>
                    <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                        {{ $activeReportData['summary']['avg_view_duration'] ?? '0s' }}
                    </span>
                </div>
            </div>

            <!-- 2. Top Videos Table (YouTube) -->
            <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Performing Videos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                <th class="px-6 py-3.5">Video Title</th>
                                <th class="px-6 py-3.5 text-right">Views</th>
                                <th class="px-6 py-3.5 text-right">Watch Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeReportData['top_videos'] ?? [] as $video)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                    <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[200px]" title="{{ $video['title'] }}">{{ $video['title'] }}</td>
                                    <td class="px-6 py-3.5 text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($video['views'] ?? 0) }}</td>
                                    <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($video['watch_time'] ?? 0, 1) }} hrs</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-slate-500 font-medium">No video data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white/40 dark:bg-slate-900/10 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-12 text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">YouTube Data Unavailable</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Please ensure YouTube has been synced for this month.</p>
            </div>
        @endif
    @elseif ($activeReportIntegrationId === 'keyword')
        @if($hasKeyword)
            <!-- 1. Stats Summary Widgets (Keyword) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Keywords</span>
                    <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                        {{ number_format($activeReportData['summary']['total_keywords'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10 Rankings</span>
                    <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($activeReportData['summary']['top_10'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Improved Keywords</span>
                    <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                        {{ number_format($activeReportData['summary']['improved'] ?? 0) }}
                    </span>
                </div>
                <div class="p-4 bg-red-50/20 dark:bg-red-950/10 border border-red-100/30 dark:border-red-900/20 rounded-2xl">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Declined Keywords</span>
                    <span class="text-lg font-extrabold text-red-600 dark:text-red-400">
                        {{ number_format($activeReportData['summary']['declined'] ?? 0) }}
                    </span>
                </div>
            </div>

            <!-- 2. Keyword Rankings Table (Keyword) -->
            <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Keyword Rankings</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                <th class="px-6 py-3.5">Keyword</th>
                                <th class="px-6 py-3.5">Tags</th>
                                <th class="px-6 py-3.5 text-right">Volume</th>
                                <th class="px-6 py-3.5 text-right">Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeReportData['keywords'] ?? [] as $kw)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300 border-b border-slate-50/50 dark:border-slate-800/30">
                                    <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $kw['keyword'] }}
                                        @if($kw['search_engine'] ?? false)
                                            <span class="ml-1 text-[10px] text-slate-400">({{ $kw['search_engine'] }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if(!empty($kw['tags']))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($kw['tags'] as $tag)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                        {{ $tag['name'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($kw['volume'] ?? 0) }}</td>
                                    <td class="px-6 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(strpos($kw['change'] ?? '', '+') !== false)
                                                <span class="text-xs font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded">{{ $kw['change'] }}</span>
                                            @elseif(strpos($kw['change'] ?? '', '-') !== false)
                                                <span class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-500/10 px-1.5 py-0.5 rounded">{{ $kw['change'] }}</span>
                                            @else
                                                <span class="text-xs font-bold text-slate-400">{{ $kw['change'] ?? '-' }}</span>
                                            @endif
                                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $kw['position'] > 0 ? $kw['position'] : '-' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-slate-500 font-medium">No keyword ranking data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white/40 dark:bg-slate-900/10 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-12 text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Keyword Data Unavailable</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Please ensure Keyword.com has been synced for this month.</p>
            </div>
        @endif
    @endif
</div>
