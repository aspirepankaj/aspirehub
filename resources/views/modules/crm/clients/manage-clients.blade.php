@section('page_title', 'Clients Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Clients' => null]" />

    <!-- Search and Actions Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <!-- Search bar -->
        <div class="relative w-full sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   autocomplete="off"
                   placeholder="Search by name, email, or company..." 
                   class="block w-full pl-11 pr-4.5 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
        </div>

        <!-- Add Client Button -->
        <x-admin.button @click="$dispatch('open-modal', { name: 'add-client-modal' })" size="md" variant="primary" class="w-full sm:w-auto space-x-2">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Client</span>
        </x-admin.button>
    </div>

    <!-- Success Message Alert -->
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    <!-- Clients Table Card -->
    <x-admin.card>
        @if($clients->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Clients Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new client profile above.</p>
            </div>
        @else
            <x-admin.table :headers="['Client Details', 'Company Name', 'Phone', 'Status', 'Registered', 'Actions']">
                @foreach($clients as $client)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $client->user->name ?? 'Deleted User' }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ $client->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                            {{ $client->company_name ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">
                            {{ $client->phone ?: '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg 
                                  {{ $client->status === 'active' 
                                      ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                      : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $client->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" wire:click="editClient({{ $client->id }})" 
                                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-6">
                {{ $clients->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Add Client Modal -->
    <x-admin.modal name="add-client-modal" title="Add New Client">
        <div class="space-y-4.5 mt-2">
            <!-- Name -->
            <div>
                <label for="name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="name" type="text" required autocomplete="new-name" placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="client_email" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="client_email" type="email" required autocomplete="new-email" placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="client_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="client_password" type="password" required autocomplete="new-password" placeholder="Min 8 characters"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Grid: Company & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Company Name -->
                <div>
                    <label for="company_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                    <input wire:model="company_name" id="company_name" type="text" placeholder="e.g. Acme Corp"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Phone Number') }}</label>
                    <input wire:model="phone" id="phone" type="text" placeholder="e.g. +1 555-0199"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="client_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="client_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-client-modal' })" 
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="saveClient" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Client</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- Edit Client Modal -->
    <x-admin.modal name="edit-client-modal" title="Edit Client">
        <div class="space-y-4.5 mt-2">
            <!-- Name -->
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="edit_name" type="text" required placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="edit_client_email" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_client_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_client_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Password (Leave blank to keep current)') }}</label>
                <input wire:model="password" id="edit_client_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Grid: Company & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Company Name -->
                <div>
                    <label for="edit_company_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                    <input wire:model="company_name" id="edit_company_name" type="text" placeholder="e.g. Acme Corp"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="edit_phone" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Phone Number') }}</label>
                    <input wire:model="phone" id="edit_phone" type="text" placeholder="e.g. +1 555-0199"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="edit_client_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="edit_client_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the client..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-client-modal' })" 
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateClient" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="updateClient" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Client</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
