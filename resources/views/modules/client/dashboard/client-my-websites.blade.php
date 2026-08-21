<div>
    <x-admin.breadcrumbs
        :items="[
            'My Websites' => null,
        ]"
    />
    <div class="mb-6 sm:mb-8">

        <div class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">
            My Websites
        </div>

        <h1 class="mt-2 text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white">
            All your websites.
        </h1>

        <p class="mt-2 sm:mt-3 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            All websites associated with your account.
        </p>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

        @forelse($websites as $website)

            @php
                $health = min((int)($website->health_score ?? 0), 100);
                $performance = min((int)($website->performance_desktop ?? 0), 100);
                
                $securityText = $website->security_health ?: 'Excellent';
                $securityScore = 100;
                $securityColor = 'bg-emerald-500';
                
                if (strcasecmp($securityText, 'excellent') === 0 || strcasecmp($securityText, 'clean') === 0) {
                    $securityScore = 95;
                    $securityColor = 'bg-emerald-500';
                    $securityText = 'Excellent';
                } elseif (strcasecmp($securityText, 'good') === 0) {
                    $securityScore = 75;
                    $securityColor = 'bg-blue-500';
                    $securityText = 'Good';
                } elseif (strcasecmp($securityText, 'action required') === 0) {
                    $securityScore = 50;
                    $securityColor = 'bg-amber-500';
                    $securityText = 'Action Required';
                } elseif (strcasecmp($securityText, 'critical') === 0) {
                    $securityScore = 25;
                    $securityColor = 'bg-rose-500';
                    $securityText = 'Critical';
                } elseif (is_numeric($securityText)) {
                    $securityScore = min((int)$securityText, 100);
                    if ($securityScore >= 90) { $securityText = 'Excellent'; $securityColor = 'bg-emerald-500'; }
                    elseif ($securityScore >= 75) { $securityText = 'Good'; $securityColor = 'bg-blue-500'; }
                    elseif ($securityScore >= 50) { $securityText = 'Action Required'; $securityColor = 'bg-amber-500'; }
                    else { $securityText = 'Critical'; $securityColor = 'bg-rose-500'; }
                }

                $status = $website->maintenance_status ?: 'Operational';
            @endphp

            <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                {{-- Image --}}
                <div class="relative h-48 sm:h-64 overflow-hidden bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center">

                    <img
                        src="{{ $website->image ? asset('storage/' . $website->image) : asset('default-website.svg') }}"
                        alt="{{ $website->site_name }}"
                        class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">

                    <span class="absolute left-3 top-3 sm:left-4 sm:top-4 z-20 rounded-lg bg-emerald-100/95 dark:bg-emerald-950/90 px-2.5 py-1 text-[11px] sm:text-xs font-semibold text-emerald-700 dark:text-emerald-300 backdrop-blur">

                        ● {{ ucfirst($status) }}

                    </span>

                    @if(!empty($website->url))

                        <a
                            href="{{ $website->url }}"
                            target="_blank"
                            class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 z-20 rounded-lg bg-white/95 dark:bg-slate-900/90 text-slate-800 dark:text-slate-100 px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-medium shadow transition hover:bg-white dark:hover:bg-slate-800">

                            Visit

                        </a>

                    @endif

                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6">

                    <h3 class="text-xl sm:text-3xl font-bold text-slate-900 dark:text-white truncate">

                        {{ $website->site_name }}

                    </h3>

                    <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400 truncate">

                        {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}

                    </p>

                    {{-- Health --}}
                    <div class="mt-6 sm:mt-8 space-y-4 sm:space-y-5">

                        <div>

                            <div class="mb-1.5 sm:mb-2 flex justify-between text-[10px] sm:text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">

                                <span>Health</span>

                                <span>{{ $health }}%</span>

                            </div>

                            <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800">

                                <div
                                    class="h-2 rounded-full {{ $health >= 90 ? 'bg-emerald-500' : ($health >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                    style="width: {{ $health }}%">
                                </div>

                            </div>

                        </div>

                        {{-- Performance --}}
                        <div>

                            <div class="mb-1.5 sm:mb-2 flex justify-between text-[10px] sm:text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">

                                <span>Performance</span>

                                <span>{{ $performance }}%</span>

                            </div>

                            <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800">

                                <div
                                    class="h-2 rounded-full {{ $performance >= 90 ? 'bg-emerald-500' : ($performance >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                    style="width: {{ $performance }}%">
                                </div>

                            </div>

                        </div>

                        {{-- Security --}}
                        <div>

                            <div class="mb-1.5 sm:mb-2 flex justify-between text-[10px] sm:text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">

                                <span>Security</span>

                                <span>{{ $securityScore }}%</span>

                            </div>

                            <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800">

                                <div
                                    class="h-2 rounded-full {{ $securityColor }}"
                                    style="width: {{ $securityScore }}%">
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="mt-6 sm:mt-8 flex justify-between border-t border-slate-200 dark:border-slate-800/60 pt-4 sm:pt-5 gap-2">

                        <div class="min-w-0">

                            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 truncate">

                                Maintenance Cycle

                            </div>

                            <div class="mt-1 sm:mt-2 text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">

                                {{ $website->maintenance_month ?: '-' }}

                            </div>

                        </div>

                        <div class="text-right min-w-0">

                            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 truncate">

                                Account Manager

                            </div>

                            <div class="mt-1 sm:mt-2 text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">

                                {{ $assignedStaffName ?? 'Not Assigned' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/40 py-16 sm:py-20 text-center">

                <svg
                    class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M3 7h18M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7"/>

                </svg>

                <h3 class="mt-4 text-lg font-bold text-slate-800 dark:text-slate-200">

                    No Websites Found

                </h3>

                <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">

                    There are currently no websites assigned to your account.

                </p>

            </div>

        @endforelse

    </div>

</div>