<div>
    <x-admin.breadcrumbs
        :items="[
            'Marketing Reports' => null,
        ]"
    />



    <div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4 relative overflow-hidden">
        <!-- Decorative subtle background shapes -->
        <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-72 h-72 bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl mx-auto">
            <!-- Icon/Illustration -->
            <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 text-indigo-600 dark:text-indigo-400 shadow-sm animate-pulse">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>

            <!-- Coming Soon Title -->
            <div class="text-[11px] font-extrabold uppercase tracking-[0.25em] text-indigo-600 dark:text-indigo-400 mb-3">
                Feature Development
            </div>
            
            <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-slate-900 dark:text-white mb-4">
                Marketing Reports
            </h1>

            <p class="text-slate-500 dark:text-slate-400 text-base font-semibold max-w-md mx-auto leading-relaxed mb-8">
                We are actively working on building comprehensive marketing performance reports. Soon, you will be able to track your search performance, campaign statistics, and SEO health directly from this tab.
            </p>

            <!-- Status Indicator -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200/40 dark:border-slate-700/40 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                <span>Coming Soon</span>
            </div>

            <!-- Back to Dashboard button -->
            <div class="mt-8">
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-extrabold hover:bg-slate-800 dark:hover:bg-slate-100 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
