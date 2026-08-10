<style>
    .glass {
        background: rgb(255 255 255);
    }
</style>
<aside :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 glass border-r border-slate-200/50 dark:border-slate-800/50 z-40 transition-transform duration-300 lg:translate-x-0 flex flex-col">
    <!-- Brand / Title -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200/50 dark:border-slate-800/50">
        <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-2.5">
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
                ['route' => 'staff.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'staff.clients', 'label' => 'My Clients', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['route' => 'staff.websites', 'label' => 'Websites', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                ['route' => 'staff.maintenance', 'label' => 'Maintenance Reports', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'staff.documents', 'label' => 'Documents Library', 'icon' => 'M8 7v12m0 0l-4-4m4 4l4-4m0 6h8a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2v10a2 2 0 00-2 2z'],
                ['route' => 'staff.profile', 'label' => 'My Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']) || (request()->is('staffadspnl/' . strtolower(explode(' ', $item['label'])[0]) . '*'));
                if ($item['route'] === 'staff.dashboard') {
                    $isActive = request()->fullUrl() === route('staff.dashboard');
                }
            @endphp
            <a href="{{ route($item['route']) }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
               {{ $isActive 
                   ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-4 border-indigo-500 pl-3 shadow-sm' 
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
