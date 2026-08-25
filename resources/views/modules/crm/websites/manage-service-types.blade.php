@section('page_title', 'Service Types')

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Websites' => route('admin.websites'), 'Service Types' => null]" />

    {{-- Page Header --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage custom service categories and color coding badges
        </p>

        <button type="button" @click="$dispatch('open-modal', { name: 'add-service-type-modal' })"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Service Type
        </button>
    </div>

    {{-- Success and Error Alert Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-5" :message="session('success')" />
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" class="mb-5" :message="session('error')" />
    @endif

    {{-- Data Table Card --}}
    <x-admin.card>
        @if($types->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Service Types Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Create a dynamic service type category to classify customer websites.</p>
            </div>
        @else
            <x-admin.table :headers="['Service Type Name', 'Badge Preview', 'Color Code', 'Actions']">
                @foreach($types as $type)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-sm">
                            {{ $type->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-{{ $type->color }}-500/10 text-{{ $type->color }}-600 dark:text-{{ $type->color }}-400 uppercase tracking-wider">
                                {{ $type->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-500 dark:text-slate-400">
                            {{ $type->color }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1 justify-center">
                                <button type="button" wire:click="editType({{ $type->id }})"
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" wire:click="deleteType({{ $type->id }})"
                                        wire:confirm="Are you sure you want to delete this service type? Existing websites using this type might prevent deletion."
                                        class="p-2 rounded-xl text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-6">
                {{ $types->links() }}
            </div>
        @endif
    </x-admin.card>

    {{-- Add Service Type Modal --}}
    <x-admin.modal name="add-service-type-modal" title="Add Service Type">
        <div class="space-y-4 mt-2">
            <div>
                <label for="name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Name</label>
                <input wire:model="name" id="name" type="text" placeholder="e.g. SEO, API Integration"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="color" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Badge Color Theme</label>
                <select wire:model="color" id="color"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="indigo">Indigo (Blue-Purple)</option>
                    <option value="emerald">Emerald (Green)</option>
                    <option value="pink">Pink (Red-Pink)</option>
                    <option value="amber">Amber (Orange-Yellow)</option>
                    <option value="slate">Slate (Gray)</option>
                    <option value="red">Red (Danger)</option>
                    <option value="sky">Sky (Light Blue)</option>
                    <option value="violet">Violet (Purple)</option>
                    <option value="rose">Rose (Deep Red)</option>
                </select>
                <x-input-error :messages="$errors->get('color')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-service-type-modal' })"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveType" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="saveType" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Create Type</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    {{-- Edit Service Type Modal --}}
    <x-admin.modal name="edit-service-type-modal" title="Edit Service Type">
        <div class="space-y-4 mt-2">
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Name</label>
                <input wire:model="name" id="edit_name" type="text"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="edit_color" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Badge Color Theme</label>
                <select wire:model="color" id="edit_color"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="indigo">Indigo (Blue-Purple)</option>
                    <option value="emerald">Emerald (Green)</option>
                    <option value="pink">Pink (Red-Pink)</option>
                    <option value="amber">Amber (Orange-Yellow)</option>
                    <option value="slate">Slate (Gray)</option>
                    <option value="red">Red (Danger)</option>
                    <option value="sky">Sky (Light Blue)</option>
                    <option value="violet">Violet (Purple)</option>
                    <option value="rose">Rose (Deep Red)</option>
                </select>
                <x-input-error :messages="$errors->get('color')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-service-type-modal' })"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateType" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="updateType" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Type</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
