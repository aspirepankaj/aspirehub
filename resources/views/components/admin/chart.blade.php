@props(['type' => 'bar', 'data' => []])

<div {{ $attributes->merge(['class' => 'relative w-full']) }}>
    @if($type === 'line')
        <!-- Beautiful SVG Line Chart -->
        <svg viewBox="0 0 500 200" class="w-full h-48 overflow-visible">
            <!-- Grid Lines -->
            <line x1="0" y1="50" x2="500" y2="50" stroke="rgba(200,200,200,0.15)" stroke-dasharray="4" />
            <line x1="0" y1="100" x2="500" y2="100" stroke="rgba(200,200,200,0.15)" stroke-dasharray="4" />
            <line x1="0" y1="150" x2="500" y2="150" stroke="rgba(200,200,200,0.15)" stroke-dasharray="4" />
            
            <!-- Area Gradient -->
            <defs>
                <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="rgba(99, 102, 241, 0.25)" />
                    <stop offset="100%" stop-color="rgba(99, 102, 241, 0.0)" />
                </linearGradient>
            </defs>

            <!-- Line Path -->
            <path d="M 0 160 Q 75 140, 150 110 T 300 70 T 450 40 T 500 30" fill="none" stroke="rgb(99, 102, 241)" stroke-width="3" stroke-linecap="round" />
            <path d="M 0 160 Q 75 140, 150 110 T 300 70 T 450 40 T 500 30 L 500 200 L 0 200 Z" fill="url(#chartGrad)" />
            
            <!-- Points -->
            <circle cx="150" cy="110" r="5" fill="rgb(99, 102, 241)" stroke="white" stroke-width="2" class="cursor-pointer hover:r-7 transition-all" />
            <circle cx="300" cy="70" r="5" fill="rgb(99, 102, 241)" stroke="white" stroke-width="2" class="cursor-pointer hover:r-7 transition-all" />
            <circle cx="450" cy="40" r="5" fill="rgb(99, 102, 241)" stroke="white" stroke-width="2" class="cursor-pointer hover:r-7 transition-all" />
        </svg>
    @else
        <!-- Beautiful CSS/SVG Bar Chart -->
        <div class="flex items-end justify-between h-48 pt-6 px-2">
            @foreach($data as $label => $val)
                <div class="flex flex-col items-center flex-1 group px-1 sm:px-2">
                    <!-- Tooltip -->
                    <div class="absolute bottom-full mb-2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none shadow-md">
                        ${{ number_format($val) }}
                    </div>
                    <!-- Bar -->
                    <div class="w-full bg-gradient-to-t from-indigo-500 to-pink-500 rounded-t-lg transition-all duration-500 group-hover:scale-y-105" 
                         style="height: {{ max(10, min(100, $val / 1000)) }}px;"></div>
                    <!-- Label -->
                    <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 mt-2.5 uppercase tracking-wider">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
