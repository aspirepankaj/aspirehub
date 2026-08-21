<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" class="h-4 w-auto dark:brightness-200" alt="ClickUp Logo" />
                <span class="text-[11px] font-extrabold uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">Integration</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span>ClickUp Tickets</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Track and manage tasks assigned to you across your client accounts in real-time.
            </p>
        </div>

        {{-- Sync Button --}}
        <div class="flex items-center gap-3">
            <button type="button" wire:click="syncTasks"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold rounded-xl shadow-sm transition active:scale-95">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Sync ClickUp</span>
            </button>
        </div>
    </div>

    <!-- View Filter Tabs (Assigned to Me vs All Client Tickets) -->
    <div class="flex items-center gap-2 mb-5 border-b border-slate-200/60 dark:border-slate-800/60 pb-3">
        <button type="button" wire:click="setViewFilter('assigned_to_me')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $viewFilter === 'assigned_to_me' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Assigned to Me</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $viewFilter === 'assigned_to_me' ? 'bg-white/20 text-white' : 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' }}">
                {{ $assignedToMeCount }}
            </span>
        </button>

        <button type="button" wire:click="setViewFilter('all_client_tickets')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $viewFilter === 'all_client_tickets' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>All Assigned Client Tickets</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $viewFilter === 'all_client_tickets' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                {{ $totalTicketsCount }}
            </span>
        </button>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            {{-- Search --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search tickets by title, client, folder..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 text-sm transition" />
            </div>

            {{-- Status Filter --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                <select wire:model.live="statusFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition appearance-none">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Client Filter --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <select wire:model.live="clientFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition appearance-none">
                    <option value="">All Assigned Clients</option>
                    @foreach($clients as $cl)
                        <option value="{{ $cl['id'] }}">{{ $cl['name'] }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Success Flash Message -->
    @if(session('success'))
        <x-admin.alert type="success" class="mb-5" :message="session('success')" />
    @endif

    <!-- Tickets List Card -->
    <x-admin.card class="w-full max-w-full overflow-hidden">
        @if(!$tasksLoaded)
            <div class="text-center py-16 text-slate-500 dark:text-slate-400 flex flex-col items-center">
                <svg class="w-8 h-8 animate-spin text-indigo-600 mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm font-semibold">Loading ClickUp tickets...</p>
            </div>
        @elseif($tickets->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">No Tickets Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto">
                    @if($viewFilter === 'assigned_to_me')
                        No tasks currently assigned to your staff profile in ClickUp.
                    @else
                        No tasks found for your assigned clients.
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto custom-scrollbar w-full rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="px-4 py-3.5">Ticket / Task Title</th>
                            <th class="px-4 py-3.5">Client & Folder</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 hidden lg:table-cell">Due Date</th>
                            <th class="px-4 py-3.5">Assignees</th>
                            <th class="px-4 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($tickets as $task)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition">
                                {{-- Title & Description --}}
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 transition text-sm">
                                        <a href="{{ $task['url'] }}" target="_blank" class="flex items-center gap-1.5 group">
                                            <span>{{ $task['name'] }}</span>
                                            <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                    @if(!empty($task['description']))
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 line-clamp-1 max-w-md">
                                            {{ $task['description'] }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Client & Folder --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs">
                                        {{ $task['client_name'] }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        {{ $task['folder_name'] }}
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border"
                                          style="background-color: {{ $task['status_color'] }}15; color: {{ $task['status_color'] }}; border-color: {{ $task['status_color'] }}40;">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $task['status_color'] }};"></span>
                                        <span class="uppercase text-[10px] tracking-wider font-extrabold">{{ $task['status'] }}</span>
                                    </span>
                                </td>

                                {{-- Due Date --}}
                                <td class="px-4 py-4 hidden lg:table-cell whitespace-nowrap text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $task['due_date'] ?: '—' }}
                                </td>

                                {{-- Assignees --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if(!empty($task['assignees']))
                                        <div class="flex items-center -space-x-1.5">
                                            @foreach($task['assignees'] as $assignee)
                                                @if(!empty($assignee['profilePicture']))
                                                    <img src="{{ $assignee['profilePicture'] }}" title="{{ $assignee['username'] }}" class="w-7 h-7 rounded-full object-cover border-2 border-white dark:border-slate-800 shadow-sm" />
                                                @else
                                                    <div class="w-7 h-7 rounded-full border-2 border-white dark:border-slate-800 bg-indigo-500 text-white text-[10px] font-bold flex items-center justify-center shadow-sm" title="{{ $assignee['username'] }}">
                                                        {{ strtoupper(substr($assignee['username'], 0, 2)) }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <a href="{{ $task['url'] }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition active:scale-95">
                                        <span>Open</span>
                                        <img src="{{ asset('aspire-hub-clickup-logo.svg') }}" class="h-3.5 w-auto dark:brightness-200" alt="" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.card>
</div>
