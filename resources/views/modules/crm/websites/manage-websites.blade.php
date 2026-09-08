@section('page_title', 'Websites Management')

@php
    $typeColors = [
        'maintenance'        => 'emerald',
        'design'             => 'pink',
        'development'        => 'indigo',
        'speed_optimisation' => 'amber',
        'other'              => 'slate',
    ];
    $typeLabels = [
        'maintenance'        => 'Maintenance',
        'design'             => 'Design',
        'development'        => 'Development',
        'speed_optimisation' => 'Speed Optimisation',
        'other'              => 'Other',
    ];
    $statusColors = [
        'active'    => 'emerald',
        'inactive'  => 'slate',
        'suspended' => 'red',
    ];
@endphp

<div>
    <!-- Breadcrumbs -->
    @if ($selectedWebsiteDetailId && $websiteDetails)
        <x-admin.breadcrumbs :items="['Websites' => route('admin.websites'), $websiteDetails->site_name => null]" />
    @else
        <x-admin.breadcrumbs :items="['Websites' => null]" />
    @endif

    @if($websiteDetails)
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.websites') }}" wire:navigate class="inline-flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to websites
            </a>
        </div>

        {{-- Website Header Card --}}
        <div class="bg-white/93 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 mb-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $websiteDetails->site_name }}</h1>
                        @php $statColor = $statusColors[$websiteDetails->status] ?? 'slate'; @endphp
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-{{ $statColor }}-500/10 text-{{ $statColor }}-600 dark:text-{{ $statColor }}-400 tracking-wider">
                            {{ $websiteDetails->status }}
                        </span>
                        @foreach($websiteDetails->serviceTypes as $sT)
                            @php
                                $colorHex = str_starts_with($sT->color, '#') ? $sT->color : '#' . $sT->color;
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider"
                                  style="background-color: {{ $colorHex }}1a; color: {{ $colorHex }}; border: 1px solid {{ $colorHex }}33;">
                                {{ $sT->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <a href="{{ $websiteDetails->url }}" target="_blank" class="flex items-center gap-1.5 text-indigo-500 hover:underline">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            {{ $websiteDetails->url }}
                        </a>
                        @if($websiteDetails->client && $websiteDetails->client->user)
                            <a href="{{ route('admin.clients.detail', ['id' => $websiteDetails->client_id]) }}" class="flex items-center gap-1.5 hover:text-slate-700 dark:hover:text-slate-200">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Client: {{ $websiteDetails->client->user->name }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $websiteDetailEditData = [
                    'id' => $websiteDetails->id,
                    'client_id' => $websiteDetails->client_id,
                    'site_name' => $websiteDetails->site_name ?? '',
                    'url' => $websiteDetails->url ?? '',
                    'service_type_ids' => $websiteDetails->serviceTypes->pluck('id')->toArray(),
                    'plan_ids' => $websiteDetails->plans->pluck('id')->toArray(),
                    'status' => $websiteDetails->status ?? 'active',
                    'image' => $websiteDetails->image ?? '',
                    'admin_url' => $websiteDetails->admin_url ?? '',
                    'admin_username' => $websiteDetails->admin_username ?? '',
                    'hosting_provider' => $websiteDetails->hosting_provider ?? '',
                    'hosting_expiry_date' => $websiteDetails->hosting_expiry_date ? \Carbon\Carbon::parse($websiteDetails->hosting_expiry_date)->format('Y-m-d') : '',
                    'notes' => $websiteDetails->notes ?? '',
                ];
            @endphp
            <div class="flex items-center gap-2">
                <button type="button" 
                        @click="
                            const data = {{ Js::from($websiteDetailEditData) }};
                            $wire.editingWebsiteId = data.id;
                            $wire.client_id = data.client_id;
                            $wire.site_name = data.site_name;
                            $wire.url = data.url;
                            $wire.service_type_ids = data.service_type_ids;
                            $wire.plan_ids = data.plan_ids;
                            $wire.status = data.status;
                            $wire.existing_image = data.image;
                            $wire.image = '';
                            $wire.admin_url = data.admin_url;
                            $wire.admin_username = data.admin_username;
                            $wire.admin_password = '';
                            $wire.hosting_provider = data.hosting_provider;
                            $wire.hosting_expiry_date = data.hosting_expiry_date;
                            $wire.notes = data.notes;
                            $dispatch('open-modal', { name: 'edit-website-modal' });
                        "
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl active:scale-95 transition">
                    Edit
                </button>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="border-b border-slate-200/60 dark:border-slate-800/40 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                @foreach(['overview' => 'Overview', 'maintenance' => 'Maintenance Reports', 'activity log' => 'Activity Log'] as $tabKey => $tabLabel)
                    <a href="{{ route('admin.websites.detail', ['id' => $websiteDetails->id, 'tab' => $tabKey]) }}" wire:navigate
                       class="py-4 px-1 border-b-2 font-bold text-sm whitespace-nowrap transition {{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                        {{ $tabLabel }}
                        @if($tabKey === 'maintenance')
                            <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-extrabold rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $websiteMaintenanceReports->total() }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Tab Contents --}}
        @if ($activeTab === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Credentials Card --}}
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Admin / CMS Credentials
                        </h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="sm:col-span-2 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-1">Admin URL</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">
                                    @if($websiteDetails->admin_url)
                                        <a href="{{ $websiteDetails->admin_url }}" target="_blank" class="text-indigo-500 hover:underline inline-flex items-center gap-1">
                                            {{ $websiteDetails->admin_url }}
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-1">Admin Username</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->admin_username ?: '—' }}</dd>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-1">Admin Password</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300 flex items-center justify-between">
                                    <span>
                                        @if(!empty($websiteDetails->admin_password))
                                            {{ $revealDetailPassword ? $websiteDetails->admin_password : '••••••••' }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                    @if(!empty($websiteDetails->admin_password))
                                        <button type="button" wire:click="toggleDetailPassword" class="text-slate-400 hover:text-indigo-500 transition">
                                            @if($revealDetailPassword)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            @endif
                                        </button>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Hosting & Server Card --}}
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                            Hosting & Server Info
                        </h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-1">Hosting Provider</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->hosting_provider ?: '—' }}</dd>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-1">Server IP</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->server_ip ?: '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Notes</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $websiteDetails->notes ?: 'No additional notes provided for this website.' }}
                        </p>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Website Info</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Website ID</dt>
                                <dd class="font-bold text-slate-800 dark:text-slate-200">ADSWS-{{ $websiteDetails->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Client</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->client->user->name ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Status</dt>
                                <dd>
                                    @php $statColor = $statusColors[$websiteDetails->status] ?? 'slate'; @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-{{ $statColor }}-500/10 text-{{ $statColor }}-600 dark:text-{{ $statColor }}-400 tracking-wider">
                                        {{ $websiteDetails->status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Plans</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">
                                    @if($websiteDetails->plans->isNotEmpty())
                                        {{ $websiteDetails->plans->pluck('name')->implode(', ') }}
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Created</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->created_at->format('d M Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Added By</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $websiteDetails->addedBy->name ?? 'System' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        @elseif ($activeTab === 'maintenance')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if($websiteMaintenanceReports->isEmpty())
                    <div class="p-12 text-center">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">No Maintenance Reports</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">There are no maintenance reports submitted for this website yet.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Report Month</th>
                                <th class="px-6 py-4">Developer</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50">
                            @foreach($websiteMaintenanceReports as $report)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        <a href="{{ route('admin.maintenance.view', $report->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                            {{ $report->maintenance_month ? \Carbon\Carbon::parse($report->maintenance_month)->format('F Y') : '—' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs font-medium">{{ $report->developer->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md {{ $report->status === 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-500' }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-medium">{{ $report->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-900/50">{{ $websiteMaintenanceReports->links() }}</div>
                @endif
            </div>
        @elseif ($activeTab === 'activity log')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if($websiteActivityLogs->isEmpty())
                    <div class="p-12 text-center">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">No Activity Logged</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">No activity logs recorded for this website yet.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-900/50">
                        @foreach($websiteActivityLogs as $log)
                            <div class="px-6 py-4 flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $log->description }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-900/50">{{ $websiteActivityLogs->links() }}</div>
                @endif
            </div>
        @endif

    @else

    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Website button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage and track all customer websites, logins, and configurations
        </p>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.websites.service-types') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/50 hover:bg-slate-200/50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 text-sm font-semibold transition-all duration-150 active:scale-95 shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
                Manage Service Types
            </a>

            {{-- Add Website button --}}
            <button type="button" @click="$dispatch('open-modal', { name: 'add-website-modal' })"
                    style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Website
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTERS — 3-Column Layout (Search + Status + Service Type)
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Search --}}
            <div class="relative flex items-center">
                <svg wire:loading.remove wire:target="search" class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <div wire:loading wire:target="search" class="absolute left-3 pointer-events-none shrink-0 z-10 flex items-center">
                    <svg class="animate-spin w-4 h-4 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       autocomplete="off"
                       placeholder="Search by site name, URL, or client..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                <select wire:model.live="statusFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Statuses</option>
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                    <option value="suspended" class="dark:bg-slate-900">Suspended</option>
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Service Type Filter --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <select wire:model.live="serviceTypeFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Service Types</option>
                    @foreach($serviceTypes as $typeOpt)
                        <option value="{{ $typeOpt->id }}" class="dark:bg-slate-900">{{ $typeOpt->name }}</option>
                    @endforeach
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
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
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

        {{-- Clear Filters row --}}
        @if($hasActiveFilters)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Filters active — showing filtered results
                </span>
                <button type="button" wire:click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    {{-- Websites Table Card --}}
    <x-admin.card>

        {{-- Bulk Action Bar --}}
        @if(count($selectedWebsites) > 0)
            <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/60 dark:border-indigo-700/30">
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2 pl-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    {{ count($selectedWebsites) }} website(s) selected
                </span>
                <div class="flex items-center gap-2 pr-2">
                    <button type="button" wire:click="bulkActivate"
                            wire:confirm="Activate {{ count($selectedWebsites) }} selected website(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-emerald-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Activate
                    </button>
                    <button type="button" wire:click="bulkDeactivate"
                            wire:confirm="Deactivate {{ count($selectedWebsites) }} selected website(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-500 hover:bg-slate-600 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-slate-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Deactivate
                    </button>
                    <button type="button" wire:click="$set('selectedWebsites', []); $set('selectAll', false)"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 transition-all duration-150 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </button>
                </div>
            </div>
        @endif

        @if($websites->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Websites Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search or add a new website above.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            {{-- Select All checkbox --}}
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox"
                                       wire:model="selectAll"
                                             x-on:change="const isChecked = $event.target.checked; Array.from(document.querySelectorAll('input[type=checkbox]')).filter(cb => cb.getAttribute('wire:model') && cb.getAttribute('wire:model').startsWith('selected')).forEach(cb => { if(cb.checked !== isChecked) { cb.checked = isChecked; cb.dispatchEvent(new window.Event('change')); } });"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </th>
                            <th class="px-4 py-4">Website</th>
                            <th class="px-4 py-4">Client</th>
                            <th class="px-4 py-4">Service Type</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Hosting</th>
                            <th class="px-4 py-4">Added</th>
                            <th class="px-4 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($websites as $website)
                            @php
                                $typeColor  = $typeColors[$website->site_type]  ?? 'slate';
                                $typeLabel  = $typeLabels[$website->site_type]  ?? 'Other';
                                $statColor  = $statusColors[$website->status]   ?? 'slate';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($website->id, $selectedWebsites) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                {{-- Row Checkbox --}}
                                <td class="px-4 py-4 w-10">
                                    <input type="checkbox"
                                           wire:model="selectedWebsites"
                                           value="{{ $website->id }}"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </td>

                                {{-- Website Name + URL --}}
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.websites.detail', ['id' => $website->id]) }}" wire:navigate class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition text-left block">{{ $website->site_name }}</a>
                                    <a href="{{ $website->url }}" target="_blank"
                                       class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 font-medium truncate max-w-[200px] block">
                                        {{ $website->url }}
                                    </a>
                                </td>

                                {{-- Client --}}
                                <td class="px-4 py-4">
                                     <div class="text-sm">
                                         @if($website->client)
                                             <a href="{{ route('admin.clients.detail', ['id' => $website->client_id]) }}" class="font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                 {{ $website->client->user->name ?? '—' }}
                                             </a>
                                         @else
                                             —
                                         @endif
                                     </div>
                                     <div class="text-xs text-slate-400 dark:text-slate-500">
                                         {{ $website->client->company_name ?: ($website->client->user->email ?? '') }}
                                     </div>
                                 </td>

                                {{-- Service Type Badge --}}
                                <td class="px-4 py-4">
                                    @if($website->serviceTypes->isNotEmpty())
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($website->serviceTypes as $sT)
                                                @php
                                                    $colorHex = str_starts_with($sT->color, '#') ? $sT->color : '#' . $sT->color;
                                                @endphp
                                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider"
                                                      style="background-color: {{ $colorHex }}1a; color: {{ $colorHex }}; border: 1px solid {{ $colorHex }}33;">
                                                    {{ $sT->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-4 py-4">
                                    @php
                                        $statColor = $statusColors[$website->status] ?? 'slate';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                        bg-{{ $statColor }}-500/10 text-{{ $statColor }}-600 dark:text-{{ $statColor }}-400">
                                        {{ ucfirst($website->status) }}
                                    </span>
                                </td>

                                {{-- Hosting --}}
                                <td class="px-4 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $website->hosting_provider ?: '—' }}
                                </td>

                                {{-- Added At --}}
                                <td class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $website->created_at->diffForHumans() }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-4 text-right">
                                    @php
                                        $websiteEditData = [
                                            'id' => $website->id,
                                            'client_id' => $website->client_id,
                                            'site_name' => $website->site_name ?? '',
                                            'url' => $website->url ?? '',
                                            'service_type_ids' => $website->serviceTypes->pluck('id')->toArray(),
                                            'plan_ids' => $website->plans->pluck('id')->toArray(),
                                            'status' => $website->status ?? 'active',
                                            'image' => $website->image ?? '',
                                            'admin_url' => $website->admin_url ?? '',
                                            'admin_username' => $website->admin_username ?? '',
                                            'hosting_provider' => $website->hosting_provider ?? '',
                                            'hosting_expiry_date' => $website->hosting_expiry_date ? \Carbon\Carbon::parse($website->hosting_expiry_date)->format('Y-m-d') : '',
                                            'notes' => $website->notes ?? '',
                                        ];
                                    @endphp
                                    <button type="button" 
                                            @click="
                                                const data = {{ Js::from($websiteEditData) }};
                                                $wire.editingWebsiteId = data.id;
                                                $wire.client_id = data.client_id;
                                                $wire.site_name = data.site_name;
                                                $wire.url = data.url;
                                                $wire.service_type_ids = data.service_type_ids;
                                                $wire.plan_ids = data.plan_ids;
                                                $wire.status = data.status;
                                                $wire.existing_image = data.image;
                                                $wire.image = '';
                                                $wire.admin_url = data.admin_url;
                                                $wire.admin_username = data.admin_username;
                                                $wire.admin_password = '';
                                                $wire.hosting_provider = data.hosting_provider;
                                                $wire.hosting_expiry_date = data.hosting_expiry_date;
                                                $wire.notes = data.notes;
                                                $dispatch('open-modal', { name: 'edit-website-modal' });
                                            "
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $websites->links() }}
            </div>
        @endif
    </x-admin.card>

    @endif {{-- end @else (list view) --}}

    {{-- ═══════════════════════════════════════════════════════════
         ADD WEBSITE MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <x-admin.modal name="add-website-modal" title="Add New Website" maxWidth="max-w-3xl">
        <div class="space-y-4 mt-2">
            
            <!-- Website Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Website Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($image)
                        <img src="{{ Str::startsWith($image, 'http') ? $image : asset('storage/' . $image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>
                    
                    @if ($image)
                        <button type="button" wire:click="removeImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>

            {{-- Client Searchable Dropdown (Add Modal) --}}
            <div
                x-data="{
                    open: false,
                    search: '',
                    selectedId: @entangle('client_id'),
                    selectedLabel: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'label' => $c->user->name, 'sub' => $c->company_name ?: ($c->user->email ?? '')])) }},
                    get filtered() {
                        if (!this.search) return this.clients;
                        const q = this.search.toLowerCase();
                        return this.clients.filter(c =>
                            c.label.toLowerCase().includes(q) || c.sub.toLowerCase().includes(q)
                        );
                    },
                    select(c) {
                        this.selectedId = c.id;
                        this.selectedLabel = c.label;
                        this.search = '';
                        this.open = false;
                        $wire.set('client_id', c.id);
                    },
                    syncLabel() {
                        if (!this.selectedId) { this.selectedLabel = ''; return; }
                        const found = this.clients.find(c => c.id == this.selectedId);
                        this.selectedLabel = found ? found.label : '';
                    },
                    init() {
                        this.syncLabel();
                        this.$watch('selectedId', () => this.syncLabel());
                        this.$watch('open', v => { if (v) this.$nextTick(() => this.$refs.addSearch.focus()); });
                    }
                }"
                x-on:close-modal.window="open = false"
                class="relative"
            >
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Client *</label>

                {{-- Trigger Button --}}
                <button type="button" @click="open = !open"
                        class="relative mt-1.5 w-full flex items-center justify-between px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                        :class="selectedId ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400'"
                >
                    <span x-text="selectedLabel || '— Select Client —'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     @click.outside="open = false"
                     class="absolute z-50 top-full mt-1.5 w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/70 shadow-2xl ring-1 ring-black/5 dark:ring-white/5"
                >
                    {{-- Search Input --}}
                    <div class="p-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input x-ref="addSearch" x-model="search" type="text" placeholder="Search clients..."
                                   class="w-full pl-8 pr-3 py-2 text-sm rounded-lg bg-slate-100 dark:bg-slate-800 border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>

                    {{-- Options List — shows 5 rows, then scrolls --}}
                    <ul class="overflow-y-auto py-1.5" style="max-height: 215px;">
                        <template x-for="c in filtered" :key="c.id">
                            <li @click="select(c)"
                                class="flex items-center gap-2.5 mx-1.5 px-2.5 py-2 rounded-lg cursor-pointer transition-colors duration-100"
                                :class="selectedId === c.id
                                    ? 'bg-indigo-500 text-white'
                                    : 'hover:bg-slate-100 dark:hover:bg-slate-800'"
                            >
                                {{-- Avatar --}}
                                <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-extrabold"
                                     :class="selectedId === c.id ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400'"
                                >
                                    <span x-text="c.label.charAt(0).toUpperCase()"></span>
                                </div>
                                {{-- Labels --}}
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-semibold leading-tight truncate"
                                         :class="selectedId === c.id ? 'text-white' : 'text-slate-800 dark:text-slate-100'"
                                         x-text="c.label"></div>
                                    <div class="text-[11px] leading-tight truncate mt-px"
                                         :class="selectedId === c.id ? 'text-indigo-100' : 'text-slate-400 dark:text-slate-500'"
                                         x-text="c.sub"></div>
                                </div>
                                {{-- Check Icon --}}
                                <svg x-show="selectedId === c.id" class="w-3.5 h-3.5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-4 text-sm text-slate-400 dark:text-slate-500 text-center">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            No clients found
                        </li>
                    </ul>
                </div>

                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="add_site_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="add_site_name" type="text" placeholder="e.g. Acme Corp Website"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="add_url" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="add_url" type="url" placeholder="https://example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Service Types *</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                        @foreach($serviceTypes as $typeOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="service_type_ids" value="{{ $typeOpt->id }}"
                                       class="rounded border-slate-200/60 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $typeOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('service_type_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Plans (Optional)</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                        @foreach($plans as $planOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                       class="rounded border-slate-200/60 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="add_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="add_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="active"    class="dark:bg-slate-900">Active</option>
                        <option value="inactive"  class="dark:bg-slate-900">Inactive</option>
                        <option value="suspended" class="dark:bg-slate-900">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            {{-- ── CMS Login Credentials ─────────────────────────────── --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    {{-- Admin URL --}}
                    <div>
                        <label for="add_admin_url" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="add_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('admin_url')" class="mt-1" />
                    </div>

                    {{-- Username & Password side-by-side --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="add_admin_username" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="add_admin_username" type="text" autocomplete="new-username" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            <x-input-error :messages="$errors->get('admin_username')" class="mt-1" />
                        </div>
                        <div x-data="{ show: false }">
                            <label for="add_admin_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Admin Password</label>
                            <div class="relative mt-1.5">
                                <input wire:model="admin_password" id="add_admin_password" :type="show ? 'text' : 'password'" autocomplete="new-password" placeholder="••••••••"
                                       class="block w-full px-4 py-2.5 pr-10 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-500 transition-colors z-20">
                                    <!-- Eye Open Icon (Visible when show is true) -->
                                    <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Closed Slash Icon (Visible when show is false) -->
                                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('admin_password')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Hosting Details ────────────────────────────────────── --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="add_hosting_provider" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="add_hosting_provider" type="text" placeholder="e.g. SiteGround, WP Engine"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('hosting_provider')" class="mt-1" />
                    </div>
                    <div>
                        <label for="add_server_ip" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="add_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('server_ip')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="add_notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="add_notes" rows="3" placeholder="Any additional notes about this website..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-website-modal' })"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveWebsite" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="saveWebsite" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Website</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    {{-- ═══════════════════════════════════════════════════════════
         EDIT WEBSITE MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-website-modal" title="Edit Website" maxWidth="max-w-3xl">
        <div class="space-y-4 mt-2">

            <!-- Website Image -->
            <div x-data="{
                get imgUrl() {
                    const path = $wire.image || $wire.existing_image;
                    if (!path) return '';
                    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) {
                        return path;
                    }
                    return '/storage/' + path;
                }
            }">
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Website Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    <template x-if="imgUrl">
                        <img :src="imgUrl" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-800" />
                    </template>
                    <template x-if="!imgUrl">
                        <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </template>
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>
                    
                    <template x-if="imgUrl">
                        <button type="button" @click="$wire.image = ''; $wire.existing_image = '';" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    </template>
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>

            {{-- Client Searchable Dropdown (Edit Modal) --}}
            <div
                x-data="{
                    open: false,
                    search: '',
                    selectedId: @entangle('client_id'),
                    selectedLabel: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'label' => $c->user->name, 'sub' => $c->company_name ?: ($c->user->email ?? '')])) }},
                    get filtered() {
                        if (!this.search) return this.clients;
                        const q = this.search.toLowerCase();
                        return this.clients.filter(c =>
                            c.label.toLowerCase().includes(q) || c.sub.toLowerCase().includes(q)
                        );
                    },
                    select(c) {
                        this.selectedId = c.id;
                        this.selectedLabel = c.label;
                        this.search = '';
                        this.open = false;
                        $wire.set('client_id', c.id);
                    },
                    syncLabel() {
                        const found = this.clients.find(c => c.id == this.selectedId);
                        this.selectedLabel = found ? found.label : '';
                    },
                    init() {
                        this.syncLabel();
                        this.$watch('selectedId', () => this.syncLabel());
                        this.$watch('open', v => { if (v) this.$nextTick(() => this.$refs.editSearch.focus()); });
                    }
                }"
                x-on:close-modal.window="open = false"
                class="relative"
            >
                <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Client *</label>

                {{-- Trigger Button --}}
                <button type="button" @click="open = !open"
                        class="relative mt-1.5 w-full flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                        :class="selectedId ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400'"
                >
                    <span x-text="selectedLabel || '— Select Client —'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     @click.outside="open = false"
                     class="absolute z-50 top-full mt-1.5 w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/70 shadow-2xl ring-1 ring-black/5 dark:ring-white/5"
                >
                    {{-- Search Input --}}
                    <div class="p-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input x-ref="editSearch" x-model="search" type="text" placeholder="Search clients..."
                                   class="w-full pl-8 pr-3 py-2 text-sm rounded-lg bg-slate-100 dark:bg-slate-800 border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>

                    {{-- Options List — shows 5 rows, then scrolls --}}
                    <ul class="overflow-y-auto py-1.5" style="max-height: 215px;">
                        <template x-for="c in filtered" :key="c.id">
                            <li @click="select(c)"
                                class="flex items-center gap-2.5 mx-1.5 px-2.5 py-2 rounded-lg cursor-pointer transition-colors duration-100"
                                :class="selectedId == c.id
                                    ? 'bg-indigo-500 text-white'
                                    : 'hover:bg-slate-100 dark:hover:bg-slate-800'"
                            >
                                {{-- Avatar --}}
                                <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-extrabold"
                                     :class="selectedId == c.id ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400'"
                                >
                                    <span x-text="c.label.charAt(0).toUpperCase()"></span>
                                </div>
                                {{-- Labels --}}
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-semibold leading-tight truncate"
                                         :class="selectedId == c.id ? 'text-white' : 'text-slate-800 dark:text-slate-100'"
                                         x-text="c.label"></div>
                                    <div class="text-[11px] leading-tight truncate mt-px"
                                         :class="selectedId == c.id ? 'text-indigo-100' : 'text-slate-400 dark:text-slate-500'"
                                         x-text="c.sub"></div>
                                </div>
                                {{-- Check Icon --}}
                                <svg x-show="selectedId == c.id" class="w-3.5 h-3.5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-4 text-sm text-slate-400 dark:text-slate-500 text-center">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            No clients found
                        </li>
                    </ul>
                </div>

                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="edit_site_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="edit_site_name" type="text"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="edit_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="edit_url" type="url"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Service Types *</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                        @foreach($serviceTypes as $typeOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="service_type_ids" value="{{ $typeOpt->id }}"
                                       class="rounded border-slate-200/60 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $typeOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('service_type_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Plans (Optional)</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                        @foreach($plans as $planOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                       class="rounded border-slate-200/60 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="edit_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="edit_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="active"    class="dark:bg-slate-900">Active</option>
                        <option value="inactive"  class="dark:bg-slate-900">Inactive</option>
                        <option value="suspended" class="dark:bg-slate-900">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            {{-- CMS Login Credentials --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    <div>
                        <label for="edit_admin_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="edit_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('admin_url')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="edit_admin_username" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="edit_admin_username" type="text" autocomplete="off" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            <x-input-error :messages="$errors->get('admin_username')" class="mt-1" />
                        </div>
                        <div x-data="{ show: false }">
                            <label for="edit_admin_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                Admin Password
                                @if($passwordIsSet)
                                    <span class="ml-1 text-emerald-500 font-bold">(set)</span>
                                @endif
                            </label>
                            <div class="relative mt-1.5">
                                <input wire:model="admin_password" id="edit_admin_password" :type="show ? 'text' : 'password'"
                                       autocomplete="new-password"
                                       placeholder="{{ $passwordIsSet ? 'Leave blank to keep current' : '••••••••' }}"
                                       class="block w-full px-4 py-2.5 pr-10 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-500 transition-colors z-20">
                                    <!-- Eye Open Icon (Visible when show is true) -->
                                    <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Closed Slash Icon (Visible when show is false) -->
                                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('admin_password')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hosting Details --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_hosting_provider" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="edit_hosting_provider" type="text" placeholder="e.g. SiteGround"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('hosting_provider')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_server_ip" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="edit_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('server_ip')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Any additional notes..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-website-modal' })"
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateWebsite" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="updateWebsite" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Website</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
