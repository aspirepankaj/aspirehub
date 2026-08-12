@section('page_title', 'Clients Management')

<div>
    <!-- Breadcrumbs -->
    @if ($selectedClientDetailId && $clientDetails)
        <x-admin.breadcrumbs :items="['Clients' => route('admin.clients'), $clientDetails->user->name ?? 'Detail' => null]" />
    @else
        <x-admin.breadcrumbs :items="['Clients' => null]" />
    @endif

    @if ($selectedClientDetailId && $clientDetails)
        {{-- ==========================================
             CLIENT DETAIL DASHBOARD VIEW
             ========================================== --}}
        <!-- Back Button -->
        <div class="mb-4">
            <button type="button" wire:click="closeClientDetail" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to clients
            </button>
        </div>

        <!-- Client Header Card -->
        <div class="bg-white/93 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 mb-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    @if($clientDetails->profile_image)
                        <img src="{{ asset('storage/' . $clientDetails->profile_image) }}" alt="{{ $clientDetails->user->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $clientDetails->getInitials() }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $clientDetails->user->name }}</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $clientDetails->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-600 dark:text-slate-400' }} tracking-wider">
                            {{ $clientDetails->status }}
                        </span>
                        @foreach($clientDetails->plans as $pl)
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-{{ $pl->color }}-500/10 text-{{ $pl->color }}-600 dark:text-{{ $pl->color }}-400 tracking-wider">
                                {{ $pl->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $clientDetails->company_name ?: 'No Company' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $clientDetails->user->email }}
                        </span>
                        @if($clientDetails->phones->isNotEmpty())
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $clientDetails->phones->first()->phone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="editClient({{ $clientDetails->id }})" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl active:scale-95 transition">
                    Edit
                </button>
                <button type="button" class="px-4 py-2 rounded-xl text-white font-bold text-xs active:scale-95 transition" style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);">
                    Message
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-slate-200/60 dark:border-slate-800/40 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                @foreach(['overview' => 'Overview', 'websites' => 'Websites', 'maintenance' => 'Maintenance', 'documents' => 'Documents', 'activity log' => 'Activity Log', 'settings' => 'Settings'] as $tabKey => $tabLabel)
                    <button type="button" wire:click="setTab('{{ $tabKey }}')" class="py-4 px-1 border-b-2 font-bold text-sm whitespace-nowrap transition {{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                        {{ $tabLabel }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Tab Contents -->
        @if ($activeTab === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fadeIn">
                <!-- About Card -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">About</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $clientDetails->notes ?: 'No additional notes provided for this client.' }}
                        </p>
                    </div>

                    <!-- Address Card -->
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Address details</h3>
                        @if ($clientDetails->address || $clientDetails->landmark || $clientDetails->state || $clientDetails->country || $clientDetails->region || $clientDetails->zip_code)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-400">
                                @if($clientDetails->address)
                                    <div class="md:col-span-2">
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Address</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->address }}</p>
                                    </div>
                                @endif
                                @if($clientDetails->landmark)
                                    <div>
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Landmark</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->landmark }}</p>
                                    </div>
                                @endif
                                @if($clientDetails->state)
                                    <div>
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">State</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->state }}</p>
                                    </div>
                                @endif
                                @if($clientDetails->country)
                                    <div>
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Country</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->country }}</p>
                                    </div>
                                @endif
                                @if($clientDetails->region)
                                    <div>
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Region</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->region }}</p>
                                    </div>
                                @endif
                                @if($clientDetails->zip_code)
                                    <div>
                                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Zip Code</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $clientDetails->zip_code }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-6 text-sm text-slate-450 dark:text-slate-500 italic">
                                No address details provided for this client yet.
                            </div>
                        @endif
                    </div>
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
                    <div class="text-center py-12 text-slate-500">No websites associated with this client.</div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Domain</th>
                                <th class="px-6 py-4">Hosting</th>
                                <th class="px-6 py-4">SSL</th>
                                <th class="px-6 py-4">Health</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach ($clientWebsites as $site)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">
                                            <a href="{{ $site->url }}" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                                {{ $site->site_name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                                            {{ parse_url($site->url, PHP_URL_HOST) ?: $site->url }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-semibold">{{ $site->hosting_provider ?: '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Valid</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-350">
                                        {{ $site->latestMaintenanceReport?->health_score ? $site->latestMaintenanceReport->health_score . '%' : '—' }}
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

        @elseif ($activeTab === 'maintenance')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm animate-fadeIn">
                @if ($clientMaintenanceReports->isEmpty())
                    <div class="text-center py-12 text-slate-500">No reports yet</div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
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
                                    <td class="px-6 py-4 text-slate-400 dark:text-slate-500">#{{ $report->id }}</td>
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
                                            <a href="{{ route('admin.maintenance.view', $report->id) }}"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="View Report">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Edit button --}}
                                            <a href="{{ route('admin.maintenance.edit', $report->id) }}"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150" title="Edit Report">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- PDF button --}}
                                            <a href="{{ route('admin.maintenance.pdf', $report->id) }}" target="_blank"
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
                    <div class="text-center py-12 text-slate-500">No documents uploaded yet.</div>
                @else
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
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
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-350 font-semibold">{{ $doc->file_size ? number_format($doc->file_size / (1024 * 1024), 1) . ' MB' : '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-500 text-xs font-medium">{{ $doc->created_at->format('Y-m-d') }} - {{ $doc->addedBy->name ?? 'System' }}</td>
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
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-800" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-850 flex items-center justify-center ring-8 ring-white dark:ring-slate-900">
                                                    <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-slate-600 dark:text-slate-350">{{ $log->description }}</p>
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

        @elseif ($activeTab === 'settings')
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 shadow-sm animate-fadeIn">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Quick Settings</h3>
                <p class="text-xs text-slate-450 dark:text-slate-500 mb-6">Manage administrative state settings for this client's workspace.</p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" wire:click="editClient({{ $clientDetails->id }})" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl active:scale-95 transition">
                        Edit Full Profile Settings
                    </button>
                    @if ($clientDetails->status === 'active')
                        <button type="button" wire:click="toggleClientStatus({{ $clientDetails->id }}, 'inactive')" wire:confirm="Are you sure you want to deactivate this account?" class="px-4 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/30 font-bold text-xs rounded-xl active:scale-95 transition">
                            Deactivate Account
                        </button>
                    @else
                        <button type="button" wire:click="toggleClientStatus({{ $clientDetails->id }}, 'active')" class="px-4 py-2.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/30 font-bold text-xs rounded-xl active:scale-95 transition">
                            Activate Account
                        </button>
                    @endif
                </div>
            </div>
        @endif

    @else
        {{-- ══════════════════════════════════════════════
             PAGE HEADER — Title + Add Client button
             ══════════════════════════════════════════════ --}}
        <div class="flex items-center justify-between gap-4 mb-5">
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                Manage and track all your client accounts
            </p>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.clients.plans') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 hover:bg-slate-200/50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 text-sm font-semibold transition-all duration-150 active:scale-95 shrink-0 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Manage Plans
                </a>

                {{-- Add Client button --}}
                <button type="button" wire:click="openAddModal"
        style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add Client
    </button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             FILTERS — 50 / 50 layout
             ══════════════════════════════════════════════ --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                {{-- Search (33%) --}}
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           autocomplete="off"
                           placeholder="Search by name, email, or company..."
                           class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
                </div>

                {{-- Status Filter (33%) --}}
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    <select wire:model.live="statusFilter"
                            class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                        <option value="" class="dark:bg-slate-900">All Statuses</option>
                        <option value="active" class="dark:bg-slate-900">Active</option>
                        <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                    </select>
                    <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Plan Filter (33%) --}}
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

            {{-- Clear Filters row — only shown when a filter is active --}}
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

        <!-- Clients Table Card -->
        <x-admin.card>

            {{-- Bulk Action Bar (visible only when items are selected) --}}
            @if(count($selectedClients) > 0)
                <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/60 dark:border-indigo-700/30">
                    <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2 pl-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        Selected {{ count($selectedClients) }} item(s)
                    </span>
                    <div class="flex items-center gap-2 pr-2">
                        <button type="button" wire:click="bulkActivate" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition active:scale-95">
                            Activate
                        </button>
                        <button type="button" wire:click="bulkDeactivate" class="px-3 py-1.5 bg-slate-600 hover:bg-slate-700 text-white text-xs font-bold rounded-lg transition active:scale-95">
                            Deactivate
                        </button>
                        <button type="button" wire:click="clearSelection" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition active:scale-95">
                            Cancel
                        </button>
                    </div>
                </div>
            @endif

            @if($clients->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Clients Found</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new client profile above.</p>
                </div>
            @else
                {{-- Custom table with checkbox column --}}
                <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                    <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                        <thead>
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                {{-- Select All checkbox --}}
                                <th class="px-4 py-4 w-10">
                                    <input type="checkbox"
                                           wire:model.live="selectAll"
                                           wire:change="toggleSelectAll({{ json_encode($pageIds) }})"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </th>
                                <th class="px-4 py-4">Client Details</th>
                                <th class="px-4 py-4">Company Name</th>
                                <th class="px-4 py-4">Plans</th>
                                <th class="px-4 py-4">Websites</th>
                                <th class="px-4 py-4">Phone Numbers</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Registered</th>
                                <th class="px-4 py-4">Last Login</th>
                                <th class="px-4 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach($clients as $client)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($client->id, $selectedClients) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                    {{-- Row Checkbox --}}
                                    <td class="px-4 py-4 w-10">
                                        <input type="checkbox"
                                               wire:model.live="selectedClients"
                                               value="{{ $client->id }}"
                                               class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0 cursor-pointer" wire:click="viewClientDetail({{ $client->id }})">
                                                @if($client->profile_image)
                                                    <img src="{{ asset('storage/' . $client->profile_image) }}" alt="{{ $client->user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                        {{ $client->getInitials() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition" wire:click="viewClientDetail({{ $client->id }})">
                                                    {{ $client->user->name ?? 'Deleted User' }}
                                                </div>
                                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $client->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-700 dark:text-slate-350 font-semibold cursor-pointer" wire:click="viewClientDetail({{ $client->id }})">
                                        {{ $client->company_name ?: '—' }}
                                    </td>
                                    <td class="px-4 py-4">
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
                                    <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                        {{ $client->websites_count }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-slate-500 dark:text-slate-400 font-medium text-xs">
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
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                          {{ $client->status === 'active'
                                              ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                              : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $client->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                    {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <button type="button" wire:click="editClient({{ $client->id }})"
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
                {{ $clients->links() }}
            </div>
        @endif
    </x-admin.card>
    @endif

    <!-- Add Client Modal -->
    <x-admin.modal name="add-client-modal" title="Add New Client" maxWidth="max-w-3xl">
        <div class=" mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ $profile_image->temporaryUrl() }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <input type="file" wire:model="profile_image" class="text-xs text-slate-550 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/30 dark:file:text-indigo-400 cursor-pointer" />
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
                <label for="client_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="client_email" type="email" required autocomplete="new-email" placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="client_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="client_password" type="password" required autocomplete="new-password" placeholder="Min 8 characters"
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

            <!-- Phones Section -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 ">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="add-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1 relative">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Assign Staff - Checkbox Grid for multiple staff --}}
            <div>
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Assign Staff Members</label>
                <div class="grid grid-cols-2 gap-2 mt-1.5 p-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-40 overflow-y-auto scrollbar-thin">
                    @foreach($staffMembers as $staffOpt)
                        <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                            <input type="checkbox" wire:model="assigned_staff_ids" value="{{ $staffOpt['id'] }}"
                                   class="rounded border-slate-200/100 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                            <div class="flex flex-col truncate">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $staffOpt['name'] }}</span>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 truncate">{{ $staffOpt['role'] ?: 'Staff Member' }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('assigned_staff_ids')" class="mt-1" />
            </div>

            <!-- Address Details -->
            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 mt-2">
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3">Address Details</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Address</label>
                        <textarea wire:model="address" id="address" rows="2" placeholder="Street Address"
                                  class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div>
                        <label for="landmark" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Landmark</label>
                        <input wire:model="landmark" id="landmark" type="text" placeholder="Near Hospital, Mall etc."
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('landmark')" class="mt-1" />
                    </div>
                    <div>
                        <label for="state" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">State</label>
                        <input wire:model="state" id="state" type="text" placeholder="State/Province"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('state')" class="mt-1" />
                    </div>
                    <div>
                        <label for="country" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Country</label>
                        <input wire:model="country" id="country" type="text" placeholder="Country"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('country')" class="mt-1" />
                    </div>
                    <div>
                        <label for="region" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Region</label>
                        <input wire:model="region" id="region" type="text" placeholder="Region"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('region')" class="mt-1" />
                    </div>
                    <div>
                        <label for="zip_code" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-550 uppercase tracking-widest">Zip Code</label>
                        <input wire:model="zip_code" id="zip_code" type="text" placeholder="Postal Code"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('zip_code')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Plans Selection -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Plans</label>
                <div class="grid grid-cols-2 gap-2 mt-1.5 p-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                    @foreach($plans as $planOpt)
                        <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                            <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                   class="rounded border-slate-200/100 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="client_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="client_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end  space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-client-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5 ">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="saveClient" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Client</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- Edit Client Modal -->
    <x-admin.modal name="edit-client-modal" title="Edit Client" maxWidth="max-w-3xl">
        <div class="space-y-4.5 mt-2">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ $profile_image->temporaryUrl() }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        @if ($existing_profile_image)
                            <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" />
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    @endif
                    <input type="file" wire:model="profile_image" class="text-xs text-slate-550 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/30 dark:file:text-indigo-400 cursor-pointer" />
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
                <label for="edit_client_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_client_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_client_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password (Leave blank to keep current)') }}</label>
                <input wire:model="password" id="edit_client_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
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

            <!-- Phones Section (Edit) -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="edit-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1 relative">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-600 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Assign Staff - Checkbox Grid for multiple staff --}}
            <div>
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Assign Staff Members</label>
                <div class="grid grid-cols-2 gap-2 mt-1.5 p-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-40 overflow-y-auto scrollbar-thin">
                    @foreach($staffMembers as $staffOpt)
                        <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                            <input type="checkbox" wire:model="assigned_staff_ids" value="{{ $staffOpt['id'] }}"
                                   class="rounded border-slate-200/100 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                            <div class="flex flex-col truncate">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $staffOpt['name'] }}</span>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 truncate">{{ $staffOpt['role'] ?: 'Staff Member' }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('assigned_staff_ids')" class="mt-1" />
            </div>

            <!-- Address Details -->
            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 mt-2">
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3">Address Details</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="md:col-span-2">
                        <label for="edit_address" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Address</label>
                        <textarea wire:model="address" id="edit_address" rows="2" placeholder="Street Address"
                                  class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_landmark" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Landmark</label>
                        <input wire:model="landmark" id="edit_landmark" type="text" placeholder="Near Hospital, Mall etc."
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('landmark')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_state" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">State</label>
                        <input wire:model="state" id="edit_state" type="text" placeholder="State/Province"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('state')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_country" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Country</label>
                        <input wire:model="country" id="edit_country" type="text" placeholder="Country"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('country')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_region" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Region</label>
                        <input wire:model="region" id="edit_region" type="text" placeholder="Region"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('region')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_zip_code" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-555 uppercase tracking-widest">Zip Code</label>
                        <input wire:model="zip_code" id="edit_zip_code" type="text" placeholder="Postal Code"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('zip_code')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Plans Selection -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Plans</label>
                <div class="grid grid-cols-2 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 max-h-28 overflow-y-auto scrollbar-thin">
                    @foreach($plans as $planOpt)
                        <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150 cursor-pointer">
                            <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                   class="rounded border-slate-200/100 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500/50">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="edit_client_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="edit_client_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-client-modal' })" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="updateClient" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Client</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
