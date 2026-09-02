@section('page_title', $clientDetails ? 'Client: ' . $clientDetails->company_name : 'My Clients')

<div>
    <!-- Breadcrumbs -->
    @if ($clientDetails)
        <x-admin.breadcrumbs :items="['My Clients' => route('staff.clients'), $clientDetails->user->name ?? 'Detail' => null]" />
    @else
        <x-admin.breadcrumbs :items="['My Clients' => null]" />
    @endif

    @if ($clientDetails)
        {{-- ==========================================
             CLIENT DETAIL DASHBOARD VIEW
             ========================================== --}}
        <!-- Back Button -->
        <div class="mb-4">
            <button type="button" wire:click="selectClient(null)" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to clients
            </button>
        </div>

        <!-- Client Header Card -->
        <div class="bg-white/93 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 sm:p-6 mb-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6 overflow-hidden">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full">
                <div class="relative shrink-0">
                    @if($clientDetails->profile_image)
                        <img src="{{ asset('storage/' . $clientDetails->profile_image) }}" alt="{{ $clientDetails->user->name }}" class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg sm:text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $clientDetails->getInitials() }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white truncate">{{ $clientDetails->user->name }}</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $clientDetails->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-600 dark:text-slate-400' }} tracking-wider">
                            {{ $clientDetails->status }}
                        </span>
                        @foreach($clientDetails->plans as $pl)
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-{{ $pl->color }}-500/10 text-{{ $pl->color }}-600 dark:text-{{ $pl->color }}-400 tracking-wider">
                                {{ $pl->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-1.5 sm:mt-2 flex flex-wrap gap-x-3 sm:gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <span class="flex items-center gap-1.5 truncate max-w-full">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">{{ $clientDetails->company_name ?: 'No Company' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 truncate max-w-full">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 00-2 2z" />
                            </svg>
                            <span class="truncate">{{ $clientDetails->user->email }}</span>
                        </span>
                        @if($clientDetails->phones->isNotEmpty())
                            <span class="flex items-center gap-1.5 truncate max-w-full">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="truncate">{{ $clientDetails->phones->first()->phone }}</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation (Responsive Horizontal Scroll) -->
        <div class="border-b border-slate-200/60 dark:border-slate-800/40 mb-6 overflow-hidden">
            <nav class="flex space-x-3 sm:space-x-8 overflow-x-auto whitespace-nowrap scrollbar-none pb-0.5" aria-label="Tabs">
                @php
                    $navTabs = [
                        'overview' => 'Overview', 
                        'websites' => 'Websites',
                    ];
                    if ($isAssignedToStaff) {
                        $navTabs['integrations'] = 'Integrations';
                        $navTabs['clickup_tickets'] = 'ClickUp Tickets';
                    }
                    $navTabs['maintenance'] = 'Maintenance';
                    $navTabs['documents'] = 'Resources';
                    $navTabs['activity log'] = 'Activity Log';
                @endphp
                @foreach($navTabs as $tabKey => $tabLabel)
                    <button type="button" wire:click="$set('activeTab', '{{ $tabKey }}')" class="py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-bold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 shrink-0 {{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                        <span>{{ $tabLabel }}</span>
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Tab Content --}}
        <div>
            @if ($activeTab === 'overview')
                {{-- Overview content --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fadeIn">
                    <!-- About Card -->
                    <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">About</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $clientDetails->notes ?: 'No additional notes provided for this client.' }}
                        </p>
                    </div>

                    <!-- Assigned Team Card -->
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Assigned team</h3>
                        @if ($clientDetails->assignedStaff->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($clientDetails->assignedStaff as $staff)
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl">
                                        <div class="shrink-0">
                                            @if($staff->profile_image)
                                                <img src="{{ asset('storage/' . $staff->profile_image) }}" alt="Staff Avatar" class="w-10 h-10 rounded-full object-cover" />
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                                    {{ $staff->getInitials() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $staff->user->name ?? 'Deleted Staff' }}</div>
                                            <div class="text-[10px] font-extrabold uppercase text-slate-450 dark:text-slate-500 tracking-wider">
                                                {{ $staff->designations->pluck('name')->implode(', ') ?: 'Staff Member' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-sm text-slate-450 dark:text-slate-500 italic">
                                No team members assigned to this client yet.
                            </div>
                        @endif
                    </div>
                </div>

            @elseif ($activeTab === 'websites')
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm animate-fadeIn">
                    @if ($clientWebsites->isEmpty())
                        <div class="text-center py-12 text-slate-500">No websites mapped yet.</div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Site Name</th>
                                    <th class="px-6 py-4">URL</th>
                                    <th class="px-6 py-4">Latest Maintenance Score</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                                @foreach ($clientWebsites as $web)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $web->site_name }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ $web->url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">{{ $web->url }}</a>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-650 dark:text-slate-350">
                                            {{ $web->latestMaintenanceReport ? $web->latestMaintenanceReport->health_score . '%' : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Online</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            @elseif ($activeTab === 'integrations')
                <!-- Website Selection Dropdown -->
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4 p-4 bg-slate-50/50 dark:bg-slate-900/30 border border-slate-200/40 dark:border-slate-800/40 rounded-2xl">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Manage Integrations</h3>
                        <p class="text-xs text-slate-500">Select a specific website to manage its third-party connection credentials.</p>
                    </div>
                    <div class="w-full sm:w-64">
                        <select wire:model.live="selectedWebsiteId" class="w-full text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-750 dark:text-slate-300 py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            @if($clientWebsites->isEmpty())
                                <option value="">No websites registered</option>
                            @else
                                @foreach($clientWebsites as $site)
                                    <option value="{{ $site->id }}">{{ $site->site_name }} ({{ parse_url($site->url, PHP_URL_HOST) ?? $site->url }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                @if(!$selectedWebsiteId)
                    <div class="border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-8 text-center bg-slate-50/20 dark:bg-slate-950/10">
                        <svg class="w-12 h-12 text-slate-350 dark:text-slate-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                        </svg>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-1">No Website Selected</h4>
                        <p class="text-xs text-slate-450 dark:text-slate-550">Please register or select a website from the list above to configure its third-party app integrations.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fadeIn">
                        @foreach ($clientIntegrations as $integration)
                            @php
                                $isNotConfigured = $integration['status'] === 'not_configured';
                                $isConfigured = $integration['status'] === 'credentials_configured';
                                $isConnected = $integration['status'] === 'connected';
                                $isDegraded = $integration['status'] === 'degraded';

                                $cardTheme = match($integration['id']) {
                                    'ga4' => [
                                        'iconBg' => 'bg-orange-50 dark:bg-orange-950/20',
                                        'hoverBorder' => 'hover:border-orange-500/35 dark:hover:border-orange-500/25',
                                        'hoverShadow' => 'hover:shadow-orange-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-orange-500 dark:text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>'
                                    ],
                                    'gsc' => [
                                        'iconBg' => 'bg-blue-50 dark:bg-blue-950/20',
                                        'hoverBorder' => 'hover:border-blue-500/35 dark:hover:border-blue-500/25',
                                        'hoverShadow' => 'hover:shadow-blue-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-blue-500 dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>'
                                    ],
                                    'gads' => [
                                        'iconBg' => 'bg-emerald-50 dark:bg-emerald-950/20',
                                        'hoverBorder' => 'hover:border-emerald-500/35 dark:hover:border-emerald-500/25',
                                        'hoverShadow' => 'hover:shadow-emerald-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 6l-9.5 9.5-5-5L1 18"></path><polyline points="17 6 23 6 23 12"></polyline></svg>'
                                    ],
                                    'youtube' => [
                                        'iconBg' => 'bg-red-50 dark:bg-red-950/20',
                                        'hoverBorder' => 'hover:border-red-500/35 dark:hover:border-red-500/25',
                                        'hoverShadow' => 'hover:shadow-red-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.14 1 12 1 12s0 3.86.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.86 23 12 23 12s0-3.86-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>'
                                    ],
                                    'keyword' => [
                                        'iconBg' => 'bg-slate-100 dark:bg-slate-800/60',
                                        'hoverBorder' => 'hover:border-slate-400/35 dark:hover:border-slate-500/25',
                                        'hoverShadow' => 'hover:shadow-slate-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-slate-600 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>'
                                    ],
                                    default => [
                                        'iconBg' => 'bg-slate-50 dark:bg-slate-800/40',
                                        'hoverBorder' => 'hover:border-indigo-500/35 dark:hover:border-indigo-500/25',
                                        'hoverShadow' => 'hover:shadow-indigo-500/5',
                                        'svg' => '<svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="21" x2="9" y2="9"></line><line x1="3" y1="14" x2="21" y2="14"></line></svg>'
                                    ]
                                };
                            @endphp
                            <div wire:key="integration-card-{{ $integration['id'] }}" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-3xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:hover:shadow-none {{ $cardTheme['hoverBorder'] }} {{ $cardTheme['hoverShadow'] }} {{ $isNotConfigured ? 'opacity-70 bg-slate-50/20 dark:bg-slate-950/10' : '' }}">
                                <div>
                                    <!-- Top Row: Logo, Name/Category, Status Badge -->
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <!-- Circular icon container (Custom per integration) -->
                                            <div class="w-10 h-10 rounded-full {{ $cardTheme['iconBg'] }} flex items-center justify-center shrink-0">
                                                {!! $cardTheme['svg'] !!}
                                            </div>
                                            <div>
                                                <h4 class="text-[15px] font-bold text-slate-900 dark:text-white leading-tight">
                                                    {{ $integration['name'] }}
                                                </h4>
                                                <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">{{ $integration['category'] }}</span>
                                                    @if(in_array($integration['id'], ['gsc', 'youtube', 'keyword']))
                                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[9px] uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-sm">Last 7 Days</span>
                                                    @elseif($integration['id'] === 'ga4')
                                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[9px] uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-sm">Last 30 Days</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            @if ($isNotConfigured)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-450 dark:bg-slate-800 dark:text-slate-500">
                                                    Not Configured
                                                </span>
                                            @elseif ($isConfigured)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                                                    Keys Configured
                                                </span>
                                            @elseif ($isDegraded)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                    Degraded
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-600 dark:text-emerald-455">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                                    Connected
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Middle: Stats columns (Modern card sub-containers) -->
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div class="bg-slate-50/50 dark:bg-slate-900/30 border border-slate-100/50 dark:border-slate-850/50 p-3.5 rounded-2xl transition hover:bg-slate-100/30 dark:hover:bg-slate-850/20">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">API health</span>
                                            <span class="text-base font-extrabold text-slate-800 dark:text-slate-200">
                                                {{ $isConnected ? $integration['api_health'] . '%' : '—' }}
                                            </span>
                                        </div>
                                        <div class="bg-slate-50/50 dark:bg-slate-900/30 border border-slate-100/50 dark:border-slate-855/50 p-3.5 rounded-2xl transition hover:bg-slate-100/30 dark:hover:bg-slate-855/20">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Last sync</span>
                                            <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate block mt-0.5" title="{{ $integration['last_sync'] }}">
                                                {{ $integration['last_sync'] }}
                                            </span>
                                        </div>
                                    </div>
                                @if ($integration['id'] === 'ga4' && $isConnected)
                                    @if (!$integration['property_id'])
                                        <div class="mt-4 p-3 bg-indigo-50/30 dark:bg-indigo-950/10 rounded-xl border border-indigo-100/30 dark:border-indigo-900/20 mb-4">
                                            <span class="text-[10px] font-bold text-indigo-650 dark:text-indigo-400 block mb-1.5 uppercase tracking-wider">Select GA4 Property</span>
                                            <div class="flex gap-2">
                                                <select wire:model.live="selectedPropertyId" wire:key="property-select-{{ $integration['id'] }}" class="flex-1 text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 py-1.5 px-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    <option value="">Select Property...</option>
                                                    @foreach ($this->getGA4Properties() as $prop)
                                                        <option value="{{ $prop['id'] }}">{{ $prop['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" wire:click="savePropertyId('{{ $integration['id'] }}')" wire:key="property-save-btn-{{ $integration['id'] }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-[10px] rounded-lg transition shrink-0">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                            <span class="text-slate-455 dark:text-slate-550 font-medium">GA4 Property ID</span>
                                            <span class="font-bold text-slate-755 dark:text-slate-300">{{ $integration['property_id'] }}</span>
                                        </div>
                                    @endif
                                @endif

                                @if ($integration['id'] === 'youtube' && $isConnected)
                                    @if (!$integration['property_id'])
                                        <div class="mt-4 p-3 bg-red-50/30 dark:bg-red-950/10 rounded-xl border border-red-100/30 dark:border-red-900/20 mb-4">
                                            <span class="text-[10px] font-bold text-red-650 dark:text-red-400 block mb-1.5 uppercase tracking-wider">Select YouTube Channel</span>
                                            <div class="flex gap-2">
                                                <select wire:model.live="youtubeChannelId" wire:key="youtube-channel-select-{{ $integration['id'] }}" class="flex-1 text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 py-1.5 px-2 focus:outline-none focus:ring-1 focus:ring-red-500">
                                                    <option value="">Select Channel...</option>
                                                    @foreach ($this->getYoutubeChannels() as $channel)
                                                        <option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                            <span class="text-slate-455 dark:text-slate-550 font-medium">YouTube Channel</span>
                                            <span class="font-bold text-slate-755 dark:text-slate-300 max-w-[120px] truncate" title="{{ $integration['property_id'] }}">{{ $integration['property_id'] }}</span>
                                        </div>
                                    @endif
                                @endif

                                @if ($integration['id'] === 'keyword' && $isConnected)
                                    @if (!$integration['property_id'])
                                        <div class="mt-4 p-3 bg-purple-50/30 dark:bg-purple-950/10 rounded-xl border border-purple-100/30 dark:border-purple-900/20 mb-4">
                                            <span class="text-[10px] font-bold text-purple-650 dark:text-purple-400 block mb-1.5 uppercase tracking-wider">Select Keyword Project</span>
                                            <div class="flex gap-2">
                                                <select wire:model.live="keywordProjectId" wire:key="keyword-project-select-{{ $integration['id'] }}" class="flex-1 text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 py-1.5 px-2 focus:outline-none focus:ring-1 focus:ring-purple-500">
                                                    <option value="">Select Project...</option>
                                                    @foreach ($this->getKeywordProjects() as $project)
                                                        <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                            <span class="text-slate-455 dark:text-slate-550 font-medium">Keyword Project ID</span>
                                            <span class="font-bold text-slate-755 dark:text-slate-300 max-w-[120px] truncate" title="{{ $integration['property_id'] }}">{{ $integration['property_id'] }}</span>
                                        </div>
                                    @endif
                                @endif
                                
                                @if ($integration['id'] === 'gtm' && $isConnected)
                                    @if (!$integration['property_id'])
                                        <div class="mt-4 p-3 bg-teal-50/30 dark:bg-teal-950/10 rounded-xl border border-teal-100/30 dark:border-teal-900/20 mb-4">
                                            <span class="text-[10px] font-bold text-teal-650 dark:text-teal-400 block mb-1.5 uppercase tracking-wider">Select GTM Container</span>
                                            <div class="flex gap-2">
                                                <select wire:model.live="gtmContainerId" wire:key="gtm-container-select-{{ $integration['id'] }}" class="flex-1 text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 py-1.5 px-2 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                                    <option value="">Select Container...</option>
                                                    @foreach ($this->getGtmContainers() as $container)
                                                        <option value="{{ $container['id'] }}">{{ $container['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                            <span class="text-slate-455 dark:text-slate-550 font-medium">GTM Container ID</span>
                                            <span class="font-bold text-slate-755 dark:text-slate-300 max-w-[120px] truncate" title="{{ $integration['property_id'] }}">{{ $integration['property_id'] }}</span>
                                        </div>
                                    @endif
                                @endif
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex items-center gap-3 mt-auto">
                                    @if ($isNotConfigured)
                                        <!-- Configure credentials first -->
                                        <button type="button" 
                                                wire:click="openConfigModal('{{ $integration['id'] }}')"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition active:scale-95">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                            </svg>
                                            Configure Credentials
                                        </button>
                                    @elseif ($isConfigured)
                                        <!-- Auth stage -->
                                        <a href="{{ route('staff.integrations.google.redirect', $integration['db_id']) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-indigo-600 hover:bg-indigo-755 text-white font-bold text-xs rounded-xl shadow-sm transition active:scale-95 text-center flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            Sign in with Google
                                        </a>
                                        <button type="button" 
                                                wire:click="removeCredentials('{{ $integration['id'] }}')"
                                                wire:confirm="Are you sure you want to remove the credentials for this integration?"
                                                class="px-3 py-2.5 bg-slate-100 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-955/20 text-slate-500 hover:text-red-650 dark:text-slate-400 dark:hover:text-red-400 font-bold text-xs rounded-xl transition active:scale-95"
                                                title="Remove Credentials">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @else
                                        <!-- Connected stage -->
                                        <div class="w-full flex flex-col gap-2.5 mt-auto">
                                            @if (in_array($integration['id'], ['ga4', 'gsc', 'youtube', 'keyword', 'gtm']) && !empty($integration['property_id']))
                                                <button type="button" 
                                                        wire:click="openReportModal('{{ $integration['id'] }}')"
                                                        class="w-full py-2.5 px-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/40 text-indigo-650 dark:text-indigo-400 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 active:scale-95">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83M22 12A10 10 0 0 0 12 2v10z"/>
                                                    </svg>
                                                    View Full Report
                                                </button>
                                            @endif
                                            <div class="flex items-center gap-3">
                                                <button type="button" 
                                                        wire:click="refreshIntegration('{{ $integration['id'] }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="refreshIntegration('{{ $integration['id'] }}')"
                                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-850 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl transition shadow-sm active:scale-95">
                                                    <svg wire:loading.class="animate-spin" wire:target="refreshIntegration('{{ $integration['id'] }}')" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    Refresh
                                                </button>
                                                <button type="button" 
                                                        wire:click="disconnectIntegration('{{ $integration['id'] }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:confirm="Are you sure you want to disconnect {{ $integration['name'] }}?"
                                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-850 hover:bg-red-50 dark:hover:bg-red-955/20 border border-slate-200/80 dark:border-slate-700/80 hover:border-red-200 dark:hover:border-red-900/40 text-slate-750 hover:text-red-650 dark:text-slate-300 dark:hover:text-red-400 font-bold text-xs rounded-xl transition shadow-sm active:scale-95">
                                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-linecap="round"/>
                                                        <path d="M18.364 5.636L5.636 18.364" stroke="red" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    Disconnect
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Credentials Upload Modal -->
                @if ($showConfigModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-slate-905/60 backdrop-blur-sm animate-fadeIn">
                        <div class="relative w-full max-w-lg mx-auto my-6 p-4">
                            <div class="relative flex flex-col w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-xl outline-none focus:outline-none">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800/60 rounded-t-3xl">
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                        @if($activeConfigIntegrationId === 'ga4')
                                            Configure Google Analytics 4
                                        @elseif($activeConfigIntegrationId === 'gsc')
                                            Configure Google Search Console
                                        @else
                                            Configure {{ strtoupper($activeConfigIntegrationId) }}
                                        @endif
                                    </h3>
                                    <button type="button" wire:click="closeConfigModal" class="text-slate-400 hover:text-slate-655 dark:hover:text-slate-200 focus:outline-none transition">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Modal Body -->
                                <form wire:submit.prevent="saveCredentials">
                                    <div class="p-6 flex-auto">
                                        @if($activeConfigIntegrationId === 'keyword')
                                            <div class="mb-5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Keyword.com API Token</label>
                                                <input type="text" wire:model="apiKey" placeholder="Enter your Keyword.com API Token" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-sm focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                                @error('apiKey') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="text-[11px] text-slate-450 leading-relaxed bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/30 dark:border-indigo-900/20 p-3 rounded-xl text-indigo-650 dark:text-indigo-400 font-medium">
                                                Note: Your API key will be securely stored and used only to fetch your keyword ranking data.
                                            </div>
                                        @else
                                            <div class="mb-5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Google OAuth Credentials (.json)</label>
                                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 dark:border-slate-800 border-dashed rounded-2xl hover:border-indigo-500/50 dark:hover:border-indigo-400/50 transition-colors relative">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-xs text-slate-600 dark:text-slate-400 justify-center">
                                                            <label for="credentials-upload" class="relative cursor-pointer bg-white dark:bg-slate-900 rounded-md font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                                                                <span>Upload a file</span>
                                                                <input id="credentials-upload" name="credentials-upload" type="file" wire:model="credentialsFile" class="sr-only">
                                                            </label>
                                                            <p class="pl-1">or drag and drop</p>
                                                        </div>
                                                        <p class="text-[10px] text-slate-400">JSON keys only up to 2MB</p>
                                                    </div>
                                                </div>
                                                @if($credentialsFile)
                                                    <div class="mt-3 flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-950/20 border border-slate-200/50 dark:border-slate-800/50 rounded-xl">
                                                        <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[200px]" title="{{ $credentialsFile->getClientOriginalName() }}">
                                                            {{ $credentialsFile->getClientOriginalName() }}
                                                        </span>
                                                        <button type="button" wire:click="$set('credentialsFile', null)" class="text-red-500 text-xs font-bold hover:underline">Remove</button>
                                                    </div>
                                                @endif
                                                @error('credentialsFile') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="text-[11px] text-slate-455 leading-relaxed bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/30 dark:border-indigo-900/20 p-3 rounded-xl text-indigo-650 dark:text-indigo-400 font-medium">
                                                Note: We securely encrypt and store your credentials file. Your OAuth Client details are isolated and only used for your integration.
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Modal Footer -->
                                    <div class="flex items-center justify-end p-4 border-t border-slate-100 dark:border-slate-800/60 rounded-b-3xl gap-2">
                                        <button type="button" wire:click="closeConfigModal" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-330 font-bold text-xs rounded-xl transition hover:bg-slate-200">
                                            Cancel
                                        </button>
                                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-755 text-white font-bold text-xs rounded-xl transition shadow-sm active:scale-95">
                                            <span wire:loading.remove>Save & Configure</span>
                                            <span wire:loading>Processing...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- View Report Modal -->
                @if ($showReportModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-slate-905/60 backdrop-blur-sm animate-fadeIn">
                        <div class="relative w-full max-w-4xl mx-auto my-6 p-4">
                            <div class="relative flex flex-col w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl outline-none focus:outline-none max-h-[90vh] overflow-y-auto">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800/60 rounded-t-3xl sticky top-0 bg-white dark:bg-slate-900 z-10">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                            <svg class="w-5 h-5 text-indigo-655 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                            </svg>
                                            @if($activeReportIntegrationId === 'gsc')
                                                Google Search Console - Detailed Report
                                            @elseif($activeReportIntegrationId === 'youtube')
                                                YouTube - Detailed Report
                                            @elseif($activeReportIntegrationId === 'keyword')
                                                Keyword.com - Detailed Report
                                            @elseif($activeReportIntegrationId === 'gtm')
                                                Google Tag Manager - Container Summary
                                            @else
                                                Google Analytics 4 - Detailed Report
                                            @endif
                                        </h3>
                                        <p class="text-[11px] text-slate-400 mt-1">
                                            Property ID: <span class="font-semibold text-slate-655 dark:text-slate-350">{{ $activeReportData['metadata']['property_id'] ?? '—' }}</span> &bull; Source: <span class="text-slate-655 dark:text-slate-350">{{ $activeReportData['metadata']['source'] ?? '—' }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                         <!-- Month Filter Selector -->
                                         <div class="flex items-center gap-2">
                                             <label for="report-month-select" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider shrink-0">Report Month:</label>
                                             <select id="report-month-select" wire:model.live="selectedReportMonth" class="text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 text-slate-700 dark:text-slate-200 py-1.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition cursor-pointer">
                                                 @foreach ($this->getAvailableReportMonths() as $opt)
                                                     <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                                 @endforeach
                                             </select>
                                         </div>
                                        <button type="button" wire:click="closeReportModal" class="text-slate-400 hover:text-slate-655 dark:hover:text-slate-250 focus:outline-none transition p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Modal Body -->
                                <div class="p-6">
                                    @if (empty($activeReportData))
                                        <div class="py-12 text-center">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                                            <p class="text-xs text-slate-500 font-medium">Loading report dataset...</p>
                                        </div>
                                    @else
                                        @if ($activeReportIntegrationId === 'gsc')
                                        <!-- 1. Stats Summary Widgets (GSC) -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Clicks</span>
                                                <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                                                    {{ number_format($activeReportData['summary']['clicks'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Impressions</span>
                                                <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($activeReportData['summary']['impressions'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg. CTR</span>
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                    {{ $activeReportData['summary']['ctr'] ?? 0 }}%
                                                </span>
                                            </div>
                                            <div class="p-4 bg-amber-50/20 dark:bg-amber-950/10 border border-amber-100/30 dark:border-amber-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg. Position</span>
                                                <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400">
                                                    {{ $activeReportData['summary']['position'] ?? 0 }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2. Top Queries Table (GSC) -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Search Queries</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">Query</th>
                                                                <th class="py-2 text-right font-bold">Clicks</th>
                                                                <th class="py-2 text-right font-bold">Imp.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($activeReportData['top_queries'] ?? [] as $query)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold truncate max-w-[150px]" title="{{ $query['query'] }}">{{ $query['query'] }}</td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($query['clicks'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($query['impressions'] ?? 0) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Ranking URLs</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">URL</th>
                                                                <th class="py-2 text-right font-bold">Clicks</th>
                                                                <th class="py-2 text-right font-bold">Imp.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($activeReportData['top_pages'] ?? [] as $page)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold truncate max-w-[150px] text-indigo-600" title="{{ $page['page'] }}">
                                                                        <a href="{{ $page['page'] }}" target="_blank" class="hover:underline">{{ str_replace('https://', '', $page['page']) }}</a>
                                                                    </td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($page['clicks'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($page['impressions'] ?? 0) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. Devices and Countries -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Device Breakdown</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">Device</th>
                                                                <th class="py-2 text-right font-bold">Clicks</th>
                                                                <th class="py-2 text-right font-bold">Imp.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($activeReportData['devices'] ?? [] as $device)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold capitalize">{{ strtolower($device['device']) }}</td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($device['clicks'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($device['impressions'] ?? 0) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Countries</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">Country</th>
                                                                <th class="py-2 text-right font-bold">Clicks</th>
                                                                <th class="py-2 text-right font-bold">Imp.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($activeReportData['countries'] ?? [] as $country)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold capitalize">{{ strtolower(strlen($country['country']) === 3 ? $country['country'] : $country['country']) }}</td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($country['clicks'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($country['impressions'] ?? 0) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($activeReportIntegrationId === 'ga4')
                                    <!-- 1. Stats Summary Widgets -->
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                                        <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Active Users</span>
                                            <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                                                {{ number_format($activeReportData['overall_summary']['active_users'] ?? 0) }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Page Views</span>
                                            <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                                                {{ number_format($activeReportData['overall_summary']['pageviews'] ?? 0) }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Sessions</span>
                                            <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                {{ number_format($activeReportData['overall_summary']['sessions'] ?? 0) }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-amber-50/20 dark:bg-amber-950/10 border border-amber-100/30 dark:border-amber-900/20 rounded-2xl">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Bounce Rate</span>
                                            <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400">
                                                {{ $activeReportData['overall_summary']['bounce_rate'] ?? '—' }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-rose-50/20 dark:bg-rose-950/10 border border-rose-100/30 dark:border-rose-900/20 rounded-2xl col-span-2 md:col-span-1">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Session Duration</span>
                                            <span class="text-lg font-extrabold text-rose-600 dark:text-rose-400">
                                                {{ $activeReportData['overall_summary']['avg_session_duration'] ?? '—' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- 2. Tables Grid -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                        <!-- Pageviews by Page Path -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50" x-data="{ showAllPages: false }">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Viewed Pages</h4>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead>
                                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                            <th class="py-2 font-bold w-1/2">Page Path</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Views</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Users</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (array_slice($activeReportData['pages_report'] ?? [], 0, 6) as $page)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($page['pageviews'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($page['users'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['pages_report'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllPages ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['pages_report'] ?? [], 6) as $page)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                        <td class="py-2.5 font-mono text-[10px] max-w-[220px] truncate w-1/2" title="{{ $page['page_path'] }}">{{ $page['page_path'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($page['pageviews'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($page['users'] ?? 0) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                                                    <button type="button" @click="showAllPages = !showAllPages" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                                                        <span x-text="showAllPages ? 'Show Less' : 'View Full'"></span>
                                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllPages ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Traffic Sources & Channels -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50" x-data="{ showAllSources: false }">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Traffic Sources / Mediums</h4>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead>
                                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                            <th class="py-2 font-bold w-1/2">Source / Medium</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Sessions</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Bounce Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (array_slice($activeReportData['traffic_sources'] ?? [], 0, 6) as $source)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($source['sessions'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ $source['bounce_rate'] ?? '—' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['traffic_sources'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllSources ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['traffic_sources'] ?? [], 6) as $source)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                        <td class="py-2.5 font-bold w-1/2">{{ $source['source_medium'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($source['sessions'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ $source['bounce_rate'] ?? '—' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                                                    <button type="button" @click="showAllSources = !showAllSources" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                                                        <span x-text="showAllSources ? 'Show Less' : 'View Full'"></span>
                                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllSources ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- 3. Lower Grid: Demographics, Geography -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Devices -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Device Breakdowns</h4>
                                            <div class="space-y-4">
                                                @foreach ($activeReportData['device_demographics'] ?? [] as $device)
                                                    <div>
                                                        <div class="flex justify-between text-xs mb-1">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $device['device'] }}</span>
                                                            <span class="text-slate-400 dark:text-slate-500 font-bold">{{ $device['percentage'] }}</span>
                                                        </div>
                                                        <div class="w-full bg-slate-200 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                                            <div class="bg-indigo-600 dark:bg-indigo-400 h-1.5 rounded-full" style="width: {{ $device['percentage'] }}"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Geographic Country Sources -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 col-span-2" x-data="{ showAllGeo: false }">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Geographic Audience</h4>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead>
                                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                            <th class="py-2 font-bold w-1/2">Country</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Active Users</th>
                                                            <th class="py-2 text-right font-bold w-1/4">Sessions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (array_slice($activeReportData['geographic_sources'] ?? [], 0, 6) as $geo)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                                                <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($geo['active_users'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($geo['sessions'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (count($activeReportData['geographic_sources'] ?? []) > 6)
                                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                                     :style="showAllGeo ? 'max-height: 1000px; opacity: 100;' : 'max-height: 0px; opacity: 0;'">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-xs">
                                                            <tbody>
                                                                @foreach (array_slice($activeReportData['geographic_sources'] ?? [], 6) as $geo)
                                                                    <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-700 dark:text-slate-300">
                                                                        <td class="py-2.5 font-bold w-1/2">{{ $geo['country'] }}</td>
                                                                        <td class="py-2.5 text-right font-bold w-1/4">{{ number_format($geo['active_users'] ?? 0) }}</td>
                                                                        <td class="py-2.5 text-right text-slate-400 dark:text-slate-500 w-1/4">{{ number_format($geo['sessions'] ?? 0) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-3 pt-2 border-t border-slate-200/20 dark:border-slate-800/40">
                                                    <button type="button" @click="showAllGeo = !showAllGeo" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition focus:outline-none">
                                                        <span x-text="showAllGeo ? 'Show Less' : 'View Full'"></span>
                                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="showAllGeo ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @elseif ($activeReportIntegrationId === 'youtube')
                                        <!-- 1. Stats Summary Widgets (YouTube) -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="p-4 bg-red-50/20 dark:bg-red-950/10 border border-red-100/30 dark:border-red-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Views</span>
                                                <span class="text-lg font-extrabold text-red-600 dark:text-red-400">
                                                    {{ number_format($activeReportData['summary']['views'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Watch Time (hrs)</span>
                                                <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                                                    {{ number_format($activeReportData['summary']['watch_time'] ?? 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Subscribers</span>
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                    {{ number_format($activeReportData['summary']['subscribers'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100/30 dark:border-indigo-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Avg Duration</span>
                                                <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                                                    {{ $activeReportData['summary']['avg_view_duration'] ?? '0s' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2. Top Videos Table (YouTube) -->
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mb-6">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Performing Videos</h4>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead>
                                                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                            <th class="py-2 font-bold">Video Title</th>
                                                            <th class="py-2 text-right font-bold">Views</th>
                                                            <th class="py-2 text-right font-bold">Watch Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($activeReportData['top_videos'] ?? [] as $video)
                                                            <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                <td class="py-2.5 font-bold truncate max-w-[200px]" title="{{ $video['title'] }}">{{ $video['title'] }}</td>
                                                                <td class="py-2.5 text-right font-bold">{{ number_format($video['views'] ?? 0) }}</td>
                                                                <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($video['watch_time'] ?? 0, 1) }} hrs</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="py-4 text-center text-slate-500">No video data available.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @elseif($activeReportIntegrationId === 'keyword')
                                        <!-- 1. Summary Cards (Keyword) -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="p-4 bg-purple-50/20 dark:bg-purple-950/10 border border-purple-100/30 dark:border-purple-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Total Keywords</span>
                                                <span class="text-lg font-extrabold text-purple-600 dark:text-purple-400">
                                                    {{ number_format($activeReportData['summary']['total_keywords'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100/30 dark:border-emerald-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Top 10 Rankings</span>
                                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-450">
                                                    {{ number_format($activeReportData['summary']['top_10'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/30 dark:border-blue-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Up Movements</span>
                                                <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($activeReportData['summary']['up_movements'] ?? 0) }}
                                                </span>
                                            </div>
                                            <div class="p-4 bg-orange-50/20 dark:bg-orange-950/10 border border-orange-100/30 dark:border-orange-900/20 rounded-2xl">
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mb-1">Share of Voice</span>
                                                <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400">
                                                    {{ $activeReportData['summary']['share_of_voice'] ?? '0%' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2. Keywords and Pages Tables (Keyword) -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                            <!-- Top Keyword Rankings -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Keyword Rankings</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">Keyword</th>
                                                                <th class="py-2 text-right font-bold">Position</th>
                                                                <th class="py-2 text-right font-bold">Change</th>
                                                                <th class="py-2 text-right font-bold">Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($activeReportData['keywords'] ?? [] as $kw)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 font-bold truncate max-w-[200px]" title="{{ $kw['keyword'] }}">{{ $kw['keyword'] }}</td>
                                                                    <td class="py-2.5 text-right font-bold">#{{ $kw['position'] ?? '-' }}</td>
                                                                    <td class="py-2.5 text-right {{ (strpos($kw['change'], '+') !== false) ? 'text-emerald-500' : ((strpos($kw['change'], '-') !== false) ? 'text-red-500' : 'text-slate-400') }} font-bold">
                                                                        {{ $kw['change'] ?? '0' }}
                                                                    </td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($kw['volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="py-4 text-center text-slate-500">No keyword data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Top Ranking URLs -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Ranking URLs</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">URL</th>
                                                                <th class="py-2 text-right font-bold">Keywords</th>
                                                                <th class="py-2 text-right font-bold">Total Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($activeReportData['pages'] ?? [] as $pg)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 truncate max-w-[250px]" title="{{ $pg['url'] }}">{{ $pg['path'] }}</td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($pg['keyword_count'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($pg['total_volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="py-4 text-center text-slate-500">No URL data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Top Pages -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/50 mt-5">
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Top Pages</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead>
                                                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                                                <th class="py-2 font-bold">URL</th>
                                                                <th class="py-2 text-right font-bold">Keywords</th>
                                                                <th class="py-2 text-right font-bold">Avg. Rank</th>
                                                                <th class="py-2 text-right font-bold">Total Search Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($activeReportData['pages'] ?? [] as $pg)
                                                                <tr class="border-b border-slate-100 dark:border-slate-800/40 text-slate-750 dark:text-slate-350">
                                                                    <td class="py-2.5 truncate max-w-[250px]" title="{{ $pg['url'] }}">
                                                                        <a href="{{ $pg['url'] }}" target="_blank" class="hover:text-indigo-500 hover:underline">
                                                                            {{ $pg['url'] }}
                                                                        </a>
                                                                    </td>
                                                                    <td class="py-2.5 text-right font-bold">{{ number_format($pg['keyword_count'] ?? 0) }}</td>
                                                                    <td class="py-2.5 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ isset($pg['avg_rank']) ? $pg['avg_rank'] : 0 }}</td>
                                                                    <td class="py-2.5 text-right text-slate-400 dark:text-slate-500">{{ number_format($pg['total_volume'] ?? 0) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="py-4 text-center text-slate-500">No URL data available.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($activeReportIntegrationId === 'gtm')
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
                                
                                <!-- Modal Footer -->
                                <div class="flex items-center justify-end p-4 border-t border-slate-100 dark:border-slate-800/60 rounded-b-3xl sticky bottom-0 bg-white dark:bg-slate-900 z-10">
                                    <button type="button" wire:click="closeReportModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition">
                                        Close Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @elseif ($activeTab === 'clickup_tickets')
                <div wire:init="loadClickUpTasks" class="space-y-5 animate-fadeIn">
                    
                    <!-- Header Banner -->
                    <div class="bg-white/70 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" alt="ClickUp" class="h-5 w-auto dark:brightness-200" />
                                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Tickets & Tasks</h3>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Live tickets from ClickUp folders assigned to {{ $clientDetails->company_name ?: ($clientDetails->user->name ?? 'this client') }}.
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="syncClientClickUpTasks" wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#135266] hover:bg-[#0f4152] text-white font-bold text-xs rounded-xl shadow transition active:scale-95">
                                    <svg wire:loading.class="animate-spin" wire:target="syncClientClickUpTasks" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span wire:loading.remove wire:target="syncClientClickUpTasks">Sync Tickets API</span>
                                    <span wire:loading wire:target="syncClientClickUpTasks">Syncing...</span>
                                </button>
                            </div>
                        </div>

                        <!-- Mapped Folders Badges -->
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center flex-wrap gap-2 text-xs">
                            <span class="font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px]">Assigned Folders:</span>
                            @forelse($clientClickUpFolders as $cFolder)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-700 dark:text-amber-400 font-bold border border-amber-500/20">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    {{ $cFolder->name }}
                                </span>
                            @empty
                                <span class="text-slate-400 italic">No folders assigned yet.</span>
                            @endforelse
                        </div>
                    </div>

                    @if(!$clickUpTasksLoaded)
                        <!-- Livewire Deferred Loading Skeleton Animation -->
                        <div class="w-full space-y-4 animate-pulse">
                            <div class="h-14 bg-slate-200/60 dark:bg-slate-800/50 rounded-2xl w-full"></div>
                            <div class="space-y-4">
                                @for($i = 0; $i < 3; $i++)
                                    <div class="bg-white/70 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                                                <div class="space-y-2">
                                                    <div class="w-44 h-4 bg-slate-200 dark:bg-slate-800 rounded-md"></div>
                                                    <div class="w-28 h-3 bg-slate-200/70 dark:bg-slate-800/50 rounded-md"></div>
                                                </div>
                                            </div>
                                            <div class="w-20 h-7 bg-slate-200 dark:bg-slate-800 rounded-full"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @else
                        @if($clientClickUpFolders->isEmpty())
                            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-10 text-center">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">No ClickUp Folders Assigned</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto">No ClickUp folders currently mapped to this client.</p>
                            </div>
                        @else
                            <!-- Status & Folder Filters Bar -->
                            @if($clientClickUpFolders->count() > 1 || !empty($clientClickUpTasks))
                                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-3.5 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 shrink-0">
                                        <svg class="w-4 h-4 text-[#135266]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                        </svg>
                                        Filter Tickets:
                                    </span>

                                    <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
                                        <!-- Folder Filter -->
                                        @if($clientClickUpFolders->count() > 1)
                                            <select wire:model.live="clickUpTaskFolderFilter"
                                                    class="block w-full sm:w-48 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-[#135266]/40 focus:border-[#135266]">
                                                <option value="">All Folders ({{ $clientClickUpFolders->count() }})</option>
                                                @foreach($clientClickUpFolders as $cf)
                                                    <option value="{{ $cf->id }}">{{ $cf->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        <!-- Status Filter -->
                                        <select wire:model.live="clickUpTaskStatusFilter"
                                                class="block w-full sm:w-48 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-[#135266]/40 focus:border-[#135266]">
                                            <option value="">All Statuses ({{ count($clickUpStatuses) }})</option>
                                            @foreach($clickUpStatuses as $st)
                                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <!-- Tasks List (FAQ/Accordion for Multiple Folders, Flat View for Single Folder) -->
                            @if($clientClickUpFolders->count() > 1)
                                <div class="space-y-4">
                                    @foreach($clientClickUpFolders as $cFolder)
                                        @php
                                            $folderTasks = collect($filteredClickUpTasks)->where('folder_id', (string)$cFolder->id);
                                        @endphp
                                        <div x-data="{ open: false }" class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800/70 rounded-2xl overflow-hidden shadow-sm transition">
                                            <button type="button" @click="open = !open" 
                                                    class="w-full px-5 py-4 flex items-center justify-between gap-4 bg-slate-50/80 dark:bg-slate-800/50 hover:bg-slate-100/80 dark:hover:bg-slate-800/80 transition text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 shrink-0">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-extrabold text-slate-900 dark:text-white text-base">
                                                            {{ $cFolder->name }}
                                                        </h4>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                                            {{ count($folderTasks) }} {{ Str::plural('ticket', count($folderTasks)) }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#135266]/10 text-[#135266] dark:bg-teal-400/10 dark:text-teal-400">
                                                        {{ count($folderTasks) }} {{ Str::plural('Ticket', count($folderTasks)) }}
                                                    </span>
                                                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </button>

                                            <div x-show="open" x-transition:enter="transition ease-out duration-200" class="p-4 border-t border-slate-100 dark:border-slate-800 space-y-3 bg-slate-50/30 dark:bg-slate-950/20">
                                                @forelse($folderTasks as $task)
                                                    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-4 shadow-sm transition">
                                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                            <div class="space-y-1.5 flex-1 min-w-0">
                                                                <div class="flex items-center flex-wrap gap-2">
                                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                                        {{ $task['list_name'] }}
                                                                    </span>
                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider" style="background-color: {{ $task['status_color'] }}1a; color: {{ $task['status_color'] }}">
                                                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task['status_color'] }}"></span>
                                                                        {{ $task['status'] }}
                                                                    </span>
                                                                </div>

                                                                <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug">
                                                                    {{ $task['name'] }}
                                                                </h4>

                                                                @if($task['description'])
                                                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                                        {{ $task['description'] }}
                                                                    </p>
                                                                @endif
                                                            </div>

                                                            <div class="flex sm:flex-col items-end justify-between sm:justify-center gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 dark:border-slate-800">
                                                                <a href="{{ $task['url'] }}" target="_blank"
                                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 font-bold text-xs rounded-xl transition">
                                                                    <span>Open Ticket</span>
                                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                    </svg>
                                                                </a>

                                                                <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                                                                    @if(!empty($task['assignees']))
                                                                        <div class="flex items-center -space-x-1.5" title="Assigned To: {{ implode(', ', array_column($task['assignees'], 'username')) }}">
                                                                            @foreach($task['assignees'] as $assignee)
                                                                                @if(!empty($assignee['profilePicture']))
                                                                                    <img src="{{ $assignee['profilePicture'] }}" alt="{{ $assignee['username'] }}" class="w-5 h-5 rounded-full object-cover border border-white dark:border-slate-900" />
                                                                                @else
                                                                                    <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-white dark:border-slate-900 flex items-center justify-center text-[9px] font-bold">
                                                                                        {{ strtoupper(substr($assignee['username'], 0, 2)) }}
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="text-[11px] italic">Unassigned</span>
                                                                    @endif

                                                                    @if($task['date_created'])
                                                                        <span class="text-[11px] font-mono">{{ $task['date_created'] }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="py-6 text-center text-slate-400 text-xs italic">
                                                        No active tickets found in this folder.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Single Folder Flat View -->
                                <div class="space-y-3">
                                    @forelse($filteredClickUpTasks as $task)
                                        <div class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-4 shadow-sm transition">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                <div class="space-y-1.5 flex-1 min-w-0">
                                                    <div class="flex items-center flex-wrap gap-2">
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                            <svg class="w-3 h-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                            </svg>
                                                            {{ $task['folder_name'] }} / {{ $task['list_name'] }}
                                                        </span>

                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider" style="background-color: {{ $task['status_color'] }}1a; color: {{ $task['status_color'] }}">
                                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task['status_color'] }}"></span>
                                                            {{ $task['status'] }}
                                                        </span>
                                                    </div>

                                                    <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug">
                                                        {{ $task['name'] }}
                                                    </h4>

                                                    @if($task['description'])
                                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                            {{ $task['description'] }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <div class="flex sm:flex-col items-end justify-between sm:justify-center gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 dark:border-slate-800">
                                                    <a href="{{ $task['url'] }}" target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 font-bold text-xs rounded-xl transition">
                                                        <span>Open Ticket</span>
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>

                                                    <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                                                        @if(!empty($task['assignees']))
                                                            <div class="flex items-center -space-x-1.5" title="Assigned To: {{ implode(', ', array_column($task['assignees'], 'username')) }}">
                                                                @foreach($task['assignees'] as $assignee)
                                                                    @if(!empty($assignee['profilePicture']))
                                                                        <img src="{{ $assignee['profilePicture'] }}" alt="{{ $assignee['username'] }}" class="w-5 h-5 rounded-full object-cover border border-white dark:border-slate-900" />
                                                                    @else
                                                                        <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-white dark:border-slate-900 flex items-center justify-center text-[9px] font-bold">
                                                                            {{ strtoupper(substr($assignee['username'], 0, 2)) }}
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-[11px] italic">Unassigned</span>
                                                        @endif

                                                        @if($task['date_created'])
                                                            <span class="text-[11px] font-mono">{{ $task['date_created'] }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-8 text-center text-slate-400 text-xs">
                                            No ClickUp tickets found matching your filter criteria.
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                        @endif
                    @endif
                </div>

            @elseif ($activeTab === 'maintenance')
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm animate-fadeIn">
                    @if ($clientMaintenanceReports->isEmpty())
                        <div class="text-center py-12 text-slate-500">No reports yet</div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Month</th>
                                    <th class="px-6 py-4">Score</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Author</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                                @foreach ($clientMaintenanceReports as $report)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                        <td class="px-6 py-4 text-slate-450 dark:text-slate-500">#{{ $report->id }}</td>
                                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $report->maintenance_month }}</td>
                                        <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-350">{{ $report->health_score }}%</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }} tracking-wider">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-semibold">{{ $report->developer->name ?? 'System' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex items-center justify-end gap-1">
                                                {{-- View button --}}
                                                <a href="{{ route('staff.maintenance.view', $report->id) }}"
                                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="View Report">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                {{-- Edit button --}}
                                                <a href="{{ route('staff.maintenance.edit', $report->id) }}"
                                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Edit Report">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                {{-- PDF button --}}
                                                <a href="{{ route('staff.maintenance.pdf', $report->id) }}" target="_blank"
                                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Generate PDF">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4 p-4 border-t border-slate-200/50 dark:border-slate-800/40">
                            {{ $clientMaintenanceReports->links() }}
                        </div>
                    @endif
                </div>

            @elseif ($activeTab === 'documents')
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm animate-fadeIn">
                    @if ($clientDocuments->isEmpty())
                        <div class="text-center py-12 text-slate-500">No resources uploaded yet.</div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Name</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4">Size</th>
                                    <th class="px-6 py-4">Uploaded</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                                @foreach ($clientDocuments as $doc)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                        <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="hover:underline">
                                                {{ $doc->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 uppercase font-bold text-xs">{{ $doc->file_type ?: 'PDF' }}</td>
                                        <td class="px-6 py-4 text-slate-650 dark:text-slate-350 font-semibold">{{ $doc->file_size ? number_format($doc->file_size / (1024 * 1024), 1) . ' MB' : '—' }}</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-550 text-xs font-medium">{{ $doc->created_at->format('Y-m-d') }} - {{ $doc->addedBy->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4 p-4 border-t border-slate-200/50 dark:border-slate-800/40">
                            {{ $clientDocuments->links() }}
                        </div>
                    @endif
                </div>

            @elseif ($activeTab === 'activity log')
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 shadow-sm animate-fadeIn">
                    @if ($clientActivityLogs->isEmpty())
                        <div class="text-center py-6 text-slate-500">No activity logs recorded yet.</div>
                    @else
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach ($clientActivityLogs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-800/50"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-indigo-50 dark:bg-indigo-950/20 flex items-center justify-center border border-indigo-100 dark:border-indigo-900/25">
                                                        <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-slate-600 dark:text-slate-350 font-semibold">{{ $log->description }}</p>
                                                    </div>
                                                    <div class="text-right text-xs whitespace-nowrap text-slate-400 dark:text-slate-500">
                                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-4">
                            {{ $clientActivityLogs->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

    @else
        {{-- Clients List view --}}

        <!-- View Tabs -->
        <div class="flex border-b border-slate-200/60 dark:border-slate-800/40 mb-4 mt-2">
            <button type="button" wire:click="setViewTab('my_clients')"
                    class="py-3 px-6 border-b-2 font-bold text-sm transition-all duration-150 {{ $activeViewTab === 'my_clients' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                Clients
            </button>
            <button type="button" wire:click="setViewTab('all_clients')"
                    class="py-3 px-6 border-b-2 font-bold text-sm transition-all duration-150 {{ $activeViewTab === 'all_clients' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                All Clients
            </button>
        </div>

        {{-- Filters --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm relative z-30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                {{-- Search --}}
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           autocomplete="off"
                           placeholder="Search by name, email, or company..."
                           class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-400 text-sm transition duration-150" />
                </div>

                {{-- Status Filter --}}
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    <select wire:model.live="statusFilter"
                            class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-400 text-sm transition duration-150 appearance-none">
                        <option value="" class="dark:bg-slate-900">All Statuses</option>
                        <option value="active" class="dark:bg-slate-900">Active</option>
                        <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                    </select>
                    <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Plan Filter --}}
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <select wire:model.live="planFilter"
                            class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-400 text-sm transition duration-150 appearance-none">
                        <option value="" class="dark:bg-slate-900">All Plans</option>
                        @foreach($plans as $planOpt)
                            <option value="{{ $planOpt->id }}" class="dark:bg-slate-900">{{ $planOpt->name }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Clear Filters --}}
            @if($hasActiveFilters)
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                    <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Filters active — showing filtered results
                    </span>
                    <button type="button" wire:click="clearFilters"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-650 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>

        <x-admin.card>
            @if ($clients->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if ($activeViewTab === 'my_clients')
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Assigned Clients</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">You do not have any clients assigned to you yet.</p>
                    @else
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Clients Found</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">There are no clients matching the current filter criteria.</p>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                    <table class="w-full min-w-[1100px] text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4 w-[280px]">Client Details</th>
                                <th class="px-6 py-4 w-[220px]">Company Name</th>
                                <th class="px-6 py-4 w-[150px]">Plans</th>
                                <th class="px-6 py-4 w-[100px]">Websites</th>
                                <th class="px-6 py-4 w-[160px]">Phone Numbers</th>
                                <th class="px-6 py-4 w-[100px]">Status</th>
                                <th class="px-6 py-4 w-[125px]">Registered</th>
                                <th class="px-6 py-4 w-[90px] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach ($clients as $client)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0 cursor-pointer" wire:click="selectClient({{ $client->id }})">
                                                @if($client->profile_image)
                                                    <img src="{{ asset('storage/' . $client->profile_image) }}" alt="{{ $client->user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                        {{ $client->getInitials() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition" wire:click="selectClient({{ $client->id }})">
                                                    {{ $client->user->name ?? 'Deleted User' }}
                                                </div>
                                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $client->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold cursor-pointer" wire:click="selectClient({{ $client->id }})">
                                        {{ $client->company_name ?: '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($client->plans->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($client->plans as $pl)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-{{ $pl->color }}-500/10 text-{{ $pl->color }}-600 dark:text-{{ $pl->color }}-400 tracking-wider">
                                                        {{ $pl->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            {{ $client->websites_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium text-xs">
                                        @if($client->phones->isEmpty())
                                            {{ $client->phone ?: '—' }}
                                        @else
                                            <div class="space-y-1">
                                                @foreach($client->phones as $phoneRec)
                                                    <div><span class="font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest text-[9px] mr-1">{{ $phoneRec->label }}:</span> {{ $phoneRec->phone }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                              {{ $client->status === 'active'
                                                  ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                  : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                            {{ ucfirst($client->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                        {{ $client->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" wire:click="selectClient({{ $client->id }})"
                                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90" title="View Details">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $clients->links() }}
                </div>
            @endif
        </x-admin.card>
    @endif
</div>
