@section('page_title', 'Plans')

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Clients' => route('admin.clients'), 'Plans' => null]" />

    {{-- Page Header --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage custom plans (e.g. Starter, Growth, Enterprise) and assign them to clients
        </p>

        <button type="button" wire:click="openAddModal"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Plan
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
        @if($plans->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Plans Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Create client tier plans like Starter, Growth, or Enterprise.</p>
            </div>
        @else
            <x-admin.table :headers="['Plan Name', 'Price', 'Badge Preview', 'Color Code', 'Actions']">
                @foreach($plans as $plan)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-sm">
                            {{ $plan->name }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 text-sm">
                            ${{ number_format($plan->price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-{{ $plan->color }}-500/10 text-{{ $plan->color }}-600 dark:text-{{ $plan->color }}-400 uppercase tracking-wider">
                                {{ $plan->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-500 dark:text-slate-400">
                            {{ $plan->color }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button type="button" wire:click="editPlan({{ $plan->id }})"
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" wire:click="deletePlan({{ $plan->id }})"
                                        wire:confirm="Are you sure you want to delete this plan? Clients assigned to this plan might prevent deletion."
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
        @endif
    </x-admin.card>

    {{-- Add Plan Modal --}}
    <x-admin.modal name="add-plan-modal" title="Add Plan">
        <div class="space-y-4 mt-2">
            <div>
                <label for="name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Name</label>
                <input wire:model="name" id="name" type="text" placeholder="e.g. Starter, Growth, Enterprise"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="price" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Price ($)</label>
                <input wire:model="price" id="price" type="number" step="0.01" min="0" placeholder="0.00"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('price')" class="mt-1" />
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
                <button type="button" @click="$dispatch('close-modal', { name: 'add-plan-modal' })"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="savePlan" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="savePlan" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Create Plan</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    {{-- Edit Plan Modal --}}
    <x-admin.modal name="edit-plan-modal" title="Edit Plan">
        <div class="space-y-4 mt-2">
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Name</label>
                <input wire:model="name" id="edit_name" type="text"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="edit_price" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Price ($)</label>
                <input wire:model="price" id="edit_price" type="number" step="0.01" min="0"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/55 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('price')" class="mt-1" />
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
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-plan-modal' })"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updatePlan" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="updatePlan" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Plan</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
