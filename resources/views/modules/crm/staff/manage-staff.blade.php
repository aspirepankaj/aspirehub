@section('page_title', 'Staff Management')

<div>
    <!-- Breadcrumbs -->
    @if ($selectedStaffDetailId && $staffDetails)
        <x-admin.breadcrumbs :items="['Staff' => route('admin.staff'), $staffDetails->user->name ?? 'Detail' => null]" />
    @else
        <x-admin.breadcrumbs :items="['Staff' => null]" />
    @endif

    @if ($selectedStaffDetailId && $staffDetails)
    {{-- ==========================================
         STAFF DETAIL VIEW
         ========================================== --}}

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.staff') }}" wire:navigate class="inline-flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to staff
            </a>
        </div>

        <!-- Staff Header Card -->
        <div class="bg-white/93 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 mb-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    @if($staffDetails->profile_image)
                        <img src="{{ asset('storage/' . $staffDetails->profile_image) }}" alt="{{ $staffDetails->user->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $staffDetails->getInitials() }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $staffDetails->user->name }}</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $staffDetails->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-600 dark:text-slate-400' }} tracking-wider">
                            {{ $staffDetails->status }}
                        </span>
                        @foreach($staffDetails->designations as $desg)
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 tracking-wider">
                                {{ $desg->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        @if($staffDetails->company_name)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $staffDetails->company_name }}
                        </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $staffDetails->user->email }}
                        </span>
                        @if($staffDetails->phones->isNotEmpty())
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $staffDetails->phones->first()->phone }}
                            </span>
                        @endif
                        @php
                            $deptsList = $staffDetails->departments ?? ($staffDetails->department ? [$staffDetails->department] : []);
                        @endphp
                        @if(!empty($deptsList))
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M3 20h18" />
                                </svg>
                                {{ implode(', ', $deptsList) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="editStaff({{ $staffDetails->id }})" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl active:scale-95 transition">
                    Edit
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-slate-200/60 dark:border-slate-800/40 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                @foreach(['overview' => 'Overview', 'clients' => 'Clients', 'websites' => 'Websites', 'maintenance' => 'Maintenance', 'activity log' => 'Activity Log'] as $tabKey => $tabLabel)
                    <a href="{{ route('admin.staff.detail', ['id' => $staffDetails->id, 'tab' => $tabKey]) }}" wire:navigate class="py-4 px-1 border-b-2 font-bold text-sm whitespace-nowrap transition {{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                        {{ $tabLabel }}
                        @if($tabKey === 'clients')
                            <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-extrabold rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $staffDetails->clients->count() }}</span>
                        @elseif($tabKey === 'websites')
                            <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-extrabold rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $staffWebsites->count() }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Tab Contents -->
        @if ($activeTab === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">About</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $staffDetails->notes ?: 'No additional notes provided for this staff member.' }}
                        </p>
                    </div>
                    @if($staffDetails->phones->isNotEmpty())
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Phone Numbers</h3>
                        <div class="space-y-2">
                            @foreach($staffDetails->phones as $phoneRec)
                                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 w-14">{{ $phoneRec->label }}</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $phoneRec->phone }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="space-y-6">
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Account Info</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Staff ID</dt>
                                <dd class="font-bold text-slate-800 dark:text-slate-200">ADSTM-{{ $staffDetails->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Departments</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ !empty($deptsList) ? implode(', ', $deptsList) : '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Status</dt>
                                <dd>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $staffDetails->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-500' }} tracking-wider">
                                        {{ $staffDetails->status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Last Login</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $staffDetails->last_login_at ? $staffDetails->last_login_at->diffForHumans() : 'Never' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Joined</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $staffDetails->created_at->format('d M Y') }}</dd>
                            </div>
                            @if($staffDetails->addedBy)
                            <div class="flex justify-between">
                                <dt class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Added By</dt>
                                <dd class="font-semibold text-slate-700 dark:text-slate-300">{{ $staffDetails->addedBy->name }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @if($staffDetails->designations->isNotEmpty())
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Designations</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($staffDetails->designations as $desg)
                                <span class="px-3 py-1 text-xs font-bold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200/40 dark:border-indigo-800/30">
                                    {{ $desg->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        @elseif ($activeTab === 'clients')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if ($staffDetails->clients->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-sm font-medium">No clients assigned to this staff member.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Company</th>
                                <th class="px-6 py-4">Plans</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Email</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach($staffDetails->clients as $client)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($client->profile_image)
                                                <img src="{{ asset('storage/' . $client->profile_image) }}" class="w-8 h-8 rounded-full object-cover" alt="" />
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                    {{ $client->getInitials() }}
                                                </div>
                                            @endif
                                            <a href="{{ route('admin.clients.detail', $client->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                {{ $client->user->name ?? 'Deleted User' }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">{{ $client->company_name ?: '—' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($client->plans as $plan)
                                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-{{ $plan->color }}-500/10 text-{{ $plan->color }}-600 dark:text-{{ $plan->color }}-400">{{ $plan->name }}</span>
                                            @empty
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md {{ $client->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-500' }}">
                                            {{ $client->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs font-medium">{{ $client->user->email ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        @elseif ($activeTab === 'websites')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if ($staffWebsites->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                        </svg>
                        <p class="text-sm font-medium">No websites found for this staff member's assigned clients.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Domain</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Hosting</th>
                                <th class="px-6 py-4">SSL</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach($staffWebsites as $website)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $website->site_name }}</div>
                                        @if($website->url)
                                            <a href="{{ $website->url }}" target="_blank" class="text-xs text-indigo-500 hover:text-indigo-600 transition">{{ $website->url }}</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium text-xs">{{ $website->client->user->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs font-medium">{{ $website->hosting_provider ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php $ssl = $website->ssl_status ?? ''; @endphp
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md {{ $ssl === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($ssl === 'expiring' ? 'bg-amber-500/10 text-amber-600' : 'bg-slate-500/10 text-slate-500') }}">
                                            {{ $ssl ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md {{ ($website->status ?? '') === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-500' }}">
                                            {{ $website->status ?: '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        @elseif ($activeTab === 'maintenance')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if ($staffMaintenanceReports->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium">No maintenance reports where this staff was developer.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Report</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Website</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach($staffMaintenanceReports as $report)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.maintenance.view', $report->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm">
                                            {{ $report->title ?? 'Report #' . $report->id }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium text-xs">{{ $report->client->user->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs font-medium">{{ $report->website->site_name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColor = match($report->status ?? '') {
                                                'completed' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                                'in_progress' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                                'pending' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                                default => 'bg-slate-500/10 text-slate-500',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status ?? '—')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-semibold">{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 px-6 pb-4">{{ $staffMaintenanceReports->links() }}</div>
                @endif
            </div>

        @elseif ($activeTab === 'activity log')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm">
                @if ($staffActivityLogs->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium">No activity found for this staff member.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-900/50">
                        @foreach($staffActivityLogs as $log)
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
                    <div class="px-6 py-4">{{ $staffActivityLogs->links() }}</div>
                @endif
            </div>
        @endif

    @else
    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Staff button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage and track all your staff profiles, roles, and departments
        </p>

        {{-- Add Staff button --}}
        <button type="button" @click="$dispatch('open-modal', { name: 'add-staff-modal' })"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Staff</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTERS — 4-Column layout
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Search (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       autocomplete="off"
                       placeholder="Search by name, email..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                <select wire:model.live="statusFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Statuses</option>
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Designation Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <select wire:model.live="designationFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Designations</option>
                    @foreach($designations as $desg)
                        <option value="{{ $desg->id }}" class="dark:bg-slate-900">{{ $desg->name }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Department Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <select wire:model.live="departmentFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d }}" class="dark:bg-slate-900">{{ $d }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
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



    <!-- Success Message Alert -->
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    <!-- Staff Table Card -->
    <x-admin.card class="w-full max-w-full overflow-hidden">

        {{-- Bulk Action Bar (visible only when items are selected) --}}
        @if(count($selectedStaff) > 0)
            <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/60 dark:border-indigo-700/30">
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2 pl-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ count($selectedStaff) }} Selected
                </span>
                <div class="flex gap-2 pr-1.5">
                    <button type="button" wire:click="bulkActivate"
                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white text-xs font-semibold rounded-lg shadow-sm shadow-emerald-500/20 transition-all">
                        Activate All
                    </button>
                    <button type="button" wire:click="bulkDeactivate"
                            class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 active:scale-95 text-slate-700 dark:text-slate-350 text-xs font-semibold rounded-lg transition-all">
                        Deactivate All
                    </button>
                </div>
            </div>
        @endif

        @if($Staff->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Staff Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new staff profile above.</p>
            </div>
        @else
            {{-- Custom table with checkbox column --}}
            <div class="overflow-x-auto custom-scrollbar w-full rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            {{-- Select All checkbox --}}
                            <th class="px-3 py-3.5 w-10 text-center">
                                <input type="checkbox"
                                       wire:model.live="selectAll"
                                       wire:change="toggleSelectAll({{ json_encode($pageIds) }})"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </th>
                            <th class="px-3 py-3.5">Staff Details</th>
                            <th class="px-3 py-3.5 hidden xl:table-cell">Company Name</th>
                            <th class="px-3 py-3.5">Designation</th>
                            <th class="px-3 py-3.5">Department</th>
                            <th class="px-3 py-3.5 hidden xl:table-cell">Phone Numbers</th>
                            <th class="px-3 py-3.5 text-center">Status</th>
                            <th class="px-3 py-3.5 text-center hidden lg:table-cell">Registered</th>
                            <th class="px-3 py-3.5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($Staff as $staff)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($staff->id, $selectedStaff) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                {{-- Row Checkbox --}}
                                <td class="px-3 py-3.5 w-10 text-center">
                                    <input type="checkbox"
                                           wire:model.live="selectedStaff"
                                           value="{{ $staff->id }}"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </td>
                                <td class="px-3 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.staff.detail', ['id' => $staff->id]) }}" wire:navigate class="shrink-0">
                                            @if($staff->profile_image)
                                                <img src="{{ asset('storage/' . $staff->profile_image) }}" alt="{{ $staff->user->name ?? 'Staff' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400'>{{ $staff->getInitials() }}</div>`" />
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                    {{ $staff->getInitials() }}
                                                </div>
                                            @endif
                                        </a>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('admin.staff.detail', ['id' => $staff->id]) }}" wire:navigate class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition text-left truncate max-w-[160px] sm:max-w-[200px] lg:max-w-none lg:whitespace-normal block" title="{{ $staff->user->name ?? 'Deleted User' }}">{{ $staff->user->name ?? 'Deleted User' }}</a>
                                            <div class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-[160px] sm:max-w-[200px] lg:max-w-none lg:whitespace-normal" title="{{ $staff->user->email ?? 'N/A' }}">{{ $staff->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 hidden xl:table-cell text-slate-700 dark:text-slate-300 font-semibold">
                                    {{ $staff->company_name ?: '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-slate-650 dark:text-slate-350 text-sm font-medium">
                                    @if($staff->designations->isEmpty())
                                        <span class="text-slate-400 dark:text-slate-650">—</span>
                                    @else
                                        <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                            @foreach($staff->designations as $desg)
                                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-750 dark:text-slate-300 border border-slate-200/50 dark:border-slate-700/40">
                                                    {{ $desg->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5">
                                    @php
                                        $sDepts = $staff->departments ?? ($staff->department ? [$staff->department] : []);
                                    @endphp
                                    @if(empty($sDepts))
                                        <span class="text-slate-455 dark:text-slate-555 italic text-xs">—</span>
                                    @else
                                        <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                            @foreach($sDepts as $deptName)
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                                    {{ $deptName }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 hidden xl:table-cell text-slate-500 dark:text-slate-400 font-medium text-xs">
                                    @if($staff->phones->isEmpty())
                                        {{ $staff->phone ?: '—' }}
                                    @else
                                        <div class="space-y-1">
                                            @foreach($staff->phones as $phoneRec)
                                                <div><span class="font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest text-[9px] mr-1">{{ $phoneRec->label }}:</span> {{ $phoneRec->phone }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                          {{ $staff->status === 'active'
                                              ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                              : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-center hidden lg:table-cell text-xs text-slate-400 dark:text-slate-555 font-semibold">
                                    {{ $staff->created_at->diffForHumans() }}
                                </td>
                                <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                    @php
                                        $staffEditData = [
                                            'id' => $staff->id,
                                            'user_id' => $staff->user_id,
                                            'name' => $staff->user->name ?? 'Deleted User',
                                            'email' => $staff->user->email ?? '',
                                            'company_name' => $staff->company_name ?? '',
                                            'designation_ids' => $staff->designations->pluck('id')->toArray(),
                                            'departments_list' => $staff->departments ?? ($staff->department ? [$staff->department] : []),
                                            'profile_image' => $staff->profile_image ?? '',
                                            'status' => $staff->status ?? 'active',
                                            'notes' => $staff->notes ?? '',
                                            'phones' => $staff->phones->isNotEmpty() 
                                                ? $staff->phones->map(fn($p) => ['phone' => $p->phone, 'label' => $p->label])->toArray() 
                                                : [['phone' => '', 'label' => 'Work']],
                                        ];
                                    @endphp
                                    <div class="inline-flex items-center justify-center gap-1.5 shrink-0">
                                        <a href="{{ route('impersonate.start', $staff->user_id) }}"
                                           class="inline-flex items-center justify-center p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90 shrink-0"
                                           title="Login as {{ $staff->user->name ?? 'this staff member' }}"
                                           onclick="return confirm('Are you sure you want to login as {{ $staff->user->name ?? 'this staff member' }}?')">
                                            <img src="{{ asset('aspire-hub-staff-switch.svg') }}" class="w-6 h-6 shrink-0 opacity-80 hover:opacity-100 transition-opacity" alt="Switch Account" />
                                        </a>
                                        <button type="button" 
                                                @click="
                                                    const data = {{ Js::from($staffEditData) }};
                                                    $wire.editingStaffId = data.id;
                                                    $wire.editingUserId = data.user_id;
                                                    $wire.name = data.name;
                                                    $wire.email = data.email;
                                                    $wire.password = '';
                                                    $wire.company_name = data.company_name;
                                                    $wire.designation_ids = data.designation_ids;
                                                    $wire.departments_list = data.departments_list;
                                                    $wire.existing_profile_image = data.profile_image;
                                                    $wire.profile_image = '';
                                                    $wire.status = data.status;
                                                    $wire.notes = data.notes;
                                                    $wire.phones = data.phones;
                                                    $dispatch('open-modal', { name: 'edit-staff-modal' });
                                                "
                                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90 shrink-0"
                                                title="Edit Staff Member">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </tbody>
            </table>
            </div>

            <div class="mt-4">
                {{ $Staff->links() }}
            </div>
        @endif
    </x-admin.card>

    {{-- ══════════════════════════════════════════════
         ADD STAFF MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="add-staff-modal" title="Add New Staff" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'S', 0, 2)) }}</div>`" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>

                    @if ($profile_image)
                        <button type="button" wire:click="removeProfileImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="name" type="text" required autocomplete="new-name" placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="staff_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="staff_email" type="email" required autocomplete="new-email" placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="staff_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="staff_password" type="password" required autocomplete="new-password" placeholder="Min 8 characters"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Designation & Department -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Designations') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($designations as $desg)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="designation_ids" value="{{ $desg->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $desg->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('designation_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Departments') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($departments as $d)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="departments_list" value="{{ $d }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $d }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('departments_list')" class="mt-1" />
                </div>
            </div>

            <!-- Phones Section -->
            <div x-data="{
                    phones: $wire.entangle('phones'),
                    addPhone() {
                        if (!this.phones) this.phones = [];
                        if (this.phones.length < 5) {
                            this.phones.push({ label: 'Work', phone: '' });
                        }
                    },
                    removePhone(index) {
                        if (this.phones.length > 1) {
                            this.phones.splice(index, 1);
                        }
                    }
                 }" class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    <template x-if="phones && phones.length < 5">
                        <button type="button" @click="addPhone()" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    </template>
                    <template x-if="phones && phones.length >= 5">
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    </template>
                </div>
                
                <div class="space-y-3">
                    <template x-for="(phoneItem, index) in phones" :key="index">
                        <div class="flex items-start gap-3">
                            <div class="w-1/3">
                                <select x-model="phoneItem.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="flex-1 relative">
                                <input x-model="phoneItem.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            </div>
                            <template x-if="phones.length > 1">
                                <button type="button" @click="removePhone(index)" class="p-2.5 text-slate-400 hover:text-red-500 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="staff_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="staff_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Enter any additional details about the staff..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-staff-modal' })" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveStaff" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="saveStaff" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Staff</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    @endif {{-- end @else (list view) --}}

    {{-- ══════════════════════════════════════════════
         EDIT STAFF MODAL (Available in both List and Detail view)
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-staff-modal" title="Edit Staff" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div x-data="{
                get imgUrl() {
                    const path = $wire.profile_image || $wire.existing_profile_image;
                    if (!path) return '';
                    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) {
                        return path;
                    }
                    return '/storage/' + path;
                }
            }">
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    <template x-if="imgUrl">
                        <img :src="imgUrl" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                    </template>
                    <template x-if="!imgUrl">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </template>
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>

                    <template x-if="imgUrl">
                        <button type="button" @click="$wire.profile_image = ''; $wire.existing_profile_image = '';" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    </template>
                </div>
                <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
            </div>

            <!-- Name -->
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="edit_name" type="text" required placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="edit_staff_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_staff_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_staff_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password (Leave blank to keep current)') }}</label>
                <input wire:model="password" id="edit_staff_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Company Name -->
            <div>
                <label for="edit_company_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="edit_company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Designation & Department -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Designations') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($designations as $desg)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="designation_ids" value="{{ $desg->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $desg->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('designation_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Departments') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($departments as $d)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="departments_list" value="{{ $d }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $d }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('departments_list')" class="mt-1" />
                </div>
            </div>

            <!-- Phones Section (Edit) -->
            <div x-data="{
                    phones: $wire.entangle('phones'),
                    addPhone() {
                        if (!this.phones) this.phones = [];
                        if (this.phones.length < 5) {
                            this.phones.push({ label: 'Work', phone: '' });
                        }
                    },
                    removePhone(index) {
                        if (this.phones.length > 1) {
                            this.phones.splice(index, 1);
                        }
                    }
                 }" class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    <template x-if="phones && phones.length < 5">
                        <button type="button" @click="addPhone()" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    </template>
                    <template x-if="phones && phones.length >= 5">
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    </template>
                </div>
                
                <div class="space-y-3">
                    <template x-for="(phoneItem, index) in phones" :key="index">
                        <div class="flex items-start gap-3">
                            <div class="w-1/3">
                                <select x-model="phoneItem.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="flex-1 relative">
                                <input x-model="phoneItem.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            </div>
                            <template x-if="phones.length > 1">
                                <button type="button" @click="removePhone(index)" class="p-2.5 text-slate-400 hover:text-red-500 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="edit_staff_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="edit_staff_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the staff..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-staff-modal' })" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateStaff" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="updateStaff" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Staff</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

</div>
