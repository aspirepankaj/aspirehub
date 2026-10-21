<div>
    @section('page_title', 'Marketing Reports')
    <x-admin.breadcrumbs :items="['Marketing Reports' => null]" />
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Marketing Reports</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Select a client and website to view integration reports.</p>
            </div>
        </div>
        <div class="flex shrink-0">
            @if($clientId)
                <button type="button" wire:click="resetSelection()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Client Select --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Select Client</label>
                <div x-data="{
                        open: false,
                        search: ''
                    }"
                    class="relative"
                    @click.away="open = false"
                >
                    <button type="button" @click="open = !open; if(open) setTimeout(() => $refs.searchInput.focus(), 50)" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all cursor-pointer flex items-center justify-between">
                        <span class="truncate">
                            @if($clientId)
                                @php
                                    $selectedClient = collect($clients)->firstWhere('id', $clientId) ?? \App\Modules\CRM\Clients\Models\Client::with('user')->find($clientId);
                                @endphp
                                {{ $selectedClient?->user?->name ?? 'Choose a Client' }} {{ $selectedClient?->company_name ? '('.$selectedClient->company_name.')' : '' }}
                            @else
                                -- Choose a Client --
                            @endif
                        </span>
                        <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                        <div class="p-2 border-b border-slate-100 dark:border-slate-800">
                            <input type="text" x-model="search" x-ref="searchInput" placeholder="Search client..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="max-h-60 overflow-y-auto p-1">
                            @foreach($clients as $client)
                                @php
                                    $clientName = ($client->user->name ?? 'Deleted User') . ($client->company_name ? ' ('.$client->company_name.')' : '');
                                    $clientEmail = $client->user->email ?? '';
                                    
                                    // Build a hidden search string containing name, email, and websites
                                    $searchString = $clientName . ' ' . $clientEmail;
                                    if ($client->websites) {
                                        foreach ($client->websites as $ws) {
                                            $searchString .= ' ' . ($ws->site_name ?? '') . ' ' . ($ws->url ?? '');
                                        }
                                    }
                                    $searchString = strtolower(addslashes($searchString));
                                @endphp
                                <div wire:key="client-opt-{{ $client->id }}"
                                     x-show="search === '' || '{{ $searchString }}'.includes(search.toLowerCase())"
                                     @click="$wire.selectClient({{ $client->id }}); open = false; search = '';" 
                                     class="px-3 py-2.5 rounded-lg text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block w-full text-left {{ $clientId == $client->id ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-700 dark:text-slate-300' }}">
                                    <span class="truncate block w-full">{{ $clientName }}</span>
                                    @if($clientEmail)
                                        <span class="text-[11px] text-slate-400 font-medium block w-full truncate">({{ $clientEmail }})</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Website Select --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Select Website</label>
                <div x-data="{
                        open: false,
                        search: ''
                    }"
                    class="relative"
                    @click.away="open = false"
                >
                    <button type="button" @click="@if($clientId) open = !open; if(open) setTimeout(() => $refs.searchInput.focus(), 50) @endif" class="w-full appearance-none bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all flex items-center justify-between {{ !$clientId ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                        <span class="truncate">
                            @if($websiteId && collect($websites)->isNotEmpty())
                                @php $activeSite = collect($websites)->firstWhere('id', $websiteId); @endphp
                                {{ $activeSite ? $activeSite->site_name . ' (' . (parse_url($activeSite->url, PHP_URL_HOST) ?: $activeSite->url) . ')' : '-- Choose a Website --' }}
                            @else
                                -- Choose a Website --
                            @endif
                        </span>
                        <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                        <div class="p-2 border-b border-slate-100 dark:border-slate-800">
                            <input type="text" x-model="search" x-ref="searchInput" placeholder="Search website..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="max-h-60 overflow-y-auto p-1">
                            @forelse($websites as $site)
                                @php
                                    $siteName = $site->site_name . ' (' . (parse_url($site->url, PHP_URL_HOST) ?: $site->url) . ')';
                                @endphp
                                <div wire:key="website-opt-{{ $site->id }}"
                                     x-show="search === '' || '{{ strtolower(addslashes($siteName)) }}'.includes(search.toLowerCase())"
                                     @click="$wire.selectWebsite({{ $site->id }}); open = false; search = '';" 
                                     class="px-3 py-2.5 rounded-lg text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block w-full text-left {{ $websiteId == $site->id ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-700 dark:text-slate-300' }}">
                                    <span class="truncate block w-full">{{ $siteName }}</span>
                                </div>
                            @empty
                                <div class="px-3 py-2.5 text-sm text-slate-500 text-center">No websites found</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Marketing Prompt Bar --}}
    {{-- AI Marketing Filter Bar --}}
    <div class="mb-6 bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/70 dark:border-slate-800/70 rounded-2xl p-5 shadow-xs relative transition-all">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-3.5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">AI Executive Marketing Report</h2>
                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">OpenAI Telemetry</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ask AI to generate a complete visual HTML marketing report for any client, website, platform, and date range (e.g. "90-days Google Ads for Christine").</p>
                </div>
            </div>

            @if($clientId && $websiteId)
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" wire:click="resetSelection()" title="Reset Filters" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 border border-transparent hover:border-rose-200/50 dark:hover:border-rose-900/30 transition-all cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset All</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Prompt Input Form --}}
        <form wire:submit.prevent="generateAiReport" class="relative">
            <div class="flex items-center bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 rounded-2xl p-1.5 shadow-xs focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all">
                <div class="pl-3 pr-1 text-slate-400 dark:text-slate-500 shrink-0">
                    <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model="aiPrompt" 
                    placeholder="e.g. Create a 90-days Google Ads report for Christine or last 30 days GA4..." 
                    class="w-full py-2 px-3 bg-transparent text-sm font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 placeholder:font-normal border-0 focus:border-0 outline-none focus:outline-none ring-0 focus:ring-0 focus:ring-offset-0 shadow-none focus:shadow-none"
                    style="outline: none !important; box-shadow: none !important; border: none !important;"
                    :disabled="$isGeneratingAiReport"
                />
                @if(!empty($aiPrompt))
                    <button type="button" wire:click="$set('aiPrompt', '')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 mr-1 transition-colors cursor-pointer shrink-0" title="Clear prompt">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
                <div class="shrink-0">
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="h-10 min-w-[150px] px-5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow active:scale-95 disabled:cursor-wait disabled:bg-indigo-600 dark:disabled:bg-indigo-500 cursor-pointer inline-flex items-center justify-center gap-2"
                    >
                        <span wire:loading.remove wire:target="generateAiReport" class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            <span>Generate Report</span>
                        </span>
                        <span wire:loading wire:target="generateAiReport" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Generating...</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Error Banner --}}
        @if($aiError)
            <div class="mt-3 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-start justify-between gap-3 shadow-2xs">
                <div class="flex items-start gap-2.5 min-w-0">
                    <div class="w-6 h-6 rounded-lg bg-rose-100 dark:bg-rose-900/50 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="leading-relaxed break-words">{{ $aiError }}</span>
                </div>
                <button type="button" wire:click="$set('aiError', '')" class="text-rose-400 hover:text-rose-600 transition-colors p-1 cursor-pointer shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Active Filter Pills --}}
        @if(!$aiError && $clientId && $websiteId && $activeTab && $dateFrom && $dateTo)
            <div class="mt-3.5 pt-3 border-t border-slate-200/60 dark:border-slate-800/60 flex items-center gap-2 flex-wrap text-xs">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mr-1">Active View:</span>
                
                {{-- Client Pill --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs border border-slate-200/80 dark:border-slate-700">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    {{ collect($clients)->firstWhere('id', $clientId)?->user?->name ?? 'Client' }}
                </span>

                {{-- Website Pill --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs border border-slate-200/80 dark:border-slate-700">
                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                    {{ collect($websites)->firstWhere('id', $websiteId)?->site_name ?? 'Website' }}
                </span>

                {{-- Platform Pill --}}
                @php
                    $platformPills = [
                        'gads'     => ['name' => 'Google Ads', 'class' => 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/40'],
                        'ga4'      => ['name' => 'Google Analytics 4', 'class' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/40'],
                        'gsc'      => ['name' => 'Search Console', 'class' => 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 border-blue-200/60 dark:border-blue-800/40'],
                        'facebook' => ['name' => 'Facebook', 'class' => 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40'],
                        'linkedin' => ['name' => 'LinkedIn', 'class' => 'bg-sky-50 dark:bg-sky-950/30 text-sky-700 dark:text-sky-300 border-sky-200/60 dark:border-sky-800/40'],
                        'youtube'  => ['name' => 'YouTube', 'class' => 'bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border-rose-200/60 dark:border-rose-800/40'],
                        'keyword'  => ['name' => 'Keyword.com', 'class' => 'bg-violet-50 dark:bg-violet-950/30 text-violet-700 dark:text-violet-300 border-violet-200/60 dark:border-violet-800/40'],
                        'gbp'      => ['name' => 'Business Profile', 'class' => 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 border-blue-200/60 dark:border-blue-800/40'],
                        'gtm'      => ['name' => 'Tag Manager', 'class' => 'bg-teal-50 dark:bg-teal-950/30 text-teal-700 dark:text-teal-300 border-teal-200/60 dark:border-teal-800/40'],
                    ];
                    $currentPill = $platformPills[$activeTab] ?? ['name' => strtoupper($activeTab), 'class' => 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $currentPill['class'] }} font-extrabold uppercase text-xs border">
                    {{ $currentPill['name'] }}
                </span>

                {{-- Date Range Pill --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50/70 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 font-bold text-xs border border-indigo-200/60 dark:border-indigo-800/40">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo)) + 1 }} Days)
                </span>
            </div>
        @endif
    </div>    {{-- Print Styles --}}
    <style>
        @media print {
            nav, header, .no-print, button, form, .breadcrumbs, [role="navigation"] {
                display: none !important;
            }
            body, main {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .ai-report-content {
                width: 100% !important;
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    {{-- AI GENERATED HTML REPORT SECTION --}}
    {{-- 1. Loading Skeleton for AI Report Generation --}}
    <div wire:loading wire:target="generateAiReport" class="mb-6 w-full">
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-indigo-100 dark:border-indigo-900/40 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 dark:bg-indigo-400/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 animate-pulse shrink-0">
                    <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>AI Intelligence Engine is Generating Report...</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/40">OpenAI Telemetry</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Reading JSON metrics, analyzing channel performance, and composing custom executive HTML...</p>
                </div>
            </div>

            <div class="space-y-4 animate-pulse">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @for($i = 0; $i < 4; $i++)
                        <div class="p-5 rounded-xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-2">
                            <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/3"></div>
                            <div class="h-7 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
                            <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded w-1/2"></div>
                        </div>
                    @endfor
                </div>
                <div class="h-32 rounded-xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800"></div>
            </div>
        </div>
    </div>

    {{-- 2. Display Generated AI HTML Report --}}
    @if($showAiHtmlReport && (!empty($aiGeneratedHtml) || !empty($aiReportsByPlatform[$activeAiTab])))
        <div wire:loading.remove wire:target="generateAiReport" class="mb-8 space-y-3">
            
            {{-- Multiple AI Platform Tabs --}}
            @if(count($aiTabs) > 1)
                @php
                    $platformMeta = [
                        'gads'     => ['title' => 'Google Ads', 'color' => 'emerald'],
                        'ga4'      => ['title' => 'Google Analytics 4', 'color' => 'amber'],
                        'gsc'      => ['title' => 'Search Console', 'color' => 'blue'],
                        'facebook' => ['title' => 'Facebook', 'color' => 'indigo'],
                        'linkedin' => ['title' => 'LinkedIn', 'color' => 'sky'],
                        'youtube'  => ['title' => 'YouTube', 'color' => 'rose'],
                        'keyword'  => ['title' => 'Keyword.com', 'color' => 'purple'],
                        'gbp'      => ['title' => 'Business Profile', 'color' => 'blue'],
                        'gtm'      => ['title' => 'Tag Manager', 'color' => 'teal'],
                    ];
                @endphp
                <div class="no-print bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/70 dark:border-slate-800/70 rounded-2xl p-2.5 shadow-2xs">
                    <div class="flex items-center justify-between gap-3 px-2 pt-0.5 pb-2 border-b border-slate-100 dark:border-slate-800/60 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                AI Channel Reports ({{ count($aiTabs) }} Platforms)
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">
                            Click any platform tab to view its dedicated AI analysis
                        </span>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-0.5">
                        @foreach($aiTabs as $tabKey)
                            @php
                                $isActive = ($activeAiTab === $tabKey);
                                $isCached = !empty($aiReportsByPlatform[$tabKey]);
                                $meta = $platformMeta[$tabKey] ?? ['title' => strtoupper($tabKey), 'color' => 'indigo'];
                            @endphp
                            <button 
                                type="button" 
                                wire:click="switchAiTab('{{ $tabKey }}')" 
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer shrink-0 border {{ $isActive ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25 border-indigo-600 font-black' : 'bg-slate-50/80 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200/70 dark:border-slate-700/70 hover:border-slate-300' }}"
                            >
                                {{-- Tab Platform Icon --}}
                                @if($tabKey === 'gads')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-emerald-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                @elseif($tabKey === 'ga4')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-amber-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                @elseif($tabKey === 'gsc')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-blue-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                @elseif($tabKey === 'facebook')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-blue-600' }} fill-current shrink-0" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($tabKey === 'linkedin')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-sky-500' }} fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                @elseif($tabKey === 'youtube')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-rose-500' }} fill-current shrink-0" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @elseif($tabKey === 'keyword')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-purple-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                @elseif($tabKey === 'gbp')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-blue-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @elseif($tabKey === 'gtm')
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-teal-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                @else
                                    <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-indigo-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                @endif

                                <span>{{ $meta['title'] }}</span>

                                {{-- Cached Status Indicator --}}
                                @if($isCached)
                                    <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-emerald-300' : 'bg-emerald-500' }}" title="Report ready"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-indigo-300' : 'bg-slate-300 dark:bg-slate-600' }}" title="Click to generate"></span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Action & Control Toolbar --}}
            <div class="no-print flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/70 dark:border-slate-800/70 rounded-2xl px-5 py-3 shadow-2xs">
                @php
                    $platformTitles = [
                        'gads'     => 'Google Ads',
                        'ga4'      => 'Google Analytics 4',
                        'gsc'      => 'Search Console',
                        'facebook' => 'Facebook',
                        'linkedin' => 'LinkedIn',
                        'youtube'  => 'YouTube',
                        'keyword'  => 'Keyword.com',
                        'gbp'      => 'Business Profile',
                        'gtm'      => 'Tag Manager',
                    ];
                    $currentPlatformName = $platformTitles[$activeAiTab] ?? strtoupper($activeAiTab ?? 'Executive');
                @endphp
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-black tracking-tight text-slate-800 dark:text-slate-200 uppercase">
                        AI-Generated {{ $currentPlatformName }} Executive Report
                    </span>
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Built from raw JSON telemetry files</span>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto justify-end">
                    {{-- Print / Export PDF --}}
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200/60 dark:border-indigo-800/40 transition-all cursor-pointer shadow-2xs"
                        title="Print or save as PDF"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Print Report</span>
                    </button>

                    {{-- Close / Dismiss AI Report --}}
                    <button 
                        type="button" 
                        wire:click="closeAiHtmlReport" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 bg-slate-50 hover:bg-rose-50 dark:bg-slate-800 dark:hover:bg-rose-950/20 border border-slate-200 dark:border-slate-700 hover:border-rose-200 dark:hover:border-rose-900/30 transition-all cursor-pointer shadow-2xs"
                        title="Dismiss AI report"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Dismiss</span>
                    </button>
                </div>
            </div>

            {{-- Dedicated Tab Switch Loading Skeleton --}}
            <div wire:loading wire:target="switchAiTab" class="w-full">
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-indigo-100 dark:border-indigo-900/40 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600/10 dark:bg-indigo-400/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 animate-pulse shrink-0">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Generating Dedicated AI Report for this Platform...</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/40">OpenAI Telemetry</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Fetching platform metrics from JSON telemetry and creating custom visual charts...</p>
                        </div>
                    </div>
                    <div class="space-y-3 animate-pulse">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div class="h-20 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                            <div class="h-20 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                            <div class="h-20 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                            <div class="h-20 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                        </div>
                        <div class="h-40 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                    </div>
                </div>
            </div>

            {{-- Pure AI Generated HTML Output --}}
            <div wire:loading.remove wire:target="switchAiTab" class="ai-report-content">
                {!! $aiReportsByPlatform[$activeAiTab] ?? $aiGeneratedHtml !!}
            </div>
        </div>
    @elseif(!$isGeneratingAiReport)
        {{-- When No AI Report is Active Yet --}}
        @if($websiteId)
            @php 
                $activeSite = collect($websites)->firstWhere('id', $websiteId);
                $activeClient = collect($clients)->firstWhere('id', $clientId);
            @endphp
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-10 text-center shadow-xs">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-100 dark:border-indigo-900/30">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">
                    {{ $activeSite->site_name ?? 'Website' }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-5">
                    Client: <strong class="text-slate-700 dark:text-slate-300">{{ $activeClient?->user?->name ?? 'Client' }}</strong> • Telemetry is ready. Click below or enter a custom prompt above to generate the full AI HTML executive report.
                </p>
                <button 
                    type="button" 
                    wire:click="generateAiReport" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow active:scale-95 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <span>Generate AI Executive Report</span>
                </button>
            </div>
        @elseif($clientId)
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center shadow-xs">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Select a Website</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Choose a website from the dropdown above or enter any prompt to generate the AI report.</p>
            </div>
        @else
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center shadow-xs">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Ready to Generate AI Marketing Report</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">Select a client and website above, or type any prompt (e.g. "Create a 90-days Google Ads report for Christine") to instantly generate the AI report.</p>
            </div>
        @endif
    @endif
</div>
