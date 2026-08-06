@props(['name', 'title' => null, 'maxWidth' => 'max-w-lg'])
<div x-data="{ show: false }" 
     x-show="show" 
     @open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
     @close-modal.window="show = false"
     @keydown.escape.window="show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div x-show="show" x-transition.opacity @click="show = false" class="fixed inset-0 bg-slate-950/45 backdrop-blur-sm"></div>

    <!-- Modal Content -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full {{ $maxWidth }} max-h-[90vh] flex flex-col glass-card p-6 rounded-3xl shadow-2xl z-10 border border-white/20 dark:border-slate-800/30">
        
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200/50 dark:border-slate-800/50 shrink-0">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title ?? 'Modal Window' }}</h3>
            <button @click="show = false" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto flex-1 pr-1.5 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
            {{ $slot }}
        </div>
    </div>
</div>
