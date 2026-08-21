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
                    $navTabs = ['overview' => 'Overview', 'websites' => 'Websites'];
                    if ($isAssignedToStaff) {
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
