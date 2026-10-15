<div class="space-y-6">
    {{-- Breadcrumbs --}}
    @if(!$isEmbedded)
        <x-admin.breadcrumbs :items="['Marketing Reports' => null]" />
    @endif
    @php
        $isGa4Connected = isset($integrations['ga4']);
        $isGscConnected = isset($integrations['gsc']);
        $isYoutubeConnected = isset($integrations['youtube']);
        $isKeywordConnected = isset($integrations['keyword']);
        $isGbpConnected = isset($integrations['gbp']);
        $isGadsConnected = isset($integrations['gads']);
        $isGtmConnected = isset($integrations['gtm']);
        $isFacebookConnected = isset($integrations['facebook']);
        $isLinkedinConnected = isset($integrations['linkedin']);

        $hasGa4 = !empty($ga4Data) && !isset($ga4Data['error']);
        $hasGsc = !empty($gscData) && !isset($gscData['error']);
        $hasYoutube = !empty($youtubeData) && !isset($youtubeData['error']);
        $hasKeyword = !empty($keywordData) && !isset($keywordData['error']);
        $hasGtm = !empty($gtmData) && !isset($gtmData['error']);
        $hasGbp = !empty($gbpData) && !isset($gbpData['error']);
        $hasGads = !empty($gadsData) && !isset($gadsData['error']);
        $hasFacebook = !empty($facebookData) && !isset($facebookData['error']);
        $linkedinData = $linkedinData ?? [];
        $hasLinkedin = !empty($linkedinData) && !isset($linkedinData['error']);
    @endphp

    <!-- Header Controls Widget -->
    <div class="bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-100 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Website Selector -->
            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Select Website</label>
                @if(count($websites) > 0)
                    <div class="relative w-full sm:w-64">
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
                            <!-- Normal Chevron -->
                            <svg wire:loading.remove wire:target="selectedWebsiteId" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            <!-- Spinner -->
                            <svg wire:loading wire:target="selectedWebsiteId" class="w-4 h-4 animate-spin text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                @else
                    <span class="text-xs text-slate-400">No registered websites found.</span>
                @endif
            </div>


        </div>
    </div>

    <!-- Integration Selector Tabs / Date Filter Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Integration Tabs (Scrollable on mobile) -->
        @if($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected || $isGbpConnected || $isGadsConnected || $isFacebookConnected || $isLinkedinConnected)
            <div class="w-full lg:w-auto overflow-x-auto pb-1 scrollbar-none max-w-full">
                <div class="flex items-center p-1 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur border border-slate-200/30 dark:border-slate-700/30 rounded-xl min-w-max">
                    <button type="button" wire:click="selectIntegration('overview')" 
                        class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Overview Dashboard</span>
                    </button>
                    @if($isGbpConnected)
                        <button type="button" wire:click="selectIntegration('gbp')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gbp' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Business Profile</span>
                        </button>
                    @endif
                    @if($isFacebookConnected)
                        <button type="button" wire:click="selectIntegration('facebook')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'facebook' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-500 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                            <span>Facebook</span>
                        </button>
                    @endif
                    @if($isGadsConnected)
                        <button type="button" wire:click="selectIntegration('gads')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gads' ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Google Ads</span>
                        </button>
                    @endif
                    @if($isGa4Connected)
                        <button type="button" wire:click="selectIntegration('ga4')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'ga4' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                            <span>Google Analytics 4</span>
                        </button>
                    @endif
                    @if($isKeywordConnected)
                        <button type="button" wire:click="selectIntegration('keyword')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'keyword' ? 'bg-white dark:bg-slate-900 text-purple-600 dark:text-purple-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            <span>Keyword.com</span>
                        </button>
                    @endif
                    @if($isLinkedinConnected)
                        <button type="button" wire:click="selectIntegration('linkedin')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'linkedin' ? 'bg-white dark:bg-slate-900 text-sky-600 dark:text-sky-500 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-sky-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            <span>LinkedIn</span>
                        </button>
                    @endif
                    @if($isGscConnected)
                        <button type="button" wire:click="selectIntegration('gsc')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gsc' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            <span>Search Console</span>
                        </button>
                    @endif
                    @if($isGtmConnected)
                        <button type="button" wire:click="selectIntegration('gtm')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gtm' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            <span>Tag Manager</span>
                        </button>
                    @endif
                    @if($isYoutubeConnected)
                        <button type="button" wire:click="selectIntegration('youtube')" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'youtube' ? 'bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span>YouTube</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        @if($activeReportIntegrationId !== 'overview')
            <div class="flex items-center gap-3">
                @include('partials.marketing-report-send-modal')
                @php
                    $minDateBound = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
                    $maxDateBound = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                @include('partials.ga4-date-picker', ['minDateBound' => $minDateBound, 'maxDateBound' => $maxDateBound])
            </div>
        @endif
    </div>

    <!-- Integration Views (Uses Admin/Staff Layout) -->
    <div class="relative min-h-[400px] w-full mt-4">
        {{-- Skeleton Loader --}}
        <div wire:loading wire:target="selectIntegration" class="w-full font-sans pb-8 animate-pulse mt-2 relative">
            <div class="w-full h-full p-6">
                <!-- Skeleton cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5 gap-5 mb-6 relative" style="z-index: 10;">
                    @for($i = 0; $i < 5; $i++)
                        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm h-32 flex flex-col justify-center">
                            <div class="flex items-start gap-4 mb-2">
                                <div class="w-12 h-12 rounded-full bg-slate-200/60 dark:bg-slate-800/60 shrink-0"></div>
                                <div class="flex-1 space-y-2 py-1">
                                    <div class="h-3 bg-slate-200/80 dark:bg-slate-700/80 rounded w-1/2"></div>
                                    <div class="h-8 bg-slate-200/80 dark:bg-slate-700/80 rounded w-3/4"></div>
                                </div>
                            </div>
                            <div class="mt-2 h-3 bg-slate-100 dark:bg-slate-800/50 rounded w-2/3"></div>
                        </div>
                    @endfor
                </div>
                <!-- Skeleton Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5 relative" style="z-index: 10;">
                    <div class="lg:col-span-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border border-slate-100 dark:border-slate-800 p-5 rounded-xl shadow-sm h-96 flex flex-col justify-center items-center">
                        <div class="w-full h-full flex flex-col">
                            <div class="h-4 bg-slate-200/80 dark:bg-slate-700/80 rounded w-1/4 mb-6"></div>
                            <div class="flex-1 bg-slate-50/50 dark:bg-slate-800/30 rounded w-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-slate-300 dark:text-slate-700 tracking-wider">LOADING DATA</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border border-slate-100 dark:border-slate-800 p-5 rounded-xl shadow-sm h-96 flex flex-col">
                        <div class="h-4 bg-slate-200/80 dark:bg-slate-700/80 rounded w-1/3 mb-6"></div>
                        <div class="flex justify-center mb-8 flex-1 items-center">
                            <div class="w-48 h-48 rounded-full border-[16px] border-slate-100 dark:border-slate-800/50 flex items-center justify-center">
                                <div class="w-32 h-32 rounded-full border-[8px] border-slate-50 dark:border-slate-800/20"></div>
                            </div>
                        </div>
                        <div class="space-y-3 mt-auto"><div class="h-3 bg-slate-100 dark:bg-slate-800/50 rounded w-full"></div><div class="h-3 bg-slate-100 dark:bg-slate-800/50 rounded w-5/6"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading.remove wire:target="selectIntegration" class="animate-fadeIn w-full">
            @if(!($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected || $isGbpConnected || $isGadsConnected || $isGtmConnected || $isFacebookConnected || $isLinkedinConnected))
                {{-- Data not found / No Integrations --}}
                <div class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
                    <div class="w-20 h-20 mb-4 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2">No Data Found</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">There are no marketing integrations or data available for this website. Please check your integrations or select a different website.</p>
                </div>
            @else
                @if($activeReportIntegrationId === 'overview')
                    <div class="space-y-6" wire:key="integration-view-overview">
                        @php
                            $daysDiff = 30;
                            if (!empty($dateFrom) && !empty($dateTo)) {
                                try {
                                    $d1 = \Carbon\Carbon::parse($dateFrom);
                                    $d2 = \Carbon\Carbon::parse($dateTo);
                                    $daysDiff = $d1->diffInDays($d2) + 1;
                                } catch (\Exception $e) {}
                            }

                            $getDelta = function($curr, $prev) {
                                $c = (float)$curr;
                                $p = (float)$prev;
                                if ($p == 0) return ['val' => ($c > 0 ? '100%' : '0%'), 'trend' => ($c > 0 ? 'up' : 'flat')];
                                $diff = $c - $p;
                                if ($diff == 0) return ['val' => '0%', 'trend' => 'flat'];
                                $trend = $diff > 0 ? 'up' : 'down';
                                $pct = round((abs($diff) / $p) * 100);
                                return ['val' => $pct . '%', 'trend' => $trend];
                            };
                        @endphp

                        {{-- 1. Google Analytics 4 --}}
                        @if($isGa4Connected)
                            @php
                                $ga4Base = $ga4Data ?? [];
                                $ga4Summary = $ga4Base['overall_summary'] ?? [];
                                $ga4ActiveUsers = $ga4Summary['active_users'] ?? 0;
                                $ga4NewUsers = $ga4Summary['new_users'] ?? round($ga4ActiveUsers * 0.71);
                                $ga4ReturningUsers = $ga4Summary['returning_users'] ?? ($ga4ActiveUsers - $ga4NewUsers);
                                $ga4AvgDuration = $ga4Summary['avg_session_duration'] ?? '0m 0s';
                                
                                $ga4EventsCount = 0;
                                if (!empty($ga4Base['events_report'])) {
                                    foreach ($ga4Base['events_report'] as $eventName => $channels) {
                                        $ga4EventsCount += array_sum(array_column($channels, 'count'));
                                    }
                                } elseif (!empty($ga4Base['key_events'])) {
                                    $ga4EventsCount = array_sum(array_column($ga4Base['key_events'], 'event_count'));
                                }

                                $ga4Comp = $ga4Base['compare_data'] ?? $ga4Base['default_compare_data'] ?? [];
                                $ga4CompSummary = $ga4Comp['overall_summary'] ?? [];
                                $hasGa4Comp = !empty($ga4CompSummary);
                                $compGa4Users = $ga4CompSummary['active_users'] ?? 0;
                                $compGa4New = $ga4CompSummary['new_users'] ?? round($compGa4Users * 0.71);
                                $compGa4Ret = $compGa4Users - $compGa4New;
                                
                                $durParts = explode('m', str_replace('s', '', $ga4AvgDuration));
                                $currDurSecs = (isset($durParts[1])) ? ((int)$durParts[0]*60 + (int)trim($durParts[1])) : 0;
                                $compDurParts = explode('m', str_replace('s', '', $ga4CompSummary['avg_session_duration'] ?? '0m 0s'));
                                $compDurSecs = (isset($compDurParts[1])) ? ((int)$compDurParts[0]*60 + (int)trim($compDurParts[1])) : 0;

                                $compGa4Events = 0;
                                if (!empty($ga4Comp['events_report'])) {
                                    foreach ($ga4Comp['events_report'] as $eventName => $channels) {
                                        $compGa4Events += array_sum(array_column($channels, 'count'));
                                    }
                                }
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-ga4">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Google Analytics 4</h3>
                                            <span class="text-xs text-slate-400">Website Traffic & Engagement</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('ga4')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Users</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ga4ActiveUsers) }}</div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4ActiveUsers, $compGa4Users); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">New Users</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ga4NewUsers) }}</div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4NewUsers, $compGa4New); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Returning Users</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ga4ReturningUsers) }}</div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4ReturningUsers, $compGa4Ret); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Engagement Time</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $ga4AvgDuration }}</div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($currDurSecs, $compDurSecs); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Key Events</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ga4EventsCount) }}</div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4EventsCount, $compGa4Events); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 2. Google Search Console --}}
                        @if($isGscConnected)
                            @php
                                $gscBase = $gscData ?? [];
                                $gscSum = $gscBase['summary'] ?? [];
                                $gscClicks = $gscSum['clicks'] ?? 0;
                                $gscImpr = $gscSum['impressions'] ?? 0;
                                $gscCtr = $gscSum['ctr'] ?? 0;
                                $gscPos = $gscSum['position'] ?? 0;

                                $gscComp = $gscBase['compare_data'] ?? [];
                                $gscCompSum = $gscComp['summary'] ?? [];
                                $hasGscComp = !empty($gscCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gsc">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Search Console</h3>
                                            <span class="text-xs text-slate-400">Organic Search Performance</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('gsc')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Clicks</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gscClicks) }}</div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscClicks, $gscCompSum['clicks'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Impressions</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ (isset($this) && method_exists($this, 'formatAbbreviated')) ? $this->formatAbbreviated($gscImpr) : number_format($gscImpr) }}</div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscImpr, $gscCompSum['impressions'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Avg. CTR</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $gscCtr }}%</div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscCtr, $gscCompSum['ctr'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Avg. Position</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $gscPos }}</div>
                                        @if($hasGscComp)
                                            @php 
                                                // For position, lower is better
                                                $pDiff = (float)$gscPos - (float)($gscCompSum['position'] ?? 0);
                                                $pTrend = $pDiff < 0 ? 'up' : ($pDiff > 0 ? 'down' : 'flat');
                                                $pPct = ($gscCompSum['position'] ?? 0) > 0 ? round((abs($pDiff) / $gscCompSum['position']) * 100) : 0;
                                            @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $pTrend === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($pTrend === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $pTrend === 'up' ? '↑' : ($pTrend === 'down' ? '↓' : '•') }} {{ $pPct }}%</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 3. Google Ads --}}
                        @if($isGadsConnected)
                            @php
                                $gadsBase = $gadsData ?? [];
                                $gadsTraffic = $gadsBase['daily_traffic'] ?? [];
                                $gadsClicks = 0; $gadsImpr = 0; $gadsCost = 0; $gadsConv = 0;
                                foreach ($gadsTraffic as $gd) {
                                    $gadsClicks += (int)($gd['clicks'] ?? 0);
                                    $gadsImpr += (int)($gd['impressions'] ?? 0);
                                    $gadsCost += (float)($gd['cost'] ?? 0);
                                    $gadsConv += (float)($gd['conversions'] ?? 0);
                                }
                                $costPerConv = ($gadsConv > 0) ? ($gadsCost / $gadsConv) : 0;

                                $gadsComp = $gadsBase['compare_data'] ?? [];
                                $gadsCompTraffic = $gadsComp['daily_traffic'] ?? [];
                                $hasGadsComp = !empty($gadsCompTraffic);
                                $compGadsCost = 0; $compGadsClicks = 0; $compGadsConv = 0;
                                foreach ($gadsCompTraffic as $gcd) {
                                    $compGadsClicks += (int)($gcd['clicks'] ?? 0);
                                    $compGadsCost += (float)($gcd['cost'] ?? 0);
                                    $compGadsConv += (float)($gcd['conversions'] ?? 0);
                                }
                                $compCostPerConv = ($compGadsConv > 0) ? ($compGadsCost / $compGadsConv) : 0;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gads">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Google Ads</h3>
                                            <span class="text-xs text-slate-400">Paid Campaigns & ROI</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('gads')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Spend</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">${{ number_format($gadsCost) }}</div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsCost, $compGadsCost); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Clicks</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gadsClicks) }}</div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsClicks, $compGadsClicks); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Conversions</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gadsConv) }}</div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsConv, $compGadsConv); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Cost per conv.</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">${{ number_format($costPerConv, 2) }}</div>
                                        @if($hasGadsComp)
                                            @php 
                                                $cpcDiff = $costPerConv - $compCostPerConv;
                                                $cpcTrend = $cpcDiff < 0 ? 'up' : ($cpcDiff > 0 ? 'down' : 'flat');
                                                $cpcPct = $compCostPerConv > 0 ? round((abs($cpcDiff) / $compCostPerConv) * 100) : 0;
                                            @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $cpcTrend === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($cpcTrend === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $cpcTrend === 'up' ? '↑' : ($cpcTrend === 'down' ? '↓' : '•') }} {{ $cpcPct }}%</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 4. Facebook --}}
                        @if($isFacebookConnected)
                            @php
                                $fbBase = $facebookData ?? [];
                                $fbSum = $fbBase['summary'] ?? [];
                                $fbFollowers = $fbSum['followers'] ?? 0;
                                $fbReach = $fbSum['reach'] ?? 0;
                                $fbViews = $fbSum['views'] ?? 0;
                                $fbEngaged = $fbSum['engaged'] ?? 0;

                                $fbComp = $fbBase['compare_data'] ?? [];
                                $fbCompSum = $fbComp['summary'] ?? [];
                                $hasFbComp = !empty($fbCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-facebook">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Facebook</h3>
                                            <span class="text-xs text-slate-400">Social Reach & Engagement</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('facebook')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Page Followers</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($fbFollowers) }}</div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbFollowers, $fbCompSum['followers'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Reach</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($fbReach) }}</div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbReach, $fbCompSum['reach'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Views</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($fbViews) }}</div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbViews, $fbCompSum['views'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Engaged</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($fbEngaged) }}</div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbEngaged, $fbCompSum['engaged'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 5. Google Business Profile --}}
                        @if($isGbpConnected)
                            @php
                                $gbpBase = $gbpData ?? [];
                                $gbpSum = $gbpBase['summary'] ?? [];
                                $gbpViews = $gbpSum['views'] ?? 0;
                                $gbpSearches = $gbpSum['searches'] ?? 0;
                                $gbpInteractions = $gbpSum['interactions'] ?? 0;
                                $gbpCalls = $gbpSum['calls'] ?? 0;

                                $gbpComp = $gbpBase['compare_data'] ?? [];
                                $gbpCompSum = $gbpComp['summary'] ?? [];
                                $hasGbpComp = !empty($gbpCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gbp">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Google Business Profile</h3>
                                            <span class="text-xs text-slate-400">Local Listing & Customer Actions</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('gbp')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Views</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gbpViews) }}</div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpViews, $gbpCompSum['views'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Searches</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gbpSearches) }}</div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpSearches, $gbpCompSum['searches'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Interactions</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gbpInteractions) }}</div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpInteractions, $gbpCompSum['interactions'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Calls</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gbpCalls) }}</div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpCalls, $gbpCompSum['calls'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 6. Keyword.com --}}
                        @if($isKeywordConnected)
                            @php
                                $kwBase = $keywordData ?? [];
                                $kwSum = $kwBase['summary'] ?? [];
                                $kwTotal = $kwSum['total_keywords'] ?? 0;
                                $kwTop10 = $kwSum['top_10'] ?? 0;
                                $kwTop15 = $kwSum['top_15'] ?? 0;
                                $kwPages = $kwSum['top_10_pages'] ?? 0;

                                $kwComp = $kwBase['compare_data'] ?? [];
                                $kwCompSum = $kwComp['summary'] ?? [];
                                $hasKwComp = !empty($kwCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-keyword">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Keyword.com</h3>
                                            <span class="text-xs text-slate-400">SEO Keyword Rank Tracking</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('keyword')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Keywords</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($kwTotal) }}</div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTotal, $kwCompSum['total_keywords'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Top 10 Keywords</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($kwTop10) }}</div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTop10, $kwCompSum['top_10'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Top 15 Keywords</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($kwTop15) }}</div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTop15, $kwCompSum['top_15'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Top 10 Pages</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($kwPages) }}</div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwPages, $kwCompSum['top_10_pages'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 7. YouTube --}}
                        @if($isYoutubeConnected)
                            @php
                                $ytBase = $youtubeData ?? [];
                                $ytSum = $ytBase['summary'] ?? [];
                                $ytViews = $ytSum['views'] ?? 0;
                                $ytWatchTime = $ytSum['watch_time'] ?? 0;
                                $ytSubs = $ytSum['subscribers'] ?? 0;
                                $ytAvgDuration = $ytSum['avg_view_duration'] ?? '0s';

                                $ytComp = $ytBase['compare_data'] ?? [];
                                $ytCompSum = $ytComp['summary'] ?? [];
                                $hasYtComp = !empty($ytCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-youtube">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">YouTube</h3>
                                            <span class="text-xs text-slate-400">Video Views & Channel Growth</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('youtube')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Views</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ytViews) }}</div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytViews, $ytCompSum['views'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Watch Time (hrs)</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ytWatchTime, 1) }}</div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytWatchTime, $ytCompSum['watch_time'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Subscribers</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($ytSubs) }}</div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytSubs, $ytCompSum['subscribers'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Avg Duration</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $ytAvgDuration }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 8. LinkedIn --}}
                        @if($isLinkedinConnected)
                            @php
                                $liBase = $linkedinData ?? [];
                                $liSum = $liBase['summary'] ?? [];
                                $liFollowers = $liSum['followers'] ?? 0;
                                $liImpr = $liSum['impressions'] ?? 0;
                                $liClicks = $liSum['clicks'] ?? 0;
                                $liEngagements = $liSum['engagements'] ?? 0;

                                $liComp = $liBase['compare_data'] ?? [];
                                $liCompSum = $liComp['summary'] ?? [];
                                $hasLiComp = !empty($liCompSum);
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-linkedin">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">LinkedIn</h3>
                                            <span class="text-xs text-slate-400">Professional Network Engagement</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('linkedin')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Followers</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($liFollowers) }}</div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liFollowers, $liCompSum['followers'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Impressions</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($liImpr) }}</div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liImpr, $liCompSum['impressions'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Clicks</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($liClicks) }}</div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liClicks, $liCompSum['clicks'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Engagements</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($liEngagements) }}</div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liEngagements, $liCompSum['engagements'] ?? 0); @endphp
                                            <div class="mt-2 text-[11px] font-bold flex items-center gap-1 {{ $d['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($d['trend'] === 'down' ? 'text-rose-500' : 'text-slate-400') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="text-slate-400 font-normal">vs. prev</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 9. Google Tag Manager --}}
                        @if($isGtmConnected)
                            @php
                                $gtmBase = $gtmData ?? [];
                                $gtmSum = $gtmBase['summary'] ?? [];
                                $gtmTags = $gtmSum['tags_count'] ?? 0;
                                $gtmTriggers = $gtmSum['triggers_count'] ?? 0;
                                $gtmVariables = $gtmSum['variables_count'] ?? 0;
                                $gtmFolders = $gtmSum['folders_count'] ?? 0;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gtm">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Tag Manager</h3>
                                            <span class="text-xs text-slate-400">Container Overview & Assets</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="selectIntegration('gtm')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Tags</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gtmTags) }}</div>
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Triggers</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gtmTriggers) }}</div>
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Variables</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gtmVariables) }}</div>
                                    </div>

                                    <div class="bg-slate-50/60 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Folders</div>
                                        <div class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($gtmFolders) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div wire:key="integration-view-single-{{ $activeReportIntegrationId }}">
                        @include('modules.crm.clients.client-report', ['hideHeader' => true])
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
