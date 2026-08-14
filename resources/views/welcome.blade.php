<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">

        <title>Aspire Hub</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                /* background: rgba(255, 255, 255, 0.55);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.25); */
                /* From https://css.glass */
background: rgba(255, 255, 255, 0.93);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(1px);
-webkit-backdrop-filter: blur(1px);
border: 1px solid rgba(255, 255, 255, 0.43);
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

        <div class="relative min-h-screen flex flex-col z-10 justify-center items-center">
            
            <main class="flex flex-col items-center justify-center p-6 text-center w-full max-w-2xl mx-auto mt-[-10vh]">
                <!-- Logo -->
                <div class="mb-14 sm:mb-20">
                    <img src="{{ asset('aspire-hub-1.svg') }}" class="h-32 sm:h-56 w-auto mx-auto drop-shadow-xl hover:scale-105 transition-transform duration-300" alt="Aspire Hub" />
                </div>

                <!-- Admin Button -->
                <div class="mb-8 w-full px-4 sm:px-8">
                    <a href="{{ route('admin.login') }}" class="flex items-center justify-center gap-4 w-full px-8 py-6 sm:py-8 text-2xl sm:text-3xl font-extrabold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 rounded-[2rem] shadow-2xl shadow-indigo-500/25 active:scale-[0.98] transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 sm:w-10 sm:h-10">
                          <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                        </svg>
                        Super Admin Login
                    </a>
                </div>

                <!-- Staff and Client Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 w-full px-4 sm:px-8">
                    <a href="{{ route('login') }}" class="flex-1 flex items-center justify-center gap-3 w-full px-6 py-5 sm:py-6 border-2 border-slate-200/80 dark:border-slate-700/80 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-500 text-xl font-bold rounded-2xl shadow-md active:scale-95 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-indigo-500">
                          <path fill-rule="evenodd" d="M3 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5H21a.75.75 0 000-1.5h-.75V9a.75.75 0 00-.75-.75h-6V2.25a.75.75 0 00-.75-.75H3zm3.75 4.5a.75.75 0 00-.75.75v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5zm.75 4.5a.75.75 0 01.75-.75h1.5a.75.75 0 01.75.75v1.5a.75.75 0 01-.75.75h-1.5a.75.75 0 01-.75-.75v-1.5zm-.75 4.5a.75.75 0 00-.75.75v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5zm6-7.5a.75.75 0 00-.75.75v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5zm.75 4.5a.75.75 0 01.75-.75h1.5a.75.75 0 01.75.75v1.5a.75.75 0 01-.75.75h-1.5a.75.75 0 01-.75-.75v-1.5zm-.75 4.5a.75.75 0 00-.75.75v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5z" clip-rule="evenodd" />
                        </svg>
                        Client Login
                    </a>
                    <a href="{{ route('staff.login') }}" class="flex-1 flex items-center justify-center gap-3 w-full px-6 py-5 sm:py-6 border-2 border-slate-200/80 dark:border-slate-700/80 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-500 text-xl font-bold rounded-2xl shadow-md active:scale-95 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-pink-500">
                          <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd" />
                          <path d="M5.082 14.254a8.287 8.287 0 00-1.308 5.135 9.687 9.687 0 01-1.764-.44l-.115-.04a.563.563 0 01-.373-.487l-.01-.121a3.75 3.75 0 016.576-1.994 8.27 8.27 0 00-3.006-2.053zM18.918 14.254a8.27 8.27 0 00-3.006 2.053 3.75 3.75 0 016.576 1.994l-.01.121a.563.563 0 01-.373.487l-.115.04a9.687 9.687 0 01-1.764.44 8.287 8.287 0 00-1.308-5.135z" />
                        </svg>
                        Staff Login
                    </a>
                </div>
            </main>

            <!-- Footer -->
            <footer class="absolute bottom-6 w-full text-center text-xs text-slate-400 dark:text-slate-500 font-semibold px-4">
                &copy; {{ date('Y') }} Aspire Hub &bull; Custom Enterprise System
            </footer>
        </div>
    </body>
</html>
