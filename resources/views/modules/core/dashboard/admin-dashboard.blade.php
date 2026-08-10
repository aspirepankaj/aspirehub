@section('page_title', 'Dashboard')

<div>
    <!-- Header with breadcrumbs -->
    <x-admin.breadcrumbs :items="['Dashboard' => null]" />

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Clients card -->
        <x-admin.card class="relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-extrabold tracking-wider uppercase text-slate-400 dark:text-slate-500">Total Clients</span>
                <div class="p-2 bg-indigo-500/10 text-indigo-500 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ number_format($stats['total_clients']) }}</h2>
            <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/50 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                <div>
                    <span class="block text-emerald-500">{{ number_format($stats['active_clients']) }}</span>
                    Active
                </div>
                <div>
                    <span class="block text-slate-500">{{ number_format($stats['inactive_clients']) }}</span>
                    Inactive
                </div>
                <div>
                    <span class="block text-indigo-500">+{{ number_format($stats['new_clients']) }}</span>
                    New
                </div>
            </div>
        </x-admin.card>

        <!-- Staff Card -->
        <x-admin.card class="relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-pink-500/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-extrabold tracking-wider uppercase text-slate-400 dark:text-slate-500">Total Staff</span>
                <div class="p-2 bg-pink-500/10 text-pink-500 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $stats['total_staff'] }}</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Active portal users</p>
        </x-admin.card>

        <!-- Websites Card -->
        <x-admin.card class="relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-500/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-extrabold tracking-wider uppercase text-slate-400 dark:text-slate-500">Monitored Websites</span>
                <div class="p-2 bg-sky-500/10 text-sky-500 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $stats['total_websites'] }}</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">All domains online</p>
        </x-admin.card>

        <!-- Revenue Card -->
        <x-admin.card class="relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-extrabold tracking-wider uppercase text-slate-400 dark:text-slate-500">Monthly Revenue</span>
                <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">${{ number_format($stats['monthly_revenue']) }}</h2>
            <p class="text-xs text-emerald-500 mt-2 font-semibold flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                +12.4% from last month
            </p>
        </x-admin.card>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Growth Card -->
        <x-admin.card class="lg:col-span-2" title="Revenue History" subtitle="SaaS platform revenue metrics for the current calendar year">
            <x-admin.chart type="line" />
        </x-admin.card>

        <!-- Growth breakdown bar chart -->
        <x-admin.card title="Monthly Sales Pipeline" subtitle="Detailed sales trends by month">
            <x-admin.chart type="bar" :data="$revenueData" />
        </x-admin.card>
    </div>

    <!-- Tables and Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Clients -->
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Recent Client Signups" subtitle="Listing the latest registered enterprise clients">
                <x-admin.table :headers="['Client Name', 'Plan', 'Status', 'Registered']">
                    @foreach($recentClients as $client)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $client['name'] }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $client['email'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ $client['plan'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg 
                                      {{ $client['status'] === 'Active' 
                                          ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                          : 'bg-slate-500/10 text-slate-500' }}">
                                    {{ $client['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                {{ $client['date'] }}
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{-- View All Link --}}
                <div class="mt-5 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                    <a href="{{ route('admin.clients') }}" wire:navigate
                       class="flex items-center justify-center gap-2 w-full py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors duration-150">
                        View All Clients
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </x-admin.card>
        </div>

        <!-- Right Side: Activities & Notifications -->
        <div class="space-y-6">
            <!-- Recent Notifications -->
            <x-admin.card title="System Alerts" subtitle="Recent automated platform notices">
                <div class="space-y-3.5 mt-2">
                    @foreach($recentNotifications as $notif)
                        <div class="flex items-start space-x-3 p-3 rounded-xl border border-slate-200/40 dark:border-slate-800/30 bg-slate-50/40 dark:bg-slate-900/10">
                            <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full mt-1.5 
                                  {{ $notif['type'] === 'warning' ? 'bg-amber-500' : '' }}
                                  {{ $notif['type'] === 'info' ? 'bg-indigo-500' : '' }}
                                  {{ $notif['type'] === 'success' ? 'bg-emerald-500' : '' }}
                                  {{ $notif['type'] === 'danger' ? 'bg-red-500' : '' }}
                            "></span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 leading-normal">{{ $notif['title'] }}</p>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block font-medium">{{ $notif['time'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin.card>

            <!-- Recent Activity Stream -->
            <x-admin.card title="Recent Activity" subtitle="Real-time log of administrative events">
                <div class="relative pl-4 space-y-5 border-l border-slate-200 dark:border-slate-800/60 mt-4 ml-2">
                    @foreach($recentActivities as $act)
                        <div class="relative">
                            <span class="absolute -left-[20.5px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-slate-950 bg-indigo-500"></span>
                            <div>
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-normal">{{ $act['description'] }}</p>
                                <div class="flex items-center space-x-1.5 mt-1 text-[10px] font-medium text-slate-400 dark:text-slate-500">
                                    <span class="text-indigo-500 dark:text-indigo-400 font-semibold">{{ $act['user'] }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $act['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Link --}}
                <div class="mt-5 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                    <a href="{{ route('admin.activity-logs') }}" wire:navigate
                       class="flex items-center justify-center gap-2 w-full py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors duration-150">
                        View All Activity
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </x-admin.card>
        </div>
    </div>
</div>
