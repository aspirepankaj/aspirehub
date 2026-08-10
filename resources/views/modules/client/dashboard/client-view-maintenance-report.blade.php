<div>
    <x-admin.breadcrumbs
        :items="[
            'Maintenance Reports' => route('client.maintenance'),
            'Report Details' => null,
        ]"
    />

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-white dark:bg-slate-900/60 p-4 border border-slate-200 dark:border-slate-800/60 rounded-3xl shadow-sm">
        <div>
            <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Report Status</div>
            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mt-1 uppercase">
                {{ $report->status }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('client.maintenance.pdf', $report->id) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition active:scale-95 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <!-- Grid Details -->
    <div class="space-y-6">
        
        <!-- Context Card -->
        <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Report Details</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Website</h5>
                    <p class="font-bold text-slate-800 dark:text-white mt-1">{{ $report->website->site_name }}</p>
                    <a href="{{ $report->website->url }}" target="_blank" class="text-xs text-indigo-500 hover:underline mt-1 block">{{ $report->website->url }}</a>
                </div>
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Maintenance Month</h5>
                    <p class="font-bold text-slate-800 dark:text-white mt-1">{{ $report->maintenance_month }}</p>
                </div>
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Completed On</h5>
                    <p class="font-bold text-slate-800 dark:text-white mt-1">{{ \Carbon\Carbon::parse($report->maintenance_date)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- WordPress Card -->
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">WordPress Core</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="font-bold text-slate-400">Current Version</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->wp_version_current ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-400">Latest Available</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->wp_version_latest ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500">Core Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->wp_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->wp_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->wp_notes)
                        <div class="text-xs">
                            <span class="font-bold text-slate-400">Notes:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->wp_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PHP Card -->
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">PHP Environment</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="font-bold text-slate-400">Current PHP</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->php_version_current ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-400">Recommended</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->php_version_recommended ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500">PHP Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->php_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->php_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->php_notes)
                        <div class="text-xs">
                            <span class="font-bold text-slate-400">Notes:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->php_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Theme Card -->
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Active Theme</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="font-bold text-slate-400">Theme Name</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->theme_name ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-400">Theme Version</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-350 mt-0.5">{{ $report->theme_version ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500">Theme Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->theme_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->theme_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->theme_notes)
                        <div class="text-xs">
                            <span class="font-bold text-slate-400">Notes:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->theme_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Plugins Updated -->
        @if($report->plugins->isNotEmpty())
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Plugins Maintenance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                <th class="py-3 pr-4">Plugin Name</th>
                                <th class="py-3 px-4">Old Version</th>
                                <th class="py-3 px-4">New Version</th>
                                <th class="py-3 pl-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 font-semibold text-slate-700 dark:text-slate-300">
                            @foreach($report->plugins as $plugin)
                                <tr>
                                    <td class="py-3 pr-4">{{ $plugin->plugin_name }}</td>
                                    <td class="py-3 px-4">{{ $plugin->old_version }}</td>
                                    <td class="py-3 px-4">{{ $plugin->new_version }}</td>
                                    <td class="py-3 pl-4">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $plugin->status === 'updated' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-amber-500/10 text-amber-600' }}">
                                            {{ $plugin->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Work Summary -->
        @if($report->support_work_summary)
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Work Summary</h3>
                </div>
                <div class="text-sm text-slate-700 dark:text-slate-350 leading-relaxed whitespace-pre-line">
                    {{ $report->support_work_summary }}
                </div>
            </div>
        @endif
    </div>
</div>
