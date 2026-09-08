<div class="space-y-6">
    @php
        $isGa4Connected = isset($integrations['ga4']);
        $isGscConnected = isset($integrations['gsc']);
        $isYoutubeConnected = isset($integrations['youtube']);
        $isKeywordConnected = isset($integrations['keyword']);

        $hasGa4 = !empty($ga4Data) && !isset($ga4Data['error']);
        $hasGsc = !empty($gscData) && !isset($gscData['error']);
        $hasYoutube = !empty($youtubeData) && !isset($youtubeData['error']);
        $hasKeyword = !empty($keywordData) && !isset($keywordData['error']);
        $hasGtm = !empty($gtmData) && !isset($gtmData['error']);
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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                @else
                    <span class="text-xs text-slate-400">No registered websites found.</span>
                @endif
            </div>

            <!-- Integrations Status List -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-2 w-full sm:w-auto">Integrations Status</span>
                
                <!-- GA4 Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isGa4Connected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Google Analytics 4</span>
                </div>

                <!-- GSC Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isGscConnected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Search Console</span>
                </div>

                <!-- YouTube Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isYoutubeConnected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">YouTube</span>
                </div>

                <!-- Keyword Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isKeywordConnected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Keyword.com</span>
                </div>

                <!-- GTM Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ isset($integrations['gtm']) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Google Tag Manager</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Selector Tabs / Date Filter Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Integration Tabs (Scrollable on mobile) -->
        @if($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected)
            <div class="w-full lg:w-auto overflow-x-auto pb-1 scrollbar-none max-w-full">
                <div class="flex items-center p-1 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur border border-slate-200/30 dark:border-slate-700/30 rounded-xl min-w-max">
                    <button wire:click="selectIntegration('overview')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Overview Dashboard</span>
                    </button>
                    @if($isGa4Connected)
                        <button wire:click="selectIntegration('ga4')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'ga4' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                            <span>Google Analytics 4</span>
                        </button>
                    @endif
                    @if($isGscConnected)
                        <button wire:click="selectIntegration('gsc')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gsc' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            <span>Search Console</span>
                        </button>
                    @endif
                    @if($isYoutubeConnected)
                        <button wire:click="selectIntegration('youtube')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'youtube' ? 'bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span>YouTube</span>
                        </button>
                    @endif
                    @if($isKeywordConnected)
                        <button wire:click="selectIntegration('keyword')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'keyword' ? 'bg-white dark:bg-slate-900 text-purple-600 dark:text-purple-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            <span>Keyword.com</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Month Selectors -->
        @if(!empty($availableMonths))
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <!-- Primary Month Selector -->
                <div class="relative flex-1 lg:w-44">
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-0.5 ml-1">Select Month</label>
                    <select wire:model.live="selectedMonth" style="background-image: none;" class="appearance-none w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 pr-8 cursor-pointer shadow-sm">
                        @foreach($availableMonths as $monthOpt)
                            <option value="{{ $monthOpt['value'] }}">{{ $monthOpt['label'] }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute bottom-2 right-0 flex items-center px-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>

                <!-- Compare Month Selector (Only shown on individual integration tabs) -->
                @if($activeReportIntegrationId !== 'overview')
                    <div class="relative flex-1 lg:w-48">
                        <label class="block text-[9px] font-bold text-indigo-500 uppercase tracking-widest mb-0.5 ml-1">Compare With</label>
                        <select wire:model.live="compareMonth" style="background-image: none;" class="appearance-none w-full bg-indigo-50/50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-900/60 rounded-xl px-3 py-1.5 text-xs font-bold text-indigo-700 dark:text-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 pr-8 cursor-pointer shadow-sm">
                            <option value="">Compare: Off</option>
                            @foreach($availableMonths as $monthOpt)
                                @if($monthOpt['value'] !== $selectedMonth)
                                    <option value="{{ $monthOpt['value'] }}">vs {{ $monthOpt['label'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute bottom-2 right-0 flex items-center px-2.5 text-indigo-500">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- ==================== OVERVIEW DASHBOARD VIEW ==================== -->
    @if($activeReportIntegrationId === 'overview')
        @if($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected)
            <!-- Dynamic Metric Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" wire:key="overview-grid-{{ $selectedMonth }}">
                <!-- GA4 Metrics -->
                @if($hasGa4)
                    @php 
                        $uCount = (float)($ga4Data['overall_summary']['active_users'] ?? 0);
                        $sCount = (float)($ga4Data['overall_summary']['sessions'] ?? 0);
                        $pCount = (float)($ga4Data['overall_summary']['pageviews'] ?? 0);
                    @endphp
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Users</span>
                            <span class="text-[9px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded">GA4</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $uCount }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($uCount) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Sessions</span>
                            <span class="text-[9px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded">GA4</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $sCount }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($sCount) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Pageviews</span>
                            <span class="text-[9px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded">GA4</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $pCount }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($pCount) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Avg Duration</span>
                            <span class="text-[9px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded">GA4</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ $ga4Data['overall_summary']['avg_session_duration'] ?? '0s' }}
                        </div>
                    </div>
                @endif

                <!-- GSC Metrics -->
                @if($hasGsc)
                    @php 
                        $cCount = (float)($gscData['summary']['clicks'] ?? 0);
                        $iCount = (float)($gscData['summary']['impressions'] ?? 0);
                        $ctrVal = (float)($gscData['summary']['ctr'] ?? 0);
                        $posVal = (float)($gscData['summary']['position'] ?? 0);
                    @endphp
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Total Clicks</span>
                            <span class="text-[9px] font-bold text-blue-500 bg-blue-50 dark:bg-blue-950/30 px-1.5 py-0.5 rounded">GSC</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $cCount }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($cCount) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Impressions</span>
                            <span class="text-[9px] font-bold text-blue-500 bg-blue-50 dark:bg-blue-950/30 px-1.5 py-0.5 rounded">GSC</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $iCount }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($iCount) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Average CTR</span>
                            <span class="text-[9px] font-bold text-blue-500 bg-blue-50 dark:bg-blue-950/30 px-1.5 py-0.5 rounded">GSC</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0%', target: {{ $ctrVal }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1)+'%'; if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($ctrVal, 1) }}%
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Avg Position</span>
                            <span class="text-[9px] font-bold text-blue-500 bg-blue-50 dark:bg-blue-950/30 px-1.5 py-0.5 rounded">GSC</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white"
                             x-data="{ val: '0', target: {{ $posVal }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($posVal, 1) }}
                        </div>
                    </div>
                @endif

                <!-- YouTube Metrics -->
                @if($hasYoutube)
                    @php 
                        $ytViews = (float)($youtubeData['summary']['views'] ?? 0);
                        $ytHrs = (float)($youtubeData['summary']['watch_time'] ?? 0);
                        $ytSubs = (float)($youtubeData['summary']['subscribers'] ?? 0);
                    @endphp
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>YouTube Views</span>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-1.5 py-0.5 rounded">YouTube</span>
                        </div>
                        <div class="text-2xl font-extrabold text-red-600 dark:text-red-400"
                             x-data="{ val: '0', target: {{ $ytViews }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($ytViews) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Watch Time (hrs)</span>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-1.5 py-0.5 rounded">YouTube</span>
                        </div>
                        <div class="text-2xl font-extrabold text-orange-600 dark:text-orange-400"
                             x-data="{ val: '0', target: {{ $ytHrs }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($ytHrs, 1) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Subscribers</span>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-1.5 py-0.5 rounded">YouTube</span>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400"
                             x-data="{ val: '0', target: {{ $ytSubs }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($ytSubs) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Avg Duration</span>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-1.5 py-0.5 rounded">YouTube</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ $youtubeData['summary']['avg_view_duration'] ?? '0s' }}
                        </div>
                    </div>
                @endif

                <!-- Keyword.com Metrics -->
                @if($hasKeyword)
                    @php 
                        $kwTotal = (float)($keywordData['summary']['total_keywords'] ?? 0);
                        $kwTop10 = (float)($keywordData['summary']['top_10'] ?? 0);
                        $kwUp = (float)($keywordData['summary']['up_movements'] ?? $keywordData['summary']['improved'] ?? 0);
                    @endphp
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Total Keywords</span>
                            <span class="text-[9px] font-bold text-purple-500 bg-purple-50 dark:bg-purple-950/30 px-1.5 py-0.5 rounded">Keyword</span>
                        </div>
                        <div class="text-2xl font-extrabold text-blue-600 dark:text-blue-400"
                             x-data="{ val: '0', target: {{ $kwTotal }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($kwTotal) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Top 10 Rankings</span>
                            <span class="text-[9px] font-bold text-purple-500 bg-purple-50 dark:bg-purple-950/30 px-1.5 py-0.5 rounded">Keyword</span>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400"
                             x-data="{ val: '0', target: {{ $kwTop10 }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($kwTop10) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Up Movements</span>
                            <span class="text-[9px] font-bold text-purple-500 bg-purple-50 dark:bg-purple-950/30 px-1.5 py-0.5 rounded">Keyword</span>
                        </div>
                        <div class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400"
                             x-data="{ val: '0', target: {{ $kwUp }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                             x-text="val">
                            {{ number_format($kwUp) }}
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                            <span>Share of Voice</span>
                            <span class="text-[9px] font-bold text-purple-500 bg-purple-50 dark:bg-purple-950/30 px-1.5 py-0.5 rounded">Keyword</span>
                        </div>
                        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                            {{ $keywordData['summary']['share_of_voice'] ?? '0%' }}
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
                                      chart: null,
                                      initChart() {
                                          const canvas = document.getElementById('weeklyTrendChart');
                                          if (!canvas) return;
                                          try {
                                              let existing = Chart.getChart(canvas);
                                              if (existing) existing.destroy();
                                          } catch(e) {}
                                          if (this.chart) {
                                              try { this.chart.destroy(); } catch(e) {}
                                              this.chart = null;
                                          }
                                          const ctx = canvas.getContext('2d');
                                          
                                          // Get reactive daily_traffic array directly from Livewire property
                                          let dailyTraffic = ($wire.ga4Data && $wire.ga4Data.daily_traffic) ? $wire.ga4Data.daily_traffic : [];
                                          
                                          // Parse selected month (e.g. '2026-august' or '2026-september')
                                          let parts = (this.month || '').split('-');
                                          let year = parseInt(parts[0], 10) || (new Date()).getFullYear();
                                          let monthName = (parts[1] || '').toLowerCase();
                                          
                                          const monthMap = {
                                              'january':1, 'february':2, 'march':3, 'april':4, 'may':5, 'june':6,
                                              'july':7, 'august':8, 'september':9, 'october':10, 'november':11, 'december':12
                                          };
                                          let monthNum = monthMap[monthName] || ((new Date()).getMonth() + 1);
                                          let daysInMonth = new Date(year, monthNum, 0).getDate();

                                          // Map existing data by day number
                                          let dataByDay = {};
                                          dailyTraffic.forEach(item => {
                                              let rawStr = String(item.date || '');
                                              let dayNum = 0;
                                              if (rawStr.length === 8) {
                                                  dayNum = parseInt(rawStr.substring(6, 8), 10);
                                              } else if (rawStr) {
                                                  dayNum = parseInt(rawStr, 10);
                                              }
                                              if (dayNum > 0) {
                                                  dataByDay[dayNum] = item;
                                              }
                                          });

                                          let now = new Date();
                                          let currentYear = now.getFullYear();
                                          let currentMonth = now.getMonth() + 1;
                                          let isCurrentMonth = (year === currentYear && monthNum === currentMonth);
                                          
                                          // Current month -> display up to today's date
                                          // Past month -> display all days of that month
                                          let maxDayToDisplay = isCurrentMonth ? Math.min(daysInMonth, now.getDate()) : daysInMonth;

                                          let labels = [];
                                          let uData = [];
                                          let pData = [];

                                          if (maxDayToDisplay === 1) {
                                              labels = ['Day 1', 'Day 1 (Today)'];
                                              let day1Data = dataByDay[1] || (dailyTraffic && dailyTraffic[0]) || { users: 0, pageviews: 0 };
                                              let uVal = day1Data.users || 0;
                                              let pVal = day1Data.pageviews || 0;
                                              uData = [uVal, uVal];
                                              pData = [pVal, pVal];
                                          } else {
                                              for (let day = 1; day <= maxDayToDisplay; day++) {
                                                  labels.push('Day ' + day);
                                                  if (dataByDay[day]) {
                                                      uData.push(dataByDay[day].users || 0);
                                                      pData.push(dataByDay[day].pageviews || 0);
                                                  } else {
                                                      uData.push(0);
                                                      pData.push(0);
                                                  }
                                              }
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
                                                          backgroundColor: 'rgba(59, 130, 246, 0.08)',
                                                          borderWidth: 2.5,
                                                          pointBackgroundColor: '#3b82f6',
                                                          pointBorderColor: '#ffffff',
                                                          pointBorderWidth: 2,
                                                          pointRadius: maxDayToDisplay === 1 ? 6 : 3,
                                                          pointHoverRadius: 7,
                                                          tension: 0.35,
                                                          fill: true
                                                      },
                                                      {
                                                          label: 'Pageviews',
                                                          data: pData,
                                                          borderColor: '#6366f1',
                                                          backgroundColor: 'rgba(99, 102, 241, 0.08)',
                                                          borderWidth: 2.5,
                                                          pointBackgroundColor: '#6366f1',
                                                          pointBorderColor: '#ffffff',
                                                          pointBorderWidth: 2,
                                                          pointRadius: maxDayToDisplay === 1 ? 6 : 3,
                                                          pointHoverRadius: 7,
                                                          tension: 0.35,
                                                          fill: true
                                                      }
                                                  ]
                                              },
                                              options: {
                                                  responsive: true,
                                                  maintainAspectRatio: false,
                                                  animation: {
                                                      duration: 1200,
                                                      easing: 'easeInOutQuart'
                                                  },
                                                  plugins: {
                                                      legend: {
                                                          position: 'bottom',
                                                          labels: { color: '#64748b', boxWidth: 10, usePointStyle: true }
                                                      }
                                                  },
                                                  scales: {
                                                      x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
                                                      y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.08)' }, ticks: { color: '#64748b', font: { size: 10 } } }
                                                  }
                                              }
                                          });
                                      }
                                  }"
                                  x-init="initChart(); $watch('month', () => { setTimeout(() => initChart(), 150); })"
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
                                     chart: null,
                                     initChart() {
                                          const canvas = document.getElementById('channelMixChart');
                                          if (!canvas) return;
                                          try {
                                              let existing = Chart.getChart(canvas);
                                              if (existing) existing.destroy();
                                          } catch(e) {}
                                          if (this.chart) {
                                              try { this.chart.destroy(); } catch(e) {}
                                              this.chart = null;
                                          }
                                          const ctx = canvas.getContext('2d');
                                         
                                         let sources = ($wire.ga4Data && $wire.ga4Data.traffic_sources) ? $wire.ga4Data.traffic_sources : [];
                                         let labels = sources.map(c => c.source_medium);
                                         let data = sources.map(c => c.sessions);
                                         
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
                                                 cutout: '72%',
                                                 animation: {
                                                     duration: 1200,
                                                     animateRotate: true,
                                                     animateScale: true,
                                                     easing: 'easeInOutQuart'
                                                 },
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:key="overview-tables-{{ $selectedMonth }}">
                <!-- Top Viewed Pages (GA4) -->
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

                <!-- Top Keywords / Search Queries (GSC / GA4) -->
                @if(($hasGsc && !empty($gscData['top_queries'])) || ($hasGa4 && !empty($ga4Data['top_keywords'])))
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Search Queries</h3>
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

                <!-- YouTube Top Videos Summary (If Active) -->
                @if($hasYoutube && !empty($youtubeData['top_videos']))
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Top Performing Videos</h3>
                            <span class="text-[10px] font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-2 py-0.5 rounded-full">YouTube</span>
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
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                    @foreach(array_slice($youtubeData['top_videos'], 0, 5) as $v)
                                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                            <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300 truncate max-w-xs">{{ $v['title'] }}</td>
                                            <td class="px-6 py-3.5 text-right font-semibold text-red-600 dark:text-red-400">{{ number_format($v['views'] ?? 0) }}</td>
                                            <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($v['watch_time'] ?? 0, 1) }} hrs</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Keyword.com Summary (If Active) -->
                @if($hasKeyword && !empty($keywordData['keywords']))
                    <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Keyword Rankings</h3>
                            <span class="text-[10px] font-bold text-purple-500 bg-purple-50 dark:bg-purple-950/30 px-2 py-0.5 rounded-full">Keyword.com</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-50 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/40 dark:bg-slate-900/10">
                                        <th class="px-6 py-3.5">Keyword</th>
                                        <th class="px-6 py-3.5 text-right">Volume</th>
                                        <th class="px-6 py-3.5 text-right">Rank</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60 text-xs">
                                    @foreach(array_slice($keywordData['keywords'], 0, 5) as $kw)
                                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/10 text-slate-700 dark:text-slate-300">
                                            <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300">{{ $kw['keyword'] }}</td>
                                            <td class="px-6 py-3.5 text-right font-medium text-slate-500">{{ number_format($kw['volume'] ?? 0) }}</td>
                                            <td class="px-6 py-3.5 text-right font-bold text-purple-600 dark:text-purple-400">{{ $kw['position'] > 0 ? $kw['position'] : '-' }}</td>
                                        </tr>
                                    @endforeach
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
            @if(!empty($compareMonth))
                <div class="mb-5 p-4 rounded-2xl bg-slate-900 text-white shadow-sm flex items-center justify-between border border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-500/20 rounded-xl text-indigo-400 border border-indigo-400/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 block mb-0.5">Month Comparison Active</span>
                            <div class="text-xs font-bold flex items-center gap-2">
                                <span class="text-white">{{ ucfirst(explode('-', $selectedMonth)[1] ?? '') }} {{ explode('-', $selectedMonth)[0] ?? '' }}</span>
                                <span class="text-slate-400 font-normal">compared to</span>
                                <span class="text-indigo-300">{{ ucfirst(explode('-', $compareMonth)[1] ?? '') }} {{ explode('-', $compareMonth)[0] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="$set('compareMonth', '')" class="text-xs bg-white/10 hover:bg-white/20 text-slate-200 px-3 py-1.5 rounded-xl transition-colors font-semibold border border-white/10 flex items-center gap-1.5">
                        <span>Clear Comparison</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            <!-- GA4 Top Metrics Cards -->
            @php 
                $ga4Users = (float)($ga4Data['overall_summary']['active_users'] ?? 0);
                $ga4Views = (float)($ga4Data['overall_summary']['pageviews'] ?? 0);
                $ga4Sess = (float)($ga4Data['overall_summary']['sessions'] ?? 0);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6" wire:key="ga4-grid-{{ $selectedMonth }}-{{ $compareMonth }}">
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Active Users</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $ga4Users }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($ga4Users) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($ga4Data['overall_summary']['active_users'] ?? 0, $compareGa4Data['overall_summary']['active_users'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Pageviews</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $ga4Views }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($ga4Views) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($ga4Data['overall_summary']['pageviews'] ?? 0, $compareGa4Data['overall_summary']['pageviews'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Sessions</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $ga4Sess }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($ga4Sess) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($ga4Data['overall_summary']['sessions'] ?? 0, $compareGa4Data['overall_summary']['sessions'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Bounce Rate</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ $ga4Data['overall_summary']['bounce_rate'] ?? '0.0%' }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($ga4Data['overall_summary']['bounce_rate'] ?? 0, $compareGa4Data['overall_summary']['bounce_rate'] ?? 0, false) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Avg Session Duration</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white">
                        {{ $ga4Data['overall_summary']['avg_session_duration'] ?? '0s' }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($ga4Data['overall_summary']['avg_session_duration'] ?? 0, $compareGa4Data['overall_summary']['avg_session_duration'] ?? 0) !!}
                    @endif
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
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
            @if(!empty($compareMonth))
                <div class="mb-5 p-4 rounded-2xl bg-slate-900 text-white shadow-sm flex items-center justify-between border border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-500/20 rounded-xl text-indigo-400 border border-indigo-400/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 block mb-0.5">Search Console Comparison Active</span>
                            <div class="text-xs font-bold flex items-center gap-2">
                                <span class="text-white">{{ ucfirst(explode('-', $selectedMonth)[1] ?? '') }} {{ explode('-', $selectedMonth)[0] ?? '' }}</span>
                                <span class="text-slate-400 font-normal">compared to</span>
                                <span class="text-indigo-300">{{ ucfirst(explode('-', $compareMonth)[1] ?? '') }} {{ explode('-', $compareMonth)[0] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="$set('compareMonth', '')" class="text-xs bg-white/10 hover:bg-white/20 text-slate-200 px-3 py-1.5 rounded-xl transition-colors font-semibold border border-white/10 flex items-center gap-1.5">
                        <span>Clear Comparison</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            <!-- GSC Top Metrics Cards -->
            @php
                $gscClicks = (float)($gscData['summary']['clicks'] ?? 0);
                $gscImps = (float)($gscData['summary']['impressions'] ?? 0);
                $gscCtr = (float)($gscData['summary']['ctr'] ?? 0);
                $gscPos = (float)($gscData['summary']['position'] ?? 0);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6" wire:key="gsc-grid-{{ $selectedMonth }}-{{ $compareMonth }}">
                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Clicks</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $gscClicks }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($gscClicks) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($gscData['summary']['clicks'] ?? 0, $compareGscData['summary']['clicks'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Impressions</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $gscImps }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($gscImps) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($gscData['summary']['impressions'] ?? 0, $compareGscData['summary']['impressions'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Average CTR</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0%', target: {{ $gscCtr }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1)+'%'; if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($gscCtr, 1) }}%
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($gscData['summary']['ctr'] ?? 0, $compareGscData['summary']['ctr'] ?? 0) !!}
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 p-5 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Average Position</div>
                    <div class="text-xl font-black text-slate-800 dark:text-white"
                         x-data="{ val: '0', target: {{ $gscPos }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                         x-text="val">
                        {{ number_format($gscPos, 1) }}
                    </div>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($gscData['summary']['position'] ?? 0.0, $compareGscData['summary']['position'] ?? 0.0, false) !!}
                    @endif
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
            @if(!empty($compareMonth))
                <div class="mb-5 p-4 rounded-2xl bg-slate-900 text-white shadow-sm flex items-center justify-between border border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-500/20 rounded-xl text-red-400 border border-red-400/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-red-400 block mb-0.5">YouTube Comparison Active</span>
                            <div class="text-xs font-bold flex items-center gap-2">
                                <span class="text-white">{{ ucfirst(explode('-', $selectedMonth)[1] ?? '') }} {{ explode('-', $selectedMonth)[0] ?? '' }}</span>
                                <span class="text-slate-400 font-normal">compared to</span>
                                <span class="text-red-300">{{ ucfirst(explode('-', $compareMonth)[1] ?? '') }} {{ explode('-', $compareMonth)[0] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="$set('compareMonth', '')" class="text-xs bg-white/10 hover:bg-white/20 text-slate-200 px-3 py-1.5 rounded-xl transition-colors font-semibold border border-white/10 flex items-center gap-1.5">
                        <span>Clear Comparison</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            <!-- 1. Stats Summary Widgets (YouTube) -->
            @php
                $ytV = (float)($activeReportData['summary']['views'] ?? 0);
                $ytW = (float)($activeReportData['summary']['watch_time'] ?? 0);
                $ytS = (float)($activeReportData['summary']['subscribers'] ?? 0);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6" wire:key="youtube-grid-{{ $selectedMonth }}-{{ $compareMonth }}">
                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Views</span>
                    <span class="text-xl font-black text-red-600 dark:text-red-400"
                          x-data="{ val: '0', target: {{ $ytV }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($ytV) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['views'] ?? 0, $compareYoutubeData['summary']['views'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Watch Time (hrs)</span>
                    <span class="text-xl font-black text-orange-600 dark:text-orange-400"
                          x-data="{ val: '0', target: {{ $ytW }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=(this.target*e).toFixed(1); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($ytW, 1) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['watch_time'] ?? 0, $compareYoutubeData['summary']['watch_time'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Subscribers</span>
                    <span class="text-xl font-black text-emerald-600 dark:text-emerald-400"
                          x-data="{ val: '0', target: {{ $ytS }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($ytS) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['subscribers'] ?? 0, $compareYoutubeData['summary']['subscribers'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Duration</span>
                    <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">
                        {{ $activeReportData['summary']['avg_view_duration'] ?? '0s' }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['avg_view_duration'] ?? 0, $compareYoutubeData['summary']['avg_view_duration'] ?? 0) !!}
                    @endif
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
            @if(!empty($compareMonth))
                <div class="mb-5 p-4 rounded-2xl bg-slate-900 text-white shadow-sm flex items-center justify-between border border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500/20 rounded-xl text-purple-400 border border-purple-400/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-purple-400 block mb-0.5">Keyword.com Comparison Active</span>
                            <div class="text-xs font-bold flex items-center gap-2">
                                <span class="text-white">{{ ucfirst(explode('-', $selectedMonth)[1] ?? '') }} {{ explode('-', $selectedMonth)[0] ?? '' }}</span>
                                <span class="text-slate-400 font-normal">compared to</span>
                                <span class="text-purple-300">{{ ucfirst(explode('-', $compareMonth)[1] ?? '') }} {{ explode('-', $compareMonth)[0] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="$set('compareMonth', '')" class="text-xs bg-white/10 hover:bg-white/20 text-slate-200 px-3 py-1.5 rounded-xl transition-colors font-semibold border border-white/10 flex items-center gap-1.5">
                        <span>Clear Comparison</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            <!-- 1. Stats Summary Widgets (Keyword) -->
            @php
                $kwTot = (float)($activeReportData['summary']['total_keywords'] ?? 0);
                $kwTop = (float)($activeReportData['summary']['top_10'] ?? 0);
                $kwImp = (float)($activeReportData['summary']['improved'] ?? 0);
                $kwDec = (float)($activeReportData['summary']['declined'] ?? 0);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6" wire:key="keyword-grid-{{ $selectedMonth }}-{{ $compareMonth }}">
                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Keywords</span>
                    <span class="text-xl font-black text-blue-600 dark:text-blue-400"
                          x-data="{ val: '0', target: {{ $kwTot }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($kwTot) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['total_keywords'] ?? 0, $compareKeywordData['summary']['total_keywords'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10 Rankings</span>
                    <span class="text-xl font-black text-emerald-600 dark:text-emerald-400"
                          x-data="{ val: '0', target: {{ $kwTop }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($kwTop) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['top_10'] ?? 0, $compareKeywordData['summary']['top_10'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Improved Keywords</span>
                    <span class="text-xl font-black text-indigo-600 dark:text-indigo-400"
                          x-data="{ val: '0', target: {{ $kwImp }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($kwImp) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['improved'] ?? 0, $compareKeywordData['summary']['improved'] ?? 0) !!}
                    @endif
                </div>

                <div class="p-5 bg-white dark:bg-slate-950/40 border border-slate-100 dark:border-slate-900 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Declined Keywords</span>
                    <span class="text-xl font-black text-red-600 dark:text-red-400"
                          x-data="{ val: '0', target: {{ $kwDec }}, init() { let s=Date.now(), d=1000, f=()=>{ let p=Math.min((Date.now()-s)/d, 1), e=1-Math.pow(1-p, 3); this.val=Math.floor(this.target*e).toLocaleString(); if(p<1) requestAnimationFrame(f); }; requestAnimationFrame(f); } }"
                          x-text="val">
                        {{ number_format($kwDec) }}
                    </span>
                    @if(!empty($compareMonth))
                        {!! $this->renderCompareDiff($activeReportData['summary']['declined'] ?? 0, $compareKeywordData['summary']['declined'] ?? 0, false) !!}
                    @endif
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
    <!-- ==================== GOOGLE TAG MANAGER VIEW ==================== -->
    @if($activeReportIntegrationId === 'gtm')
        @if($hasGtm)
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

