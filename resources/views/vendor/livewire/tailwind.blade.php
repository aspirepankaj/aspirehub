@if ($paginator->hasPages())
    @php $pageName = $paginator->getPageName(); @endphp
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            <span>
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 cursor-default leading-5 rounded-md select-none">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $pageName }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-slate-700 transition ease-in-out duration-150">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif
            </span>

            <span>
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $pageName }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-slate-700 transition ease-in-out duration-150">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 cursor-default rounded-md select-none">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </span>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-slate-700 dark:text-slate-400 leading-5">
                    Showing
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Previous Page Link --}}
                    <span>
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 cursor-default rounded-l-md leading-5 select-none" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $pageName }}')" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-l-md leading-5 hover:text-slate-450 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-slate-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </span>

                    @if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        @php
                            $currentPage = $paginator->currentPage();
                            $lastPage = $paginator->lastPage();
                            $onEachSide = 1;
                            
                            $pages = [];
                            $pages[] = 1;
                            
                            $start = max(2, $currentPage - $onEachSide);
                            $end = min($lastPage - 1, $currentPage + $onEachSide);
                            
                            if ($start > 2) {
                                $pages[] = '...';
                            }
                            
                            for ($i = $start; $i <= $end; $i++) {
                                $pages[] = $i;
                            }
                            
                            if ($end < $lastPage - 1) {
                                $pages[] = '...';
                            }
                            
                            if ($lastPage > 1) {
                                $pages[] = $lastPage;
                            }
                        @endphp

                        @foreach ($pages as $page)
                            @if ($page === '...')
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-700 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 cursor-default leading-5 select-none">{{ $page }}</span>
                                </span>
                            @elseif ($page == $currentPage)
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-700 bg-gray-200 dark:bg-slate-850 border border-slate-300 dark:border-slate-750 cursor-default leading-5 select-none">{{ $page }}</span>
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }}, '{{ $pageName }}')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-700 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 leading-5 hover:text-slate-450 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-slate-750 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif

                    {{-- Next Page Link --}}
                    <span>
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $pageName }}')" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-r-md leading-5 hover:text-slate-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-slate-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-slate-500 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 cursor-default rounded-r-md leading-5 select-none" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </span>
            </div>
        </div>
    </nav>
@endif
