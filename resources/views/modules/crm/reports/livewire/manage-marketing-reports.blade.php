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
                                {{ collect($clients)->firstWhere('id', $clientId)?->user?->name ?? 'Deleted User' }} {{ collect($clients)->firstWhere('id', $clientId)?->company_name ? '('.collect($clients)->firstWhere('id', $clientId)->company_name.')' : '' }}
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

    {{-- Tabs & Reports Content --}}
    @if($websiteId)
        @if($integrations->isEmpty())
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">No Integrations Connected</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">This website doesn't have any connected marketing integrations yet.</p>
            </div>
        @else
            {{-- Integrations Tabs --}}
            <div class="mb-6 bg-slate-100/80 dark:bg-slate-900/50 p-1.5 rounded-2xl overflow-x-auto no-scrollbar border border-slate-200/40 dark:border-slate-800/40">
                <nav x-data class="flex flex-nowrap space-x-1 min-w-max" aria-label="Tabs">
                    @foreach($integrations as $integration)
                        @php $intgId = $integration->integration_type; @endphp
                        <button type="button" 
                           x-on:click="
                               $wire.activeTab = '{{ $intgId }}';
                               let u = new URL(window.location.href); 
                               u.searchParams.delete('compareDateFrom'); 
                               u.searchParams.delete('compareDateTo'); 
                               window.history.replaceState({}, '', u.toString());
                               $wire.switchTab('{{ $intgId }}');
                           "
                           :class="$wire.activeTab === '{{ $intgId }}' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-850 hover:bg-white/40 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/30'"
                           class="py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm whitespace-nowrap transition-all duration-150 flex items-center gap-1.5 shrink-0">
                            {{-- Icon mapping --}}
                            @if($intgId === 'ga4')
                                <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M22 20.5H2v-2h20v2zM5.5 17.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M5.5 16V4M11.5 16V9M17.5 16V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                <span>Google Analytics 4</span>
                            @elseif($intgId === 'gsc')
                                <svg class="w-4 h-4 text-blue-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                <span>Search Console</span>
                            @elseif($intgId === 'gads')
                                <svg class="w-4 h-4 text-teal-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Google Ads</span>
                            @elseif($intgId === 'facebook')
                                <svg class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                <span>Facebook</span>
                            @elseif($intgId === 'linkedin')
                                <svg class="w-4 h-4 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                <span>LinkedIn</span>
                            @elseif($intgId === 'youtube')
                                <svg class="w-4 h-4 text-red-500 fill-current shrink-0" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <span>YouTube</span>
                            @elseif($intgId === 'keyword')
                                <svg class="w-4 h-4 text-purple-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                <span>Keyword.com</span>
                            @elseif($intgId === 'gbp')
                                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 012 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Business Profile</span>
                            @elseif($intgId === 'gtm')
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                <span>Tag Manager</span>
                            @else
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                <span>{{ ucfirst($intgId) }}</span>
                            @endif
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- Selected Integration Report --}}
            <div class="relative min-h-[400px]">
                {{-- Skeleton Loader --}}
                <div wire:loading wire:target="switchTab, resetSelection" class="w-full font-sans pb-8 animate-pulse mt-2 relative">
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

                <div wire:loading.remove wire:target="switchTab, resetSelection" class="animate-fadeIn" wire:key="tab-container-{{ $activeTab }}">
                    @if($activeTab)
                        @livewire('admin-client-report', [
                            'id' => $clientId, 
                            'integration' => $activeTab, 
                            'websiteId' => $websiteId, 
                            'hideHeader' => true,
                            'compareDateFrom' => '',
                            'compareDateTo' => ''
                        ], key('report-'.$clientId.'-'.$websiteId.'-'.$activeTab))
                    @endif
                </div>
            </div>
        @endif
    @elseif($clientId)
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Select a Website</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Choose a website from the dropdown above to view its marketing reports.</p>
        </div>
    @endif
</div>
