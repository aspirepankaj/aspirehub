@section('page_title', 'Activity Logs')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Activity Logs' => null]" />

    <!-- Filters Section -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <!-- Search bar -->
        <div class="relative w-full sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   autocomplete="off"
                   placeholder="Search by description or user..." 
                   class="block w-full pl-11 pr-4.5 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
        </div>

        <!-- Action Filter -->
        <div class="w-full sm:w-auto">
            <select wire:model.live="actionFilter" 
                    class="block w-full sm:w-48 px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                <option value="" class="dark:bg-slate-900">All Action Types</option>
                <option value="created" class="dark:bg-slate-900">Created</option>
                <option value="updated" class="dark:bg-slate-900">Updated</option>
                <option value="deleted" class="dark:bg-slate-900">Deleted</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <x-admin.card>
        @if($logs->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Activity Logs Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or check back later.</p>
            </div>
        @else
            <x-admin.table :headers="['Event Description', 'Action', 'Performed By', 'IP Address', 'Recorded']">
                @foreach($logs as $log)
                    @php
                        $actionColors = [
                            'created' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                            'updated' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400',
                            'deleted' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                        ];
                        $badgeClass = $actionColors[$log->action] ?? 'bg-slate-500/10 text-slate-600 dark:text-slate-400';
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4 font-semibold text-sm text-slate-800 dark:text-slate-200">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase tracking-wider {{ $badgeClass }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-sm text-slate-700 dark:text-slate-300">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 dark:text-slate-400">
                            {{ $log->meta['ip'] ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $log->created_at->diffForHumans() }}
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
