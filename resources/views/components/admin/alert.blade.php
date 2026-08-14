@props(['type' => 'info', 'message' => null])

@php
    $baseStyles = 'p-4 rounded-xl border flex items-start justify-between space-x-3 text-sm transition-all duration-300 shadow-sm border-l-4';
    
    $types = [
        'info' => 'bg-teal-500/5 border-slate-200/70 border-l-[#135266] text-slate-800 dark:bg-slate-900/10 dark:border-slate-800/60 dark:border-l-[#135266] dark:text-slate-200',
        'success' => 'bg-emerald-500/5 border-slate-200/70 border-l-emerald-500 text-slate-800 dark:bg-slate-900/10 dark:border-slate-800/60 dark:border-l-emerald-500 dark:text-slate-200',
        'warning' => 'bg-amber-500/5 border-slate-200/70 border-l-amber-500 text-slate-800 dark:bg-slate-900/10 dark:border-slate-800/60 dark:border-l-amber-500 dark:text-slate-200',
        'danger' => 'bg-red-500/5 border-slate-200/70 border-l-red-500 text-slate-800 dark:bg-slate-900/10 dark:border-slate-800/60 dark:border-l-red-500 dark:text-slate-200',
    ];

    $iconColors = [
        'info' => 'text-[#135266]',
        'success' => 'text-emerald-500',
        'warning' => 'text-amber-500',
        'danger' => 'text-red-500',
    ];

    $icons = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'danger' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ];

    $class = $baseStyles . ' ' . ($types[$type] ?? $types['info']);
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     {{ $attributes->merge(['class' => $class]) }}>
    <div class="flex items-start space-x-3 flex-1">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $iconColors[$type] ?? $iconColors['info'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$type] ?? $icons['info'] }}" />
        </svg>
        <div class="font-medium text-slate-700 dark:text-slate-200">
            {{ $message ?? $slot }}
        </div>
    </div>
    
    <!-- Dismiss Button -->
    <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg p-0.5 transition-colors focus:outline-none shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
