<div>
    <x-admin.breadcrumbs
        :items="[
            'Documents' => null,
        ]"
    />

    <div class="mb-8">
        <div class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400">
            Documents
        </div>
        <h1 class="mt-2 text-5xl font-black tracking-tight text-slate-900 dark:text-white">
            Your files, always accessible.
        </h1>
        <p class="mt-3 text-slate-500 dark:text-slate-400">
            Reports, invoices, contracts, and guides — all in one place.
        </p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Tabs -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <!-- Search -->
        <div class="relative flex items-center flex-1 max-w-md">
            <svg class="absolute left-3 w-4 h-4 text-slate-450 dark:text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" wire:model.live.debounce.350ms="search" placeholder="Search documents..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
        </div>

        <!-- Filter Categories Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 bg-slate-50 dark:bg-slate-950/20 p-1.5 rounded-2xl border border-slate-200/50 dark:border-slate-800/40">
            @php
                $categories = ['All', 'Reports', 'Invoices', 'Contracts', 'Guides', 'Training', 'Downloads'];
            @endphp
            @foreach ($categories as $cat)
                <button type="button" wire:click="selectCategory('{{ $cat }}')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all
                           {{ $selectedCategory === $cat
                                 ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-sm'
                                 : 'text-slate-500 dark:text-slate-450 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-800 dark:hover:text-white' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($documents as $doc)
            @php
                $sizeInMb = number_format($doc->file_size / (1024 * 1024), 1);
                $formattedDate = \Carbon\Carbon::parse($doc->created_at)->format('M j, Y');
            @endphp
            <div class="bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-800/60 p-4 flex items-center justify-between shadow-sm hover:border-slate-350 dark:hover:border-slate-750 transition duration-150">
                <div class="flex items-center gap-3.5 min-w-0">
                    <!-- Icon based on extension -->
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-750/30 flex items-center justify-center text-slate-500 dark:text-slate-400">
                        @if ($doc->file_type === 'pdf')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        @elseif (in_array($doc->file_type, ['xlsx', 'xls', 'csv']))
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        @elseif (in_array($doc->file_type, ['zip', 'rar']))
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-850 dark:text-slate-205 truncate" title="{{ $doc->title }}">
                            {{ $doc->title }}
                        </h4>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide mt-1.5">
                            {{ $doc->category }} &bull; {{ $sizeInMb }} MB &bull; {{ $formattedDate }}
                        </p>
                        @if ($doc->website_name)
                            <p class="text-[10px] text-indigo-500 dark:text-indigo-400 font-semibold mt-1">
                                Site: {{ $doc->website_name }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0 ml-4">
                    <!-- Preview -->
                    @if ($doc->file_path)
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 transition"
                            title="Preview file">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    @endif

                    <!-- Download -->
                    <button type="button" wire:click="downloadDocument({{ $doc->id }})"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 transition"
                        title="Download file">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white py-20 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V4a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-4 text-sm font-bold text-slate-850 dark:text-slate-300">No Documents Found</h3>
                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">There are currently no files uploaded for your account.</p>
            </div>
        @endforelse
    </div>
</div>
