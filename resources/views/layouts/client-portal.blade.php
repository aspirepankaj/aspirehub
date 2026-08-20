<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('client-theme') === 'dark' }" x-init="$watch('dark', v => localStorage.setItem('client-theme', v ? 'dark' : 'light'))" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Client Portal' }} - Aspire Hub</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.93);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            border: 1px solid rgba(255, 255, 255, 0.43);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 25%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">

<!-- Background decorative blobs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-pink-500/10 dark:bg-pink-500/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-sky-500/10 dark:bg-sky-500/5 rounded-full blur-3xl"></div>
</div>

<div class="relative min-h-screen flex z-10">

    <!-- Sidebar -->
    <aside class="w-64 shrink-0 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800/60 flex flex-col justify-between">
        <div>
            <div class="px-6 py-6 flex flex-col gap-1">
                <img src="{{ asset('aspire-hub-1.svg') }}" class="h-8 w-auto self-start" alt="Aspire Hub" />
                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 tracking-widest uppercase pl-1 mt-0.5">Client Portal</div>
            </div>

            <div class="px-4 mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
                    <input type="text" placeholder="Quick search..." class="w-full pl-9 pr-10 py-2 rounded-lg bg-slate-50 dark:bg-slate-850 border border-slate-200/60 dark:border-slate-800 text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-[10px] font-bold text-slate-400 border-l border-slate-200 dark:border-slate-800 pl-2">⌘K</span>
                </div>
            </div>

            <div class="px-6 mb-2 text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Portal</div>

            <nav class="px-3 space-y-1">
                @php
                    $navItems = [
                        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['label' => 'My Websites', 'route' => 'client.websites', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Marketing Reports', 'route' => 'client.marketing', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                        ['label' => 'Maintenance Reports', 'route' => 'client.maintenance', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ['label' => 'Support Center', 'route' => 'client.support', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Resources', 'route' => 'client.documents', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['label' => 'My Profile', 'route' => 'client.profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                    $currentRoute = request()->route()?->getName();
                @endphp

                @foreach($navItems as $item)
                    @php
                        $route = $item['route'];
                        $isActive = $route && (
                            $currentRoute === $route ||
                            str_starts_with($currentRoute ?? '', $route . '.') ||
                            str_starts_with($currentRoute ?? '', $route . '-')
                        );
                        $href = $route ? route($route) : '#';
                    @endphp
                    <a href="{{ $href }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition
                              {{ $isActive
                                    ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-extrabold shadow-sm shadow-indigo-500/10'
                                    : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- User footer -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800/60">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-xs text-slate-600 dark:text-slate-300 overflow-hidden shrink-0 border border-slate-200/60 dark:border-slate-800">
                    @if(auth()->user()->client?->profile_image)
                        <img src="{{ Str::startsWith(auth()->user()->client->profile_image, 'http') ? auth()->user()->client->profile_image : asset('storage/' . auth()->user()->client->profile_image) }}" class="w-full h-full object-cover" onerror="this.outerHTML=`{{ auth()->user()->getInitials() }}`" />
                    @else
                        {{ auth()->user()->getInitials() }}
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Guest' }}</div>
                    <div class="text-[11px] text-slate-400 dark:text-slate-500 truncate">{{ $clientCompanyName ?? '' }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="dark = !dark" class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <span x-text="dark ? 'Light' : 'Dark'"></span>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1 flex">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-2 py-1.5 rounded-lg bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-500/20 dark:hover:text-red-300 text-[11px] font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 sticky top-0 z-50 flex items-center justify-between px-8 border-b border-slate-100 dark:border-slate-800/60 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl">
            <div class="text-sm font-semibold text-slate-400 dark:text-slate-500">{{ now()->format('l, F j') }}</div>
            <div class="flex items-center gap-3">
                <livewire:header-notifications />
                <a href="{{ route('client.support') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800/60 transition">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Support
                </a>
            </div>
        </header>

        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
