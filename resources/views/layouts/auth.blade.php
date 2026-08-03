<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Aspire Hub - Portal' }}</title>

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
            .glass-auth {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.4);
            }
            .dark .glass-auth {
                background: rgba(15, 23, 42, 0.55);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
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

        <!-- Top right theme toggle -->
        <div class="absolute top-6 right-6 z-50">
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2.5 rounded-xl border border-slate-200/60 dark:border-slate-800/40 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-300 text-slate-500 dark:text-slate-400">
                <!-- Sun Icon -->
                <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828 0l-.707-.707m2.828-11.314l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                </svg>
                <!-- Moon Icon -->
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <div class="relative min-h-screen flex flex-col justify-center items-center p-4 z-10">
            <!-- Brand Logo / Title -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/20 text-white font-extrabold text-2xl tracking-wider mb-3">
                    AH
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent dark:from-indigo-400 dark:to-pink-400">
                    Aspire Hub
                </h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-widest font-semibold">Enterprise SaaS Platform</p>
            </div>

            <!-- Card Container -->
            <div class="w-full max-w-md p-8 glass-auth shadow-2xl rounded-3xl">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-xs text-slate-400 dark:text-slate-600 font-medium">
                &copy; {{ date('Y') }} Aspire Hub &bull; Phase 1 Development Setup
            </div>
        </div>
    </body>
</html>
