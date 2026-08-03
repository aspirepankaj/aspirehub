@props(['title' => null, 'subtitle' => null])
<div {{ $attributes->merge(['class' => 'glass-card p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md']) }}>
    @if($title || isset($actions))
        <div class="flex items-center justify-between mb-4">
            <div>
                @if($title)
                    <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center space-x-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
