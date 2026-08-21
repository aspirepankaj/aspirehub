<div>
    @section('page_title', 'Clients Management')
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
        <div class="bg-white/93 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 sm:p-6 mb-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 min-w-0 w-full md:w-auto">
                <div class="relative shrink-0">
                    @if($clientDetails->profile_image)
                        <img src="{{ asset('storage/' . $clientDetails->profile_image) }}" alt="{{ $clientDetails->user->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg sm:text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $clientDetails->getInitials() }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white break-words">{{ $clientDetails->user->name }}</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $clientDetails->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-600 dark:text-slate-400' }} tracking-wider shrink-0">
                            {{ $clientDetails->status }}
                        </span>
                        @foreach($clientDetails->plans as $pl)
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-{{ $pl->color }}-500/10 text-{{ $pl->color }}-600 dark:text-{{ $pl->color }}-400 tracking-wider shrink-0">
                                {{ $pl->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <span class="flex items-center gap-1.5 truncate max-w-full">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">{{ $clientDetails->company_name ?: 'No Company' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 truncate max-w-full">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">{{ $clientDetails->user->email }}</span>
                        </span>
                        @if($clientDetails->phones->isNotEmpty())
                            <span class="flex items-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $clientDetails->phones->first()->phone }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5 shrink-0">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Registered {{ $clientDetails->created_at->format('M d, Y') }} ({{ $clientDetails->created_at->diffForHumans() }})
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                <button type="button" wire:click="openClickUpMappingModal({{ $clientDetails->id }})" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs rounded-xl shadow-sm transition active:scale-95">
                    <span>Map With</span>
                    <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" alt="ClickUp Logo" class="h-4 w-auto shrink-0 dark:brightness-200" />
                </button>
                <button type="button" wire:click="editClient({{ $clientDetails->id }})" class="flex-1 sm:flex-none px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl active:scale-95 transition">
                    Edit
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-slate-200/60 dark:border-slate-800/40 mb-6 overflow-x-auto no-scrollbar">
            <nav class="flex space-x-4 sm:space-x-8 min-w-max pb-1" aria-label="Tabs">
                @foreach(['overview' => 'Overview', 'clickup_tickets' => 'ClickUp Tickets', 'websites' => 'Websites', 'maintenance' => 'Maintenance', 'documents' => 'Documents', 'activity log' => 'Activity Log', 'settings' => 'Settings'] as $tabKey => $tabLabel)
                    <button type="button" wire:click="setTab('{{ $tabKey }}')" class="py-3 sm:py-4 px-1 border-b-2 font-bold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-1.5 shrink-0 {{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                        @if($tabKey === 'hide_clickup_tickets')
                            <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" alt="ClickUp" class="h-3.5 w-auto shrink-0" />
                        @endif
                        <span>{{ $tabLabel }}</span>
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

                    <!-- Address Section -->
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Address Details</h3>
                        @if($clientDetails->address || $clientDetails->landmark || $clientDetails->state || $clientDetails->country || $clientDetails->region || $clientDetails->zip_code)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                @if($clientDetails->address)
                                    <div class="col-span-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Street Address</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->address }}</span>
                                    </div>
                                @endif
                                @if($clientDetails->landmark)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Landmark</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->landmark }}</span>
                                    </div>
                                @endif
                                @if($clientDetails->region)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Region</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->region }}</span>
                                    </div>
                                @endif
                                @if($clientDetails->state)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">State / Province</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->state }}</span>
                                    </div>
                                @endif
                                @if($clientDetails->country)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Country</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->country }}</span>
                                    </div>
                                @endif
                                @if($clientDetails->zip_code)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                        <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">ZIP / Postal Code</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $clientDetails->zip_code }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-sm text-slate-500 dark:text-slate-400 italic">No address details added.</div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Cards -->
                <div class="space-y-6">
                    <!-- Assigned Plans -->
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Assigned Plans</h3>
                            @if($clientDetails->plans->isNotEmpty())
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ $clientDetails->plans->count() }} {{ Str::plural('Plan', $clientDetails->plans->count()) }}
                                </span>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @forelse($clientDetails->plans as $plan)
                                <div class="p-3 bg-indigo-50/50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/40 flex items-center justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-300 truncate">{{ $plan->name }}</h4>
                                        <p class="text-[10px] text-indigo-700/70 dark:text-indigo-400/70 line-clamp-2 mt-0.5">{{ $plan->description ?: 'No description' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 bg-indigo-600 text-white rounded text-[10px] font-bold shrink-0">Active</span>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500 dark:text-slate-400 italic">No plans assigned.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Assigned Staff Members -->
                    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Assigned Staff</h3>
                            @if($clientDetails->assignedStaff->isNotEmpty())
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ $clientDetails->assignedStaff->count() }} {{ Str::plural('Member', $clientDetails->assignedStaff->count()) }}
                                </span>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @forelse($clientDetails->assignedStaff as $staff)
                                @php
                                    $staffImg = $staff->profile_image;
                                    $staffImgUrl = $staffImg ? (Str::startsWith($staffImg, 'http') ? $staffImg : asset('storage/' . $staffImg)) : null;
                                    $staffDesignation = $staff->designations->pluck('name')->implode(', ') ?: 'Staff Member';
                                    $staffDetailUrl = route('admin.staff.detail', ['id' => $staff->id]);
                                @endphp
                                <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200/60 dark:border-slate-700/60 hover:bg-slate-100/60 dark:hover:bg-slate-800 transition-colors">
                                    <a href="{{ $staffDetailUrl }}" class="flex items-center gap-3 min-w-0 flex-1 group">
                                        <div class="shrink-0">
                                            @if($staffImgUrl)
                                                <img src="{{ $staffImgUrl }}" alt="{{ $staff->user->name ?? 'Staff' }}"
                                                     class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:border-indigo-500 transition-colors"
                                                     onerror="this.outerHTML=`<div class='w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 font-bold text-xs flex items-center justify-center'>{{ $staff->getInitials() }}</div>`" />
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 font-bold text-xs flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    {{ $staff->getInitials() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate flex items-center gap-1">
                                                <span>{{ $staff->user->name ?? 'Staff Member' }}</span>
                                                <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </h4>
                                            <div class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 truncate">{{ $staffDesignation }}</div>
                                            @if(!empty($staff->user->email))
                                                <div class="flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="truncate">{{ $staff->user->email }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500 dark:text-slate-400 italic">No staff assigned.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        @elseif ($activeTab === 'clickup_tickets')
            <div wire:init="loadClickUpTasks" class="space-y-6 animate-fadeIn">
                <!-- Header Banner -->
                <div class="bg-white/70 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl p-5 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" alt="ClickUp" class="h-5 w-auto" />
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Tickets & Tasks</h3>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Live tickets from ClickUp folders assigned to {{ $clientDetails->company_name ?: ($clientDetails->user->name ?? 'this client') }}.
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="openClickUpMappingModal({{ $clientDetails->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Manage Mapped Folders</span>
                            </button>

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
                    <!-- Livewire Deferred Loading Skeleton Animation (Shown immediately when tab opens) -->
                    <div class="w-full space-y-4 animate-pulse">
                        <!-- Skeleton Filter Bar -->
                        <div class="h-14 bg-slate-200/60 dark:bg-slate-800/50 rounded-2xl w-full"></div>

                        <!-- Skeleton Accordion / Ticket Cards -->
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
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">Please assign 1 or more ClickUp folders to this client to view their tickets here.</p>
                            <button type="button" wire:click="openClickUpMappingModal({{ $clientDetails->id }})" class="inline-flex items-center gap-2 px-4 py-2 bg-[#135266] text-white text-xs font-bold rounded-xl shadow">
                                Map With ClickUp
                            </button>
                        </div>
                    @else
                    <!-- Status & Folder Filters Bar (Search Removed) -->
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

                                <!-- Status Filter (Dynamic Options from ClickUp) -->
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
                        <!-- Accordion / FAQ Collapsible View for Multiple Folders (Closed by default) -->
                        <div class="space-y-4">
                            @foreach($clientClickUpFolders as $cFolder)
                                @php
                                    $folderTasks = collect($filteredClickUpTasks)->where('folder_id', (string)$cFolder->id);
                                @endphp
                                <div x-data="{ open: false }" class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800/70 rounded-2xl overflow-hidden shadow-sm transition">
                                    <!-- Accordion Header (Click to toggle) -->
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

                                    <!-- Accordion Body (Tickets inside folder) -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-4 border-t border-slate-100 dark:border-slate-800 space-y-3 bg-slate-50/30 dark:bg-slate-950/20">
                                        @forelse($folderTasks as $task)
                                            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-4 shadow-sm transition">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                    <div class="space-y-1.5 flex-1 min-w-0">
                                                        <div class="flex items-center flex-wrap gap-2">
                                                            <!-- List Tag -->
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                                {{ $task['list_name'] }}
                                                            </span>

                                                            <!-- Status Badge -->
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider" style="background-color: {{ $task['status_color'] }}1a; color: {{ $task['status_color'] }}">
                                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task['status_color'] }}"></span>
                                                                {{ $task['status'] }}
                                                            </span>
                                                        </div>

                                                        <!-- Task Title -->
                                                        <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug">
                                                            {{ $task['name'] }}
                                                        </h4>

                                                        @if($task['description'])
                                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                                {{ $task['description'] }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <!-- Right Side Info & Actions -->
                                                    <div class="flex sm:flex-col items-end justify-between sm:justify-center gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 dark:border-slate-800">
                                                        <!-- Open in ClickUp Link -->
                                                        <a href="{{ $task['url'] }}" target="_blank"
                                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 font-bold text-xs rounded-xl transition">
                                                            <span>Open Ticket</span>
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                        </a>

                                                        <!-- Assignees & Dates -->
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
                        <!-- Single Folder Flat View (No Accordion) -->
                        <div class="space-y-3">
                            @forelse($filteredClickUpTasks as $task)
                                <div class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-4 shadow-sm transition">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="space-y-1.5 flex-1 min-w-0">
                                            <div class="flex items-center flex-wrap gap-2">
                                                <!-- Folder Tag -->
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    <svg class="w-3 h-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                    {{ $task['folder_name'] }} / {{ $task['list_name'] }}
                                                </span>

                                                <!-- Status Badge -->
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider" style="background-color: {{ $task['status_color'] }}1a; color: {{ $task['status_color'] }}">
                                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task['status_color'] }}"></span>
                                                    {{ $task['status'] }}
                                                </span>
                                            </div>

                                            <!-- Task Title -->
                                            <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug">
                                                {{ $task['name'] }}
                                            </h4>

                                            @if($task['description'])
                                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                    {{ $task['description'] }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Right Side Info & Actions -->
                                        <div class="flex sm:flex-col items-end justify-between sm:justify-center gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 dark:border-slate-800">
                                            <!-- Open in ClickUp Link -->
                                            <a href="{{ $task['url'] }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 font-bold text-xs rounded-xl transition">
                                                <span>Open Ticket</span>
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>

                                            <!-- Assignees & Dates -->
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
                    <div class="text-center py-12 text-slate-500">No resources or links added yet.</div>
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
             FILTERS — Responsive grid
             ══════════════════════════════════════════════ --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">

                {{-- Search (33%) --}}
                <div class="relative flex items-center col-span-1 sm:col-span-2 md:col-span-1">
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
        <x-admin.card class="w-full max-w-full overflow-hidden">

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
                                <th class="px-3 py-3.5">Client Details</th>
                                <th class="px-3 py-3.5">Company Name</th>
                                <th class="px-3 py-3.5">Plans</th>
                                <th class="px-3 py-3.5 text-center">Websites</th>
                                <th class="px-3 py-3.5 hidden xl:table-cell">Phone Numbers</th>
                                <th class="px-3 py-3.5 text-center">Status</th>
                                <th class="px-3 py-3.5 text-center hidden lg:table-cell">Last Login</th>
                                <th class="px-3 py-3.5 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                            @foreach($clients as $client)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($client->id, $selectedClients) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                    {{-- Row Checkbox --}}
                                    <td class="px-3 py-3.5 w-10 text-center">
                                        <input type="checkbox"
                                               wire:model.live="selectedClients"
                                               value="{{ $client->id }}"
                                               class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0 cursor-pointer" wire:click="viewClientDetail({{ $client->id }})">
                                                @if($client->profile_image)
                                                    <img src="{{ asset('storage/' . $client->profile_image) }}" alt="{{ $client->user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400'>{{ $client->getInitials() }}</div>`" />
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                        {{ $client->getInitials() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition truncate max-w-[160px] sm:max-w-[200px] lg:max-w-none lg:whitespace-normal" title="{{ $client->user->name ?? 'Deleted User' }}" wire:click="viewClientDetail({{ $client->id }})">
                                                    {{ $client->user->name ?? 'Deleted User' }}
                                                </div>
                                                <div class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-[160px] sm:max-w-[200px] lg:max-w-none lg:whitespace-normal" title="{{ $client->user->email ?? 'N/A' }}">{{ $client->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-700 dark:text-slate-350 font-semibold cursor-pointer" wire:click="viewClientDetail({{ $client->id }})">
                                        {{ $client->company_name ?: '—' }}
                                    </td>
                                    <td class="px-3 py-3.5">
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
                                    <td class="px-3 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                        {{ $client->websites_count }}
                                    </span>
                                </td>

                                <td class="px-3 py-3.5 hidden xl:table-cell text-slate-500 dark:text-slate-400 font-medium text-xs">
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
                                <td class="px-3 py-3.5 text-center">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                          {{ $client->status === 'active'
                                              ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                              : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-center hidden lg:table-cell text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                    {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 shrink-0">
                                        <button type="button" wire:click="openClickUpMappingModal({{ $client->id }})"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 shadow-sm transition-all duration-150 active:scale-95 shrink-0"
                                                title="Map ClickUp Folders to this Client">
                                                <span class="hidden 2xl:inline">Map With</span>
                                            <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" alt="ClickUp Logo" class="h-4 w-auto shrink-0 dark:brightness-200" />
                                        </button>

                                        <button type="button" wire:click="editClient({{ $client->id }})"
                                                class="inline-flex items-center gap-1.5 p-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 transition-all duration-150 active:scale-95 shrink-0"
                                                title="Edit Client Profile">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
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
        <div class="space-y-6 mt-2">
            
            <!-- Section 1: Profile & Basic Details -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Basic Profile</h4>
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Profile Image') }}</label>
                    <div class="flex items-center gap-3">
                        @if ($profile_image)
                            <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-500/30 shadow-sm" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold'>{{ strtoupper(substr($name ?? 'C', 0, 2)) }}</div>`" />
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold text-xs rounded-xl transition-colors border border-indigo-200/80 dark:border-indigo-800/60">
                            Choose from Media Library
                        </button>

                        @if ($profile_image)
                            <button type="button" wire:click="removeProfileImage" class="px-3 py-2 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 border border-red-200/60 dark:border-red-900/40 rounded-xl hover:bg-red-100 transition">
                                Remove
                            </button>
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
                </div>

                <!-- 2-Column Grid: Name & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Full Name') }}</label>
                        <input wire:model="name" id="name" type="text" required autocomplete="new-name" placeholder="e.g. John Doe"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="client_email" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Email Address') }}</label>
                        <input wire:model="email" id="client_email" type="email" required autocomplete="new-email" placeholder="john.doe@example.com"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                </div>

                <!-- 2-Column Grid: Password & Company Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="client_password" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Password') }}</label>
                        <input wire:model="password" id="client_password" type="password" required autocomplete="new-password" placeholder="Min 8 characters"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label for="company_name" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Company Name') }}</label>
                        <input wire:model="company_name" id="company_name" type="text" placeholder="e.g. Acme Corp"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact Numbers -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Phone Numbers</h4>
                    </div>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 flex items-center gap-1">
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="add-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 self-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Account Assignment & Status -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Assignment & Plans</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Assign Staff -->
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assign Account Manager</label>
                        <div class="grid grid-cols-1 gap-2 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 max-h-36 overflow-y-auto custom-scrollbar">
                            @foreach($staffMembers as $staffOpt)
                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-600 transition duration-150 cursor-pointer">
                                    <input type="radio" wire:model="assigned_staff_id" value="{{ $staffOpt['id'] }}"
                                           class="rounded-full border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40">
                                    <div class="flex flex-col truncate">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $staffOpt['name'] }}</span>
                                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 truncate">{{ $staffOpt['role'] ?: 'Staff Member' }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('assigned_staff_id')" class="mt-1" />
                    </div>

                    <!-- Plans Selection -->
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Subscribed Plans</label>
                        <div class="grid grid-cols-1 gap-2 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 max-h-36 overflow-y-auto custom-scrollbar">
                            @foreach($plans as $planOpt)
                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-600 transition duration-150 cursor-pointer">
                                    <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                           class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                    </div>
                </div>

                <!-- Status Select -->
                <div>
                    <label for="client_status" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Account Status') }}</label>
                    <select wire:model="status" id="client_status" 
                            class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition duration-150">
                        <option value="active" class="dark:bg-slate-900">Active</option>
                        <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            <!-- Section 4: Address Details -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Address Details</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Street Address</label>
                        <textarea wire:model="address" id="address" rows="2" placeholder="Street Address"
                                  class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div>
                        <label for="landmark" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Landmark</label>
                        <input wire:model="landmark" id="landmark" type="text" placeholder="Near Hospital, Mall etc."
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('landmark')" class="mt-1" />
                    </div>
                    <div>
                        <label for="state" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">State / Province</label>
                        <input wire:model="state" id="state" type="text" placeholder="State/Province"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('state')" class="mt-1" />
                    </div>
                    <div>
                        <label for="country" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Country</label>
                        <input wire:model="country" id="country" type="text" placeholder="Country"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('country')" class="mt-1" />
                    </div>
                    <div>
                        <label for="region" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Region</label>
                        <input wire:model="region" id="region" type="text" placeholder="Region"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('region')" class="mt-1" />
                    </div>
                    <div>
                        <label for="zip_code" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Zip / Postal Code</label>
                        <input wire:model="zip_code" id="zip_code" type="text" placeholder="Postal Code"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('zip_code')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Section 5: Notes -->
            <div class="space-y-2 pt-2">
                <label for="notes" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-client-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="saveClient" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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
        <div class="space-y-6 mt-2">
            
            <!-- Section 1: Profile & Basic Details -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Basic Profile</h4>
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Profile Image') }}</label>
                    <div class="flex items-center gap-3">
                        @if ($profile_image)
                            <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-500/30 shadow-sm" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold'>{{ strtoupper(substr($name ?? 'C', 0, 2)) }}</div>`" />
                        @elseif ($existing_profile_image)
                            <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-500/30 shadow-sm" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold'>{{ strtoupper(substr($name ?? 'C', 0, 2)) }}</div>`" />
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold text-xs rounded-xl transition-colors border border-indigo-200/80 dark:border-indigo-800/60">
                            Choose from Media Library
                        </button>

                        @if ($profile_image || $existing_profile_image)
                            <button type="button" wire:click="removeProfileImage" class="px-3 py-2 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 border border-red-200/60 dark:border-red-900/40 rounded-xl hover:bg-red-100 transition">
                                Remove
                            </button>
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
                </div>

                <!-- 2-Column Grid: Name & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="edit_name" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Full Name') }}</label>
                        <input wire:model="name" id="edit_name" type="text" required placeholder="e.g. John Doe"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="edit_client_email" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Email Address') }}</label>
                        <input wire:model="email" id="edit_client_email" type="email" required placeholder="john.doe@example.com"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                </div>

                <!-- 2-Column Grid: Password & Company Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="edit_client_password" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Password (Leave blank to keep current)') }}</label>
                        <input wire:model="password" id="edit_client_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label for="edit_company_name" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Company Name') }}</label>
                        <input wire:model="company_name" id="edit_company_name" type="text" placeholder="e.g. Acme Corp"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact Numbers -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Phone Numbers</h4>
                    </div>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 flex items-center gap-1">
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="edit-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 self-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Account Assignment & Status -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Assignment & Plans</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Assign Staff -->
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assign Account Manager</label>
                        <div class="grid grid-cols-1 gap-2 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 max-h-36 overflow-y-auto custom-scrollbar">
                            @foreach($staffMembers as $staffOpt)
                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-600 transition duration-150 cursor-pointer">
                                    <input type="radio" wire:model="assigned_staff_id" value="{{ $staffOpt['id'] }}"
                                           class="rounded-full border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40">
                                    <div class="flex flex-col truncate">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $staffOpt['name'] }}</span>
                                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 truncate">{{ $staffOpt['role'] ?: 'Staff Member' }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('assigned_staff_id')" class="mt-1" />
                    </div>

                    <!-- Plans Selection -->
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Subscribed Plans</label>
                        <div class="grid grid-cols-1 gap-2 p-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 max-h-36 overflow-y-auto custom-scrollbar">
                            @foreach($plans as $planOpt)
                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-600 transition duration-150 cursor-pointer">
                                    <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                           class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                    </div>
                </div>

                <!-- Status Select -->
                <div>
                    <label for="edit_client_status" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Account Status') }}</label>
                    <select wire:model="status" id="edit_client_status" 
                            class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition duration-150">
                        <option value="active" class="dark:bg-slate-900">Active</option>
                        <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            <!-- Section 4: Address Details -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Address Details</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="edit_address" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Street Address</label>
                        <textarea wire:model="address" id="edit_address" rows="2" placeholder="Street Address"
                                  class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_landmark" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Landmark</label>
                        <input wire:model="landmark" id="edit_landmark" type="text" placeholder="Near Hospital, Mall etc."
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('landmark')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_state" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">State / Province</label>
                        <input wire:model="state" id="edit_state" type="text" placeholder="State/Province"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('state')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_country" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Country</label>
                        <input wire:model="country" id="edit_country" type="text" placeholder="Country"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('country')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_region" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Region</label>
                        <input wire:model="region" id="edit_region" type="text" placeholder="Region"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('region')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_zip_code" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Zip / Postal Code</label>
                        <input wire:model="zip_code" id="edit_zip_code" type="text" placeholder="Postal Code"
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150" />
                        <x-input-error :messages="$errors->get('zip_code')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Section 5: Notes -->
            <div class="space-y-2 pt-2">
                <label for="edit_notes" class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm font-semibold transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-client-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="updateClient" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Client</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- ClickUp Folder Mapping Modal -->
    <x-admin.modal name="clickup-client-mapping-modal" title="Map ClickUp Folders to Client" maxWidth="max-w-3xl">
        @if($mappingClient)
            <div class="space-y-5">
                <!-- Client Header Banner -->
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/50 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#135266]/10 text-[#135266] dark:bg-[#135266]/30 dark:text-teal-300 flex items-center justify-center font-bold text-sm">
                            {{ $mappingClient->getInitials() }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-base">
                                {{ $mappingClient->company_name ?: ($mappingClient->user->name ?? 'Client') }}
                            </h4>
                            <p class="text-xs text-slate-400 dark:text-slate-400">
                                {{ $mappingClient->user->name ?? '' }} ({{ $mappingClient->user->email ?? '' }})
                            </p>
                        </div>
                    </div>

                    <button type="button" wire:click="syncClickUpApi" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-200/70 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                        <svg wire:loading.class="animate-spin" wire:target="syncClickUpApi" class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span wire:loading.remove wire:target="syncClickUpApi">Sync ClickUp API</span>
                        <span wire:loading wire:target="syncClickUpApi">Syncing...</span>
                    </button>
                </div>

                <!-- ClickUp Spaces Selection Grid -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Select ClickUp Space
                        </label>
                        <span class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-0.5 rounded-full border border-slate-200/60 dark:border-slate-700/60">
                            {{ count($clickUpSpaces) }} {{ Str::plural('Space', count($clickUpSpaces)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-52 overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($clickUpSpaces as $space)
                            @php
                                $isSelected = $clickUpSpaceId === $space->id;
                                $spaceColor = $space->color ?: '#135266';
                            @endphp
                            <button type="button" 
                                    wire:click="selectClickUpSpace('{{ $space->id }}')"
                                    class="group relative text-left p-3 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-2.5 overflow-hidden {{ $isSelected ? 'bg-gradient-to-br from-indigo-50/90 to-slate-50 dark:from-slate-800 dark:to-slate-850 border-indigo-500 dark:border-indigo-400 shadow-md ring-2 ring-indigo-500/20' : 'bg-white dark:bg-slate-800/70 border-slate-200/80 dark:border-slate-700/70 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-sm' }}">
                                
                                <div class="flex items-start justify-between gap-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-3 h-3 rounded-full shrink-0 shadow-sm border border-black/10" style="background-color: {{ $spaceColor }}"></span>
                                        <h5 class="text-xs font-bold truncate transition-colors {{ $isSelected ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400' }}">
                                            {{ $space->name }}
                                        </h5>
                                    </div>
                                    @if($isSelected)
                                        <span class="w-4 h-4 rounded-full bg-indigo-600 text-white text-[9px] font-black flex items-center justify-center shrink-0 shadow-sm">✓</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-400 font-medium pt-1.5 border-t border-slate-100 dark:border-slate-750/50">
                                    <span class="flex items-center gap-1 font-semibold">
                                        <svg class="w-3 h-3 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        {{ $space->folders_count ?? count($space->folders) }} {{ Str::plural('folder', $space->folders_count ?? count($space->folders)) }}
                                    </span>
                                    @if($isSelected)
                                        <span class="text-indigo-600 dark:text-indigo-400 font-extrabold uppercase tracking-wider text-[9px]">Selected</span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Folders Live Search Input -->
                @if(!empty($clickUpSpaceId))
                    <div class="relative">
                        <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input wire:model.live.debounce.200ms="clickUpFolderSearch"
                               type="text"
                               placeholder="Search ClickUp folder name in this space..."
                               class="block w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-[#135266]/40 focus:border-[#135266]" />
                    </div>
                @endif

                <!-- Folders List Container with Checkboxes -->
                <div class="border border-slate-200/80 dark:border-slate-700/80 rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60 custom-scrollbar">
                        @if(empty($clickUpSpaceId))
                            <div class="px-6 py-10 text-center">
                                <svg class="w-10 h-10 text-[#135266]/40 dark:text-teal-500/40 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                </svg>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-300">No Space Selected</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Please click on a ClickUp Space tab above to view and assign folders.</p>
                            </div>
                        @else
                            @forelse($clickUpFolders as $folder)
                                @php
                                    $folderIdStr = (string) $folder->id;
                                    $isSelectedForThisClient = in_array($folderIdStr, $selectedClickUpFolderIds, true);
                                    $isOtherClientFolder = $folder->client_id && $folder->client_id !== $mappingClient->id;
                                    $otherClientName = $isOtherClientFolder ? ($folder->client->company_name ?: ($folder->client->user->name ?? 'Client #'.$folder->client_id)) : null;
                                @endphp
                                <label wire:key="clickup-folder-{{ $clickUpSpaceId }}-{{ $folder->id }}"
                                       class="flex items-center justify-between px-4 py-3 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 cursor-pointer transition {{ $isSelectedForThisClient ? 'bg-indigo-50/40 dark:bg-indigo-950/20' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox"
                                               wire:click="toggleFolderSelection('{{ $folderIdStr }}')"
                                               {{ $isSelectedForThisClient ? 'checked' : '' }}
                                               class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-[#135266] focus:ring-[#135266]/40 focus:ring-2 cursor-pointer" />
                                        
                                        <div>
                                            <div class="font-bold text-sm text-slate-800 dark:text-slate-100 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                                <span>{{ $folder->name }}</span>
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-mono">ID: {{ $folder->id }}</div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div>
                                        @if($isSelectedForThisClient)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Assigned to this Client
                                            </span>
                                        @elseif($isOtherClientFolder)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400" title="Assigned to {{ $otherClientName }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Assigned: {{ Str::limit($otherClientName, 18) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                                Unassigned
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <div class="px-6 py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                                    No ClickUp folders found in this space.
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>

                <!-- Footer Summary & Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                        Selected <span class="font-extrabold text-[#135266] dark:text-teal-400">{{ count(array_filter($selectedClickUpFolderIds)) }}</span> folder(s) for this client
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="$dispatch('close-modal', { name: 'clickup-client-mapping-modal' })"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition duration-150">
                            Cancel
                        </button>

                        <button type="button" wire:click="saveClickUpMapping"
                                class="px-5 py-2 bg-[#135266] hover:bg-[#0f4152] text-white text-xs font-bold rounded-xl shadow transition duration-150">
                            Save ClickUp Mapping
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </x-admin.modal>
</div>
