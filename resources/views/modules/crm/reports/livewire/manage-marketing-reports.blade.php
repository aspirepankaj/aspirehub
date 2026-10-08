<div>
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
                        search: '',
                        selectedId: @entangle('clientId').live
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
                                <div x-show="search === '' || '{{ $searchString }}'.includes(search.toLowerCase())"
                                     @click="
                                        let u = new URL(window.location.href); 
                                        u.searchParams.delete('compareDateFrom'); 
                                        u.searchParams.delete('compareDateTo'); 
                                        window.history.replaceState({}, '', u.toString());
                                        selectedId = '{{ $client->id }}'; 
                                        open = false; 
                                        search = '';
                                     " 
                                     class="px-3 py-2.5 rounded-lg text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-2 {{ $clientId == $client->id ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-700 dark:text-slate-300' }}">
                                    <span class="truncate">{{ $clientName }}</span>
                                    @if($clientEmail)
                                        <span class="text-[11px] text-slate-400 font-medium shrink-0">({{ $clientEmail }})</span>
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
                        search: '',
                        selectedId: @entangle('websiteId').live
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
                                <div x-show="search === '' || '{{ strtolower(addslashes($siteName)) }}'.includes(search.toLowerCase())"
                                     @click="
                                        let u = new URL(window.location.href); 
                                        u.searchParams.delete('compareDateFrom'); 
                                        u.searchParams.delete('compareDateTo'); 
                                        window.history.replaceState({}, '', u.toString());
                                        selectedId = '{{ $site->id }}'; 
                                        open = false; 
                                        search = '';
                                     " 
                                     class="px-3 py-2.5 rounded-lg text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center {{ $websiteId == $site->id ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-700 dark:text-slate-300' }}">
                                    <span class="truncate">{{ $siteName }}</span>
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
                <nav class="flex flex-nowrap space-x-1 min-w-max" aria-label="Tabs">
                    @foreach($integrations as $integration)
                        @php $intgId = $integration->integration_type; @endphp
                        <button type="button" 
                           x-on:click="let u = new URL(window.location.href); u.searchParams.delete('compareDateFrom'); u.searchParams.delete('compareDateTo'); window.history.replaceState({}, '', u.toString());"
                           wire:click="switchTab('{{ $intgId }}')"
                           class="py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm whitespace-nowrap transition-all duration-150 flex items-center gap-1.5 shrink-0 {{ $activeTab === $intgId ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-850 hover:bg-white/40 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/30' }}">
                            {{-- Icon mapping --}}
                            @if($intgId === 'ga4')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                <span>Google Analytics 4</span>
                            @elseif($intgId === 'gsc')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                <span>Search Console</span>
                            @elseif($intgId === 'gads')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 6l-9.5 9.5-5-5L1 18"></path><polyline points="17 6 23 6 23 12"></polyline></svg>
                                <span>Google Ads</span>
                            @elseif($intgId === 'facebook')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                                <span>Facebook</span>
                            @elseif($intgId === 'youtube')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.14 1 12 1 12s0 3.86.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.86 23 12 23 12s0-3.86-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>
                                <span>YouTube</span>
                            @elseif($intgId === 'keyword')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" /></svg>
                                <span>Keyword.com</span>
                            @elseif($intgId === 'gbp')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span>Business Profile</span>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                <span>{{ ucfirst($intgId) }}</span>
                            @endif
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- Selected Integration Report --}}
            <div class="animate-fadeIn">
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
