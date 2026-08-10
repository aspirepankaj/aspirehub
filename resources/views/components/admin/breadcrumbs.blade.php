@props(['items' => []])
@php
    $referer = request()->headers->get('referer', '');
    $refererPath = parse_url($referer, PHP_URL_PATH) ?: '';
    
    $isClient = request()->is('client*') || 
                \Illuminate\Support\Str::startsWith($refererPath, '/client/') || 
                $refererPath === '/client' || 
                request()->routeIs('websites');
                
    $isStaff = request()->is('staffadspnl*') || 
               \Illuminate\Support\Str::startsWith($refererPath, '/staffadspnl') || 
               request()->routeIs('staff.*');
    
    if ($isClient) {
        $rootUrl = route('client.dashboard');
        $rootLabel = 'Client';
    } elseif ($isStaff) {
        $rootUrl = route('staff.dashboard');
        $rootLabel = 'Staff';
    } else {
        $rootUrl = route('admin.dashboard');
        $rootLabel = 'Admin';
    }
@endphp
<nav class="flex text-sm text-slate-500 dark:text-slate-400 font-semibold mb-4">
    <ol class="inline-flex items-center space-x-1.5 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ $rootUrl }}" class="inline-flex items-center hover:text-slate-700 dark:hover:text-slate-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ $rootLabel }}
            </a>
        </li>
        @foreach($items as $label => $link)
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    @if($loop->last || is_null($link))
                        <span class="ml-1.5 md:ml-2 text-slate-800 dark:text-slate-200 font-bold">{{ $label }}</span>
                    @else
                        <a href="{{ $link }}" class="ml-1.5 md:ml-2 hover:text-slate-700 dark:hover:text-slate-200">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
