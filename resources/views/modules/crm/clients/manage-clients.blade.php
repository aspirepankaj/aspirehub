@section('page_title', 'Clients Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Clients' => null]" />

    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Client button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage and track all your client accounts
        </p>

        {{-- Add Client button — inline-flex keeps icon + text tight --}}
        <button type="button" wire:click="openAddModal"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-semibold shadow-sm shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-150 active:scale-95 shrink-0 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Client
        </button>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTERS — 50 / 50 layout
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            {{-- Search (50%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       autocomplete="off"
                       placeholder="Search by name, email, or company..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter (50%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                <select wire:model.live="statusFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Statuses</option>
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Clear Filters row — only shown when a filter is active --}}
        @if($hasActiveFilters)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Filters active — showing filtered results
                </span>
                <button type="button" wire:click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    <!-- Success Message Alert -->
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    <!-- Clients Table Card -->
    <x-admin.card>

        {{-- Bulk Action Bar (visible only when items are selected) --}}
        @if(count($selectedClients) > 0)
            <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/60 dark:border-indigo-700/30">
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2 pl-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    {{ count($selectedClients) }} client(s) selected
                </span>
                <div class="flex items-center gap-2 pr-2">
                    <button type="button" wire:click="bulkActivate"
                            wire:confirm="Activate {{ count($selectedClients) }} selected client(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-emerald-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Activate
                    </button>
                    <button type="button" wire:click="bulkDeactivate"
                            wire:confirm="Deactivate {{ count($selectedClients) }} selected client(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-500 hover:bg-slate-600 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-slate-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Deactivate
                    </button>
                    <button type="button" wire:click="$set('selectedClients', []); $set('selectAll', false)"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 transition-all duration-150 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </button>
                </div>
            </div>
        @endif

        @if($clients->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Clients Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new client profile above.</p>
            </div>
        @else
            {{-- Custom table with checkbox column --}}
            <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            {{-- Select All checkbox --}}
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox"
                                       wire:model.live="selectAll"
                                       wire:change="toggleSelectAll({{ json_encode($pageIds) }})"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </th>
                            <th class="px-4 py-4">Client Details</th>
                            <th class="px-4 py-4">Company Name</th>
                            <th class="px-4 py-4">Phone Numbers</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Registered</th>
                            <th class="px-4 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($clients as $client)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($client->id, $selectedClients) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                {{-- Row Checkbox --}}
                                <td class="px-4 py-4 w-10">
                                    <input type="checkbox"
                                           wire:model.live="selectedClients"
                                           value="{{ $client->id }}"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $client->user->name ?? 'Deleted User' }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500">{{ $client->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                                    {{ $client->company_name ?: '—' }}
                                </td>
                                <td class="px-4 py-4 text-slate-500 dark:text-slate-400 font-medium text-xs">
                                    @if($client->phones->isEmpty())
                                        {{ $client->phone ?: '—' }}
                                    @else
                                        <div class="space-y-1">
                                            @foreach($client->phones as $phoneRec)
                                                <div><span class="font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest text-[9px] mr-1">{{ $phoneRec->label }}:</span> {{ $phoneRec->phone }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                          {{ $client->status === 'active'
                                              ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                              : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $client->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <button type="button" wire:click="editClient({{ $client->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Phones Section -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="add-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1 relative">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
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

            <!-- Company Name -->
            <div>
                <label for="edit_company_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="edit_company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Phones Section (Edit) -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="edit-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('phones.'.$index.'.label')" class="mt-1" />
                            </div>
                            <div class="flex-1 relative">
                                <input wire:model="phones.{{ $index }}.phone" type="tel" placeholder="e.g. +1 555-0199" max="20" maxlength="20"
                                       oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                       class="block w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <x-input-error :messages="$errors->get('phones.'.$index.'.phone')" class="mt-1" />
                            </div>
                            @if(count($phones) > 1)
                                <button type="button" wire:click="removePhoneField({{ $index }})" class="p-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-150 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
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
