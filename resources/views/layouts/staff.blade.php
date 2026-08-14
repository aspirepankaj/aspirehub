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

        <title>{{ $title ?? config('app.name', 'Aspire Hub Staff') }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            html {
                scrollbar-gutter: stable;
            }
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

    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300">
        
        <!-- Background decorative blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -right-40 w-96 h-96 bg-pink-500/10 dark:bg-pink-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-sky-500/10 dark:bg-sky-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative min-h-screen flex z-10">
            <!-- Staff Sidebar Component -->
            <x-staff.sidebar />

            <!-- Main Panel -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-64">
                
                <!-- Staff Navbar Component -->
                <x-staff.navbar />

                <!-- Content Area -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="py-4 px-6 text-center text-xs text-slate-400 dark:text-slate-600 border-t border-slate-200/50 dark:border-slate-800/50">
                    &copy; {{ date('Y') }} Aspire Hub. All rights reserved. Staff Portal.
                </footer>
            </div>
        </div>

        <livewire:media-picker />
        @livewireScripts
    </body>
</html>
