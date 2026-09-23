                                                            <th class="py-2 text-right font-bold w-1/4">Users</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (array_slice($activeReportData['pages_report'] ?? [], 0, 6) as $page)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($page['pageviews'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($page['users'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['pages_report'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllPages ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['pages_report'] ?? [], 6) as $page)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                        <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($page['pageviews'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($page['users'] ?? 0) }}</td>
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

                                        <!-- Traffic Sources & Channels -->
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
                                                        @foreach (array_slice($activeReportData['traffic_sources'] ?? [], 0, 6) as $source)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($source['sessions'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ $source['bounce_rate'] ?? '—' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['traffic_sources'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllSources ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['traffic_sources'] ?? [], 6) as $source)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                        <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($source['sessions'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ $source['bounce_rate'] ?? '—' }}</td>
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

                                    <!-- 3. Lower Grid: Demographics, Geography -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Devices -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Device Breakdowns</h4>
                                            <div class="space-y-4">
                                                @foreach ($activeReportData['device_demographics'] ?? [] as $device)
                                                    <div>
                                                        <div class="flex justify-between text-xs mb-1">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $device['device'] }}</span>
                                                            <span class="text-slate-400 dark:text-slate-500 font-bold">{{ $device['percentage'] }}</span>
                                                        </div>
                                                        <div class="w-full bg-slate-200 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                                            <div class="bg-indigo-600 dark:bg-indigo-400 h-1.5 rounded-full" style="width: {{ $device['percentage'] }}"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Geographic Country Sources -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50" x-data="{ showAllGeo: false }">
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
                                                        @foreach (array_slice($activeReportData['geographic_sources'] ?? [], 0, 6) as $geo)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($geo['active_users'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($geo['sessions'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['geographic_sources'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllGeo ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['geographic_sources'] ?? [], 6) as $geo)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                        <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($geo['active_users'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($geo['sessions'] ?? 0) }}</td>
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
                                    @elseif ($activeReportIntegrationId === 'youtube')
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
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
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
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mb-6">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Performing Videos</h4>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead>
                                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                            <th class="py-2 font-bold">Video Title</th>
                                                            <th class="py-2 text-right font-bold">Views</th>
                                                            <th class="py-2 text-right font-bold">Watch Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($activeReportData['top_videos'] ?? [] as $video)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                <td class="py-2.5 font-bold truncate max-w-[200px]" title="{{ $video['title'] }}">{{ $video['title'] }}</td>
                                                                <td class="py-2.5 text-right font-bold">{{ number_format($video['views'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($video['watch_time'] ?? 0, 1) }} hrs</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="py-4 text-center text-slate-500">No video data available.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @elseif($activeReportIntegrationId === 'keyword')
                                        <!-- 1. Summary Cards (Keyword) -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="p-4 bg-purple-50/20 dark:bg-purple-950/10 border border-purple-100/30 dark:border-purple-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Keywords</span>
                                                <span class="text-lg font-extrabold text-purple-600 dark:text-purple-400">
                                                    {{ number_format($activeReportData['summary']['total_keywords'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10 Rankings</span>
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                    {{ number_format($activeReportData['summary']['top_10'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Up Movements</span>
                                                <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($activeReportData['summary']['up_movements'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Share of Voice</span>
                                                <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                                                    {{ $activeReportData['summary']['share_of_voice'] ?? '0%' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2. Keywords and Pages Tables (Keyword) -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                            <!-- Top Keyword Rankings -->
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
                                                            @forelse ($activeReportData['keywords'] ?? [] as $kw)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold truncate max-w-[200px]" title="{{ $kw['keyword'] }}">{{ $kw['keyword'] }}</td>
                                                                    <td class="py-2.5 text-right font-bold">#{{ $kw['position'] ?? '-' }}</td>
                                                                    <td class="py-2.5 text-right {{ (strpos($kw['change'], '+') !== false) ? 'text-emerald-500' : ((strpos($kw['change'], '-') !== false) ? 'text-red-500' : 'text-slate-400') }} font-bold">
                                                                        {{ $kw['change'] ?? '0' }}
                                                                    </td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($kw['volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="py-4 text-center text-slate-500">No keyword data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Top Ranking URLs -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Ranking URLs</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">URL</th>
                                                                <th class="py-2 text-right font-bold">Keywords</th>
                                                                <th class="py-2 text-right font-bold">Total Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($activeReportData['pages'] ?? [] as $pg)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 truncate max-w-[250px]" title="{{ $pg['url'] }}">{{ $pg['path'] }}</td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($pg['keyword_count'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($pg['total_volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="py-4 text-center text-slate-500">No URL data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Top Pages -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-5">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Pages</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">URL</th>
                                                                <th class="py-2 text-right font-bold">Keywords</th>
                                                                <th class="py-2 text-right font-bold">Avg. Rank</th>
                                                                <th class="py-2 text-right font-bold">Total Search Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($activeReportData['pages'] ?? [] as $pg)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 truncate max-w-[250px]" title="{{ $pg['url'] }}">
                                                                        <a href="{{ $pg['url'] }}" target="_blank" class="hover:text-indigo-500 hover:underline">
                                                                            {{ $pg['url'] }}
                                                                        </a>
                                                                    </td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($pg['keyword_count'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ isset($pg['avg_rank']) ? $pg['avg_rank'] : 0 }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($pg['total_volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="py-4 text-center text-slate-500">No URL data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($activeReportIntegrationId === 'gtm')
                                        <!-- 1. Stats Summary Widgets (GTM) -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Workspace</span>
                                                <span class="text-base font-extrabold text-indigo-600 dark:text-indigo-400 truncate block">
                                                    {{ $activeReportData['summary']['workspace_name'] ?? 'Unknown' }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Tags</span>
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                    {{ number_format($activeReportData['summary']['tags_count'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-amber-50/20 dark:bg-amber-950/10 border border-amber-100/30 dark:border-amber-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Triggers</span>
                                                <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400">
                                                    {{ number_format($activeReportData['summary']['triggers_count'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Variables</span>
                                                <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($activeReportData['summary']['variables_count'] ?? 0) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2. GTM Tags Table -->
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

                                        <!-- 3. GTM Triggers Table -->
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

                                        <!-- 4. GTM Variables Table -->
                                        @if(!empty($activeReportData['variables_list']))
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-6">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">All Variables</h4>
                                            <div class="overflow-x-auto">
