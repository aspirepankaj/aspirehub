@props(['type' => 'info', 'message' => null])

@php
    $baseStyles = 'p-4 rounded-2xl border flex items-start space-x-3 text-sm';
    
    $types = [
        'info' => 'bg-indigo-500/10 border-indigo-500/20 text-indigo-800 dark:text-indigo-300',
        'success' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-800 dark:text-emerald-300',
        'warning' => 'bg-amber-500/10 border-amber-500/20 text-amber-800 dark:text-amber-300',
        'danger' => 'bg-red-500/10 border-red-500/20 text-red-800 dark:text-red-300',
    ];

    $icons = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'danger' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ];

    $class = $baseStyles . ' ' . ($types[$type] ?? $types['info']);
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$type] ?? $icons['info'] }}" />
    </svg>
    <div>
        {{ $message ?? $slot }}
    </div>
</div>
