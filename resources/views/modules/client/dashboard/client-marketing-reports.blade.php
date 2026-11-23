<div class="client-marketing-view">
    <style>
        /* Top-left corner badges */
        .metric-corner-badge {
            position: absolute !important;
            top: 7px !important;
            left: 7px !important;
            width: 20px !important;
            height: 20px !important;
            border-radius: 6px !important;
            background: #e2f1f5 !important;
            color: #0b495b !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            border: 1px solid #cce5ec !important;
            box-shadow: 0 1px 2px rgba(11, 43, 56, 0.05) !important;
            z-index: 5 !important;
        }
        .dark .metric-corner-badge {
            background: rgba(14, 63, 78, 0.6) !important;
            color: #5eead4 !important;
            border-color: rgba(20, 184, 166, 0.3) !important;
        }
        .integration-corner-num {
            font-family: var(--font-body) !important;
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #0c3b4a !important;
            letter-spacing: -0.02em !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-right: 2px !important;
        }
        .dark .integration-corner-num {
            color: #5eead4 !important;
        }
        .metric-card-theme {
            position: relative !important;
            font-family: var(--font-body) !important;
            background: #ffffff !important;
            border: 1.5px solid #d4e2e7 !important;
            border-radius: 14px !important;
            box-shadow: 0 1px 3px rgba(11, 43, 56, 0.04) !important;
            transition: all 0.2s ease !important;
        }
        .dark .metric-card-theme {
            background: #0f1c24 !important;
            border-color: #1e3542 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
        }
        .metric-card-theme:hover {
            border-color: #b0cbd6 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(11, 43, 56, 0.06) !important;
        }
        .metric-icon-circle {
            width: 48px !important;
            height: 48px !important;
            border-radius: 9999px !important;
            background-color: #e2f1f5 !important;
            color: #0b495b !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            border: 1px solid #cce5ec !important;
        }
        .dark .metric-icon-circle {
            background-color: rgba(14, 63, 78, 0.45) !important;
            color: #5eead4 !important;
            border-color: rgba(20, 184, 166, 0.2) !important;
        }
        .metric-title {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #5f7582 !important;
            line-height: 1.25 !important;
            margin-bottom: 2px !important;
        }
        .dark .metric-title {
            color: #94a8b5 !important;
        }
        .metric-value {
            font-size: 28px !important;
            font-weight: 800 !important;
            color: #0c3b4a !important;
            line-height: 1.1 !important;
            letter-spacing: -0.02em !important;
        }
        .dark .metric-value {
            color: #ffffff !important;
        }
        .metric-trend-row {
            margin-top: 10px !important;
            padding-top: 8px !important;
            border-top: 1px solid #edf4f7 !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
        }
        .dark .metric-trend-row {
            border-top-color: #1a2c37 !important;
        }
        .metric-trend-up {
            color: #5a9ca7 !important;
        }
        .metric-trend-down {
            color: #e11d48 !important;
        }
        .metric-trend-neutral {
            color: #94a3b8 !important;
        }
        
        /* Client GA4 Detailed Report Theming */
        .client-integration-theme {
            font-family: var(--font-body) !important;
        }
        .client-integration-theme h1 {
            font-family: var(--font-heading) !important;
            letter-spacing: 2px !important;
            color: #135266 !important;
        }
        .client-integration-theme h2,
        .client-integration-theme h3,
        .client-integration-theme h4,
        .client-integration-theme h5 {
            font-family: var(--font-heading) !important;
            color: #135266 !important;
        }
        .dark .client-integration-theme h1,
        .dark .client-integration-theme h2,
        .dark .client-integration-theme h3,
        .dark .client-integration-theme h4,
        .dark .client-integration-theme h5 {
            color: #ffffff !important;
        }
        .client-integration-theme .bg-white {
            /* background-color: #ffffff !important; */
            border-color: #d4e2e7 !important;
        }
        .dark .client-integration-theme .bg-white {
            background-color: #0f1c24 !important;
            border-color: #1e3542 !important;
        }
        .client-integration-theme .border-slate-200,
        .client-integration-theme .border-slate-100 {
            border-color: #d4e2e7 !important;
        }
        .dark .client-integration-theme .border-slate-200,
        .dark .client-integration-theme .border-slate-100 {
            border-color: #1e3542 !important;
        }
        .client-integration-theme .rounded-2xl,
        .client-integration-theme .rounded-xl {
            border-radius: 14px !important;
        }
        /* Top 5 KPI Cards in GA4 Detail */
        .client-integration-theme .grid.grid-cols-1.sm\:grid-cols-2.lg\:grid-cols-3.2xl\:grid-cols-5 > div {
            border: 1.5px solid #d4e2e7 !important;
            border-radius: 14px !important;
            box-shadow: 0 1px 3px rgba(11, 43, 56, 0.04) !important;
            transition: all 0.2s ease !important;
        }
        .dark .client-integration-theme .grid.grid-cols-1.sm\:grid-cols-2.lg\:grid-cols-3.2xl\:grid-cols-5 > div {
            border-color: #1e3542 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
        }
        .client-integration-theme .grid.grid-cols-1.sm\:grid-cols-2.lg\:grid-cols-3.2xl\:grid-cols-5 > div:hover {
            border-color: #b0cbd6 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(11, 43, 56, 0.06) !important;
        }
        .client-integration-theme .text-slate-800 {
            color: #135266 !important;
        }
        .dark .client-integration-theme .text-slate-800 {
            color: #ffffff !important;
        }
        .client-integration-theme .text-slate-500,
        .client-integration-theme .text-slate-600 {
            color: #5f7582 !important;
        }
        .dark .client-integration-theme .text-slate-500,
        .dark .client-integration-theme .text-slate-600 {
            color: #94a8b5 !important;
        }
        /* Circles for Icons */
        .client-integration-theme .w-12.h-12.rounded-full {
            width: 48px !important;
            height: 48px !important;
            border-radius: 9999px !important;
            background-color: #e2f1f5 !important;
            color: #0b495b !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 1px solid #cce5ec !important;
        }
        .dark .client-integration-theme .w-12.h-12.rounded-full {
            background-color: rgba(14, 63, 78, 0.45) !important;
            color: #5eead4 !important;
            border-color: rgba(20, 184, 166, 0.2) !important;
        }
        .client-integration-theme .w-12.h-12.rounded-full svg {
            color: #0b495b !important;
        }
        .dark .client-integration-theme .w-12.h-12.rounded-full svg {
            color: #5eead4 !important;
        }
        .client-integration-theme .text-3xl.font-black {
            font-size: 28px !important;
            font-weight: 800 !important;
            color: #0c3b4a !important;
            line-height: 1.1 !important;
            letter-spacing: -0.02em !important;
        }
        .dark .client-integration-theme .text-3xl.font-black {
            color: #ffffff !important;
        }
        .client-integration-theme .text-emerald-500 {
            color: #5a9ca7 !important;
        }
        .client-integration-theme .text-blue-600 {
            color: #0b495b !important;
        }
        .dark .client-integration-theme .text-blue-600 {
            color: #5eead4 !important;
        }
        .metric-trend-sub {
            font-size: 12px !important;
            font-weight: 500 !important;
            color: #8aa0ac !important;
        }

        /* Line chart and Donut specific layout fixes */
        .client-integration-theme [wire\\:key*="line-chart-outer"] {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: stretch !important;
        }
        .client-integration-theme [wire\\:key*="line-chart-outer"] > div:first-child {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        .client-integration-theme [wire\\:key*="line-chart-outer"] select {
            width: auto !important;
            min-width: 145px !important;
            max-width: 180px !important;
            margin: 0 !important;
        }
        /* Universal Typography: Headings 18px Bold, Table Content 16px Normal across all integrations */
        .client-integration-theme h2,
        .client-integration-theme h3,
        .client-integration-theme h4 {
            font-size: 18px !important;
            font-weight: 700 !important;
            line-height: 1.3 !important;
        }
        .client-integration-theme table tbody tr td,
        .client-integration-theme table tbody td {
            font-size: 16px !important;
            font-weight: 400 !important;
            line-height: 1.4 !important;
        }
    </style>
    @php
        $isGa4Connected = isset($integrations['ga4']);
        $isGscConnected = isset($integrations['gsc']);
        $isYoutubeConnected = isset($integrations['youtube']);
        $isKeywordConnected = isset($integrations['keyword']);
        $isGbpConnected = isset($integrations['gbp']);
        $isGadsConnected = isset($integrations['gads']);
        $isGtmConnected = isset($integrations['gtm']);
        $isFacebookConnected = isset($integrations['facebook']);
        $isMetaAdsConnected = isset($integrations['meta_ads']);
        $isLinkedinConnected = isset($integrations['linkedin']);

        $hasGa4 = !empty($ga4Data) && !isset($ga4Data['error']);
        $hasGsc = !empty($gscData) && !isset($gscData['error']);
        $hasYoutube = !empty($youtubeData) && !isset($youtubeData['error']);
        $hasKeyword = !empty($keywordData) && !isset($keywordData['error']);
        $hasGtm = !empty($gtmData) && !isset($gtmData['error']);
        $hasGbp = !empty($gbpData) && !isset($gbpData['error']);
        $hasGads = !empty($gadsData) && !isset($gadsData['error']);
        $hasFacebook = !empty($facebookData) && !isset($facebookData['error']);
        $metaAdsData = $metaAdsData ?? [];
        $hasMetaAds = !empty($metaAdsData) && !isset($metaAdsData['error']);
        $linkedinData = $linkedinData ?? [];
        $hasLinkedin = !empty($linkedinData) && !isset($linkedinData['error']);
    @endphp

    <!-- Platform Navigation Tabs Bar (Visible only when inside a single integration report) -->
    @if($activeReportIntegrationId !== 'overview' && ($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected || $isGbpConnected || $isGadsConnected || $isFacebookConnected || $isMetaAdsConnected || $isLinkedinConnected || $isGtmConnected))
        @php
            $channelGroups = [
                'all' => [
                    'label' => 'All Channels',
                    'badge_bg' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
                    'container_border' => 'border-amber-200/70 dark:border-amber-900/40',
                    'tabs' => [
                        'ga4' => ['connected' => $isGa4Connected, 'label' => 'Google Analytics 4', 'color' => '#E37400'],
                    ]
                ],
                'seo' => [
                    'label' => 'SEO',
                    'badge_bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                    'container_border' => 'border-emerald-200/70 dark:border-emerald-900/40',
                    'tabs' => [
                        'gsc' => ['connected' => $isGscConnected, 'label' => 'Search Console', 'color' => '#458CF5'],
                        'keyword' => ['connected' => $isKeywordConnected, 'label' => 'Keyword.com', 'color' => '#10B981'],
                    ]
                ],
                'paid' => [
                    'label' => 'Paid Advertising',
                    'badge_bg' => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60',
                    'container_border' => 'border-blue-200/70 dark:border-blue-900/40',
                    'tabs' => [
                        'gads' => ['connected' => $isGadsConnected, 'label' => 'Google Ads', 'color' => '#4285F4'],
                        'meta_ads' => ['connected' => $isMetaAdsConnected, 'label' => 'Meta Ads', 'color' => '#0081FB'],
                    ]
                ],
                'social' => [
                    'label' => 'Organic Social',
                    'badge_bg' => 'bg-purple-50 text-purple-800 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
                    'container_border' => 'border-purple-200/70 dark:border-purple-900/40',
                    'tabs' => [
                        'facebook' => ['connected' => $isFacebookConnected, 'label' => 'Meta', 'color' => '#0081FB'],
                        'linkedin' => ['connected' => $isLinkedinConnected, 'label' => 'LinkedIn', 'color' => '#0A66C2'],
                        'youtube' => ['connected' => $isYoutubeConnected, 'label' => 'YouTube', 'color' => '#FF0000'],
                    ]
                ],
                'other' => [
                    'label' => 'Local & Measurement',
                    'badge_bg' => 'bg-slate-100 text-slate-800 border-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700',
                    'container_border' => 'border-slate-200/80 dark:border-slate-700/60',
                    'tabs' => [
                        'gbp' => ['connected' => $isGbpConnected, 'label' => 'Business Profile', 'color' => '#1A73E8'],
                        'gtm' => ['connected' => $isGtmConnected, 'label' => 'Tag Manager', 'color' => '#246FDB'],
                    ]
                ],
            ];
        @endphp

        <section class="platform-nav-wrapper mb-5">
            <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-slate-200/90 dark:border-slate-800 rounded-2xl p-3 shadow-sm">
                <!-- Grouped Channel Containers with Brand Logos -->
                <div class="flex items-stretch gap-3 overflow-x-auto no-scrollbar py-0.5" aria-label="Marketing Channels">
                    @foreach($channelGroups as $groupKey => $group)
                        @php
                            $connectedTabsInGroup = array_filter($group['tabs'], fn($tab) => !empty($tab['connected']));
                        @endphp
                        @if(count($connectedTabsInGroup) > 0)
                            <div class="inline-flex items-center gap-1.5 p-1.5 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border {{ $group['container_border'] }} shrink-0">
                                <!-- Channel Label Pill -->
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wider uppercase border select-none shrink-0 {{ $group['badge_bg'] }}">
                                    {{ $group['label'] }}
                                </span>

                                <!-- Channel Member Buttons -->
                                <div class="flex items-center gap-1.5">
                                    @foreach($connectedTabsInGroup as $tKey => $tItem)
                                        @php $isActive = ($activeReportIntegrationId === $tKey); @endphp
                                        <button type="button" 
                                                @if($isActive) disabled @else wire:click="selectIntegration('{{ $tKey }}')" @endif
                                                wire:loading.attr="disabled"
                                                class="group inline-flex items-center gap-2 px-3 py-1.5 rounded-lg font-bold text-xs sm:text-sm tracking-tight transition-all duration-200 shrink-0 whitespace-nowrap border {{ $isActive ? 'bg-[#135266] text-white border-[#135266] shadow-sm font-extrabold cursor-default pointer-events-none' : 'bg-white hover:bg-slate-100/90 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-200 border-slate-200/80 dark:border-slate-700/70 hover:border-slate-300 cursor-pointer' }}">
                                            
                                            {{-- Loading Spinner when switching --}}
                                            <svg wire:loading wire:target="selectIntegration('{{ $tKey }}')" class="animate-spin w-4 h-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>

                                            {{-- Brand Logo / Icon --}}
                                            <span wire:loading.remove wire:target="selectIntegration('{{ $tKey }}')" class="inline-flex items-center justify-center shrink-0">
                                                @if($tKey === 'facebook')
                                                    {{-- Meta Official Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'fill-white' : 'fill-[#0081FB]' }}" viewBox="0 0 24 24">
                                                        <path d="m7.70344 7c-1.23357 0-2.34615.74172-2.8206 1.8804-.4329 1.03896-1.01718 3.6147-1.28132 4.8445-.07314.3406-.10356.6331-.08009.8958.07865.8799.35137 1.3002.60615 1.5162.26749.2268.67379.3631 1.26738.3631.65348 0 1.26086-.3366 1.60721-.8908l1.64468-2.6315 2.18665-3.6444-.5099-.84981c0-.00001 0-.00001 0-.00002-.55223-.92034-1.54684-1.48347-2.62016-1.48347zm4.29626.39072c-.921-1.48497-2.54534-2.39072-4.29626-2.39072-2.04097 0-3.88177 1.2272-4.66676 3.11117-.52019 1.24848-1.14231 4.03793-1.39056 5.19383-.09571.4456-.16486.9554-.11675 1.4937.11079 1.2396.53906 2.2145 1.30481 2.8637.75304.6385 1.68679.8376 2.56078.8376 1.34306 0 2.59138-.6919 3.3032-1.8308l1.64954-2.6392.0095-.0155 1.6425-2.7375 1.6425 2.7375.0095.0155 1.6495 2.6392c.7118 1.1389 1.9601 1.8308 3.3032 1.8308.9828 0 2.0043-.2803 2.7714-1.0857.7607-.7988 1.1239-1.9601 1.1239-3.4143 0-.9227-.3499-2.2661-.6743-3.3415-.3381-1.1208-.7029-2.11985-.815-2.4217-.0261-.07037-.0574-.15077-.0953-.23604-.81-1.82143-2.6184-3.00076-4.6192-3.00076-1.7509 0-3.3752.90576-4.2962 2.39072zm1.1662 1.94259 2.1866 3.64439 1.6447 2.6315c.3463.5542.9537.8908 1.6072.8908.6162 0 1.0423-.1702 1.3231-.465.2872-.3015.5722-.8902.5722-2.035 0-.5842-.2546-1.6551-.5891-2.7639-.3207-1.0633-.6693-2.0183-.775-2.30278-.0186-.05014-.0336-.08768-.0479-.11987-.4895-1.10065-1.5825-1.81345-2.7918-1.81345-1.0733 0-2.0679.56315-2.6201 1.48352z" />
                                                    </svg>
                                                @elseif($tKey === 'meta_ads')
                                                    {{-- Meta Ads Logo (Megaphone & Chart Badge) --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#0081FB]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                    </svg>
                                                @elseif($tKey === 'gads')
                                                    {{-- Google Ads Logo --}}
                                                    <svg class="w-4 h-4" viewBox="0 0 48 48">
                                                        <path d="M46.79,32.49c-11.62-20.12-17-30.28-18.1-29.18A9,9,0,0,0,16.11,6.69c-1.52,2.6,5.46-9.48-14.82,25.66A8.94,8.94,0,0,0,0,37c0,9.19,12.22,12.42,16.79,4.5C17.5,40.26,14.23,45.94,24,29L31.2,41.5a9,9,0,1,0,15.59-9ZM9,44A7,7,0,0,1,2.93,33.5h0A1.31,1.31,0,0,0,3,33.37c4.21-6.95,15.15-2.22,12.64,5.74A7,7,0,0,1,9,44Zm8.89-8.4a9,9,0,0,0-12.12-7l9.34-16.18c.37,2.34,1.07,3.06,7.73,14.61ZM39,44a7,7,0,0,1-6.06-3.5C16.38,11.82,17,13.52,17,11A7,7,0,0,1,27.39,4.88C29.51,5.82,29.19,6,45,33.37A7,7,0,0,1,39,44Z" fill="{{ $isActive ? '#ffffff' : '#FBBC04' }}"/>
                                                    </svg>
                                                @elseif($tKey === 'ga4')
                                                    {{-- Google Analytics 4 Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'fill-white' : 'fill-[#E37400]' }}" viewBox="0 0 24 24">
                                                        <path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                                                    </svg>
                                                @elseif($tKey === 'keyword')
                                                    {{-- Keyword.com Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'fill-white' : 'fill-[#10B981]' }}" viewBox="0 0 53 44">
                                                        <rect x="2" y="8" width="10" height="34" rx="4" />
                                                        <path d="m29 2-16 20 16 20h13L26 22 42 2Z" />
                                                    </svg>
                                                @elseif($tKey === 'linkedin')
                                                    {{-- LinkedIn Official Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'fill-white' : 'fill-[#0A66C2]' }}" viewBox="0 0 24 24">
                                                        <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                                    </svg>
                                                @elseif($tKey === 'gsc')
                                                    {{-- Search Console Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#458CF5]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                                    </svg>
                                                @elseif($tKey === 'gbp')
                                                    {{-- Google Business Profile Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#1A73E8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($tKey === 'gtm')
                                                    {{-- Google Tag Manager Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#246FDB]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                    </svg>
                                                @elseif($tKey === 'youtube')
                                                    {{-- YouTube Official Logo --}}
                                                    <svg class="w-4 h-4 {{ $isActive ? 'fill-white' : 'fill-[#FF0000]' }}" viewBox="0 0 24 24">
                                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                    </svg>
                                                @endif
                                            </span>

                                            <span class="text-xs font-semibold">{{ $tItem['label'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Header Section (Title & Filters) -->
    <div class="mb-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-extrabold text-[#135266] dark:text-slate-100">
                    {{-- Platform Specific Dynamic Icon with Consistent Brand Theme Color --}}
                    <span class="inline-flex items-center justify-center text-[#135266] dark:text-[#5eead4]">
                        @if($activeReportIntegrationId === 'ga4')
                         <svg class="w-7 h-7" fill="#135266" stroke="#135266" stroke-width="1.6" version="1.1" id="fi_900772" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 480 480" style="enable-background:new 0 0 480 480;" xml:space="preserve">
                            <g>
                                <g>
                                    <g>
                                        <rect y="464" width="480" height="16"></rect>
                                        <path d="M32,448h80c4.418,0,8-3.582,8-8V296c0-4.418-3.582-8-8-8H32c-4.418,0-8,3.582-8,8v144C24,444.418,27.582,448,32,448z
                                            M40,304h64v128H40V304z"></path>
                                        <path d="M256,448h80c4.418,0,8-3.582,8-8V200c0-4.418-3.582-8-8-8h-80c-4.418,0-8,3.582-8,8v240C248,444.418,251.582,448,256,448
                                            z M264,208h64v224h-64V208z"></path>
                                        <path d="M144,448h80c4.418,0,8-3.582,8-8V104c0-4.418-3.582-8-8-8h-80c-4.418,0-8,3.582-8,8v336C136,444.418,139.582,448,144,448
                                            z M152,112h64v320h-64V112z"></path>
                                        <path d="M368,448h80c4.418,0,8-3.582,8-8V8c0-4.418-3.582-8-8-8h-80c-4.418,0-8,3.582-8,8v432C360,444.418,363.582,448,368,448z
                                            M376,16h64v416h-64V16z"></path>
                                    </g>
                                </g>
                            </g>
                         </svg>    
                        @elseif($activeReportIntegrationId === 'gsc')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        @elseif($activeReportIntegrationId === 'gads')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @elseif($activeReportIntegrationId === 'facebook')
                            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="m7.70344 7c-1.23357 0-2.34615.74172-2.8206 1.8804-.4329 1.03896-1.01718 3.6147-1.28132 4.8445-.07314.3406-.10356.6331-.08009.8958.07865.8799.35137 1.3002.60615 1.5162.26749.2268.67379.3631 1.26738.3631.65348 0 1.26086-.3366 1.60721-.8908l1.64468-2.6315 2.18665-3.6444-.5099-.84981c0-.00001 0-.00001 0-.00002-.55223-.92034-1.54684-1.48347-2.62016-1.48347zm4.29626.39072c-.921-1.48497-2.54534-2.39072-4.29626-2.39072-2.04097 0-3.88177 1.2272-4.66676 3.11117-.52019 1.24848-1.14231 4.03793-1.39056 5.19383-.09571.4456-.16486.9554-.11675 1.4937.11079 1.2396.53906 2.2145 1.30481 2.8637.75304.6385 1.68679.8376 2.56078.8376 1.34306 0 2.59138-.6919 3.3032-1.8308l1.64954-2.6392.0095-.0155 1.6425-2.7375 1.6425 2.7375.0095.0155 1.6495 2.6392c.7118 1.1389 1.9601 1.8308 3.3032 1.8308.9828 0 2.0043-.2803 2.7714-1.0857.7607-.7988 1.1239-1.9601 1.1239-3.4143 0-.9227-.3499-2.2661-.6743-3.3415-.3381-1.1208-.7029-2.11985-.815-2.4217-.0261-.07037-.0574-.15077-.0953-.23604-.81-1.82143-2.6184-3.00076-4.6192-3.00076-1.7509 0-3.3752.90576-4.2962 2.39072zm1.1662 1.94259 2.1866 3.64439 1.6447 2.6315c.3463.5542.9537.8908 1.6072.8908.6162 0 1.0423-.1702 1.3231-.465.2872-.3015.5722-.8902.5722-2.035 0-.5842-.2546-1.6551-.5891-2.7639-.3207-1.0633-.6693-2.0183-.775-2.30278-.0186-.05014-.0336-.08768-.0479-.11987-.4895-1.10065-1.5825-1.81345-2.7918-1.81345-1.0733 0-2.0679.56315-2.6201 1.48352z"/></svg>
                        @elseif($activeReportIntegrationId === 'meta_ads')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                        @elseif($activeReportIntegrationId === 'gbp')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @elseif($activeReportIntegrationId === 'keyword')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        @elseif($activeReportIntegrationId === 'youtube')
                            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        @elseif($activeReportIntegrationId === 'linkedin')
                            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                        @elseif($activeReportIntegrationId === 'gtm')
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        @else
                            <svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7">
                                <rect x="3" y="15" width="4.5" height="10" rx="1" />
                                <rect x="11.5" y="9" width="4.5" height="16" rx="1" />
                                <rect x="20" y="3" width="4.5" height="22" rx="1" />
                            </svg>
                        @endif
                    </span>
                    <span>
                        @if($activeReportIntegrationId === 'overview') Marketing Reports
                        @elseif($activeReportIntegrationId === 'ga4') Google Analytics Website Traffic
                        @elseif($activeReportIntegrationId === 'gads') Google Ads Campaigns
                        @elseif($activeReportIntegrationId === 'facebook') Meta Performance (Facebook & Instagram)
                        @elseif($activeReportIntegrationId === 'meta_ads') Meta Ads Campaigns
                        @elseif($activeReportIntegrationId === 'linkedin') LinkedIn Performance
                        @elseif($activeReportIntegrationId === 'gsc') Google Search Console Performance
                        @elseif($activeReportIntegrationId === 'keyword') Keyword Ranking Tracker
                        @elseif($activeReportIntegrationId === 'gbp') Google Business Profile
                        @elseif($activeReportIntegrationId === 'youtube') YouTube Performance
                        @elseif($activeReportIntegrationId === 'gtm') Google Tag Manager
                        @else Marketing Reports
                        @endif
                    </span>
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    @if($activeReportIntegrationId === 'overview') Here's a quick look at your digital performance and connected platforms.
                    @elseif($activeReportIntegrationId === 'ga4') Track website traffic, behavior, and key events across your website.
                    @elseif($activeReportIntegrationId === 'gads') Monitor ad spend, clicks, conversions, and campaign performance.
                    @elseif($activeReportIntegrationId === 'facebook') Track Meta reach, impressions, post interactions, followers, and Instagram activity.
                    @elseif($activeReportIntegrationId === 'meta_ads') Track ad spend, impressions, clicks, conversions, and campaign performance across Facebook & Instagram.
                    @elseif($activeReportIntegrationId === 'linkedin') Track company updates, impressions, engagement, and demographics.
                    @elseif($activeReportIntegrationId === 'gsc') Track search queries, organic impressions, clicks, and page rankings.
                    @elseif($activeReportIntegrationId === 'keyword') Monitor tracked keyword positions, search volumes, and ranking shifts.
                    @elseif($activeReportIntegrationId === 'gbp') Track search and map interactions, calls, and customer engagement.
                    @elseif($activeReportIntegrationId === 'youtube') Track video views, watch time, subscribers, and top videos.
                    @else Performance analytics and reports across connected integrations.
                    @endif
                </p>
            </div>

            <!-- Right Side: Date Picker (Left) & Website Filter (Right) -->
            <div class="flex flex-wrap items-center gap-2.5">
                @if($activeReportIntegrationId !== 'overview')
                    <!-- Date Range Picker (Calendar) -->
                    <div class="shrink-0" wire:key="dp-top-box-{{ $activeReportIntegrationId }}-{{ $selectedWebsiteId }}">
                        @php
                            $minDateBound = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
                            $maxDateBound = \Carbon\Carbon::now()->format('Y-m-d');
                        @endphp
                        @include('partials.ga4-date-picker', ['minDateBound' => $minDateBound, 'maxDateBound' => $maxDateBound])
                    </div>
                @endif

                <!-- Website Filter -->
                <div class="inline-flex items-center gap-2 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-xl px-3 py-2 shadow-xs shrink-0">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Website</span>
                    @if(count($websites) > 0)
                        <div class="relative inline-flex items-center">
                            <select wire:model.live="selectedWebsiteId" aria-label="Select website" 
                                    class="appearance-none bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold text-[#0b495b] dark:text-[#5eead4] pl-2 pr-8 py-0.5 rounded-lg border-0 focus:ring-1 focus:ring-[#0b495b] cursor-pointer transition-colors">
                                @foreach($websites as $site)
                                    <option value="{{ $site->id }}">
                                        {{ $site->site_name }}
                                        @if(!empty($site->site_url))
                                            ({{ rtrim(str_replace(['https://', 'http://'], '', $site->site_url), '/') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <!-- <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg> -->
                        </div>
                    @else
                        <span class="text-xs text-slate-400 font-medium">No websites</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($activeReportIntegrationId === 'overview' && isset($stats))
        <!-- Stats Grid (3 Cards) matching Client Dashboard -->
        <section class="stats-grid-3 mb-6">
            <!-- Connected Websites -->
            <a href="{{ route('client.websites') }}" class="stat-box">
                <div class="stat-circle-icon blue">
                    <i data-lucide="globe"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Connected Websites</div>
                    <div class="stat-number">{{ $stats['websites'] }}</div>
                    <div class="stat-foot">
                        <span>All operational</span>
                        <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                    </div>
                </div>
            </a>

            <!-- Reports Analytics -->
            <a href="javascript:void(0)" class="stat-box">
                <div class="stat-circle-icon mint">
                    <i data-lucide="chart-no-axes-column-increasing"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Reports Analytics</div>
                    <div class="stat-number">{{ $stats['linked_analytics'] }}</div>
                    <div class="stat-foot">
                        <span>Platforms connected</span>
                        <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                    </div>
                </div>
            </a>

            <!-- Latest Report -->
            <a href="{{ route('client.maintenance') }}" class="stat-box">
                <div class="stat-circle-icon orange">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Latest Report</div>
                    <div class="stat-number report-month">{{ $stats['last_report_month'] }}</div>
                    <div class="stat-foot">
                        <span>Generated reports</span>
                        <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                    </div>
                </div>
            </a>
        </section>
    @endif

    <!-- Content Views Area -->
    <div class="relative min-h-[400px] w-full mt-2">
        @if($activeReportIntegrationId === 'overview')
            {{-- In Overview: show skeleton when loading overview data --}}
            <div wire:loading wire:target="selectIntegration('overview'), selectedWebsiteId" class="w-full font-sans pb-8 animate-pulse mt-2 relative space-y-6">
                @for($c = 0; $c < 4; $c++)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                        <!-- Header skeleton with icon, title & button -->
                        <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-5 bg-slate-200 dark:bg-slate-700/60 rounded"></div>
                                <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                                <div class="space-y-2">
                                    <div class="h-4 w-36 bg-slate-200 dark:bg-slate-700/80 rounded"></div>
                                    <div class="h-3 w-48 bg-slate-100 dark:bg-slate-800/80 rounded"></div>
                                </div>
                            </div>
                            <div class="h-8 w-24 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                        </div>

                        <!-- 5 Metric Cards Skeleton -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                            @for($i = 0; $i < 5; $i++)
                                <div class="bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800 p-4 h-28 flex flex-col justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200/70 dark:bg-slate-700/60 shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2.5 bg-slate-200/80 dark:bg-slate-700/80 rounded w-2/3"></div>
                                            <div class="h-6 bg-slate-200/90 dark:bg-slate-700 rounded w-4/5"></div>
                                        </div>
                                    </div>
                                    <div class="h-2.5 bg-slate-200/50 dark:bg-slate-800 rounded w-1/2"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>

            <div wire:loading.remove wire:target="selectIntegration('overview'), selectedWebsiteId" class="animate-fadeIn w-full">
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
                                        <div class="platforms-row" wire:key="integration-view-overview-grid">
                        @php
                            $connectedTypesList = [];
                            if ($isGa4Connected) $connectedTypesList[] = 'ga4';
                            if ($isGscConnected) $connectedTypesList[] = 'gsc';
                            if ($isGadsConnected) $connectedTypesList[] = 'gads';
                            if ($isFacebookConnected) $connectedTypesList[] = 'facebook';
                            if ($isMetaAdsConnected) $connectedTypesList[] = 'meta_ads';
                            if ($isLinkedinConnected) $connectedTypesList[] = 'linkedin';
                            if ($isKeywordConnected) $connectedTypesList[] = 'keyword';
                            if ($isGbpConnected) $connectedTypesList[] = 'gbp';
                            if ($isYoutubeConnected) $connectedTypesList[] = 'youtube';
                            if ($isGtmConnected) $connectedTypesList[] = 'gtm';

                            $catalogList = [
                                'ga4' => [
                                    'key'         => 'ga4',
                                    'name'        => 'Google Analytics',
                                    'description' => 'Traffic & engagement',
                                    'tab'         => 'ga4',
                                ],
                                'gsc' => [
                                    'key'         => 'gsc',
                                    'name'        => 'Search Console',
                                    'description' => 'Search visibility',
                                    'tab'         => 'gsc',
                                ],
                                'gads' => [
                                    'key'         => 'gads',
                                    'name'        => 'Google Ads',
                                    'description' => 'Campaigns & ROI',
                                    'tab'         => 'gads',
                                ],
                                'facebook' => [
                                    'key'         => 'facebook',
                                    'name'        => 'Meta',
                                    'description' => 'Social insights',
                                    'tab'         => 'facebook',
                                ],
                                'meta_ads' => [
                                    'key'         => 'meta_ads',
                                    'name'        => 'Meta Ads',
                                    'description' => 'Campaigns & ROI',
                                    'tab'         => 'meta_ads',
                                ],
                                'keyword' => [
                                    'key'         => 'keyword',
                                    'name'        => 'Keywords.com',
                                    'description' => 'Keyword rankings',
                                    'tab'         => 'keyword',
                                ],
                                'linkedin' => [
                                    'key'         => 'linkedin',
                                    'name'        => 'LinkedIn',
                                    'description' => 'Professional network',
                                    'tab'         => 'linkedin',
                                ],
                                'youtube' => [
                                    'key'         => 'youtube',
                                    'name'        => 'YouTube',
                                    'description' => 'Video performance',
                                    'tab'         => 'youtube',
                                ],
                                'gbp' => [
                                    'key'         => 'gbp',
                                    'name'        => 'Google Business',
                                    'description' => 'Local reviews & search',
                                    'tab'         => 'gbp',
                                ],
                                'gtm' => [
                                    'key'         => 'gtm',
                                    'name'        => 'Tag Manager',
                                    'description' => 'Container & Tags',
                                    'tab'         => 'gtm',
                                ],
                            ];

                            $gridPlatforms = [];
                            foreach ($connectedTypesList as $t) {
                                if (isset($catalogList[$t])) {
                                    $gridPlatforms[] = $catalogList[$t];
                                }
                            }
                        @endphp

                        @foreach($gridPlatforms as $platform)
                            <a href="javascript:void(0)" wire:click="selectIntegration('{{ $platform['tab'] }}')" class="platform-card-box cursor-pointer">
                                <div class="platform-logo-wrap">
                                    @if($platform['key'] === 'ga4')
                                        <svg class="google-logo" id="fi_17361020" width="50" height="50" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><path d="m10.274 21.137h-4.967a.9.9 0 0 0 -.9.9v17.011a.9.9 0 0 0 .9.9h4.967a.9.9 0 0 0 .9-.9v-17.007a.9.9 0 0 0 -.9-.904zm-.9 17.008h-3.163v-15.2h3.16z"></path><path d="m20.207 15.987h-4.967a.9.9 0 0 0 -.9.9v22.161a.9.9 0 0 0 .9.9h4.967a.9.9 0 0 0 .9-.9v-22.158a.9.9 0 0 0 -.9-.903zm-.9 22.158h-3.163v-20.352h3.156z"></path><path d="m6.454 17.114a.9.9 0 0 0 .52-.165l12.446-8.791 8.421 4a.9.9 0 0 0 .819-.022l8.707-4.736c-.236.647-.3 1.4.5 1.64a.9.9 0 0 0 1.101-.64l.69-2.575a.9.9 0 0 0 -.639-1.106l-2.575-.69a.9.9 0 0 0 -1.106.638c-.179.722.4 1.117 1.039 1.213l-8.177 4.447-8.483-4.027a.908.908 0 0 0 -.909.078l-12.876 9.1a.909.909 0 0 0 .522 1.636z"></path><path d="m35.734 26.7a9.442 9.442 0 0 0 -9.434 9.427c.508 12.511 18.359 12.507 18.866 0a9.444 9.444 0 0 0 -9.432-9.427zm0 17.059a7.634 7.634 0 0 1 -7.625-7.627c.358-10.094 14.895-10.09 15.252 0a7.635 7.635 0 0 1 -7.627 7.622z"></path><path d="m58.443 53.277-11.212-11.222c3.526-6.387.3-15.055-6.256-17.754v-9.984a.9.9 0 0 0 -.9-.9h-4.975a.9.9 0 0 0 -.9.9v8.965a12.8 12.8 0 0 0 -3.151.791v-4.608a.9.9 0 0 0 -.9-.9h-4.979a.9.9 0 0 0 -.9.9v10.679a12.852 12.852 0 0 0 -1.477 5.983c-.175 9.51 10.549 16.018 18.868 11.5l11.217 11.209a3.892 3.892 0 0 0 2.783 1.164 3.957 3.957 0 0 0 2.782-6.723zm-22.434-38.057h3.159v8.439a12.862 12.862 0 0 0 -3.159-.463zm-9.936 5.148h3.171v4.577a13 13 0 0 0 -3.171 2.6zm9.661 26.9a11.139 11.139 0 0 1 0-22.278c14.778.61 14.773 21.667 0 22.276zm7.507-.611a13.055 13.055 0 0 0 3.018-3.017l2.9 2.9-3.014 3.015zm13.922 10.907a2.155 2.155 0 0 1 -3.006 0l-6.738-6.732 3.013-3.014 6.739 6.745a2.115 2.115 0 0 1 -.008 3.001z"></path><path d="m35.734 33.016a1.106 1.106 0 0 1 1.1 1.1.9.9 0 0 0 1.807 0 2.905 2.905 0 0 0 -2.007-2.752.932.932 0 1 0 -1.807 0 2.905 2.905 0 0 0 -2.007 2.752 2.755 2.755 0 0 0 1.887 2.618l1.476.492c1.109.366.652 2.062-.453 2.009a1.105 1.105 0 0 1 -1.1-1.1.9.9 0 1 0 -1.807 0 2.905 2.905 0 0 0 2.007 2.752v.257a.9.9 0 0 0 .9.9.96.96 0 0 0 .9-1.16c2.514-.736 2.714-4.556.12-5.37l-1.475-.493c-1.102-.362-.646-2.058.459-2.005z"></path></svg>
                                    @elseif($platform['key'] === 'gsc')
                                        <svg class="console-logo" id="fi_17361020" width="50" height="50" id="fi_11125057" enable-background="new 0 0 500 500" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><g fill="rgb(0,0,0)"><path d="m73.2578 315.9771 16.585-2.9316c3.5342-.625 5.8936-3.9971 5.2686-7.5322-.624-3.5356-3.999-5.8892-7.5322-5.2695l-16.585 2.9316c-3.5342.625-5.8936 3.9971-5.2686 7.5322.5566 3.1528 3.2988 5.3696 6.3926 5.3696.3759-.0001.7568-.0328 1.1396-.1001z"></path><path d="m91.1533 320.4634-16.585 2.9316c-3.5342.625-5.8936 3.9971-5.2686 7.5322.5566 3.1528 3.2988 5.3696 6.3926 5.3696.376 0 .7568-.0327 1.1396-.1001l16.585-2.9316c3.5342-.625 5.8936-3.9971 5.2686-7.5322-.624-3.5356-3.996-5.8901-7.5322-5.2695z"></path><path d="m453.1045 27.9614h-348.4902c-16.6445 0-30.1865 13.5415-30.1865 30.1865v194.8281c0 3.5898 2.9102 6.5 6.5 6.5s6.5-2.9102 6.5-6.5v-142.8476h382.8632v220.5977c0 9.4766-7.71 17.1865-17.1865 17.1865h-159.6899c2.479-18.241 1.832-39.3657-1.9819-60.938-4.7881-27.0874-14.0127-51.8208-25.9746-69.645-12.9902-19.3569-28.0068-28.6279-42.2783-26.104-8.9995 1.5911-16.6665 7.6915-22.5005 17.6536-10.7354 13.668-42.6309 49.5706-90.3862 62.8146l-52.7842 9.3312c-3.0957.5469-5.8252 2.3857-7.4902 5.0459-1.5171 2.4249-2.8101 4.9646-3.9033 7.5826-1.2671.0652-2.5352.1827-3.7852.4037-16.8906 2.9863-28.1621 19.396-25.125 36.5806 2.7148 15.3599 15.957 26.2188 30.8027 26.2188 1.7627 0 3.5488-.1533 5.3418-.4702.8789-.1554 1.751-.3507 2.6118-.5811 2.3003 2.6959 4.8657 5.1707 7.686 7.3677.7876.6138 1.6499 1.1064 2.5566 1.4834l10.6709 28.5542c.9727 2.6016 3.4434 4.2261 6.0879 4.2261.6069 0 1.2231-.0938 1.832-.2731l21.0605 64.4381c1.3848 4.2363 4.5459 7.6309 8.6729 9.3135 1.8398.7495 3.7793 1.1221 5.7158 1.1221 2.4072 0 4.8066-.5762 6.998-1.7188l24.3076-12.6816c4.3457-2.2671 7.335-6.52 7.9961-11.3765.6611-4.8574-1.082-9.7549-4.6641-13.1011l-12.6104-11.7812-11.7246-38.5789c2.793-1.1588 4.4443-4.121 3.9014-7.1482l-3.6973-20.6163 4.2168-.7455c.4316-.0763.8408-.2087 1.2388-.365 39.0327-4.5946 77.2964 21.6517 92.6465 33.7975 2.6069 2.5808 5.2588 4.7819 7.9375 6.6075.0557.0364.1099.075.1665.1095 6.1182 4.1327 12.3721 6.288 18.5459 6.288 1.5527 0 3.1006-.1348 4.6396-.4067 14.2734-2.5234 25.1992-16.3794 30.7666-39.0161.0317-.1283.0576-.2625.0889-.3911h162.1064c16.6445 0 30.1865-13.5415 30.1865-30.1865v-272.5784c.0002-16.645-13.5418-30.1865-30.1863-30.1865zm-232.0132 239.9217c1.3984.3955 3.7544 2.0894 6.3989 5.9812 3.833 5.6411 6.9355 13.9907 8.5117 22.9092 3.1338 17.7263-.748 29.2229-3.7495 31.9744l-23.9214-9.7054c-1.4033-5.4276-2.6299-11.0157-3.6406-16.7343-1.3516-7.645-2.2407-15.0193-2.7476-22.0758zm-191.0854 60.4914c-1.6489-9.3315 3.7881-18.2448 12.3462-20.9482-1.7671 12.3052.3052 25.2418 5.7925 36.4076-8.7091.0675-16.5284-6.351-18.1387-15.4594zm30.5986-34.6953 48.9531-8.6538c-6.8555 19.4404-2.7246 42.8076 10.3789 58.7192l-48.9512 8.6533c-16.4843-13.4379-21.2587-40.4433-10.3808-58.7187zm85.4023 148.979c.7021.6562.7139 1.4395.6582 1.8481s-.2764 1.1602-1.1279 1.6045l-24.3076 12.6816c-.7646.3979-1.4521.2241-1.7939.084-.3418-.1392-.9561-.4951-1.2227-1.313l-21.0088-64.2814 20.9463-6.5676 12.1904 40.1128c.332 1.0933.9463 2.0796 1.7812 2.8594zm-25.3652-70.3491-33.7451 10.5811-7.0469-18.8574 38.1001-6.7354zm14.8135-31.3631c-17.0571-13.9579-21.5361-42.697-8.9375-60.8489 27.4458-10.1438 49.0137-26.4846 64.043-40.7739-3.2173 19.1809-2.7891 41.9541 1.3291 65.2476 3.5342 19.9949 9.4917 38.6971 17.1777 54.2947-.6465-.3584-1.2983-.7158-1.958-1.0715-25.0435-13.504-49.5425-19.2211-71.6543-16.848zm122.4238 46.5721c-4.1777.7371-8.7798-.5962-13.5117-3.7482-.9263-.8461-3.3491-3.0092-7.0361-5.9559-2.9175-2.9915-5.8306-6.6073-8.6729-10.8423-5.9785-8.9081-11.2178-19.7877-15.4805-31.9344l16.6689 6.7631c.0444.0181.0898.0303.1348.0472.061.0233.123.0433.1851.0649.3135.109.6294.1979.9478.2567.0303.0056.0615.0082.0928.0134.3604.0613.7217.0967 1.0811.0967.3574 0 .7109-.0366 1.0601-.0946.0249-.004.0488-.0021.0737-.0065 4.1289-.73 11.5732-4.0122 15.0449-17.79 2.1289-8.4512 2.248-19.062.3359-29.8779-1.9111-10.8164-5.6611-20.7432-10.5586-27.9517-7.9531-11.7053-16.0352-12.2759-20.1812-11.562-.2319.0389-.4619.0902-.6904.1549-.0596.0168-.1162.0398-.1748.0582-.1431.0449-.2866.0891-.4272.1445-.1611.063-.3159.1357-.4697.2104-.0361.0178-.0737.0315-.1099.0499-.2109.1075-.415.226-.6108.3546l-14.1377 9.1177c.1816-21.3011 4.2002-38.5164 10.313-49.2104 2.2261-2.9154 3.4878-4.7825 3.7446-5.1685.1626-.2445.2998-.4979.4258-.7546 2.9121-3.2793 6.125-5.3263 9.5176-5.926.7588-.1343 1.5312-.2007 2.3164-.2007 8.2871 0 17.9492 7.4038 26.9043 20.7471 10.9756 16.3545 19.4873 39.3193 23.9678 64.6636 9.5575 54.0669-3.2658 95.1895-20.7531 98.2808zm-170.4512-290.3897v-38.9805c0-9.4766 7.71-17.1865 17.1865-17.1865h348.4902c9.4766 0 17.1865 7.71 17.1865 17.1865v38.9805z"></path><path d="m121.5498 62.6953h-2.6621c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5h2.6621c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5z"></path><path d="m178.7715 62.6953h-2.6611c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5h2.6611c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5z"></path><path d="m150.1611 62.6953h-2.6621c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5h2.6621c3.5898 0 6.5-2.9102 6.5-6.5s-2.9101-6.5-6.5-6.5z"></path><path d="m335.6602 185.1597h-29.7637c-3.5898 0-6.5 2.9102-6.5 6.5v35.3096c0 3.5898 2.9102 6.5 6.5 6.5h29.7637c3.5898 0 6.5-2.9102 6.5-6.5v-35.3096c0-3.5899-2.9102-6.5-6.5-6.5zm-6.5 35.3095h-16.7637v-22.3096h16.7637z"></path><path d="m388.6396 233.4692c3.5898 0 6.5-2.9102 6.5-6.5v-59.1318c0-3.5898-2.9102-6.5-6.5-6.5h-29.7636c-3.5898 0-6.5 2.9102-6.5 6.5v59.1318c0 3.5898 2.9102 6.5 6.5 6.5zm-23.2636-59.1318h16.7637v46.1318h-16.7637z"></path><path d="m441.0244 233.4692c3.5898 0 6.5-2.9102 6.5-6.5v-83.8721c0-3.5898-2.9102-6.5-6.5-6.5h-29.7637c-3.5898 0-6.5 2.9102-6.5 6.5v83.8721c0 3.5898 2.9102 6.5 6.5 6.5zm-23.2637-83.872h16.7637v70.8721h-16.7637z"></path><path d="m334.6865 261.3838c0 3.5898 2.9102 6.5 6.5 6.5h99.8379c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-99.8379c-3.5898 0-6.5 2.9101-6.5 6.5z"></path><path d="m341.1865 297.6987h9.832c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-9.832c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5z"></path><path d="m441.0244 284.6987h-65.8994c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5h65.8994c3.5898 0 6.5-2.9102 6.5-6.5s-2.9101-6.5-6.5-6.5z"></path><path d="m341.1865 327.5137h99.8379c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-99.8379c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5z"></path><path d="m114.3613 153.4033h16.3008c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-16.3008c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5z"></path><path d="m114.3613 182.584h16.3008c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-16.3008c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5z"></path><path d="m155.3809 153.4033h16.3018c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-16.3018c-3.5898 0-6.5 2.9102-6.5 6.5s2.9101 6.5 6.5 6.5z"></path><path d="m155.3809 182.584h16.3018c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-16.3018c-3.5898 0-6.5 2.9102-6.5 6.5s2.9101 6.5 6.5 6.5z"></path><path d="m114.3613 211.7646h16.3008c3.5898 0 6.5-2.9102 6.5-6.5s-2.9102-6.5-6.5-6.5h-16.3008c-3.5898 0-6.5 2.9102-6.5 6.5s2.9102 6.5 6.5 6.5z"></path></g></svg>
                                    @elseif($platform['key'] === 'youtube')
                                        <svg class="youtube-logo" width="50" height="50" id="fi_2111839" enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m9.939 7.856c-.497-.297-1.134.062-1.134.644v7c0 .585.638.939 1.134.645l5.869-3.495c.488-.291.487-.997.001-1.289zm.366 6.325v-4.36l3.655 2.183z"></path><path d="m19.904 3.271c-4.653-.691-11.153-.691-15.808 0-1.862.276-3.329 1.738-3.649 3.636-.596 3.523-.596 6.664 0 10.186.32 1.899 1.787 3.36 3.649 3.636 2.332.346 5.124.519 7.915.519 2.786 0 5.571-.172 7.894-.518 1.86-.276 3.326-1.737 3.648-3.636.596-3.523.596-6.665 0-10.188-.32-1.897-1.787-3.359-3.649-3.635zm2.17 13.573c-.213 1.256-1.173 2.222-2.39 2.402-4.518.671-10.838.671-15.368-.001-1.218-.181-2.179-1.146-2.391-2.402-.574-3.394-.574-6.291 0-9.687.213-1.256 1.173-2.22 2.392-2.402 2.262-.335 4.973-.503 7.682-.503 2.711 0 5.422.168 7.684.503 1.218.181 2.179 1.146 2.391 2.402.574 3.396.574 6.293 0 9.688z"></path></svg>
                                    @elseif($platform['key'] === 'gads')
                                        <svg class="gads-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="50" height="50" id="fi_3162148"><g id="_18._google_ads_logo" data-name="18. google ads logo"><path d="M46.79,32.49c-11.62-20.12-17-30.28-18.1-29.18A9,9,0,0,0,16.11,6.69c-1.52,2.6,5.46-9.48-14.82,25.66A8.94,8.94,0,0,0,0,37c0,9.19,12.22,12.42,16.79,4.5C17.5,40.26,14.23,45.94,24,29L31.2,41.5a9,9,0,1,0,15.59-9ZM9,44A7,7,0,0,1,2.93,33.5h0A1.31,1.31,0,0,0,3,33.37c4.21-6.95,15.15-2.22,12.64,5.74A7,7,0,0,1,9,44Zm8.89-8.4a9,9,0,0,0-12.12-7l9.34-16.18c.37,2.34,1.07,3.06,7.73,14.61ZM39,44a7,7,0,0,1-6.06-3.5C16.38,11.82,17,13.52,17,11A7,7,0,0,1,27.39,4.88C29.51,5.82,29.19,6,45,33.37A7,7,0,0,1,39,44Z"></path></g></svg>
                                    @elseif($platform['key'] === 'linkedin')
                                        <svg class="linkedin-logo" width="50" height="50" id="fi_3128219" enable-background="new 0 0 100 100"  viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><g id="_x31_0.Linkedin"><path d="m90 90v-29.3c0-14.4-3.1-25.4-19.9-25.4-8.1 0-13.5 4.4-15.7 8.6h-.2v-7.3h-15.9v53.4h16.6v-26.5c0-7 1.3-13.7 9.9-13.7 8.5 0 8.6 7.9 8.6 14.1v26h16.6z"></path><path d="m11.3 36.6h16.6v53.4h-16.6z"></path><path d="m19.6 10c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.7 9.6 9.7 9.6-4.4 9.6-9.7-4.3-9.6-9.6-9.6z"></path></g></svg>
                                    @elseif($platform['key'] === 'facebook')
                                        <svg class="meta-logo" fill="none" height="65" viewBox="0 0 24 24" width="65" xmlns="http://www.w3.org/2000/svg" id="fi_15071740">
                                            <path clip-rule="evenodd" d="m7.70344 7c-1.23357 0-2.34615.74172-2.8206 1.8804-.4329 1.03896-1.01718 3.6147-1.28132 4.8445-.07314.3406-.10356.6331-.08009.8958.07865.8799.35137 1.3002.60615 1.5162.26749.2268.67379.3631 1.26738.3631.65348 0 1.26086-.3366 1.60721-.8908l1.64468-2.6315 2.18665-3.6444-.5099-.84981c0-.00001 0-.00001 0-.00002-.55223-.92034-1.54684-1.48347-2.62016-1.48347zm4.29626.39072c-.921-1.48497-2.54534-2.39072-4.29626-2.39072-2.04097 0-3.88177 1.2272-4.66676 3.11117-.52019 1.24848-1.14231 4.03793-1.39056 5.19383-.09571.4456-.16486.9554-.11675 1.4937.11079 1.2396.53906 2.2145 1.30481 2.8637.75304.6385 1.68679.8376 2.56078.8376 1.34306 0 2.59138-.6919 3.3032-1.8308l1.64954-2.6392.0095-.0155 1.6425-2.7375 1.6425 2.7375.0095.0155 1.6495 2.6392c.7118 1.1389 1.9601 1.8308 3.3032 1.8308.9828 0 2.0043-.2803 2.7714-1.0857.7607-.7988 1.1239-1.9601 1.1239-3.4143 0-.9227-.3499-2.2661-.6743-3.3415-.3381-1.1208-.7029-2.11985-.815-2.4217-.0261-.07037-.0574-.15077-.0953-.23604-.81-1.82143-2.6184-3.00076-4.6192-3.00076-1.7509 0-3.3752.90576-4.2962 2.39072zm1.1662 1.94259 2.1866 3.64439 1.6447 2.6315c.3463.5542.9537.8908 1.6072.8908.6162 0 1.0423-.1702 1.3231-.465.2872-.3015.5722-.8902.5722-2.035 0-.5842-.2546-1.6551-.5891-2.7639-.3207-1.0633-.6693-2.0183-.775-2.30278-.0186-.05014-.0336-.08768-.0479-.11987-.4895-1.10065-1.5825-1.81345-2.7918-1.81345-1.0733 0-2.0679.56315-2.6201 1.48352z" fill="#fff" fill-rule="evenodd"></path>
                                        </svg>
                                    @elseif($platform['key'] === 'meta_ads')
                                        <svg class="meta-ads-logo" fill="none" height="50" viewBox="0 0 24 24" width="50" stroke="#ffffff" stroke-width="2.3" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    @elseif($platform['key'] === 'keyword')
                                        <svg class="keywords-logo" viewBox="0 0 53 44" style="width:48px;height:40px;">
                                            <rect x="2" y="8" width="10" height="50" rx="5" fill="#ffffff"/>
                                            <path d="m29 2-16 20 16 20h13L26 22 42 2Z" fill="#ffffff"/>
                                        </svg>
                                    @elseif($platform['key'] === 'gtm')
                                        <svg width="45" height="45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" class="text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    @elseif($platform['key'] === 'gbp')
                                        <svg width="45" height="45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" class="text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @else
                                        <i data-lucide="globe" style="width:36px;height:36px;color:#ffffff;"></i>
                                    @endif
                                </div>

                                <h3 class="platform-card-title">{{ $platform['name'] }}</h3>
                                <p class="platform-card-desc">{{ $platform['description'] }}</p>
                            </a>
                        @endforeach
                    </div>

                    <!-- Encouragement Banner (Matching Dashboard) -->
                    <section class="encouragement-banner mt-6">
                        <div class="encouragement-text">
                            <div class="encouragement-eyebrow">LET’S GROW TOGETHER</div>
                            <h2 class="encouragement-heading">Insights today. <span>A stronger tomorrow.</span></h2>
                            <p class="encouragement-sub">Your online presence is working for you. Keep going!</p>
                        </div>
                        
                        <div class="quote-box text-right z-10 hidden md:block">
                            <p class="text-xs italic text-slate-500 dark:text-slate-400 m-0">"Consistent progress<br>leads to extraordinary results."</p>
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 block mt-1">— The Aspire Hub Team</span>
                        </div>
                    </section>
                @endif
            @endif
            </div> {{-- closes wire:loading.remove wire:target="selectIntegration('overview')" --}}
        @else
            {{-- Single Integration View --}}
            <div wire:key="integration-view-single-{{ $activeReportIntegrationId }}-{{ $selectedWebsiteId }}" class="client-integration-theme">
                {{-- Skeleton Loader for Single Integration matching actual data layout --}}
                <div wire:loading wire:target="selectIntegration('ga4'), selectIntegration('gsc'), selectIntegration('youtube'), selectIntegration('keyword'), selectIntegration('gtm'), selectIntegration('gbp'), selectIntegration('gads'), selectIntegration('facebook'), selectIntegration('meta_ads'), selectIntegration('linkedin'), applyDateFilter, selectedWebsiteId" class="w-full font-sans pb-8 animate-pulse relative">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm p-6 space-y-6">
                        
                        @if($activeReportIntegrationId === 'gads' || $activeReportIntegrationId === 'meta_ads')
                            {{-- Google Ads Exact Replica Skeleton --}}
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900">
                                <!-- Top Metric Tabs Skeleton (5 equal columns) -->
                                <div class="grid grid-cols-2 md:grid-cols-5 border-b border-slate-200 dark:border-slate-800 divide-x divide-slate-200 dark:divide-slate-800">
                                    @for($m = 0; $m < 5; $m++)
                                        <div class="p-4 h-[100px] flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700/60 shrink-0"></div>
                                            <div class="flex-1 space-y-2">
                                                <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-16"></div>
                                                <div class="h-6 bg-slate-300 dark:bg-slate-600 rounded w-20"></div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <!-- Large Line Chart Area Skeleton with simulated wave -->
                                <div class="p-6 h-[320px] bg-slate-50/30 dark:bg-slate-800/20 flex flex-col justify-between">
                                    <div class="flex justify-between items-center text-xs text-slate-300">
                                        <div class="h-3 w-12 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        <div class="h-3 w-12 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                    </div>
                                    <div class="w-full h-44 flex items-end gap-3 px-4">
                                        @for($b = 0; $b < 20; $b++)
                                            <div class="flex-1 bg-gradient-to-t from-blue-100 to-blue-200/40 dark:from-blue-900/30 dark:to-blue-800/10 rounded-t" style="height: {{ [30, 45, 60, 85, 50, 70, 95, 80, 40, 65, 90, 75, 55, 70, 85, 90, 60, 45, 30, 15][$b] }}%;"></div>
                                        @endfor
                                    </div>
                                    <div class="flex justify-between text-xs text-slate-300 pt-2 border-t border-slate-100 dark:border-slate-800">
                                        @for($d = 0; $d < 7; $d++)
                                            <div class="h-2.5 w-16 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Top Ad Campaigns Table Skeleton -->
                            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-5 bg-white dark:bg-slate-900">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                    <div class="h-4 w-36 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-8 bg-slate-100 dark:bg-slate-800 rounded-lg w-full"></div>
                                    <div class="h-10 bg-slate-50 dark:bg-slate-800/60 rounded-lg w-full"></div>
                                    <div class="h-10 bg-slate-50 dark:bg-slate-800/60 rounded-lg w-full"></div>
                                </div>
                            </div>

                            <!-- Bottom 2 Cards Skeleton (Keywords & Devices) -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Keywords Table Skeleton -->
                                <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 bg-white dark:bg-slate-900 space-y-3">
                                    <div class="h-4 w-48 bg-slate-200 dark:bg-slate-700 rounded mb-4"></div>
                                    @for($k = 0; $k < 6; $k++)
                                        <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/50">
                                            <div class="h-3.5 bg-slate-200 dark:bg-slate-700 rounded w-1/3"></div>
                                            <div class="flex gap-4">
                                                <div class="h-3.5 bg-blue-100 dark:bg-blue-900/40 rounded w-14"></div>
                                                <div class="h-3.5 bg-slate-200 dark:bg-slate-700 rounded w-10"></div>
                                                <div class="h-3.5 bg-slate-200 dark:bg-slate-700 rounded w-12"></div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Devices Skeleton -->
                                <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-5 bg-white dark:bg-slate-900 space-y-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="h-4 w-32 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        <div class="h-5 w-24 bg-slate-100 dark:bg-slate-800 rounded"></div>
                                    </div>
                                    @for($dev = 0; $dev < 3; $dev++)
                                        <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-slate-200 dark:bg-slate-700"></div>
                                                <div class="space-y-1.5">
                                                    <div class="h-3.5 w-24 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                                    <div class="h-2.5 w-16 bg-slate-200 dark:bg-slate-700/60 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="h-6 w-14 bg-slate-300 dark:bg-slate-600 rounded"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @else
                            {{-- Standard KPI Cards + Charts Skeleton for GA4, GSC, YouTube, Facebook, LinkedIn, GBP --}}
                            <!-- Top 5 Metric Cards (Matching GA4 / Facebook KPI style) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5 gap-5">
                                @for($i = 0; $i < 5; $i++)
                                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 p-5 shadow-xs flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-[#e2f1f5] dark:bg-teal-950/40 border border-[#cce5ec] dark:border-teal-900/40 shrink-0"></div>
                                        <div class="flex-1 space-y-2 py-1">
                                            <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-20"></div>
                                            <div class="h-7 bg-slate-300 dark:bg-slate-600 rounded w-28"></div>
                                            <div class="h-2.5 bg-slate-200/60 dark:bg-slate-700/50 rounded w-24"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Main Charts Row (Line Chart 60% + Donut/Side Chart 40%) -->
                            <div class="flex flex-col lg:flex-row items-stretch gap-5">
                                <!-- Left Line Chart Skeleton (60%) -->
                                <div class="w-full lg:w-[60%] bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-6 rounded-2xl shadow-xs flex flex-col justify-between h-[340px]">
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                        <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-36"></div>
                                        <div class="h-7 bg-slate-100 dark:bg-slate-800 rounded-lg w-32"></div>
                                    </div>
                                    <div class="w-full flex-1 flex items-end gap-3 py-6 px-4">
                                        @for($b = 0; $b < 16; $b++)
                                            <div class="flex-1 bg-gradient-to-t from-slate-200 to-slate-100 dark:from-slate-800 dark:to-slate-700/40 rounded-t" style="height: {{ [40, 55, 75, 60, 80, 95, 70, 85, 65, 70, 90, 80, 60, 75, 85, 60][$b] }}%;"></div>
                                        @endfor
                                    </div>
                                    <div class="flex justify-center gap-6 pt-2 border-t border-slate-100 dark:border-slate-800">
                                        <div class="h-3 w-20 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        <div class="h-3 w-20 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                    </div>
                                </div>

                                <!-- Right Donut / Breakdown Skeleton (40%) -->
                                <div class="w-full lg:w-[40%] bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-6 rounded-2xl shadow-xs flex flex-col justify-between h-[340px]">
                                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-36 mb-4"></div>
                                    <div class="flex items-center gap-6 flex-1">
                                        <div class="w-36 h-36 rounded-full border-[14px] border-slate-200 dark:border-slate-700/60 shrink-0"></div>
                                        <div class="flex-1 space-y-3">
                                            @for($d = 0; $d < 4; $d++)
                                                <div class="flex justify-between items-center">
                                                    <div class="h-3 w-20 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                                    <div class="h-3 w-10 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom 3 Columns Table Skeleton -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                                @for($col = 0; $col < 3; $col++)
                                    <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-3">
                                        <div class="h-4 w-32 bg-slate-200 dark:bg-slate-700 rounded mb-4"></div>
                                        @for($row = 0; $row < 4; $row++)
                                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                                                <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/2"></div>
                                                <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-12"></div>
                                            </div>
                                        @endfor
                                    </div>
                                @endfor
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Real Single Integration Report --}}
                <div wire:loading.remove wire:target="selectIntegration('ga4'), selectIntegration('gsc'), selectIntegration('youtube'), selectIntegration('keyword'), selectIntegration('gtm'), selectIntegration('gbp'), selectIntegration('gads'), selectIntegration('facebook'), selectIntegration('meta_ads'), selectIntegration('linkedin'), applyDateFilter, selectedWebsiteId">
                    @include('modules.crm.clients.client-report', [
                        'hideHeader' => true, 
                        'hideToolbar' => true,
                        'activeReportData' => $activeReportData,
                        'dateFrom' => $dateFrom,
                        'dateTo' => $dateTo,
                        'compareDateFrom' => $compareDateFrom,
                        'compareDateTo' => $compareDateTo,
                        'compareFormat' => $compareFormat ?? 'percentage',
                        'compareStacked' => true,
                        'isClientPortal' => true
                    ])
                </div>
            </div>
        @endif
    </div>
</div>
