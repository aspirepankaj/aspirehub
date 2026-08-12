<style>
    .glass {
    background: rgb(255 255 255);
}
</style>
<aside :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 glass border-r border-slate-200/50 dark:border-slate-800/50 z-40 transition-transform duration-300 lg:translate-x-0 flex flex-col">
    <!-- Brand / Title -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200/50 dark:border-slate-800/50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white font-extrabold text-lg shadow-md shadow-indigo-500/10">
                A
            </div>
            <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                Aspire Hub
            </span>
        </a>
        <button @click="mobileSidebar = false" class="lg:hidden p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation links -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar">
        @php
            $navItems = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'admin.clients', 'label' => 'Clients', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['route' => 'admin.staff', 'label' => 'Staff', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['route' => 'admin.staff.designations', 'label' => 'Designations', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'admin.websites', 'label' => 'Websites', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                ['route' => 'admin.websites.service-types', 'label' => 'Service Types', 'icon' => 'M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zm10-10V7a3 3 0 00-3-3h-2a3 3 0 00-3 3v3H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-3z'],
                ['route' => 'admin.clients.plans', 'label' => 'Plans', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['route' => 'admin.marketing', 'label' => 'Marketing Reports', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z'],
                ['route' => 'admin.maintenance', 'label' => 'Maintenance Reports', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.support', 'label' => 'Support Center', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                ['route' => 'admin.integrations', 'label' => 'Integrations', 'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'],
                ['route' => 'admin.analytics', 'label' => 'Analytics', 'icon' => 'M16 8v8m-4-5v5m-4-2v2M8 21.75a2 2 0 002-2v-1.5h4v1.5a2 2 0 002 2h3a2 2 0 002-2v-11a2 2 0 00-2-2h-3a2 2 0 00-2 2v1.5h-4v-1.5a2 2 0 00-2-2H8a2 2 0 00-2 2v11a2 2 0 002 2h2z'],
                ['route' => 'admin.revenue', 'label' => 'Revenue', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'admin.notifications', 'label' => 'Notifications', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ['route' => 'admin.documents', 'label' => 'Documents', 'icon' => 'M8 7v12m0 0l-4-4m4 4l4-4m0 6h8a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2v10a2 2 0 00-2 2z'],
                ['route' => 'admin.automation', 'label' => 'Automation', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['route' => 'admin.activity-logs', 'label' => 'Activity Logs', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['route' => 'admin.email-logs', 'label' => 'Email Logs', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['route' => 'admin.profile', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['route' => 'admin.settings', 'label' => 'Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']);
            @endphp
            <a href="{{ route($item['route']) }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
               {{ $isActive 
                   ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-4 border-indigo-500 pl-3 shadow-sm' 
                   : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/60 dark:hover:bg-slate-900/40 hover:text-slate-900 dark:hover:text-slate-100' }}">
                
                <!-- SVG Icon -->
                <svg class="w-5 h-5 transition-colors duration-200
                     {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>

                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    <!-- User / Logout Area -->
    <div class="p-4 border-t border-slate-200/50 dark:border-slate-800/50">
        <livewire:layout.navigation-logout />
    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="mobileSidebar" x-cloak @click="mobileSidebar = false" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-30 lg:hidden" x-transition.opacity></div>
