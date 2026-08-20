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

    @error('files.*')
        <div class="mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium border border-red-200 dark:border-red-800">
            {{ $message }}
        </div>
    @enderror

    <!-- Media Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($mediaFiles as $media)
            <div class="group relative bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
                <div class="aspect-square bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 relative overflow-hidden">
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
                                onclick="navigator.clipboard.writeText('{{ asset('storage/' . $media->file_path) }}').then(() => { alert('URL Copied!'); })"
                                class="p-2 bg-white/20 hover:bg-white/40 text-white rounded-lg transition-colors"
                                title="Copy URL">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        
                        <button type="button"
                                wire:click="deleteMedia({{ $media->id }})"
                                wire:confirm="Are you sure you want to delete this file?"
                                class="p-2 bg-red-500/80 hover:bg-red-500 text-white rounded-lg transition-colors"
                                title="Delete">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-2 border-t border-slate-100 dark:border-slate-800">
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
</div>
