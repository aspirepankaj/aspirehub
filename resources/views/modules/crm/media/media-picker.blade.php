<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="z-index: 9999;" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="close" style="z-index: -1;"></div>
        
        <div class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Choose Image from Media Library</h3>
                <button type="button" wire:click="close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Toolbar -->
            <div class="px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white" placeholder="Search media...">
                </div>

                <div class="relative">
                    <input type="file" wire:model="files" id="media-picker-upload" multiple accept="image/*" class="hidden" />
                    <label for="media-picker-upload" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span x-show="!uploading">Upload New <span class="text-[10px] opacity-80">(Max 2MB)</span></span>
                        <span x-show="uploading">Uploading... <span x-text="progress + '%'"></span></span>
                    </label>
                </div>
            </div>

            @error('files.*')
                <div class="mx-6 mt-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm border border-red-200 dark:border-red-800">
                    {{ $message }}
                </div>
            @enderror

            <!-- Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @forelse($mediaFiles as $media)
                        <div wire:click="selectMedia('{{ $media->file_path }}')" class="group relative bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm hover:border-indigo-500 hover:ring-2 hover:ring-indigo-500/20 cursor-pointer transition-all">
                            <div class="aspect-square bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->file_name }}" class="object-contain w-full h-full rounded-lg">
                                <div class="absolute inset-0 bg-indigo-600/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">Select</span>
                                </div>
                            </div>
                            <div class="p-2 border-t border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-[11px] font-medium text-slate-700 dark:text-slate-300 truncate" title="{{ $media->file_name }}">
                                    {{ $media->file_name }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 px-4 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3 text-slate-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">No media found</h3>
                            <p class="mt-1 text-xs text-slate-500">Upload some files to get started.</p>
                        </div>
                    @endforelse
                </div>

                @if($mediaFiles->hasPages())
                    <div class="mt-6">
                        {{ $mediaFiles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
