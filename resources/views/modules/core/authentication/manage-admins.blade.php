@section('page_title', 'Admin Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Admins' => null]" />

    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Admin button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage and track all system administrator accounts and login roles
        </p>

        {{-- Add Admin button --}}
        <button type="button" wire:click="openAddModal"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Admin</span>
        </button>
    </div>

    {{-- Session alerts --}}
    @if(session('success'))
        <x-admin.alert type="success" class="mb-5" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-admin.alert type="danger" class="mb-5" :message="session('error')" />
    @endif

    {{-- ══════════════════════════════════════════════
         FILTERS — 2-Column layout matching CRM style
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            {{-- Search --}}
            <div class="relative flex items-center">
                <!-- Dummy inputs to consume browser credentials autofill -->
                <input type="text" style="display:none" autocomplete="username"/>
                <input type="password" style="display:none" autocomplete="current-password"/>
                
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       name="admin_search_keyword"
                       autocomplete="new-password"
                       placeholder="Search admins..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <select wire:model.live="statusFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Statuses</option>
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Filter Actions --}}
        @if($hasActiveFilters || !empty($selectedAdmins))
            <div class="flex items-center justify-between border-t border-slate-150 dark:border-slate-800/50 pt-3.5 mt-3.5">
                <div class="flex items-center gap-2">
                    @if($hasActiveFilters)
                        <button type="button" wire:click="clearFilters"
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-650 dark:text-slate-350 text-xs font-semibold rounded-lg transition-all">
                            Clear Filters
                        </button>
                    @endif
                </div>

                @if(!empty($selectedAdmins))
                    <button type="button"
                            wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete the {{ count($selectedAdmins) }} selected admin(s)?"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-650 text-white text-xs font-bold transition-all duration-150 active:scale-95 shadow-sm shadow-red-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Selected
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Main listings --}}
    @if($admins->isEmpty())
        <div class="text-center py-12">
            <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Admins Found</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new administrator account above.</p>
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
                                   class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                        </th>
                        <th class="px-4 py-4">Admin Details</th>
                        <th class="px-4 py-4">Role</th>
                        <th class="px-4 py-4">Phone</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Registered</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-850/40 text-sm">
                    @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($admin->id, $selectedAdmins) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                            <td class="px-4 py-4 w-10">
                                <input type="checkbox"
                                       wire:model.live="selectedAdmins"
                                       value="{{ $admin->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0">
                                        @if($admin->profile_image)
                                            <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="{{ $admin->user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400'>{{ $admin->getInitials() }}</div>`" />
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ $admin->getInitials() }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $admin->user->name ?? 'Deleted User' }}</div>
                                        <div class="text-xs text-slate-400 dark:text-slate-500">{{ $admin->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ $admin->role->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-300 font-medium">
                                {{ $admin->phone ?: '—' }}
                            </td>
                            <td class="px-4 py-4">
                                @if($admin->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-550 dark:bg-slate-800 dark:text-slate-400">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-400 dark:text-slate-500 text-xs">
                                {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($admin->user_id !== auth()->id())
                                        <a href="{{ route('impersonate.start', $admin->user_id) }}"
                                           class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150"
                                           title="Login as this Admin"
                                           onclick="return confirm('Are you sure you want to login as {{ $admin->user->name }}?')">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                    <button type="button" wire:click="editAdmin({{ $admin->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @if($admin->id !== auth()->user()->admin?->id)
                                        <button type="button" 
                                                wire:click="deleteAdmin({{ $admin->id }})"
                                                wire:confirm="Are you sure you want to delete this administrator account?"
                                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-red-650 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $admins->links() }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════
         ADD ADMIN MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="add-admin-modal" title="Add New Admin" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'A', 0, 2)) }}</div>`" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>

                    @if ($profile_image)
                        <button type="button" wire:click="removeProfileImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="name" type="text" required placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="admin_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="admin_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="admin_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="admin_password" type="password" required placeholder="Min 8 characters"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Phone -->
            <div>
                <label for="admin_phone" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Phone Number') }}</label>
                <input wire:model="phone" id="admin_phone" type="text" placeholder="e.g. +1 (555) 000-0000"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="admin_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="is_active" id="admin_status"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="1" class="dark:bg-slate-900">Active</option>
                    <option value="0" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-admin-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-750 active:scale-95 transition duration-150">
                    Cancel
                </button>
                <button type="button" wire:click="saveAdmin"
                        style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
                    <svg wire:loading wire:target="saveAdmin" class="animate-spin h-4 w-4 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Admin</span>
                </button>
            </div>
        </div>
    </x-admin.modal>

    {{-- ══════════════════════════════════════════════
         EDIT ADMIN MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-admin-modal" title="Edit Admin" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'A', 0, 2)) }}</div>`" />
                    @elseif ($existing_profile_image)
                        <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'A', 0, 2)) }}</div>`" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>

                    @if ($profile_image || $existing_profile_image)
                        <button type="button" wire:click="removeProfileImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
            </div>

            <!-- Name -->
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Full Name') }}</label>
                <input wire:model="name" id="edit_name" type="text" required placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="edit_admin_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_admin_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_admin_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="edit_admin_password" type="password" placeholder="Leave blank to keep current password"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Phone -->
            <div>
                <label for="edit_admin_phone" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Phone Number') }}</label>
                <input wire:model="phone" id="edit_admin_phone" type="text" placeholder="e.g. +1 (555) 000-0000"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="edit_admin_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="is_active" id="edit_admin_status"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="1" class="dark:bg-slate-900">Active</option>
                    <option value="0" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-admin-modal' })" 
                        class="px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-750 active:scale-95 transition duration-150">
                    Cancel
                </button>
                <button type="button" wire:click="updateAdmin"
                        style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
                    <svg wire:loading wire:target="updateAdmin" class="animate-spin h-4 w-4 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>
    </x-admin.modal>
</div>
