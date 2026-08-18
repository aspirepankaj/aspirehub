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

    <!-- Header Actions (Tabs + Filter) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-200 dark:border-slate-800/60 pb-3 pt-2">
        <!-- Tabs -->
        <div class="flex items-center gap-6">
            <button type="button" wire:click="$set('viewMode', 'recent')" 
                    class="pb-1 text-sm font-bold transition-all relative {{ $viewMode === 'recent' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                Recent Reports
                @if($viewMode === 'recent')
                    <div class="absolute -bottom-3.5 left-0 right-0 h-0.5 bg-indigo-600 dark:bg-indigo-400 rounded-t-full"></div>
                @endif
            </button>
            <button type="button" wire:click="$set('viewMode', 'archive')" 
                    class="pb-1 text-sm font-bold transition-all relative flex items-center gap-2 {{ $viewMode === 'archive' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                Archive
                @if($viewMode === 'archive')
                    <div class="absolute -bottom-3.5 left-0 right-0 h-0.5 bg-indigo-600 dark:bg-indigo-400 rounded-t-full"></div>
                @endif
            </button>
        </div>

        <!-- Filter -->
        @if($viewMode === 'archive')
        <div class="flex items-center">
            <select wire:model.live="filterMonth" 
                    class="block w-full sm:w-48 rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                <option value="">All Months</option>
                @foreach($availableMonths as $month)
                    <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($paginatedReports as $report)
            @php
                $formattedMonth = \Carbon\Carbon::parse($report->maintenance_date)->format('F Y');
                $formattedDate = \Carbon\Carbon::parse($report->maintenance_date)->format('M j, Y');
                $healthScore = $report->health_score ?? 100;
                $perfScore = $report->performance_desktop ?? 100;
                $securityText = $report->security_health ?: 'Excellent';
                $securityScore = 100;
                if (strcasecmp($securityText, 'excellent') === 0 || strcasecmp($securityText, 'clean') === 0) {
                    $securityScore = 95;
                } elseif (strcasecmp($securityText, 'good') === 0) {
                    $securityScore = 75;
                } elseif (strcasecmp($securityText, 'action required') === 0) {
                    $securityScore = 50;
                } elseif (strcasecmp($securityText, 'critical') === 0) {
                    $securityScore = 25;
                } elseif (is_numeric($securityText)) {
                    $securityScore = min((int)$securityText, 100);
                }
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
                                <svg width="44" height="44" viewBox="0 0 44 44" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="{{ $healthScore >= 90 ? '#10b981' : ($healthScore >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="3" fill="transparent"
                                            stroke-dasharray="{{ 2 * pi() * 18 }}" stroke-dashoffset="{{ (1 - $healthScore / 100) * (2 * pi() * 18) }}" stroke-linecap="round" />
                                </svg>
                                <span style="position: absolute; font-size: 11px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $healthScore }}</span>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">Health</span>
                        </div>

                        <!-- Perf Score -->
                        <div class="flex flex-col items-center">
                            <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <svg width="44" height="44" viewBox="0 0 44 44" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="{{ $perfScore >= 90 ? '#10b981' : ($perfScore >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="3" fill="transparent"
                                            stroke-dasharray="{{ 2 * pi() * 18 }}" stroke-dashoffset="{{ (1 - $perfScore / 100) * (2 * pi() * 18) }}" stroke-linecap="round" />
                                </svg>
                                <span style="position: absolute; font-size: 11px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $perfScore }}</span>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">Perf</span>
                        </div>

                        <!-- Security Score -->
                        <div class="flex flex-col items-center">
                            <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <svg width="44" height="44" viewBox="0 0 44 44" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                    <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="transparent" class="dark:stroke-slate-850" />
                                    <circle cx="22" cy="22" r="18" stroke="{{ $securityScore >= 90 ? '#10b981' : ($securityScore >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="3" fill="transparent"
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
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full {{ $report->wp_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-500/10 text-slate-500' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>WordPress Core: {{ $report->wp_updated ? 'Updated to latest secure version' : 'Version ' . ($report->wp_version_current ?? 'up to date') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full {{ $report->updates_count > 0 ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-500/10 text-slate-500' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Plugins: {{ $report->updates_count > 0 ? $report->updates_count . ' plugins updated successfully' : 'All plugins up to date' }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full {{ $report->security_ssl_status === 'Active' || $report->security_ssl_status === 'active' || $report->security_ssl_status === 'Enabled' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-500/10 text-slate-500' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>SSL Certificate: {{ $report->security_ssl_status ?: 'Active and healthy' }}</span>
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
                <h3 class="mt-4 text-sm font-bold text-slate-850 dark:text-slate-300">
                    {{ $viewMode === 'recent' ? 'No Recent Reports' : 'No Archived Reports' }}
                </h3>
                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                    {{ $viewMode === 'recent' ? 'There are currently no maintenance cycles completed for your websites.' : 'Older maintenance reports will appear here.' }}
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($paginatedReports->hasPages())
        <div class="mt-8">
            {{ $paginatedReports->links() }}
        </div>
    @endif

    <!-- Compare Modal -->
    @if ($showCompareModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-4xl w-full p-6 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white">Compare with previous</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-550 mt-1">Comparing optimization metrics side by side.</p>
                    </div>
                    <button type="button" wire:click="closeCompare" class="text-slate-400 hover:text-slate-650 dark:hover:text-white p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if ($compareCurrent)
                    @php
                        $cHealth = $compareCurrent['health_score'] ?? 100;
                        $cPerf = $compareCurrent['performance_desktop'] ?? 100;
                        $cSec = is_numeric($compareCurrent['security_health'] ?? null) ? (int)$compareCurrent['security_health'] : 100;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-850">
                        
                        <!-- Current Cycle -->
                        <div class="space-y-6">
                            <div class="text-xs font-bold tracking-widest text-slate-400 uppercase border-b border-slate-200 dark:border-slate-850 pb-1 w-fit">
                                {{ strtoupper(\Carbon\Carbon::parse($compareCurrent['maintenance_date'])->format('F Y')) }}
                            </div>
                            
                            <div class="flex items-center gap-6">
                                <!-- Health Score -->
                                <div class="flex flex-col items-center">
                                    <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                            <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                            <circle cx="32" cy="32" r="26" stroke="{{ $cHealth >= 90 ? '#10b981' : ($cHealth >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                    stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $cHealth / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                        </svg>
                                        <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $cHealth }}</span>
                                    </div>
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Health</span>
                                </div>

                                <!-- Perf Score -->
                                <div class="flex flex-col items-center">
                                    <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                            <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                            <circle cx="32" cy="32" r="26" stroke="{{ $cPerf >= 90 ? '#10b981' : ($cPerf >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                    stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $cPerf / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                        </svg>
                                        <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $cPerf }}</span>
                                    </div>
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Perf</span>
                                </div>

                                <!-- Security Score -->
                                <div class="flex flex-col items-center">
                                    <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                            <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                            <circle cx="32" cy="32" r="26" stroke="{{ $cSec >= 90 ? '#10b981' : ($cSec >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                    stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $cSec / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                        </svg>
                                        <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $cSec }}</span>
                                    </div>
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Sec</span>
                                </div>
                            </div>

                            <div class="space-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-semibold pt-2">
                                <div>Updates: <span class="font-bold text-slate-850 dark:text-slate-200">{{ $compareCurrent['updates_count'] }}</span></div>
                            </div>
                        </div>

                        <!-- Previous Cycle -->
                        <div class="space-y-6 pt-6 md:pt-0 md:pl-8">
                            @if ($comparePrevious)
                                @php
                                    $pHealth = $comparePrevious['health_score'] ?? 100;
                                    $pPerformance = $comparePrevious['performance_desktop'] ?? 100;
                                    $pSec = is_numeric($comparePrevious['security_health'] ?? null) ? (int)$comparePrevious['security_health'] : 100;
                                @endphp
                                <div class="text-xs font-bold tracking-widest text-slate-400 uppercase border-b border-slate-200 dark:border-slate-850 pb-1 w-fit">
                                    {{ strtoupper(\Carbon\Carbon::parse($comparePrevious['maintenance_date'])->format('F Y')) }}
                                </div>
                                
                                <div class="flex items-center gap-6">
                                    <!-- Health Score -->
                                    <div class="flex flex-col items-center">
                                        <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                            <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                                <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                                <circle cx="32" cy="32" r="26" stroke="{{ $pHealth >= 90 ? '#10b981' : ($pHealth >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                        stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $pHealth / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                            </svg>
                                            <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $pHealth }}</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Health</span>
                                    </div>

                                    <!-- Perf Score -->
                                    <div class="flex flex-col items-center">
                                        <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                            <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                                <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                                <circle cx="32" cy="32" r="26" stroke="{{ $pPerformance >= 90 ? '#10b981' : ($pPerformance >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                        stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $pPerformance / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                            </svg>
                                            <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $pPerformance }}</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Perf</span>
                                    </div>

                                    <!-- Security Score -->
                                    <div class="flex flex-col items-center">
                                        <div style="position: relative; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                            <svg width="64" height="64" viewBox="0 0 64 64" style="position: absolute; top: 0; left: 0; transform: rotate(-90deg);">
                                                <circle cx="32" cy="32" r="26" stroke="#e2e8f0" stroke-width="4" fill="transparent" class="dark:stroke-slate-800" />
                                                <circle cx="32" cy="32" r="26" stroke="{{ $pSec >= 90 ? '#10b981' : ($pSec >= 50 ? '#f59e0b' : '#ef4444') }}" stroke-width="4" fill="transparent"
                                                        stroke-dasharray="{{ 2 * pi() * 26 }}" stroke-dashoffset="{{ (1 - $pSec / 100) * (2 * pi() * 26) }}" stroke-linecap="round" />
                                            </svg>
                                            <span style="position: absolute; font-size: 13px; font-weight: 800;" class="text-slate-900 dark:text-white">{{ $pSec }}</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Sec</span>
                                    </div>
                                </div>

                                <div class="space-y-1.5 text-xs text-slate-500 dark:text-slate-400 font-semibold pt-2">
                                    <div>Updates: <span class="font-bold text-slate-850 dark:text-slate-200">{{ $comparePrevious['updates_count'] }}</span></div>
                                </div>
                            @else
                                <div class="h-full flex items-center justify-center text-xs text-slate-400 italic">
                                    No previous cycle found to compare.
                                </div>
                            @endif
                        </div>

                    </div>
                @endif

                <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" wire:click="closeCompare" class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition shadow-sm">
                        Close Comparison
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
