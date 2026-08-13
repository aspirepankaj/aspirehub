<header class="h-16 glass border-b border-slate-200/50 dark:border-slate-800/50 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center space-x-3">
        <!-- Mobile Sidebar Trigger -->
        <button @click="mobileSidebar = true" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Current Area Title / Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm font-semibold text-slate-500 dark:text-slate-400">
            <span>Admin</span>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-800 dark:text-slate-200 font-bold">@yield('page_title', 'Dashboard')</span>
        </div>
    </div>

    <!-- Right Controls -->
    <div class="flex items-center space-x-3.5">
        <!-- Theme Toggle -->
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100/60 dark:hover:bg-slate-900/40 border border-transparent hover:border-slate-200/30 dark:hover:border-slate-800/30 transition-all duration-200" title="Toggle Theme">
            <!-- Sun Icon -->
            <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828 0l-.707-.707m2.828-11.314l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
            </svg>
            <!-- Moon Icon -->
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <!-- Notification Bell placeholder -->
        <div class="relative">
            <button class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100/60 dark:hover:bg-slate-900/40 border border-transparent hover:border-slate-200/30 dark:hover:border-slate-800/30 transition-all duration-200" title="View Notifications">
                <span class="absolute top-2 right-2 w-2 h-2 bg-pink-500 rounded-full"></span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
        </div>

        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800"></div>

        <!-- Administrator Indicator Badge -->
        <div class="flex items-center space-x-2.5">
            <span class="hidden sm:inline-block px-2.5 py-1 text-[10px] font-extrabold tracking-wider uppercase bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg">
                Administrator
            </span>
            @if(auth()->user()->admin?->profile_image)
                <img src="{{ Str::startsWith(auth()->user()->admin->profile_image, 'http') ? auth()->user()->admin->profile_image : asset('storage/' . auth()->user()->admin->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-sm" onerror="this.outerHTML=`<div class='w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-600 dark:text-slate-300'>{{ auth()->user()->getInitials() }}</div>`" />
            @else
                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-600 dark:text-slate-300">
                    {{ auth()->user()->getInitials() }}
                </div>
            @endif
        </div>
    </div>
</header>
