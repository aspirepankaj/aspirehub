<div>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500 mb-1">
                {{ __('Media Library') }}
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ __('Manage Media') }}
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                {{ __('Upload and manage your images and media files here. These files can be used across the application.') }}
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <div x-data="{ uploading: false, progress: 0 }"
                 x-on:livewire-upload-start="uploading = true"
                 x-on:livewire-upload-finish="uploading = false"
                 x-on:livewire-upload-error="uploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 class="relative">
                
                <input type="file" wire:model="files" id="media-upload" multiple accept=".pdf,.xls,.xlsx,.txt,.mp4,.avi,.mov,.wmv,.flv,.mkv,.webm,.doc,.docx,.zip,.csv,.ppt,.pptx,image/*" class="hidden" />
                
                <label for="media-upload" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span x-show="!uploading">Upload Media <span class="text-xs font-normal opacity-80">(Max 100MB)</span></span>
                    <span x-show="uploading">Uploading... <span x-text="progress + '%'"></span></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-white/60 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-200/50 dark:border-slate-800/40 shadow-sm">
        {{-- Search Input --}}
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search media files by name or folder..."
                   class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition" />
        </div>

        {{-- Filter Buttons --}}
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" wire:click="$set('category', '')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $category === '' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                All Media
            </button>
            <button type="button" wire:click="$set('category', 'image')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $category === 'image' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                Images
            </button>
            <button type="button" wire:click="$set('category', 'video')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $category === 'video' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                Videos
            </button>
            <button type="button" wire:click="$set('category', 'document')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $category === 'document' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                Documents
            </button>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($mediaFiles as $media)
            <div class="group relative bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
                <div class="aspect-square bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 relative overflow-hidden cursor-pointer" @click="$dispatch('open-media-modal', { id: {{ $media->id }}, file_name: '{{ addslashes($media->file_name) }}', file_path: '{{ asset('storage/' . $media->file_path) }}', size: '{{ number_format($media->size / 1024, 1) }} KB', date: '{{ $media->created_at ? $media->created_at->format('d M Y, h:i A') : 'N/A' }}', ext: '{{ strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION)) }}', isImage: {{ Str::startsWith($media->mime_type ?? '', 'image/') ? 'true' : 'false' }} })">
                    @if(Str::startsWith($media->mime_type ?? '', 'image/'))
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->file_name }}" class="object-contain w-full h-full rounded-lg">
                    @elseif(Str::startsWith($media->mime_type ?? '', 'video/'))
                        <div class="flex flex-col items-center justify-center text-blue-500">
                            <svg class="w-10 h-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[9px] font-bold uppercase tracking-wider">Video</span>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                            <svg class="w-10 h-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Document</span>
                        </div>
                    @endif
                    
                    <!-- Overlay Actions -->
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-sm">
                        <button type="button" 
                                @click.stop="$dispatch('open-media-modal', { id: {{ $media->id }}, file_name: '{{ addslashes($media->file_name) }}', file_path: '{{ asset('storage/' . $media->file_path) }}', size: '{{ number_format($media->size / 1024, 1) }} KB', date: '{{ $media->created_at ? $media->created_at->format('d M Y, h:i A') : 'N/A' }}', ext: '{{ strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION)) }}', isImage: {{ Str::startsWith($media->mime_type ?? '', 'image/') ? 'true' : 'false' }} })"
                                class="p-2 bg-white/20 hover:bg-white/40 text-white rounded-lg transition-colors"
                                title="View Details">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                        <button type="button" 
                                onclick="event.stopPropagation(); navigator.clipboard.writeText('{{ asset('storage/' . $media->file_path) }}').then(() => { alert('URL Copied!'); })"
                                class="p-2 bg-white/20 hover:bg-white/40 text-white rounded-lg transition-colors"
                                title="Copy URL">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        
                        <button type="button"
                                wire:click.stop="deleteMedia({{ $media->id }})"
                                wire:confirm="Are you sure you want to delete this file?"
                                class="p-2 bg-red-500/80 hover:bg-red-500 text-white rounded-lg transition-colors"
                                title="Delete">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-2 border-t border-slate-100 dark:border-slate-800 cursor-pointer" @click="$dispatch('open-media-modal', { id: {{ $media->id }}, file_name: '{{ addslashes($media->file_name) }}', file_path: '{{ asset('storage/' . $media->file_path) }}', size: '{{ number_format($media->size / 1024, 1) }} KB', date: '{{ $media->created_at ? $media->created_at->format('d M Y, h:i A') : 'N/A' }}', ext: '{{ strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION)) }}', isImage: {{ Str::startsWith($media->mime_type ?? '', 'image/') ? 'true' : 'false' }} })">
                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate" title="{{ $media->file_name }}">
                        {{ $media->file_name }}
                    </p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-500 mt-0.5">
                        {{ number_format($media->size / 1024, 1) }} KB
                    </p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-dashed rounded-2xl flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-400 dark:text-slate-500">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('No media files found') }}</h3>
                <p class="mt-1 text-xs text-slate-500">{{ __('Click the "Upload Media" button above to add some files.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($mediaFiles->hasPages())
        <div class="mt-6">
            {{ $mediaFiles->links() }}
        </div>
    @endif

    <!-- Media Details Modal (WordPress Style - Premium Design) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto" 
             x-data="{ media: {}, showModal: false }" @open-media-modal.window="media = $event.detail; showModal = true" x-show="showModal" x-cloak @keydown.window.escape.window="showModal = false" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="showModal = false"></div>

            {{-- Modal Container --}}
            <div class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white dark:bg-slate-900 rounded-3xl text-left shadow-2xl shadow-slate-950/50 transform transition-all border border-slate-200/80 dark:border-slate-800 z-10 my-auto">
                    
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-950/40 backdrop-blur-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Attachment Details</h3>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Press <kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 font-mono text-[10px]">ESC</kbd> to close</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Content (2-Column Grid) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                        
                        {{-- Left Column: Media Preview (7 Cols) --}}
                        <div class="lg:col-span-7 bg-slate-950 flex flex-col items-center justify-center p-6 min-h-[340px] max-h-[520px] relative overflow-hidden">
                            

                            <template x-if="media.isImage">
                                <img x-bind:src="media.file_path" alt="<span x-text="media.file_name"></span>" class="max-h-[460px] max-w-full object-contain rounded-2xl shadow-xl border border-slate-800" />
                            </template><template x-if="!media.isImage && ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', 'flv', 'wmv'].includes(media.ext)">
                                <video controls preload="auto" playsinline class="max-h-[460px] w-full rounded-2xl shadow-2xl border border-slate-800 bg-black">
                                    <source x-bind:src="media.file_path" x-bind:type="media.mime_type">
                                    <source x-bind:src="media.file_path">
                                    Your browser does not support playing this video format.
                                </video>
                            </template><template x-if="!media.isImage && !['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', 'flv', 'wmv'].includes(media.ext)">
                                <div class="flex flex-col items-center justify-center p-8 text-center">
                                    <div class="w-20 h-20 rounded-3xl bg-slate-900 border border-slate-800 flex items-center justify-center mb-3 text-indigo-400 shadow-inner">
                                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-300"><span x-text="media.ext"></span> Document</span>
                                    <a x-bind:href="media.file_path" target="_blank" class="mt-4 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition active:scale-95">
                                        Download / View File
                                    </a>
                                </div>
                            </template>
                        </div>

                        {{-- Right Column: File Details & Edit Form (5 Cols) --}}
                        <div class="lg:col-span-5 p-6 bg-white dark:bg-slate-900 border-l border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                            <div class="space-y-5">
                                
                                {{-- File Info Box (Only Uploaded On & File Size) --}}
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-800/60 space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Uploaded On</span>
                                        <span class="font-semibold text-slate-800 dark:text-slate-200"><span x-text="media.date"></span></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">File Size</span>
                                        <span class="font-semibold text-slate-800 dark:text-slate-200"><span x-text="media.size"></span></span>
                                    </div>
                                </div>

                                {{-- Edit Title Form --}}
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Title / File Name
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="media.file_name" class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 focus:outline-none transition" />
                                        <button type="button" x-on:click="$wire.updateMediaName(media.id, media.file_name)" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition active:scale-95 shrink-0 flex items-center gap-1.5">
                                            <span>Save</span>
                                        </button>
                                    </div>
                                    @if($savedSuccessMessage)
                                        <div class="mt-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 p-2.5 rounded-xl border border-emerald-200/80 dark:border-emerald-800/60 transition">
                                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>{{ $savedSuccessMessage }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- File URL Copy Input --}}
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">
                                        File URL / Public Link
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" readonly x-bind:value="media.file_path" class="flex-1 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs font-mono text-slate-600 dark:text-slate-400 focus:outline-none select-all" />
                                        <button type="button" 
                                                x-on:click="navigator.clipboard.writeText(media.file_path).then(() => alert('URL Copied!'))"
                                                class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-bold text-xs rounded-xl transition active:scale-95 shrink-0">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                                <button type="button" x-on:click="if(confirm('Are you sure you want to permanently delete this file?')) $wire.deleteSelectedMedia(media.id)" class="text-xs font-bold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
                                    Delete Permanently
                                </button>
                                <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
