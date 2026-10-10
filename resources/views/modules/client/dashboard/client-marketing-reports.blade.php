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
                <div x-data class="flex items-center p-1 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur border border-slate-200/30 dark:border-slate-700/30 rounded-xl min-w-max">
                    <button type="button" x-on:click="$wire.activeReportIntegrationId = 'overview'; $wire.selectIntegration('overview')" 
                        :class="$wire.activeReportIntegrationId === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Overview Dashboard</span>
                    </button>
                    @if($isGbpConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'gbp'; $wire.selectIntegration('gbp')" 
                            :class="$wire.activeReportIntegrationId === 'gbp' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Business Profile</span>
                        </button>
                    @endif
                    @if($isFacebookConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'facebook'; $wire.selectIntegration('facebook')" 
                            :class="$wire.activeReportIntegrationId === 'facebook' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-500 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                            <span>Facebook</span>
                        </button>
                    @endif
                    @if($isGadsConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'gads'; $wire.selectIntegration('gads')" 
                            :class="$wire.activeReportIntegrationId === 'gads' ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Google Ads</span>
                        </button>
                    @endif
                    @if($isGa4Connected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'ga4'; $wire.selectIntegration('ga4')" 
                            :class="$wire.activeReportIntegrationId === 'ga4' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                            <span>Google Analytics 4</span>
                        </button>
                    @endif
                    @if($isKeywordConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'keyword'; $wire.selectIntegration('keyword')" 
                            :class="$wire.activeReportIntegrationId === 'keyword' ? 'bg-white dark:bg-slate-900 text-purple-600 dark:text-purple-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            <span>Keyword.com</span>
                        </button>
                    @endif
                    @if($isLinkedinConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'linkedin'; $wire.selectIntegration('linkedin')" 
                            :class="$wire.activeReportIntegrationId === 'linkedin' ? 'bg-white dark:bg-slate-900 text-sky-600 dark:text-sky-500 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-sky-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            <span>LinkedIn</span>
                        </button>
                    @endif
                    @if($isGscConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'gsc'; $wire.selectIntegration('gsc')" 
                            :class="$wire.activeReportIntegrationId === 'gsc' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            <span>Search Console</span>
                        </button>
                    @endif
                    @if($isGtmConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'gtm'; $wire.selectIntegration('gtm')" 
                            :class="$wire.activeReportIntegrationId === 'gtm' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            <span>Tag Manager</span>
                        </button>
                    @endif
                    @if($isYoutubeConnected)
                        <button type="button" x-on:click="$wire.activeReportIntegrationId = 'youtube'; $wire.selectIntegration('youtube')" 
                            :class="$wire.activeReportIntegrationId === 'youtube' ? 'bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span>YouTube</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center gap-3">
            <!-- Date picker removed as it is now handled by the embedded client-report -->
        </div>
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
                    <div class="space-y-12" wire:key="integration-view-overview">
                    @if($isGa4Connected)
                        <div wire:key="overview-ga4"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Analytics 4</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'ga4', 'activeReportData' => $ga4Data])</div>
                    @endif
                    @if($isGscConnected)
                        <div wire:key="overview-gsc"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Search Console</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'gsc', 'activeReportData' => $gscData])</div>
                    @endif
                    @if($isYoutubeConnected)
                        <div wire:key="overview-youtube"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">YouTube</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'youtube', 'activeReportData' => $youtubeData])</div>
                    @endif
                    @if($isKeywordConnected)
                        <div wire:key="overview-keyword"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Keyword.com</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'keyword', 'activeReportData' => $keywordData])</div>
                    @endif
                    @if($isGbpConnected)
                        <div wire:key="overview-gbp"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Business Profile</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'gbp', 'activeReportData' => $gbpData])</div>
                    @endif
                    @if($isGtmConnected)
                        <div wire:key="overview-gtm"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Tag Manager</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'gtm', 'activeReportData' => $gtmData])</div>
                    @endif
                    @if($isGadsConnected)
                        <div wire:key="overview-gads"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Google Ads</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'gads', 'activeReportData' => $gadsData])</div>
                    @endif
                    @if($isFacebookConnected)
                        <div wire:key="overview-facebook"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">Facebook</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'facebook', 'activeReportData' => $facebookData])</div>
                    @endif
                    @if($isLinkedinConnected)
                        <div wire:key="overview-linkedin"><h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white px-2">LinkedIn</h3>
                        @include('modules.crm.clients.client-report', ['hideHeader' => true, 'isOverviewMode' => true, 'activeReportIntegrationId' => 'linkedin', 'activeReportData' => $linkedinData])</div>
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
