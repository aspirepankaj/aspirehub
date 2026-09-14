@props(['items' => [], 'backUrl' => null, 'backLabel' => null])
@php
    $isClient = request()->is('client*');
    $isStaff = request()->is('staffadspnl*') || request()->routeIs('staff.*');
    
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

    $calcBackUrl = $backUrl;
    $calcBackLabel = $backLabel;

    // 1. Check $items for parent link with non-null URL
    if (!$calcBackUrl) {
        foreach ($items as $label => $link) {
            if (!is_null($link)) {
                $calcBackUrl = $link;
                $calcBackLabel = $label;
            }
        }
    }

    // 2. If backLabel is not set, use the current page title (last item in $items)
    if (!$calcBackLabel && !empty($items)) {
        $itemKeys = array_keys($items);
        $calcBackLabel = end($itemKeys);
    }

    // Fallback if still empty
    if (!$calcBackLabel) {
        $calcBackLabel = 'Dashboard';
    }

    $buttonText = 'Back to ' . $calcBackLabel;
@endphp
<div class="flex items-center justify-between gap-4 mb-5">
    <nav class="flex text-sm text-slate-500 dark:text-slate-400 font-semibold">
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

    {{-- Top Right Back Button (Only shown on Step 3+ nested pages to return to Step 2) --}}
    @if($calcBackUrl)
        <div class="shrink-0">
            <a href="{{ $calcBackUrl }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4 py-1.5 rounded-full shadow-xs hover:shadow transition-all duration-150 active:scale-95">
                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to {{ $calcBackLabel }}</span>
            </a>
        </div>
    @endif
</div>
