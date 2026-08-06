@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseStyles = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-150 active:scale-95 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';
    
    $variants = [
        'primary' => 'bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20',
        'secondary' => 'bg-slate-100 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 hover:bg-slate-200/50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white shadow-sm shadow-red-500/10',
        'success' => 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm shadow-emerald-500/10',
    ];

    $sizes = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $class = $baseStyles . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button
    type="{{ $attributes->get('type', 'button') }}"
    {{ $attributes->except('type')->merge(['class' => $class]) }}>
    {{ $slot }}
</button>
