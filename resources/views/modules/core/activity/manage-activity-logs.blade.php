@section('page_title', 'Activity Logs')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Activity Logs' => null]" />

    {{-- ═══════════════════════════════════════════════
         FILTERS CARD
    ═══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="flex flex-col gap-3">

            {{-- Row 1: Search + Action Type --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {{-- Search --}}
                <div class="sm:col-span-2 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           autocomplete="off"
                           placeholder="Search by description or user..."
                           class="block w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

                {{-- Action Type --}}
                <div>
                    <select wire:model.live="actionFilter"
                            class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="" class="dark:bg-slate-900">All Action Types</option>
                        <option value="login" class="dark:bg-slate-900">Login</option>
                        <option value="failed_login" class="dark:bg-slate-900">Failed Login</option>
                        <option value="access_denied" class="dark:bg-slate-900">Access Denied</option>
                        <option value="created" class="dark:bg-slate-900">Created</option>
                        <option value="updated" class="dark:bg-slate-900">Updated</option>
                        <option value="deleted" class="dark:bg-slate-900">Deleted</option>
                    </select>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100 dark:border-slate-800/60"></div>

            {{-- Row 2: Date Range + Clear --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                {{-- Date Range label --}}
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 hidden sm:flex items-center whitespace-nowrap gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Date Range
                </span>

                {{-- From date --}}
                <div class="flex-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1 sm:hidden">From</label>
                    <input wire:model.live="dateFrom"
                           type="date"
                           class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

                <span class="text-slate-300 dark:text-slate-600 text-lg font-thin hidden sm:block">—</span>

                {{-- To date --}}
                <div class="flex-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1 sm:hidden">To</label>
                    <input wire:model.live="dateTo"
                           type="date"
                           class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

                {{-- Clear Filters --}}
                @if($hasActiveFilters)
                    <button type="button" wire:click="clearFilters"
                            class="sm:ml-2 shrink-0 flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear All
                    </button>
                @endif
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         DATA TABLE
    ═══════════════════════════════════════════════ --}}
    <x-admin.card>
        @if($logs->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-300 mb-1">No Activity Logs Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">
                    @if($hasActiveFilters)
                        No records match your current filters.
                        <button wire:click="clearFilters" class="text-indigo-500 hover:underline ml-1">Clear filters</button>
                    @else
                        No system activity has been recorded yet.
                    @endif
                </p>
            </div>
        @else
            <x-admin.table :headers="['Event Description', 'Action', 'Performed By', 'IP Address', 'Recorded']">
                @foreach($logs as $log)
                    @php
                        $actionMeta = [
                            'login'         => ['class' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',  'icon' => '🔓'],
                            'failed_login'  => ['class' => 'bg-amber-500/10 text-amber-700 dark:text-amber-400',        'icon' => '🚫'],
                            'access_denied' => ['class' => 'bg-red-500/10 text-red-700 dark:text-red-400',              'icon' => '⛔'],
                            'created'       => ['class' => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-400',     'icon' => '✅'],
                            'updated'       => ['class' => 'bg-sky-500/10 text-sky-700 dark:text-sky-400',              'icon' => '✏️'],
                            'deleted'       => ['class' => 'bg-rose-500/10 text-rose-700 dark:text-rose-400',           'icon' => '🗑️'],
                        ];
                        $meta = $actionMeta[$log->action] ?? ['class' => 'bg-slate-500/10 text-slate-600 dark:text-slate-400', 'icon' => '•'];
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200 leading-snug">{{ $log->description }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg capitalize {{ $meta['class'] }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                                    <span class="text-[10px] font-extrabold text-indigo-600 dark:text-indigo-400">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 dark:text-slate-400">
                            {{ $log->meta['ip'] ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $log->created_at->diffForHumans() }}</span>
                            <p class="text-[10px] text-slate-300 dark:text-slate-600 mt-0.5">{{ $log->created_at->format('d M Y, h:i A') }}</p>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
