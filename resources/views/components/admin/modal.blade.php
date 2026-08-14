@props(['name', 'title' => null, 'maxWidth' => 'max-w-lg'])
<div x-data="{ show: false }" 
     x-show="show" 
     @open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
     @close-modal.window="show = false"
     @keydown.escape.window="show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
    <!-- Backdrop Overlay - High contrast dark navy backdrop with blur to separate from background page -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="show = false" 
         class="fixed inset-0 bg-[#0f172a]/75 backdrop-blur-[6px]"></div>

    <!-- Modal Content Container -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative w-full {{ $maxWidth }} max-h-[90vh] flex flex-col bg-white dark:bg-slate-900 rounded-2xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.35)] z-10 border border-slate-200/90 dark:border-slate-800/90 overflow-hidden">
        
        <!-- Header - Premium clean layout with left brand indicator and generous padding -->
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-slate-50/50 dark:bg-slate-900/20">
            <div class="flex items-center gap-3">
                <!-- Left brand accent indicator -->
                <div class="w-1.5 h-5 rounded bg-[#135266]"></div>
                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white tracking-tight">{{ $title ?? 'Modal Window' }}</h3>
            </div>
            <button @click="show = false" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body content with scrolling container -->
        <div class="p-6 overflow-y-auto flex-1 bg-slate-50/10 dark:bg-slate-900/10 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
            {{ $slot }}
        </div>
    </div>
</div>
