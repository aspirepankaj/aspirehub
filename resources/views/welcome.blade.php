<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Aspire Hub - Custom Enterprise SaaS Platform</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }
            .dark class.glass {
                background: rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.55);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.25);
            }
            .dark .glass-card {
                background: rgba(30, 41, 59, 0.4);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300">
        
        <!-- Glowing background blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] bg-pink-500/10 dark:bg-pink-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative min-h-screen flex flex-col z-10">
            <!-- Navigation Header -->
            <header class="sticky top-0 z-50 glass border-b border-slate-200/40 dark:border-slate-800/40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <!-- Brand -->
                    <a href="/" class="flex items-center space-x-2.5">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white font-extrabold text-lg shadow-md shadow-indigo-500/10">
                            A
                        </div>
                        <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                            Aspire Hub
                        </span>
                    </a>

                    <!-- Center Navigation Links -->
                    <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600 dark:text-slate-400">
                        <a href="#features" class="hover:text-slate-900 dark:hover:text-white transition-colors">Features</a>
                        <a href="#pricing" class="hover:text-slate-900 dark:hover:text-white transition-colors">Pricing</a>
                        <a href="#system" class="hover:text-slate-900 dark:hover:text-white transition-colors">Status</a>
                    </nav>

                    <!-- Right Controls / CTAs -->
                    <div class="flex items-center space-x-4">
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

                        <!-- Portal Button -->
                        @if (Route::has('admin.login'))
                            @auth
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-500/10 transition-all duration-150">
                                    Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('admin.login') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 text-xs font-bold rounded-xl active:scale-95 transition-all duration-150">
                                    Admin Sign In
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            <!-- Hero Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 text-center relative">
                <!-- Decorative background radial blob -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative max-w-4xl mx-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-500/10 rounded-full dark:text-indigo-400 mb-6">
                        Custom Enterprise Platform
                    </span>
                    <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-[1.1] text-slate-900 dark:text-white">
                        Empowering Enterprise Operations, 
                        <span class="bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent dark:from-indigo-400 dark:to-pink-400">Elevated.</span>
                    </h1>
                    <p class="mt-6 text-base sm:text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed font-medium">
                        A completely customized, domain-driven modular SaaS environment built using Laravel, Livewire, Tailwind CSS, and Alpine.js.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @if (Route::has('admin.login'))
                            <a href="{{ route('admin.login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/15 hover:shadow-indigo-500/25 transition duration-150">
                                Launch Admin Portal
                            </a>
                        @endif
                        <a href="#features" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-slate-900 hover:bg-slate-100/60 dark:hover:bg-slate-800/80 border border-slate-200/50 dark:border-slate-800/50 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl transition duration-150">
                            Explore Features
                        </a>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section -->
            <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-slate-200/30 dark:border-slate-800/30">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Modular Domain Architecture</h2>
                    <p class="mt-3 text-slate-500 dark:text-slate-400 text-sm font-medium">Engineered to scale with decoupled, module-oriented namespaces.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature Card 1 -->
                    <div class="glass-card p-6 rounded-2xl">
                        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Advanced CRM</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Complete control over Client profiles, Staff assignments, and dynamic domains.</p>
                    </div>

                    <!-- Feature Card 2 -->
                    <div class="glass-card p-6 rounded-2xl">
                        <div class="w-10 h-10 bg-pink-500/10 text-pink-500 dark:bg-pink-500/20 dark:text-pink-400 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2M8 21.75a2 2 0 002-2v-1.5h4v1.5a2 2 0 002 2h3a2 2 0 002-2v-11a2 2 0 00-2-2h-3a2 2 0 00-2 2v1.5h-4v-1.5a2 2 0 00-2-2H8a2 2 0 00-2 2v11a2 2 0 002 2h2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Analytics & Revenue</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Real-time revenue monitoring, invoice pipelines, and growth timelines.</p>
                    </div>

                    <!-- Feature Card 3 -->
                    <div class="glass-card p-6 rounded-2xl">
                        <div class="w-10 h-10 bg-sky-500/10 text-sky-500 dark:bg-sky-500/20 dark:text-sky-400 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Automations</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Create and trigger background workflow recipes, status alerts, and cron updates.</p>
                    </div>

                    <!-- Feature Card 4 -->
                    <div class="glass-card p-6 rounded-2xl">
                        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20 dark:text-emerald-400 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Integrations Core</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Engineered to hook directly into ClickUp, Google Cloud, Meta Ads, and Stripe.</p>
                    </div>
                </div>
            </section>

            <!-- Pricing section mock -->
            <section id="pricing" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-slate-200/30 dark:border-slate-800/30">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Simple Enterprise Licensing</h2>
                    <p class="mt-3 text-slate-500 dark:text-slate-400 text-sm font-medium">Deploy on your own infrastructure or cloud environment.</p>
                </div>

                <div class="max-w-md mx-auto glass-card p-8 rounded-3xl relative overflow-hidden shadow-xl">
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl"></div>
                    <span class="px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-500/10 dark:text-indigo-400 rounded-lg">
                        All Inclusive
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-4">Enterprise Access</h3>
                    <p class="text-xs text-slate-500 mt-1">Full self-hosted modular code package.</p>
                    <div class="mt-6 flex items-baseline">
                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">$499</span>
                        <span class="text-slate-400 dark:text-slate-500 text-sm font-semibold ml-2">/ month</span>
                    </div>

                    <ul class="mt-8 space-y-3 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>Unlimited admin, staff, and client seats</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>All modules included (CRM, Integrations)</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>Complete source code modification rights</span>
                        </li>
                    </ul>

                    <div class="mt-8">
                        @if (Route::has('admin.login'))
                            <a href="{{ route('admin.login') }}" class="w-full inline-flex items-center justify-center py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-500/10 transition-all duration-150">
                                Sign In & Get Started
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            <!-- System status section mock -->
            <section id="system" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-slate-200/30 dark:border-slate-800/30">
                <div class="glass-card p-6 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center space-x-3.5">
                        <span class="relative flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">All Operations Normal</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Checked 2 mins ago &bull; 99.98% overall system uptime</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-6 text-xs font-bold text-slate-500 dark:text-slate-400">
                        <div>
                            CRM Service: <span class="text-emerald-500">Online</span>
                        </div>
                        <div>
                            Billing Engine: <span class="text-emerald-500">Online</span>
                        </div>
                        <div>
                            Web Monitoring: <span class="text-emerald-500">Active</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="mt-auto py-8 border-t border-slate-200/40 dark:border-slate-800/40 text-center text-xs text-slate-400 dark:text-slate-600 font-semibold">
                &copy; {{ date('Y') }} Aspire Hub &bull; Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </body>
</html>
