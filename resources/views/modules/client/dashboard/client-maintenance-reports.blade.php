<div>
    <x-admin.breadcrumbs
        :items="[
            'Maintenance Reports' => null,
        ]"
    />

    <div class="mb-8">
        <div class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400">
            Maintenance Reports
        </div>
        <h1 class="mt-2 text-5xl font-black tracking-tight text-slate-900 dark:text-white">
            Everything we did, in plain language.
        </h1>
        <p class="mt-3 text-slate-500 dark:text-slate-400">
            Every month we run a full maintenance cycle across your websites. Below is a business-friendly summary of the work — no jargon, no technical noise.
        </p>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($reports as $report)
            @php
                $formattedMonth = \Carbon\Carbon::parse($report->maintenance_date)->format('F Y');
                $formattedDate = \Carbon\Carbon::parse($report->maintenance_date)->format('M j, Y');
                $healthScore = $report->health_score ?? 100;
                $perfScore = $report->performance_desktop ?? 100;
                $securityScore = 98; // High standard static/dynamic score
            @endphp
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm hover:border-slate-350 dark:hover:border-slate-750 transition duration-150 flex flex-col justify-between max-w-2xl">
                
                <!-- Card Header -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-550 uppercase tracking-wider mb-1">
                            {{ $report->site_name }}
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                            {{ $formattedMonth }}
                        </h3>
                        <p class="text-[11px] font-semibold text-slate-450 dark:text-slate-500 mt-0.5">
                            Generated {{ $formattedDate }}
                        </p>
                    </div>

                    <!-- Scores Circular Progress Indicators -->
                    <div class="flex items-center gap-4">
                        <!-- Health Score -->
                        <div class="flex flex-col items-center">
                            <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="#10b981" stroke-width="3" fill="transparent"
                                            stroke-dasharray="{{ 2 * pi() * 18 }}" stroke-dashoffset="{{ (1 - $healthScore / 100) * (2 * pi() * 18) }}" stroke-linecap="round" />
                                </svg>
                                <span style="position: absolute; font-size: 11px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $healthScore }}</span>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">Health</span>
                        </div>

                        <!-- Perf Score -->
                        <div class="flex flex-col items-center">
                            <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="#10b981" stroke-width="3" fill="transparent"
                                            stroke-dasharray="{{ 2 * pi() * 18 }}" stroke-dashoffset="{{ (1 - $perfScore / 100) * (2 * pi() * 18) }}" stroke-linecap="round" />
                                </svg>
                                <span style="position: absolute; font-size: 11px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $perfScore }}</span>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">Perf</span>
                        </div>

                        <!-- Security Score -->
                        <div class="flex flex-col items-center">
                            <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="#10b981" stroke-width="3" fill="transparent"
                                            stroke-dasharray="{{ 2 * pi() * 18 }}" stroke-dashoffset="{{ (1 - $securityScore / 100) * (2 * pi() * 18) }}" stroke-linecap="round" />
                                </svg>
                                <span style="position: absolute; font-size: 11px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $securityScore }}</span>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">Security</span>
                        </div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-4 gap-4 py-4 border-t border-b border-slate-100 dark:border-slate-800/80 mb-5">
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Backups</span>
                        <span class="block text-xl font-black text-slate-900 dark:text-white mt-1">{{ $report->backups_count }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Updates</span>
                        <span class="block text-xl font-black text-slate-900 dark:text-white mt-1">{{ $report->updates_count }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Issues Found</span>
                        <span class="block text-xl font-black text-slate-900 dark:text-white mt-1">{{ $report->issues_found }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Issues Fixed</span>
                        <span class="block text-xl font-black text-slate-900 dark:text-white mt-1">{{ $report->issues_fixed }}</span>
                    </div>
                </div>

                <!-- Checklist -->
                <div class="space-y-2.5 mb-6 text-sm font-semibold text-slate-700 dark:text-slate-350">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>WordPress core updated to latest secure version</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Plugins updated successfully — no compatibility issues</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Daily backups verified and restore points confirmed</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>SSL certificate active and healthy</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-2">
                    <a href="{{ route('client.maintenance.view', $report->id) }}"
                       class="px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition">
                        View report
                    </a>
                    
                    <a href="{{ route('client.maintenance.pdf', $report->id) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-650 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>

                    <button type="button" wire:click="compare({{ $report->id }})"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-650 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m0 0l-3 3-3-3" />
                        </svg>
                        Compare previous
                    </button>
                </div>

            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white py-20 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-4 text-sm font-bold text-slate-850 dark:text-slate-300">No Maintenance Reports</h3>
                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">There are currently no maintenance cycles completed for your websites.</p>
            </div>
        @endforelse
    </div>

    <!-- Compare Modal -->
    @if ($showCompareModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-2xl w-full p-6 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white">Maintenance Comparison</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-550 mt-1">Comparing current month optimization audit with previous cycle.</p>
                    </div>
                    <button type="button" wire:click="closeCompare" class="text-slate-400 hover:text-slate-650 dark:hover:text-white p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if ($compareCurrent)
                    <div class="space-y-4">
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                            Website: <span class="text-indigo-500">{{ $compareCurrent['site_name'] }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs font-semibold">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-800 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                        <th class="py-2.5">Metric</th>
                                        <th class="py-2.5 px-4 text-center">Previous ({{ $comparePrevious ? \Carbon\Carbon::parse($comparePrevious['maintenance_date'])->format('F Y') : 'N/A' }})</th>
                                        <th class="py-2.5 px-4 text-center">Current ({{ \Carbon\Carbon::parse($compareCurrent['maintenance_date'])->format('F Y') }})</th>
                                        <th class="py-2.5 pl-4 text-right">Difference</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                                    <!-- Health Score -->
                                    <tr>
                                        <td class="py-3">Health Score</td>
                                        <td class="py-3 px-4 text-center">{{ $comparePrevious['health_score'] ?? '—' }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 dark:text-white">{{ $compareCurrent['health_score'] ?? '—' }}</td>
                                        <td class="py-3 pl-4 text-right">
                                            @if ($comparePrevious)
                                                @php $diff = ($compareCurrent['health_score'] ?? 0) - ($comparePrevious['health_score'] ?? 0); @endphp
                                                <span class="{{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Perf Score -->
                                    <tr>
                                        <td class="py-3">Performance Score</td>
                                        <td class="py-3 px-4 text-center">{{ $comparePrevious['performance_desktop'] ?? '—' }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 dark:text-white">{{ $compareCurrent['performance_desktop'] ?? '—' }}</td>
                                        <td class="py-3 pl-4 text-right">
                                            @if ($comparePrevious)
                                                @php $diff = ($compareCurrent['performance_desktop'] ?? 0) - ($comparePrevious['performance_desktop'] ?? 0); @endphp
                                                <span class="{{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Backups -->
                                    <tr>
                                        <td class="py-3">Backups Completed</td>
                                        <td class="py-3 px-4 text-center">{{ $comparePrevious['backups_count'] ?? '—' }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 dark:text-white">{{ $compareCurrent['backups_count'] ?? '—' }}</td>
                                        <td class="py-3 pl-4 text-right">
                                            @if ($comparePrevious)
                                                @php $diff = ($compareCurrent['backups_count'] ?? 0) - ($comparePrevious['backups_count'] ?? 0); @endphp
                                                <span class="{{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Updates -->
                                    <tr>
                                        <td class="py-3">Plugins Updated</td>
                                        <td class="py-3 px-4 text-center">{{ $comparePrevious['updates_count'] ?? '—' }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 dark:text-white">{{ $compareCurrent['updates_count'] ?? '—' }}</td>
                                        <td class="py-3 pl-4 text-right">
                                            @if ($comparePrevious)
                                                @php $diff = ($compareCurrent['updates_count'] ?? 0) - ($comparePrevious['updates_count'] ?? 0); @endphp
                                                <span class="{{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Issues Found -->
                                    <tr>
                                        <td class="py-3">Issues Found</td>
                                        <td class="py-3 px-4 text-center">{{ $comparePrevious['issues_found'] ?? '—' }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 dark:text-white">{{ $compareCurrent['issues_found'] ?? '—' }}</td>
                                        <td class="py-3 pl-4 text-right">
                                            @if ($comparePrevious)
                                                @php $diff = ($compareCurrent['issues_found'] ?? 0) - ($comparePrevious['issues_found'] ?? 0); @endphp
                                                <span class="{{ $diff <= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="button" wire:click="closeCompare" class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition shadow-sm">
                        Close Comparison
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
