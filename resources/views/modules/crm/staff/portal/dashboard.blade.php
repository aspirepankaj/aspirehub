@section('page_title', 'Dashboard')

<div class="space-y-6">
    {{-- Welcome Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 p-6 sm:p-8 text-white shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-100 bg-white/10 px-3 py-1 rounded-full">Staff Access Area</span>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight mt-1">Hello, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-indigo-100/90 max-w-xl font-medium">Welcome back to your workspace. Here is a summary of your assigned clients, monitored sites, and recent maintenance progress.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        {{-- Stat 1 --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 shadow-sm backdrop-blur-md flex items-center justify-between hover:-translate-y-0.5 transition-all duration-200">
            <div>
                <span class="text-[10px] font-extrabold text-slate-450 dark:text-slate-500 uppercase tracking-widest">Assigned Clients</span>
                <h4 class="text-2xl font-black text-slate-850 dark:text-white mt-1">{{ $clientsCount }}</h4>
            </div>
            <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- Stat 2 --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 shadow-sm backdrop-blur-md flex items-center justify-between hover:-translate-y-0.5 transition-all duration-200">
            <div>
                <span class="text-[10px] font-extrabold text-slate-450 dark:text-slate-500 uppercase tracking-widest">Websites Monitored</span>
                <h4 class="text-2xl font-black text-slate-850 dark:text-white mt-1">{{ $websitesCount }}</h4>
            </div>
            <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
        </div>

        {{-- Stat 3 --}}
        <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-6 shadow-sm backdrop-blur-md flex items-center justify-between hover:-translate-y-0.5 transition-all duration-200">
            <div>
                <span class="text-[10px] font-extrabold text-slate-450 dark:text-slate-500 uppercase tracking-widest">Maintenance Logs</span>
                <h4 class="text-2xl font-black text-slate-850 dark:text-white mt-1">{{ $reportsCount }}</h4>
            </div>
            <div class="p-3 rounded-xl bg-purple-500/10 text-purple-650 dark:text-purple-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Main Contents Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Reports --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl overflow-hidden shadow-sm backdrop-blur-md">
                <div class="border-b border-slate-200/50 dark:border-slate-800/40 p-5 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Recent Maintenance Cycles</h3>
                    <a href="{{ route('staff.maintenance') }}" class="text-xs font-bold text-indigo-650 dark:text-indigo-400 hover:underline">View All</a>
                </div>

                @if($recentReports->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-500 text-xs">No maintenance reports logged yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-3">Client / Site</th>
                                    <th class="px-6 py-3">Period</th>
                                    <th class="px-6 py-3">Health Score</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-xs font-medium">
                                @foreach($recentReports as $rep)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $rep->client->company_name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $rep->website->site_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $rep->maintenance_month }}</td>
                                        <td class="px-6 py-4 font-bold text-slate-850 dark:text-slate-300">{{ $rep->health_score }}%</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $rep->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-amber-500/10 text-amber-600' }}">
                                                {{ $rep->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="space-y-4">
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-5 shadow-sm backdrop-blur-md">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-4">Assigned Activity Logs</h3>
                
                @if($recentActivities->isEmpty())
                    <div class="text-center py-8 text-slate-500 dark:text-slate-500 text-xs">No client logs reported recently.</div>
                @else
                    <ul class="space-y-4">
                        @foreach($recentActivities as $act)
                            <li class="flex items-start space-x-3 text-xs">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                                <div class="flex-1">
                                    <p class="text-slate-650 dark:text-slate-350 leading-relaxed font-semibold">{{ $act->description }}</p>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-550 block mt-1">{{ $act->created_at->diffForHumans() }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
