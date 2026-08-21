<style>
    @keyframes adsGradientFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes adsPulseGlow {
        0%, 100% {
            filter: drop-shadow(0 0 6px rgba(99, 102, 241, 0.4)) drop-shadow(0 0 12px rgba(168, 85, 247, 0.3));
        }
        50% {
            filter: drop-shadow(0 0 14px rgba(129, 140, 248, 0.8)) drop-shadow(0 0 24px rgba(236, 72, 153, 0.7));
        }
    }
    @keyframes adsSparkleSpin {
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.25); }
        100% { transform: rotate(360deg) scale(1); }
    }

    .ads-animated-brand {
        background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899, #3b82f6, #10b981, #f59e0b, #6366f1);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: adsGradientFlow 4s ease infinite, adsPulseGlow 3s ease-in-out infinite;
    }
</style>

<footer class="py-4 px-4 sm:px-6 lg:px-8 border-t border-slate-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-slate-900/40 backdrop-blur-md">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
        <div class="flex items-center gap-1.5 flex-wrap justify-center sm:justify-start">
            <span>&copy; {{ date('Y') }} <strong class="font-extrabold text-slate-800 dark:text-slate-200">Aspire Hub</strong>. All rights reserved.</span>
            <span class="hidden md:inline text-slate-300 dark:text-slate-700">•</span>
            <span class="text-slate-400 dark:text-slate-500">Next-Gen Enterprise Management System.</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-slate-400 dark:text-slate-500">Developed with</span>
            
            {{-- Pulsing Heart with Glow --}}
            <span class="relative flex h-4 w-4 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <svg class="relative w-3.5 h-3.5 text-rose-500 fill-current shrink-0 transform hover:scale-125 transition-transform duration-300" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </span>

            <span class="text-slate-400 dark:text-slate-500">by</span>

            {{-- Clean Animated Brand Inline Link (Button box removed) --}}
            <a href="https://aspiredigitalsolutions.com" target="_blank" 
               class="group relative inline-flex items-center gap-1.5 font-black text-xs transition-all duration-300">
                
                {{-- Spinning Cyber Sparkle Icon --}}
                <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 shrink-0 transform group-hover:scale-125 transition-transform duration-300" style="animation: adsSparkleSpin 6s linear infinite;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>

                {{-- Animated Shimmer Text with Hover Underline --}}
                <span class="ads-animated-brand tracking-wide">
                    Aspire Digital Solutions
                </span>

                <span class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:to-pink-400 transition-all duration-300 group-hover:w-full"></span>

                {{-- Interactive External Arrow Icon --}}
                <svg class="w-3 h-3 text-indigo-500 opacity-0 group-hover:opacity-100 transform -translate-x-1 group-hover:translate-x-0 transition-all duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </div>
</footer>
