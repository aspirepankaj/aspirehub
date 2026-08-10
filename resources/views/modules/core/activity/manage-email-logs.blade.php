@section('page_title', 'Email Dispatch Logs')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Email Logs' => null]" />

    {{-- Filters Card --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="flex flex-col gap-3">

            {{-- Row 1: Search + Status --}}
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
                           placeholder="Search by recipient email, subject or sender..."
                           class="block w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

                {{-- Status --}}
                <div>
                    <select wire:model.live="statusFilter"
                            class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="" class="dark:bg-slate-900">All Statuses</option>
                        <option value="sent" class="dark:bg-slate-900">Sent</option>
                        <option value="failed" class="dark:bg-slate-900">Failed</option>
                    </select>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100 dark:border-slate-800/60"></div>

            {{-- Row 2: Date Range + Clear --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 hidden sm:flex items-center whitespace-nowrap gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Date Range
                </span>

                <div class="flex-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1 sm:hidden">From</label>
                    <input wire:model.live="dateFrom"
                           type="date"
                           class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

                <span class="text-slate-300 dark:text-slate-600 text-lg font-thin hidden sm:block">—</span>

                <div class="flex-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1 sm:hidden">To</label>
                    <input wire:model.live="dateTo"
                           type="date"
                           class="block w-full px-3 py-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/40 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 text-sm transition duration-150" />
                </div>

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

    {{-- Data Table --}}
    <x-admin.card>
        @if($logs->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-300 mb-1">No Email Logs Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">
                    @if($hasActiveFilters)
                        No records match your current filters.
                        <button wire:click="clearFilters" class="text-indigo-500 hover:underline ml-1">Clear filters</button>
                    @else
                        No email activities have been recorded yet.
                    @endif
                </p>
            </div>
        @else
            <x-admin.table :headers="['Recipient & Website', 'Subject / Report ID', 'Status', 'Sender', 'Sent At']">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">
                                {{ $log->recipient_email }}
                            </div>
                            @if($log->report?->website)
                                <div class="text-xs text-indigo-500 font-medium">
                                    {{ $log->report->website->site_name }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $log->subject }}
                            </div>
                            @if($log->report_id)
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                    Report: <a href="{{ route('admin.maintenance.view', $log->report_id) }}" class="underline hover:text-indigo-500">#{{ $log->report_id }}</a>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($log->status === 'sent')
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    Sent
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 block w-fit" title="{{ $log->error_message }}">
                                    Failed
                                </span>
                                @if($log->error_message)
                                    <p class="text-[10px] text-rose-500 mt-1 max-w-xs truncate" title="{{ $log->error_message }}">
                                        {{ $log->error_message }}
                                    </p>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                                    <span class="text-[10px] font-extrabold text-indigo-600 dark:text-indigo-400">
                                        {{ strtoupper(substr($log->sender->name ?? 'S', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $log->sender->name ?? 'System' }}</span>
                            </div>
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
