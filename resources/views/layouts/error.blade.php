<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Error') - Aspire Hub</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-error {
                background: rgba(255, 255, 255, 0.5);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .dark .glass-error {
                background: rgba(15, 23, 42, 0.55);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen flex items-center justify-center p-4">
        <!-- Glowing background blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-pink-500/10 dark:bg-pink-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg glass-error p-8 sm:p-12 rounded-3xl shadow-2xl text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400 font-extrabold text-3xl mb-6">
                @yield('code', '!')
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-4">@yield('title', 'Error Happened')</h1>
            <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed font-medium">@yield('message')</p>
            <div>
                <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 active:scale-95 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 transition-all duration-150">
                    Go Back Home
                </a>
            </div>
        </div>
    </body>
</html>
