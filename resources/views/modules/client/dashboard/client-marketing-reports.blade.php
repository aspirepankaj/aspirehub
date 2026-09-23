<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Marketing Reports' => null]" />
    @php
        $isGa4Connected = isset($integrations['ga4']);
        $isGscConnected = isset($integrations['gsc']);
        $isYoutubeConnected = isset($integrations['youtube']);
        $isKeywordConnected = isset($integrations['keyword']);
        $isGbpConnected = isset($integrations['gbp']);
        $isGadsConnected = isset($integrations['gads']);
        $isGtmConnected = isset($integrations['gtm']);

        $hasGa4 = !empty($ga4Data) && !isset($ga4Data['error']);
        $hasGsc = !empty($gscData) && !isset($gscData['error']);
        $hasYoutube = !empty($youtubeData) && !isset($youtubeData['error']);
        $hasKeyword = !empty($keywordData) && !isset($keywordData['error']);
        $hasGtm = !empty($gtmData) && !isset($gtmData['error']);
        $hasGbp = !empty($gbpData) && !isset($gbpData['error']);
        $hasGads = !empty($gadsData) && !isset($gadsData['error']);
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

                <!-- GADS Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isGadsConnected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Google Ads</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Selector Tabs / Date Filter Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Integration Tabs (Scrollable on mobile) -->
        @if($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected || $isGbpConnected || $isGadsConnected)
            <div class="w-full lg:w-auto overflow-x-auto pb-1 scrollbar-none max-w-full">
                <div class="flex items-center p-1 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur border border-slate-200/30 dark:border-slate-700/30 rounded-xl min-w-max">
                    <button wire:click="selectIntegration('overview')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <svg wire:loading.remove wire:target="selectIntegration('overview')" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <svg wire:loading wire:target="selectIntegration('overview')" class="w-4 h-4 text-indigo-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Overview Dashboard</span>
                    </button>
                    @if($isGa4Connected)
                        <button wire:click="selectIntegration('ga4')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'ga4' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('ga4')" class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                            <svg wire:loading wire:target="selectIntegration('ga4')" class="w-4 h-4 text-amber-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Google Analytics 4</span>
                        </button>
                    @endif
                    @if($isGscConnected)
                        <button wire:click="selectIntegration('gsc')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gsc' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('gsc')" class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            <svg wire:loading wire:target="selectIntegration('gsc')" class="w-4 h-4 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Search Console</span>
                        </button>
                    @endif
                    @if($isYoutubeConnected)
                        <button wire:click="selectIntegration('youtube')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'youtube' ? 'bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('youtube')" class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <svg wire:loading wire:target="selectIntegration('youtube')" class="w-4 h-4 text-red-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>YouTube</span>
                        </button>
                    @endif
                    @if($isKeywordConnected)
                        <button wire:click="selectIntegration('keyword')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'keyword' ? 'bg-white dark:bg-slate-900 text-purple-600 dark:text-purple-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('keyword')" class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            <svg wire:loading wire:target="selectIntegration('keyword')" class="w-4 h-4 text-purple-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Keyword.com</span>
                        </button>
                    @endif
                    @if($isGbpConnected)
                        <button wire:click="selectIntegration('gbp')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gbp' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('gbp')" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg wire:loading wire:target="selectIntegration('gbp')" class="w-4 h-4 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Business Profile</span>
                        </button>
                    @endif
                    @if($isGadsConnected)
                        <button wire:click="selectIntegration('gads')" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeReportIntegrationId === 'gads' ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <svg wire:loading.remove wire:target="selectIntegration('gads')" class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg wire:loading wire:target="selectIntegration('gads')" class="w-4 h-4 text-teal-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Google Ads</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Date Range Calendar Selectors (-90 Days to +90 Days) -->
        <!-- GA4 Style Date Range Picker Popover -->
        @if($activeReportIntegrationId !== 'overview')
            @php
                $minDateBound = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
                $maxDateBound = \Carbon\Carbon::now()->format('Y-m-d');
            @endphp
            @include('partials.ga4-date-picker', ['minDateBound' => $minDateBound, 'maxDateBound' => $maxDateBound])
        @endif
    </div>

    <!-- Integration Views (Uses Admin/Staff Layout) -->
    @if($activeReportIntegrationId === 'overview')
        <div class="space-y-12">
        @if($isGa4Connected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Analytics 4</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'ga4', 'activeReportData' => $ga4Data])</div>
        @endif
        @if($isGscConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Search Console</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'gsc', 'activeReportData' => $gscData])</div>
        @endif
        @if($isYoutubeConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">YouTube</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'youtube', 'activeReportData' => $youtubeData])</div>
        @endif
        @if($isKeywordConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Keyword.com</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'keyword', 'activeReportData' => $keywordData])</div>
        @endif
        @if($isGbpConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Business Profile</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'gbp', 'activeReportData' => $gbpData])</div>
        @endif
        @if($isGtmConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Tag Manager</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'gtm', 'activeReportData' => $gtmData])</div>
        @endif
        @if($isGadsConnected)
            <div><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Ads</h3>
            @include('modules.crm.clients.client-report', ['hideHeader' => true, 'activeReportIntegrationId' => 'gads', 'activeReportData' => $gadsData])</div>
        @endif
        </div>
    @else
        @include('modules.crm.clients.client-report', ['hideHeader' => true])
    @endif
</div>
