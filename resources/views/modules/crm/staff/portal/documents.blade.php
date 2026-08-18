@section('page_title', 'Documents Library')

<div>
    {{-- Breadcrumbs & Add Action --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <x-admin.breadcrumbs :items="['Documents Library' => null]" />

        <button type="button" 
                wire:click="openAddModal"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New Document
        </button>
    </div>

    {{-- Tabs Switcher --}}
    <div class="flex border-b border-slate-200 dark:border-slate-800 mb-6 gap-2">
        <button type="button" 
                wire:click="$set('activeTab', 'client')"
                class="px-4 py-3 text-sm font-bold border-b-2 transition-all duration-150 relative -mb-[2px]
                {{ $activeTab === 'client' 
                    ? 'border-pink-500 text-pink-600 dark:text-pink-400 font-extrabold' 
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Client Documents
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'website')"
                class="px-4 py-3 text-sm font-bold border-b-2 transition-all duration-150 relative -mb-[2px]
                {{ $activeTab === 'website' 
                    ? 'border-pink-500 text-pink-600 dark:text-pink-400 font-extrabold' 
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Website Documents
        </button>
    </div>

    {{-- Filters Card --}}
    <div class="relative z-30 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-2xl p-5 mb-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Search Title/File --}}
            <div class="relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search title or file name..." 
                       class="block w-full pl-9 pr-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-450 text-sm transition duration-150" />
                <div class="absolute left-3 top-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Client Filter Searchable --}}
            <div x-data="{ 
                    open: false, 
                    search: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->user->name])) }},
                    select(id, name) {
                        this.search = name;
                        $wire.set('clientFilter', id);
                        this.open = false;
                    },
                    clear() {
                        this.search = '';
                        $wire.set('clientFilter', '');
                        this.open = false;
                    },
                    syncSearch() {
                        const val = $wire.get('clientFilter');
                        if (!val) {
                            this.search = '';
                        } else {
                            const found = this.clients.find(c => c.id == val);
                            this.search = found ? found.name : '';
                        }
                    },
                    init() {
                        this.syncSearch();
                        this.$watch('$wire.clientFilter', () => this.syncSearch());
                    }
                 }" 
                 @click.outside="open = false"
                 class="relative">
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           x-on:focus="open = true"
                           placeholder="Filter by Client..."
                           class="block w-full pl-3 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-400 text-sm transition duration-150" />
                    
                    <template x-if="$wire.clientFilter">
                        <button type="button" x-on:click="clear()" class="absolute right-8 top-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </template>
                    
                    <button type="button" x-on:click="open = !open" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                {{-- Dropdown options list --}}
                <div x-show="open" 
                     x-transition 
                     class="absolute z-[9999] w-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-56 overflow-y-auto">
                    <div class="p-1 space-y-0.5">
                        <template x-for="c in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="c.id">
                            <button type="button" 
                                    x-on:click="select(c.id, c.name)"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                <span x-text="c.name"></span>
                            </button>
                        </template>
                        <template x-if="clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                            <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No clients found</div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Website Filter Searchable (Only visible on Website Documents tab) --}}
            @if($activeTab === 'website')
                <div x-data="{ 
                        open: false, 
                        search: '',
                        allWebsites: {{ Js::from($allWebsites) }},
                        select(id, name) {
                            this.search = name;
                            $wire.set('websiteFilter', id);
                            this.open = false;
                        },
                        clear() {
                            this.search = '';
                            $wire.set('websiteFilter', '');
                            this.open = false;
                        },
                        syncSearch() {
                            const val = $wire.get('websiteFilter');
                            if (!val) {
                                this.search = '';
                            } else {
                                const found = this.allWebsites.find(w => w.id == val);
                                this.search = found ? found.name : '';
                            }
                        },
                        init() {
                            this.syncSearch();
                            this.$watch('$wire.websiteFilter', () => this.syncSearch());
                        }
                     }" 
                     @click.outside="open = false"
                     class="relative">
                    <div class="relative">
                        <input type="text" 
                               x-model="search"
                               x-on:focus="open = true"
                               placeholder="Filter by Website..."
                               class="block w-full pl-3 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 focus:border-pink-400 text-sm transition duration-150" />
                        
                        <template x-if="$wire.websiteFilter">
                            <button type="button" x-on:click="clear()" class="absolute right-8 top-3 text-slate-400 hover:text-slate-600">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </template>
                        
                        <button type="button" x-on:click="open = !open" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    {{-- Dropdown options list --}}
                    <div x-show="open" 
                         x-transition 
                         class="absolute z-[9999] w-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-56 overflow-y-auto">
                        <div class="p-1 space-y-0.5">
                            <template x-for="w in allWebsites.filter(w => (! $wire.clientFilter || w.client_id == $wire.clientFilter) && w.name.toLowerCase().includes(search.toLowerCase()))" :key="w.id">
                                <button type="button" 
                                        x-on:click="select(w.id, w.name)"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                    <span x-text="w.name"></span>
                                </button>
                            </template>
                            <template x-if="allWebsites.filter(w => (! $wire.clientFilter || w.client_id == $wire.clientFilter) && w.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No websites found</div>
                            </template>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if($hasActiveFilters)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Filters active — showing filtered documents
                </span>
                <button type="button" wire:click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" class="mb-6" :message="session('error')" />
    @endif

    {{-- Data Card --}}
    <div class="relative z-10">
        <x-admin.card>
        {{-- Bulk Selection controls --}}
        @if(count($selectedDocs) > 0)
            <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-pink-50 dark:bg-pink-500/10 border border-pink-200/60 dark:border-pink-700/30">
                <span class="text-xs font-bold text-pink-700 dark:text-pink-400 flex items-center gap-2 pl-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    {{ count($selectedDocs) }} document(s) selected
                </span>
                <div class="flex items-center gap-2 pr-2">
                    <button type="button" wire:click="bulkDelete"
                            wire:confirm="Delete {{ count($selectedDocs) }} selected document(s) permanently?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-red-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Selected
                    </button>
                    <button type="button" wire:click="$set('selectedDocs', []); $set('selectAll', false)"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 transition-all duration-150 active:scale-95">
                        Clear
                    </button>
                </div>
            </div>
        @endif

        @if($documents->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Documents Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Upload important PDF contracts, spreadsheets, TXT logs, or client speed audits.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox"
                                       wire:model.live="selectAll"
                                       wire:change="toggleSelectAll({{ json_encode($pageIds) }})"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </th>
                            <th class="px-4 py-4 w-16">ID</th>
                            <th class="px-4 py-4">Title</th>
                            <th class="px-4 py-4">Client</th>
                            @if($activeTab === 'website')
                                <th class="px-4 py-4">Website</th>
                            @endif
                            <th class="px-4 py-4">File Specs</th>
                            <th class="px-4 py-4">Uploaded</th>
                            <th class="px-4 py-4">Added By</th>
                            <th class="px-4 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($doc->id, $selectedDocs) ? 'bg-pink-50/40 dark:bg-pink-900/10' : '' }}">
                                <td class="px-4 py-4 w-10">
                                    <input type="checkbox"
                                           wire:model.live="selectedDocs"
                                           value="{{ $doc->id }}"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </td>
                                <td class="px-4 py-4 font-bold text-slate-900 dark:text-white">
                                    #{{ $doc->id }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $doc->title }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate max-w-xs" title="{{ $doc->file_name }}">
                                        {{ $doc->file_name }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $doc->client->user->name }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500">
                                        {{ $doc->client->company_name }}
                                    </div>
                                </td>
                                @if($activeTab === 'website')
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $doc->website->site_name }}
                                        </div>
                                        <div class="text-[11px] mt-0.5 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium underline">
                                            <a href="{{ $doc->website->url }}" target="_blank">{{ $doc->website->url }}</a>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-700/50">
                                            {{ $doc->file_type }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">
                                            {{ round($doc->file_size / 1024, 1) }} KB
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-550 dark:text-slate-450">
                                    {{ $doc->created_at->format('d M, Y H:i') }}
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-550 dark:text-slate-450">
                                    {{ $doc->addedBy->name }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Preview button --}}
                                        <button type="button" 
                                                wire:click="previewDocument({{ $doc->id }})"
                                                class="p-1.5 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-emerald-500 transition active:scale-90"
                                                title="Preview File">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        {{-- Download button --}}
                                        <button type="button" 
                                                wire:click="downloadDocument({{ $doc->id }})"
                                                class="p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-indigo-500 transition active:scale-90"
                                                title="Download">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </button>
                                        {{-- Edit details --}}
                                        <button type="button" 
                                                wire:click="editDocument({{ $doc->id }})"
                                                class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition active:scale-90"
                                                title="Edit Details">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        {{-- Delete --}}
                                        <button type="button" 
                                                wire:click="deleteDocument({{ $doc->id }})"
                                                wire:confirm="Delete this document permanently?"
                                                class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/20 text-red-550 transition active:scale-90"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        @endif
    </x-admin.card>
    </div>

    {{-- Modal 1: Add Document --}}
    <x-admin.modal name="add-doc-modal" title="Upload New Document" maxWidth="max-w-2xl">
        <form wire:submit.prevent="saveDocument" class="space-y-4 p-1">
            {{-- Document Title --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Document Title</label>
                <input wire:model="title" type="text" placeholder="e.g. Website speed audit / Client Contract" class="block w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            {{-- Client Searchable Input Dropdown --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Select Client</label>
                <div x-data="{ 
                        open: false, 
                        search: '',
                        clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->user->name])) }},
                        select(id, name) {
                            this.search = name;
                            $wire.set('client_id', id);
                            this.open = false;
                        },
                        syncSearch() {
                            const val = $wire.get('client_id');
                            if (!val) {
                                this.search = '';
                            } else {
                                const found = this.clients.find(c => c.id == val);
                                this.search = found ? found.name : '';
                            }
                        },
                        init() {
                            this.syncSearch();
                            this.$watch('$wire.client_id', () => this.syncSearch());
                        }
                     }" 
                     @click.outside="open = false"
                     class="relative">
                    <div class="relative">
                        <input type="text" 
                               x-model="search"
                               x-on:focus="open = true"
                               placeholder="Type to search clients..."
                               class="block w-full pl-3 pr-8 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                        
                        <button type="button" x-on:click="open = !open" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    {{-- Dropdown options list --}}
                    <div x-show="open" 
                         x-transition 
                         class="absolute z-[9999] w-full mt-1 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                        <div class="p-1 space-y-0.5">
                            <template x-for="c in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="c.id">
                                <button type="button" 
                                        x-on:click="select(c.id, c.name)"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                    <span x-text="c.name"></span>
                                </button>
                            </template>
                            <template x-if="clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No clients found</div>
                            </template>
                        </div>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Website Selection (Only for Website Documents) --}}
            @if($activeTab === 'website')
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Select Website</label>
                    <div x-data="{ 
                            open: false, 
                            search: '',
                            allWebsites: {{ Js::from($allWebsites) }},
                            select(id, name) {
                                this.search = name;
                                $wire.set('website_id', id);
                                this.open = false;
                            },
                            syncSearch() {
                                const val = $wire.get('website_id');
                                if (!val) {
                                    this.search = '';
                                } else {
                                    const found = this.allWebsites.find(w => w.id == val);
                                    this.search = found ? found.name : '';
                                }
                            },
                            init() {
                                this.syncSearch();
                                this.$watch('$wire.website_id', () => this.syncSearch());
                            }
                         }" 
                         @click.outside="open = false"
                         class="relative">
                        <div class="relative">
                            <input type="text" 
                                   x-model="search"
                                   x-on:focus="open = true"
                                   placeholder="Type to search websites..."
                                   class="block w-full pl-3 pr-8 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                            
                            <button type="button" x-on:click="open = !open" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Dropdown options list --}}
                        <div x-show="open" 
                             x-transition 
                             class="absolute z-[9999] w-full mt-1 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                            <div class="p-1 space-y-0.5">
                                <template x-for="w in allWebsites.filter(w => w.client_id == $wire.client_id && w.name.toLowerCase().includes(search.toLowerCase()))" :key="w.id">
                                    <button type="button" 
                                            x-on:click="select(w.id, w.name)"
                                            class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                        <span x-text="w.name"></span>
                                    </button>
                                </template>
                                <template x-if="allWebsites.filter(w => w.client_id == $wire.client_id && w.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                    <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No websites found</div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('website_id')" class="mt-1" />
                </div>
            @endif

            {{-- File Uploader --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">File Upload</label>
                <div class="flex items-center gap-3">
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'document_file' })" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Choose from Media Library
                    </button>
                    @if($file_name)
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium truncate max-w-[200px]" title="{{ $file_name }}">
                            {{ $file_name }}
                        </span>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('file_path')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="show = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-pink-500 hover:bg-pink-600 text-white text-xs font-semibold shadow active:scale-95 transition-all">
                    Upload Document
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal 2: Edit Document --}}
    <x-admin.modal name="edit-doc-modal" title="Edit Document Details" maxWidth="max-w-2xl">
        <form wire:submit.prevent="updateDocument" class="space-y-4 p-1">
            {{-- Document Title --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Document Title</label>
                <input wire:model="title" type="text" placeholder="e.g. Website speed audit" class="block w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 text-sm" />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            {{-- Client Searchable Input Dropdown --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Select Client</label>
                <div x-data="{ 
                        open: false, 
                        search: '',
                        clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->user->name])) }},
                        select(id, name) {
                            this.search = name;
                            $wire.set('client_id', id);
                            this.open = false;
                        },
                        syncSearch() {
                            const val = $wire.get('client_id');
                            if (!val) {
                                this.search = '';
                            } else {
                                const found = this.clients.find(c => c.id == val);
                                this.search = found ? found.name : '';
                            }
                        },
                        init() {
                            this.syncSearch();
                            this.$watch('$wire.client_id', () => this.syncSearch());
                        }
                     }" 
                     @click.outside="open = false"
                     class="relative">
                    <div class="relative">
                        <input type="text" 
                               x-model="search"
                               x-on:focus="open = true"
                               placeholder="Type to search clients..."
                               class="block w-full pl-3 pr-8 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                        
                        <button type="button" x-on:click="open = !open" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    {{-- Dropdown options list --}}
                    <div x-show="open" 
                         x-transition 
                         class="absolute z-[9999] w-full mt-1 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                        <div class="p-1 space-y-0.5">
                            <template x-for="c in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="c.id">
                                <button type="button" 
                                        x-on:click="select(c.id, c.name)"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                    <span x-text="c.name"></span>
                                </button>
                            </template>
                            <template x-if="clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No clients found</div>
                            </template>
                        </div>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Website Selection (Only for Website Documents) --}}
            @if($activeTab === 'website')
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Select Website</label>
                    <div x-data="{ 
                            open: false, 
                            search: '',
                            allWebsites: {{ Js::from($allWebsites) }},
                            select(id, name) {
                                this.search = name;
                                $wire.set('website_id', id);
                                this.open = false;
                            },
                            syncSearch() {
                                const val = $wire.get('website_id');
                                if (!val) {
                                    this.search = '';
                                } else {
                                    const found = this.allWebsites.find(w => w.id == val);
                                    this.search = found ? found.name : '';
                                }
                            },
                            init() {
                                this.syncSearch();
                                this.$watch('$wire.website_id', () => this.syncSearch());
                            }
                         }" 
                         @click.outside="open = false"
                         class="relative">
                        <div class="relative">
                            <input type="text" 
                                   x-model="search"
                                   x-on:focus="open = true"
                                   placeholder="Type to search websites..."
                                   class="block w-full pl-3 pr-8 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                            
                            <button type="button" x-on:click="open = !open" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Dropdown options list --}}
                        <div x-show="open" 
                             x-transition 
                             class="absolute z-[9999] w-full mt-1 bg-white dark:bg-slate-800 border border-slate-250 dark:border-slate-700 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                            <div class="p-1 space-y-0.5">
                                <template x-for="w in allWebsites.filter(w => w.client_id == $wire.client_id && w.name.toLowerCase().includes(search.toLowerCase()))" :key="w.id">
                                    <button type="button" 
                                            x-on:click="select(w.id, w.name)"
                                            class="w-full text-left px-3 py-2 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 hover:bg-pink-50 dark:hover:bg-pink-950/20 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                        <span x-text="w.name"></span>
                                    </button>
                                </template>
                                <template x-if="allWebsites.filter(w => w.client_id == $wire.client_id && w.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                    <div class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 italic">No websites found</div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('website_id')" class="mt-1" />
                </div>
            @endif

            {{-- File Uploader --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Replace File (Optional)</label>
                <div class="flex items-center gap-3">
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'document_file' })" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Choose from Media Library
                    </button>
                    @if($file_name)
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium truncate max-w-[200px]" title="{{ $file_name }}">
                            {{ $file_name }}
                        </span>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('file_path')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="show = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-pink-500 hover:bg-pink-650 text-white text-xs font-semibold shadow active:scale-95 transition-all">
                    Update Details
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal 3: Preview Document --}}
    <x-admin.modal name="preview-doc-modal" title="Document Preview: {{ $previewTitle }}" maxWidth="max-w-4xl">
        <div class="p-2 space-y-4">
            @if($previewType)
                @if(in_array($previewType, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white flex items-center justify-center p-4">
                        <img src="{{ $previewUrl }}" class="max-h-[500px] max-w-full object-contain" />
                    </div>
                @elseif($previewType === 'pdf')
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 h-[600px] bg-slate-100">
                        <iframe src="{{ $previewUrl }}" class="w-full h-full border-0"></iframe>
                    </div>
                @elseif(in_array($previewType, ['txt', 'csv', 'log']))
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 font-mono text-xs overflow-auto max-h-[500px] whitespace-pre-wrap text-slate-800 dark:text-slate-300">
                        {{ $previewTextContent }}
                    </div>
                @else
                    <div class="text-center py-12 text-slate-400 text-sm">
                        Preview is not supported for <strong>.{{ $previewType }}</strong> files.
                        <div class="mt-4">
                            <a href="{{ $previewUrl }}" download class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-750 text-xs">Download File</a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </x-admin.modal>
</div>
