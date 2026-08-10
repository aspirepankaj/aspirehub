@section('page_title', 'Designation Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Designations' => null]" />

    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Designation button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Configure and manage official staff roles and professional titles
        </p>

        {{-- Add Designation button --}}
        <button type="button" wire:click="openAddModal"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Designation</span>
        </button>
    </div>

    {{-- Session alerts --}}
    @if(session('success'))
        <x-admin.alert type="success" class="mb-5" :message="session('success')" />
    @endif

    {{-- ══════════════════════════════════════════════
         FILTERS — Single search row matching layout
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="relative flex items-center flex-1 max-w-md">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search designation by name..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            <div class="flex items-center gap-3 justify-end">
                @if($hasActiveFilters)
                    <button type="button" wire:click="clearFilters"
                            class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition duration-150">
                        Clear Filters
                    </button>
                @endif

                @if(!empty($selectedDesignations))
                    <button type="button"
                            wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete the {{ count($selectedDesignations) }} selected designation(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-650 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-red-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Selected
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Bulk Action header --}}
    @if(!empty($selectedDesignations))
        <div class="flex items-center justify-between p-3.5 mb-5 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-150/40 dark:border-indigo-900/30 rounded-2xl animate-fadeIn">
            <span class="text-xs text-indigo-650 dark:text-indigo-400 font-bold flex items-center gap-1.5 pl-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Selected {{ count($selectedDesignations) }} designations
            </span>
        </div>
    @endif

    {{-- Main listings --}}
    @if($designations->isEmpty())
        <div class="text-center py-12">
            <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M30 30h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No designations found</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new designation above.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50 mb-5">
            <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                <thead>
                    <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-4 w-10">
                            <input type="checkbox"
                                   wire:model.live="selectAll"
                                   x-on:click="$wire.toggleSelectAll({{ json_encode($pageIds) }})"
                                   class="w-4 h-4 rounded border-slate-300 dark:border-slate-650 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                        </th>
                        <th class="px-4 py-4">Designation Name</th>
                        <th class="px-4 py-4">Description</th>
                        <th class="px-4 py-4">Associated Staff</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-850/40 text-sm">
                    @foreach($designations as $designation)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($designation->id, $selectedDesignations) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                            <td class="px-4 py-4 w-10">
                                <input type="checkbox"
                                       wire:model.live="selectedDesignations"
                                       value="{{ $designation->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-650 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </td>
                            <td class="px-4 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $designation->name }}
                            </td>
                            <td class="px-4 py-4 text-slate-500 dark:text-slate-400 max-w-sm truncate">
                                {{ $designation->description ?: '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-bold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ $designation->staff_count }} staff
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" wire:click="editDesignation({{ $designation->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                            wire:click="deleteDesignation({{ $designation->id }})"
                                            wire:confirm="Delete designation '{{ $designation->name }}'? (Any staff with this designation will lose this reference)"
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-red-650 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

        <div>
            {{ $designations->links() }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════
         ADD DESIGNATION MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="add-designation-modal" title="Add New Designation" maxWidth="max-w-xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Name -->
            <div>
                <label for="designation_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Designation Name</label>
                <input wire:model="name" id="designation_name" type="text" required placeholder="e.g. Senior Software Architect"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-450 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Description -->
            <div>
                <label for="designation_desc" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Description</label>
                <textarea wire:model="description" id="designation_desc" rows="3" placeholder="Enter general expectations or notes about this title..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-450 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-designation-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-750 active:scale-95 transition duration-150">
                    Cancel
                </button>
                <button type="button" wire:click="saveDesignation"
                        style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
                    <svg wire:loading wire:target="saveDesignation" class="animate-spin h-3.5 w-3.5 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Designation</span>
                </button>
            </div>
        </div>
    </x-admin.modal>

    {{-- ══════════════════════════════════════════════
         EDIT DESIGNATION MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-designation-modal" title="Edit Designation" maxWidth="max-w-xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Name -->
            <div>
                <label for="edit_designation_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Designation Name</label>
                <input wire:model="name" id="edit_designation_name" type="text" required placeholder="e.g. Senior Software Architect"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-450 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Description -->
            <div>
                <label for="edit_designation_desc" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Description</label>
                <textarea wire:model="description" id="edit_designation_desc" rows="3" placeholder="Enter general expectations or notes about this title..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-450 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-designation-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-750 active:scale-95 transition duration-150">
                    Cancel
                </button>
                <button type="button" wire:click="updateDesignation"
                        style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
                    <svg wire:loading wire:target="updateDesignation" class="animate-spin h-3.5 w-3.5 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>
    </x-admin.modal>
</div>
