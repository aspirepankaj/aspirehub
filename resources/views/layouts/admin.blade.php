<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', mobileSidebar: false }" 
      x-init="$watch('darkMode', val => {
          localStorage.setItem('darkMode', val);
          if (val) {
              document.documentElement.classList.add('dark');
          } else {
              document.documentElement.classList.remove('dark');
          }
      })"
      :class="{ 'dark': darkMode }">
    <head>
        <script>
            if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Aspire Hub') }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                /* background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(181, 181, 181, 25%); */
                /* From https://css.glass */
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

    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300">
        
        @if(session()->has('impersonator_id'))
            <div class="bg-amber-500 text-slate-950 font-bold px-4 py-2.5 text-center text-xs sm:text-sm flex items-center justify-center gap-3 shadow-md relative z-50">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    You are currently impersonating <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).
                </span>
                <a href="{{ route('impersonate.stop') }}" class="underline hover:text-slate-900 bg-slate-950/10 hover:bg-slate-950/20 px-2.5 py-1 rounded-lg transition-all duration-150">
                    Switch Back to Admin
                </a>
            </div>
        @endif

        <!-- Background decorative blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -right-40 w-96 h-96 bg-pink-500/10 dark:bg-pink-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-sky-500/10 dark:bg-sky-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative min-h-screen flex z-10">
            <!-- Sidebar Component -->
            <x-admin.sidebar />

            <!-- Main Panel -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-64">
                
                <!-- Navbar Component -->
                <x-admin.navbar />

                <!-- Content Area -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="py-4 px-6 text-center text-xs text-slate-400 dark:text-slate-600 border-t border-slate-200/50 dark:border-slate-800/50">
                    &copy; {{ date('Y') }} Aspire Hub. All rights reserved. Custom Enterprise System.
                </footer>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
