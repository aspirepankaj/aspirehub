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

    <!-- Platform Navigation Tabs Bar (Visible across all screens) -->
    @if($isGa4Connected || $isGscConnected || $isYoutubeConnected || $isKeywordConnected || $isGbpConnected || $isGadsConnected || $isFacebookConnected || $isMetaAdsConnected || $isLinkedinConnected)
        @php
            $tabList = [
                'gads' => [
                    'connected' => $isGadsConnected, 
                    'label' => 'Google Ads',
                    'color' => '#4285F4'
                ],
                'ga4' => [
                    'connected' => $isGa4Connected, 
                    'label' => 'Google Analytics 4',
                    'color' => '#E37400'
                ],
                'gbp' => [
                    'connected' => $isGbpConnected, 
                    'label' => 'Google Business Profile',
                    'color' => '#1A73E8'
                ],
                'keyword' => [
                    'connected' => $isKeywordConnected, 
                    'label' => 'Keyword.com',
                    'color' => '#10B981'
                ],
                'linkedin' => [
                    'connected' => $isLinkedinConnected, 
                    'label' => 'LinkedIn',
                    'color' => '#0A66C2'
                ],
                'facebook' => [
                    'connected' => $isFacebookConnected, 
                    'label' => 'Meta',
                    'color' => '#0081FB'
                ],
                'meta_ads' => [
                    'connected' => $isMetaAdsConnected, 
                    'label' => 'Meta Ads',
                    'color' => '#0081FB'
                ],
                'gsc' => [
                    'connected' => $isGscConnected, 
                    'label' => 'Search Console',
                    'color' => '#458CF5'
                ],
                'gtm' => [
                    'connected' => $isGtmConnected, 
                    'label' => 'Tag Manager',
                    'color' => '#246FDB'
                ],
                'youtube' => [
                    'connected' => $isYoutubeConnected, 
                    'label' => 'YouTube',
                    'color' => '#FF0000'
                ],
            ];
        @endphp

        <section class="platform-nav-wrapper mb-4">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/90 dark:border-slate-800 rounded-2xl p-2.5 shadow-sm">
                <!-- Navigation Tabs with Brand Logos -->
                <nav class="platform-tabs flex items-center gap-2.5 overflow-x-auto no-scrollbar py-1" aria-label="Marketing Platforms">
                    @foreach($tabList as $tKey => $tItem)
                        @if($tItem['connected'])
                            @php $isActive = ($activeReportIntegrationId === $tKey); @endphp
                            <button type="button" 
                                    @if($isActive) disabled @else wire:click="selectIntegration('{{ $tKey }}')" @endif
                                    wire:loading.attr="disabled"
                                    class="group inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl font-bold text-sm tracking-tight transition-all duration-200 shrink-0 whitespace-nowrap border {{ $isActive ? 'bg-[#135266] text-white border-[#135266] shadow-sm font-extrabold cursor-default pointer-events-none' : 'bg-slate-50/90 hover:bg-slate-100/90 dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200/80 dark:border-slate-700/70 hover:border-slate-300 cursor-pointer' }}">
                                
                                {{-- Loading Spinner when switching --}}
                                <svg wire:loading wire:target="selectIntegration('{{ $tKey }}')" class="animate-spin w-5 h-5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                {{-- Brand Logo / Icon --}}
                                <span wire:loading.remove wire:target="selectIntegration('{{ $tKey }}')" class="inline-flex items-center justify-center shrink-0">
                                    @if($tKey === 'facebook')
                                        {{-- Meta Official Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'fill-white' : 'fill-[#0081FB]' }}" viewBox="0 0 24 24">
                                            <path d="m7.70344 7c-1.23357 0-2.34615.74172-2.8206 1.8804-.4329 1.03896-1.01718 3.6147-1.28132 4.8445-.07314.3406-.10356.6331-.08009.8958.07865.8799.35137 1.3002.60615 1.5162.26749.2268.67379.3631 1.26738.3631.65348 0 1.26086-.3366 1.60721-.8908l1.64468-2.6315 2.18665-3.6444-.5099-.84981c0-.00001 0-.00001 0-.00002-.55223-.92034-1.54684-1.48347-2.62016-1.48347zm4.29626.39072c-.921-1.48497-2.54534-2.39072-4.29626-2.39072-2.04097 0-3.88177 1.2272-4.66676 3.11117-.52019 1.24848-1.14231 4.03793-1.39056 5.19383-.09571.4456-.16486.9554-.11675 1.4937.11079 1.2396.53906 2.2145 1.30481 2.8637.75304.6385 1.68679.8376 2.56078.8376 1.34306 0 2.59138-.6919 3.3032-1.8308l1.64954-2.6392.0095-.0155 1.6425-2.7375 1.6425 2.7375.0095.0155 1.6495 2.6392c.7118 1.1389 1.9601 1.8308 3.3032 1.8308.9828 0 2.0043-.2803 2.7714-1.0857.7607-.7988 1.1239-1.9601 1.1239-3.4143 0-.9227-.3499-2.2661-.6743-3.3415-.3381-1.1208-.7029-2.11985-.815-2.4217-.0261-.07037-.0574-.15077-.0953-.23604-.81-1.82143-2.6184-3.00076-4.6192-3.00076-1.7509 0-3.3752.90576-4.2962 2.39072zm1.1662 1.94259 2.1866 3.64439 1.6447 2.6315c.3463.5542.9537.8908 1.6072.8908.6162 0 1.0423-.1702 1.3231-.465.2872-.3015.5722-.8902.5722-2.035 0-.5842-.2546-1.6551-.5891-2.7639-.3207-1.0633-.6693-2.0183-.775-2.30278-.0186-.05014-.0336-.08768-.0479-.11987-.4895-1.10065-1.5825-1.81345-2.7918-1.81345-1.0733 0-2.0679.56315-2.6201 1.48352z" />
                                        </svg>
                                    @elseif($tKey === 'meta_ads')
                                        {{-- Meta Ads Logo (Megaphone & Chart Badge) --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#0081FB]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    @elseif($tKey === 'gads')
                                        {{-- Google Ads Logo --}}
                                        <svg class="w-5 h-5" viewBox="0 0 48 48">
                                            <path d="M46.79,32.49c-11.62-20.12-17-30.28-18.1-29.18A9,9,0,0,0,16.11,6.69c-1.52,2.6,5.46-9.48-14.82,25.66A8.94,8.94,0,0,0,0,37c0,9.19,12.22,12.42,16.79,4.5C17.5,40.26,14.23,45.94,24,29L31.2,41.5a9,9,0,1,0,15.59-9ZM9,44A7,7,0,0,1,2.93,33.5h0A1.31,1.31,0,0,0,3,33.37c4.21-6.95,15.15-2.22,12.64,5.74A7,7,0,0,1,9,44Zm8.89-8.4a9,9,0,0,0-12.12-7l9.34-16.18c.37,2.34,1.07,3.06,7.73,14.61ZM39,44a7,7,0,0,1-6.06-3.5C16.38,11.82,17,13.52,17,11A7,7,0,0,1,27.39,4.88C29.51,5.82,29.19,6,45,33.37A7,7,0,0,1,39,44Z" fill="{{ $isActive ? '#ffffff' : '#FBBC04' }}"/>
                                        </svg>
                                    @elseif($tKey === 'ga4')
                                        {{-- Google Analytics 4 Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'fill-white' : 'fill-[#E37400]' }}" viewBox="0 0 24 24">
                                            <path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                                        </svg>
                                    @elseif($tKey === 'keyword')
                                        {{-- Keyword.com Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'fill-white' : 'fill-[#10B981]' }}" viewBox="0 0 53 44">
                                            <rect x="2" y="8" width="10" height="34" rx="4" />
                                            <path d="m29 2-16 20 16 20h13L26 22 42 2Z" />
                                        </svg>
                                    @elseif($tKey === 'linkedin')
                                        {{-- LinkedIn Official Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'fill-white' : 'fill-[#0A66C2]' }}" viewBox="0 0 24 24">
                                            <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                        </svg>
                                    @elseif($tKey === 'gsc')
                                        {{-- Search Console Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#458CF5]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                        </svg>
                                    @elseif($tKey === 'gbp')
                                        {{-- Google Business Profile Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#1A73E8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($tKey === 'gtm')
                                        {{-- Google Tag Manager Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#246FDB]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    @elseif($tKey === 'youtube')
                                        {{-- YouTube Official Logo --}}
                                        <svg class="w-5 h-5 {{ $isActive ? 'fill-white' : 'fill-[#FF0000]' }}" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    @endif
                                </span>

                                <span class="text-sm font-semibold">{{ $tItem['label'] }}</span>
                            </button>
                        @endif
                    @endforeach
                </nav>
            </div>
        </section>
    @endif

    <!-- Dedicated Single Integration Header (Matching Zip Design) -->
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
                        @if($activeReportIntegrationId === 'ga4') Google Analytics Website Traffic
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
                    @if($activeReportIntegrationId === 'ga4') Track website traffic, behavior, and key events across your website.
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
                <!-- Date Range Picker (Calendar) -->
                <div class="shrink-0" wire:key="dp-top-box-{{ $activeReportIntegrationId }}-{{ $selectedWebsiteId }}">
                    @php
                        $minDateBound = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
                        $maxDateBound = \Carbon\Carbon::now()->format('Y-m-d');
                    @endphp
                    @include('partials.ga4-date-picker', ['minDateBound' => $minDateBound, 'maxDateBound' => $maxDateBound])
                </div>

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

                            $integrationCounter = 0;
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
                            @php $integrationCounter++; @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-ga4">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-900/50 shadow-xs">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Google Analytics 4</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Website Traffic & Engagement</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'ga4') disabled @else wire:click="selectIntegration('ga4')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('ga4')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('ga4')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                                    {{-- 1. Total Users --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Users</div>
                                                <div class="metric-value">{{ number_format($ga4ActiveUsers) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4ActiveUsers, $compGa4Users); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. New Users --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">New Users</div>
                                                <div class="metric-value">{{ number_format($ga4NewUsers) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4NewUsers, $compGa4New); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Returning Users --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Returning Users</div>
                                                <div class="metric-value">{{ number_format($ga4ReturningUsers) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4ReturningUsers, $compGa4Ret); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Engagement Time --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Engagement Time</div>
                                                <div class="metric-value">{{ $ga4AvgDuration }}</div>
                                            </div>
                                        </div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($currDurSecs, $compDurSecs); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 5. Key Events --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Key Events</div>
                                                <div class="metric-value">{{ number_format($ga4EventsCount) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGa4Comp)
                                            @php $d = $getDelta($ga4EventsCount, $compGa4Events); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
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
                            @php $integrationCounter++; @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gsc">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50 shadow-xs">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Search Console</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Organic Search Performance</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'gsc') disabled @else wire:click="selectIntegration('gsc')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('gsc')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('gsc')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Clicks --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Clicks</div>
                                                <div class="metric-value">{{ number_format($gscClicks) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscClicks, $gscCompSum['clicks'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Impressions --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Impressions</div>
                                                <div class="metric-value">{{ (isset($this) && method_exists($this, 'formatAbbreviated')) ? $this->formatAbbreviated($gscImpr) : number_format($gscImpr) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscImpr, $gscCompSum['impressions'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Avg CTR --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Avg. CTR</div>
                                                <div class="metric-value">{{ $gscCtr }}%</div>
                                            </div>
                                        </div>
                                        @if($hasGscComp)
                                            @php $d = $getDelta($gscCtr, $gscCompSum['ctr'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Avg Position --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Avg. Position</div>
                                                <div class="metric-value">{{ $gscPos }}</div>
                                            </div>
                                        </div>
                                        @if($hasGscComp)
                                            @php 
                                                // For position, lower is better
                                                $pDiff = (float)$gscPos - (float)($gscCompSum['position'] ?? 0);
                                                $pTrend = $pDiff < 0 ? 'up' : ($pDiff > 0 ? 'down' : 'flat');
                                                $pPct = ($gscCompSum['position'] ?? 0) > 0 ? round((abs($pDiff) / $gscCompSum['position']) * 100) : 0;
                                            @endphp
                                            <div class="metric-trend-row {{ $pTrend === 'up' ? 'metric-trend-up' : ($pTrend === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $pTrend === 'up' ? '↑' : ($pTrend === 'down' ? '↓' : '•') }} {{ $pPct }}%</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
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
                            @php $integrationCounter++; @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gads">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-100 dark:border-teal-900/50 shadow-xs">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Google Ads</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Paid Campaigns & ROI</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'gads' || $activeReportIntegrationId === 'meta_ads') disabled @else wire:click="selectIntegration('gads')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('gads')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('gads')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Spend --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Spend</div>
                                                <div class="metric-value">${{ number_format($gadsCost) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsCost, $compGadsCost); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Clicks --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Clicks</div>
                                                <div class="metric-value">{{ number_format($gadsClicks) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsClicks, $compGadsClicks); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Conversions --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Conversions</div>
                                                <div class="metric-value">{{ number_format($gadsConv) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGadsComp)
                                            @php $d = $getDelta($gadsConv, $compGadsConv); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Cost per conv. --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Cost per conv.</div>
                                                <div class="metric-value">${{ number_format($costPerConv, 2) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGadsComp)
                                            @php 
                                                $cpcDiff = $costPerConv - $compCostPerConv;
                                                $cpcTrend = $cpcDiff < 0 ? 'up' : ($cpcDiff > 0 ? 'down' : 'flat');
                                                $cpcPct = $compCostPerConv > 0 ? round((abs($cpcDiff) / $compCostPerConv) * 100) : 0;
                                            @endphp
                                            <div class="metric-trend-row {{ $cpcTrend === 'up' ? 'metric-trend-up' : ($cpcTrend === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $cpcTrend === 'up' ? '↑' : ($cpcTrend === 'down' ? '↓' : '•') }} {{ $cpcPct }}%</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 4. Facebook / Meta --}}
                        @if($isFacebookConnected)
                            @php
                                $fbBase = $facebookData ?? [];
                                $fbSum = $fbBase['summary'] ?? [];
                                $fbViews = $fbSum['views'] ?? ($fbSum['impressions'] ?? 0);
                                $fbFollows = $fbSum['follows'] ?? ($fbSum['followers'] ?? 0);
                                $fbVisits = $fbSum['visits'] ?? ($fbSum['reach'] ?? 0);
                                $fbInteractions = $fbSum['interactions'] ?? ($fbSum['engaged'] ?? 0);
                                $fbVideoViews = $fbSum['video_views'] ?? (array_sum(array_column($fbBase['top_posts'] ?? [], 'video_views')) ?: (int)round($fbViews * 0.4));

                                $fbComp = $fbBase['compare_data'] ?? [];
                                $fbCompSum = $fbComp['summary'] ?? [];
                                $hasFbComp = !empty($fbCompSum);
                                $fbCompViews = $fbCompSum['views'] ?? ($fbCompSum['impressions'] ?? 0);
                                $fbCompFollows = $fbCompSum['follows'] ?? ($fbCompSum['followers'] ?? 0);
                                $fbCompVisits = $fbCompSum['visits'] ?? ($fbCompSum['reach'] ?? 0);
                                $fbCompInteractions = $fbCompSum['interactions'] ?? ($fbCompSum['engaged'] ?? 0);
                                $fbCompVideoViews = $fbCompSum['video_views'] ?? (int)round($fbCompViews * 0.4);
                            @endphp
                            @php $integrationCounter++; @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-facebook">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-500 flex items-center justify-center border border-blue-100 dark:border-blue-900/50 shadow-xs">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Meta (Facebook &amp; Instagram)</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Views, Follows, Visits &amp; Interactions</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'facebook') disabled @else wire:click="selectIntegration('facebook')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('facebook')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('facebook')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                                    {{-- 1. Views --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate" title="Content includes videos, posts, stories and ads.">Views</div>
                                                <div class="metric-value">{{ number_format($fbViews) }}</div>
                                            </div>
                                        </div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbViews, $fbCompViews); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="metric-trend-sub">vs. prev {{ $daysDiff ?? 28 }}d</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 2. Follows --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Follows</div>
                                                <div class="metric-value">{{ number_format($fbFollows) }}</div>
                                            </div>
                                        </div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbFollows, $fbCompFollows); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="metric-trend-sub">vs. prev {{ $daysDiff ?? 28 }}d</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 3. Visits --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Visits</div>
                                                <div class="metric-value">{{ number_format($fbVisits) }}</div>
                                            </div>
                                        </div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbVisits, $fbCompVisits); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="metric-trend-sub">vs. prev {{ $daysDiff ?? 28 }}d</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 4. Interactions --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Interactions</div>
                                                <div class="metric-value">{{ number_format($fbInteractions) }}</div>
                                            </div>
                                        </div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbInteractions, $fbCompInteractions); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="metric-trend-sub">vs. prev {{ $daysDiff ?? 28 }}d</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 5. Video/Reels Views --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate" title="The number of times your reels were played for at least 3 seconds, or for nearly their total length if they're shorter than 3 seconds. During a single instance of a reel playing, we'll exclude any time spent replaying it.">Video/Reels Views</div>
                                                <div class="metric-value">{{ number_format($fbVideoViews) }}</div>
                                            </div>
                                        </div>
                                        @if($hasFbComp)
                                            @php $d = $getDelta($fbVideoViews, $fbCompVideoViews); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                                <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                                <span class="metric-trend-sub">vs. prev {{ $daysDiff ?? 28 }}d</span>
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
                            @php $integrationCounter++; @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gbp">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50 shadow-xs">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Google Business Profile</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Local Listing & Customer Actions</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'gbp') disabled @else wire:click="selectIntegration('gbp')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('gbp')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('gbp')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Total Views --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Views</div>
                                                <div class="metric-value">{{ number_format($gbpViews) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpViews, $gbpCompSum['views'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Total Searches --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Searches</div>
                                                <div class="metric-value">{{ number_format($gbpSearches) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpSearches, $gbpCompSum['searches'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Interactions --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Interactions</div>
                                                <div class="metric-value">{{ number_format($gbpInteractions) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpInteractions, $gbpCompSum['interactions'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Calls --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Calls</div>
                                                <div class="metric-value">{{ number_format($gbpCalls) }}</div>
                                            </div>
                                        </div>
                                        @if($hasGbpComp)
                                            @php $d = $getDelta($gbpCalls, $gbpCompSum['calls'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
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
                                $integrationCounter++;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-keyword">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center shadow-xs">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Keyword.com</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">SEO Keyword Rank Tracking</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'keyword') disabled @else wire:click="selectIntegration('keyword')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('keyword')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('keyword')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Total Keywords --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Keywords</div>
                                                <div class="metric-value">{{ number_format($kwTotal) }}</div>
                                            </div>
                                        </div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTotal, $kwCompSum['total_keywords'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Top 10 Keywords --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Top 10 Keywords</div>
                                                <div class="metric-value">{{ number_format($kwTop10) }}</div>
                                            </div>
                                        </div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTop10, $kwCompSum['top_10'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Top 15 Keywords --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Top 15 Keywords</div>
                                                <div class="metric-value">{{ number_format($kwTop15) }}</div>
                                            </div>
                                        </div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwTop15, $kwCompSum['top_15'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Top 10 Pages --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Top 10 Pages</div>
                                                <div class="metric-value">{{ number_format($kwPages) }}</div>
                                            </div>
                                        </div>
                                        @if($hasKwComp)
                                            @php $d = $getDelta($kwPages, $kwCompSum['top_10_pages'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
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
                                $integrationCounter++;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-youtube">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center shadow-xs">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">YouTube</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Video Views & Channel Growth</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'youtube') disabled @else wire:click="selectIntegration('youtube')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('youtube')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('youtube')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Total Views --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Views</div>
                                                <div class="metric-value">{{ number_format($ytViews) }}</div>
                                            </div>
                                        </div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytViews, $ytCompSum['views'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Watch Time --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Watch Time (hrs)</div>
                                                <div class="metric-value">{{ number_format($ytWatchTime, 1) }}</div>
                                            </div>
                                        </div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytWatchTime, $ytCompSum['watch_time'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Subscribers --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Subscribers</div>
                                                <div class="metric-value">{{ number_format($ytSubs) }}</div>
                                            </div>
                                        </div>
                                        @if($hasYtComp)
                                            @php $d = $getDelta($ytSubs, $ytCompSum['subscribers'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Avg Duration --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Avg Duration</div>
                                                <div class="metric-value">{{ $ytAvgDuration }}</div>
                                            </div>
                                        </div>
                                        <div class="metric-trend-row text-slate-400 dark:text-slate-500 font-medium text-xs">
                                            Per view length
                                        </div>
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
                                $integrationCounter++;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-linkedin">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-500 flex items-center justify-center shadow-xs">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">LinkedIn</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Professional Network Engagement</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'linkedin') disabled @else wire:click="selectIntegration('linkedin')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('linkedin')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('linkedin')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Followers --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Followers</div>
                                                <div class="metric-value">{{ number_format($liFollowers) }}</div>
                                            </div>
                                        </div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liFollowers, $liCompSum['followers'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 2. Impressions --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Impressions</div>
                                                <div class="metric-value">{{ number_format($liImpr) }}</div>
                                            </div>
                                        </div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liImpr, $liCompSum['impressions'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 3. Total Clicks --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Clicks</div>
                                                <div class="metric-value">{{ number_format($liClicks) }}</div>
                                            </div>
                                        </div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liClicks, $liCompSum['clicks'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- 4. Engagements --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Engagements</div>
                                                <div class="metric-value">{{ number_format($liEngagements) }}</div>
                                            </div>
                                        </div>
                                        @if($hasLiComp)
                                            @php $d = $getDelta($liEngagements, $liCompSum['engagements'] ?? 0); @endphp
                                            <div class="metric-trend-row {{ $d['trend'] === 'up' ? 'metric-trend-up' : ($d['trend'] === 'down' ? 'metric-trend-down' : 'metric-trend-neutral') }}">
                                            <span>{{ $d['trend'] === 'up' ? '↑' : ($d['trend'] === 'down' ? '↓' : '•') }} {{ $d['val'] }}</span>
                                            <span class="metric-trend-sub">vs. previous {{ $daysDiff ?? 28 }} days</span>
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
                                $integrationCounter++;
                            @endphp
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200" wire:key="overview-card-gtm">
                                <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="integration-corner-num">#{{ $integrationCounter }}.</span>
                                        <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                            </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-base font-medium text-[#135266] dark:text-white">Tag Manager</h3>
                                            </div>
                                            <span class="text-xs text-slate-400">Container Overview & Assets</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                        @if($activeReportIntegrationId === 'gtm') disabled @else wire:click="selectIntegration('gtm')" @endif
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 hover:border-slate-300 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="selectIntegration('gtm')" class="animate-spin w-3.5 h-3.5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>View Details</span>
                                        <svg wire:loading.remove wire:target="selectIntegration('gtm')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- 1. Total Tags --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Tags</div>
                                                <div class="metric-value">{{ number_format($gtmTags) }}</div>
                                            </div>
                                        </div>
                                        <div class="metric-trend-row text-slate-400 dark:text-slate-500 font-medium text-xs">
                                            Active tags
                                        </div>
                                    </div>

                                    {{-- 2. Total Triggers --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Triggers</div>
                                                <div class="metric-value">{{ number_format($gtmTriggers) }}</div>
                                            </div>
                                        </div>
                                        <div class="metric-trend-row text-slate-400 dark:text-slate-500 font-medium text-xs">
                                            Firing rules
                                        </div>
                                    </div>

                                    {{-- 3. Total Variables --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Variables</div>
                                                <div class="metric-value">{{ number_format($gtmVariables) }}</div>
                                            </div>
                                        </div>
                                        <div class="metric-trend-row text-slate-400 dark:text-slate-500 font-medium text-xs">
                                            User-defined vars
                                        </div>
                                    </div>

                                    {{-- 4. Total Folders --}}
                                    <div class="metric-card-theme p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="metric-icon-circle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="metric-title truncate">Total Folders</div>
                                                <div class="metric-value">{{ number_format($gtmFolders) }}</div>
                                            </div>
                                        </div>
                                        <div class="metric-trend-row text-slate-400 dark:text-slate-500 font-medium text-xs">
                                            Organized sets
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
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
