@section('page_title', 'Maintenance Reports')

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Maintenance Reports' => null]" />

    {{-- Page Header --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage, review, compile, and distribute monthly website maintenance reports to clients
        </p>

        <a href="{{ route('admin.maintenance.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-semibold shadow-sm shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-150 active:scale-95 shrink-0 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New Report
        </a>
    </div>

    {{-- Summary Statistics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Card 1: Total Reports --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Reports</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $totalReportsCount }}</h3>
                <p class="text-[11px] text-emerald-500 dark:text-emerald-400 font-semibold mt-1.5 flex items-center gap-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    +{{ $reportsThisMonthCount }} this month
                </p>
            </div>
            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        {{-- Card 2: Completed --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Completed</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $completedReportsCount }}</h3>
                <p class="text-[11px] text-emerald-500 dark:text-emerald-400 font-semibold mt-1.5">on schedule</p>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Pending --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Pending</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $pendingReportsCount }}</h3>
                <p class="text-[11px] text-amber-500 dark:text-amber-400 font-semibold mt-1.5">awaiting approval</p>
            </div>
            <div class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Card 4: Avg Score --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Avg Score</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $averageDesktopScore }}</h3>
                @if($scoreChange > 0)
                    <p class="text-[11px] text-emerald-500 dark:text-emerald-400 font-semibold mt-1.5">+{{ $scoreChange }} vs last month</p>
                @elseif($scoreChange < 0)
                    <p class="text-[11px] text-red-500 dark:text-red-400 font-semibold mt-1.5">{{ $scoreChange }} vs last month</p>
                @else
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-semibold mt-1.5">No change vs last month</p>
                @endif
            </div>
            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Alert notifications --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-5" :message="session('success')" />
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" class="mb-5" :message="session('error')" />
    @endif

    {{-- Filter panel --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Search input --}}
            <div class="relative flex items-center col-span-1">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search report, client, web..."
                       class="block w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Client Searchable Filter Dropdown --}}
            <div x-data="{ 
                    open: false, 
                    search: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->user->name ?? 'Deleted', 'email' => $c->user->email ?? ''])) }},
                    select(id, name) {
                        this.search = name;
                        $wire.set('clientFilter', id);
                        this.open = false;
                    },
                    clear() {
                        this.search = '';
                        $wire.set('clientFilter', '');
                        this.open = false;
                    },
                    syncSearch() {
                        const val = $wire.get('clientFilter');
                        if (!val) {
                            this.search = '';
                        } else {
                            const found = this.clients.find(c => c.id == val);
                            this.search = found ? found.name : '';
                        }
                    },
                    init() {
                        this.syncSearch();
                        this.$watch('$wire.clientFilter', () => this.syncSearch());
                    }
                 }" 
                 class="relative col-span-1">
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           x-on:focus="open = true"
                           x-on:click.outside="open = false"
                           placeholder="Filter by Client..."
                           class="block w-full pl-3 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
                    
                    <template x-if="$wire.clientFilter">
                        <button type="button" x-on:click="clear()" class="absolute right-8 top-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </template>
                    
                    <button type="button" x-on:click="open = !open" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Dropdown Options -->
                <div x-show="open" 
                     x-transition
                     class="absolute z-50 w-full mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl max-h-60 overflow-y-auto scrollbar-thin">
                    <div x-on:click="clear()" class="px-4 py-2 text-sm text-slate-505 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors font-medium">
                        All Clients
                    </div>
                    <template x-for="c in clients" :key="c.id">
                        <div x-show="search === '' || c.name.toLowerCase().includes(search.toLowerCase()) || (c.email && c.email.toLowerCase().includes(search.toLowerCase()))"
                             x-on:click="select(c.id, c.name)"
                             class="px-4 py-2 text-sm text-slate-705 dark:text-slate-200 hover:bg-indigo-500 hover:text-white cursor-pointer transition-colors font-medium flex justify-between items-center"
                             wire:key="client-opt-${c.id}">
                            <span x-text="c.name"></span>
                            <span x-text="c.email" class="text-[10px] text-slate-400 dark:text-slate-500 ml-2 font-normal truncate max-w-[150px]"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Website Searchable Filter Dropdown --}}
            <div x-data="{ 
                    open: false, 
                    search: '',
                    websites: {{ Js::from($websites->map(fn($w) => ['id' => $w->id, 'name' => $w->site_name, 'url' => $w->url])) }},
                    select(id, name) {
                        this.search = name;
                        $wire.set('websiteFilter', id);
                        this.open = false;
                    },
                    clear() {
                        this.search = '';
                        $wire.set('websiteFilter', '');
                        this.open = false;
                    },
                    syncSearch() {
                        const val = $wire.get('websiteFilter');
                        if (!val) {
                            this.search = '';
                        } else {
                            const found = this.websites.find(w => w.id == val);
                            this.search = found ? found.name : '';
                        }
                    },
                    init() {
                        this.syncSearch();
                        this.$watch('$wire.websiteFilter', () => this.syncSearch());
                    }
                 }" 
                 class="relative col-span-1">
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           x-on:focus="open = true"
                           x-on:click.outside="open = false"
                           placeholder="Filter by Website..."
                           class="block w-full pl-3 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
                    
                    <template x-if="$wire.websiteFilter">
                        <button type="button" x-on:click="clear()" class="absolute right-8 top-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </template>
                    
                    <button type="button" x-on:click="open = !open" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Dropdown Options -->
                <div x-show="open" 
                     x-transition
                     class="absolute z-50 w-full mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl max-h-60 overflow-y-auto scrollbar-thin">
                    <div x-on:click="clear()" class="px-4 py-2 text-sm text-slate-505 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors font-medium">
                        All Websites
                    </div>
                    <template x-for="w in websites" :key="w.id">
                        <div x-show="search === '' || w.name.toLowerCase().includes(search.toLowerCase()) || (w.url && w.url.toLowerCase().includes(search.toLowerCase()))"
                             x-on:click="select(w.id, w.name)"
                             class="px-4 py-2 text-sm text-slate-750 dark:text-slate-200 hover:bg-indigo-500 hover:text-white cursor-pointer transition-colors font-medium flex justify-between items-center"
                             wire:key="web-opt-${w.id}">
                            <span x-text="w.name"></span>
                            <span x-text="w.url" class="text-[10px] text-slate-400 dark:text-slate-500 ml-2 font-normal truncate max-w-[180px]"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="statusFilter" class="block w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition duration-150">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            {{-- Month Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="monthFilter" class="block w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition duration-150">
                    <option value="">All Months</option>
                    @foreach($availableMonths as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Send Status Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="sendFilter" class="block w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition duration-150">
                    <option value="">All Send Status</option>
                    <option value="sent">Sent</option>
                    <option value="unsent">Unsent</option>
                </select>
            </div>
        </div>

        @if($hasActiveFilters)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Filters active — showing filtered reports
                </span>
                <button type="button" wire:click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    {{-- Reports Data Table --}}
    <x-admin.card>
        @if($reports->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Reports Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Create a monthly audit report for client sites to record themes, plugins and PHP upgrades.</p>
            </div>
        @else
            <x-admin.table :headers="['Report ID', 'Client', 'Website', 'Month', 'Developer', 'Status', 'Actions']">
                @foreach($reports as $report)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-4 py-4 font-bold text-slate-900 dark:text-white text-sm">
                            #{{ $report->id }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-semibold text-slate-800 dark:text-slate-200">
                                {{ $report->client->user->name }}
                            </div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $report->client->company_name }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-semibold text-slate-800 dark:text-slate-200">
                                {{ $report->website->site_name }}
                            </div>
                            <div class="text-[11px] mt-0.5 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium underline">
                                <a href="{{ $report->website->url }}" target="_blank">{{ $report->website->url }}</a>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400 font-semibold text-xs">
                            {{ $report->maintenance_month }}
                        </td>
                        <td class="px-4 py-4 text-slate-600 dark:text-slate-300 text-xs">
                            {{ $report->developer->name }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider
                                  {{ $report->status === 'completed'
                                      ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                      : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                {{ $report->status }}
                            </span>
                        <td class="px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                {{-- View button --}}
                                <a href="{{ route('admin.maintenance.view', $report->id) }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="View Report">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Edit button --}}
                                <a href="{{ route('admin.maintenance.edit', $report->id) }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Edit Report">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- PDF button --}}
                                <a href="{{ route('admin.maintenance.pdf', $report->id) }}" target="_blank"
                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-rose-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Generate PDF">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>

                                {{-- Email button --}}
                                <button type="button" 
                                        wire:click="emailReport({{ $report->id }})"
                                        wire:confirm="Send Maintenance Report #{{ $report->id }} to client's email ({{ $report->client->user->email }})?"
                                        wire:loading.attr="disabled"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-150 relative 
                                               {{ $report->last_sent_at ? 'text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20' : 'text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50' }}" 
                                        title="{{ $report->last_sent_at ? 'Emailed to client on ' . $report->last_sent_at->format('d M, H:i') . ' (Click to send again)' : 'Email Report to Client' }}">
                                    <div wire:loading.remove wire:target="emailReport({{ $report->id }})">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div wire:loading wire:target="emailReport({{ $report->id }})">
                                        <svg class="animate-spin w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </button>

                                {{-- Delete button --}}
                                <button type="button" wire:click="deleteReport({{ $report->id }})"
                                        wire:confirm="Are you sure you want to delete this maintenance report?"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Delete Report">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
