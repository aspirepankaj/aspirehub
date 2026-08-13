@section('page_title', 'Staff Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Staff' => null]" />

    {{-- ══════════════════════════════════════════════
         PAGE HEADER — Title + Add Staff button
    ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Manage and track all your staff profiles, roles, and departments
        </p>

        {{-- Add Staff button --}}
        <button type="button" wire:click="openAddModal"
                style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 active:scale-95 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Staff</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTERS — 4-Column layout
    ══════════════════════════════════════════════ --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Search (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       autocomplete="off"
                       placeholder="Search by name, email..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
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

            {{-- Designation Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <select wire:model.live="designationFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Designations</option>
                    @foreach($designations as $desg)
                        <option value="{{ $desg->id }}" class="dark:bg-slate-900">{{ $desg->name }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Department Filter (25%) --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <select wire:model.live="departmentFilter"
                        class="block w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150 appearance-none">
                    <option value="" class="dark:bg-slate-900">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d }}" class="dark:bg-slate-900">{{ $d }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
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

    <!-- Staff Table Card -->
    <x-admin.card>

        {{-- Bulk Action Bar (visible only when items are selected) --}}
        @if(count($selectedStaff) > 0)
            <div class="flex items-center justify-between gap-3 mb-4 px-1 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/60 dark:border-indigo-700/30">
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2 pl-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ count($selectedStaff) }} Selected
                </span>
                <div class="flex gap-2 pr-1.5">
                    <button type="button" wire:click="bulkActivate"
                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white text-xs font-semibold rounded-lg shadow-sm shadow-emerald-500/20 transition-all">
                        Activate All
                    </button>
                    <button type="button" wire:click="bulkDeactivate"
                            class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 active:scale-95 text-slate-700 dark:text-slate-350 text-xs font-semibold rounded-lg transition-all">
                        Deactivate All
                    </button>
                </div>
            </div>
        @endif

        @if($Staff->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Staff Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new staff profile above.</p>
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
                            <th class="px-4 py-4">Staff Details</th>
                            <th class="px-4 py-4">Company Name</th>
                            <th class="px-4 py-4">Designation</th>
                            <th class="px-4 py-4">Department</th>
                            <th class="px-4 py-4">Phone Numbers</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Registered</th>
                            <th class="px-4 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($Staff as $staff)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors {{ in_array($staff->id, $selectedStaff) ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                                {{-- Row Checkbox --}}
                                <td class="px-4 py-4 w-10">
                                    <input type="checkbox"
                                           wire:model.live="selectedStaff"
                                           value="{{ $staff->id }}"
                                           class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0">
                                            @if($staff->profile_image)
                                                <img src="{{ asset('storage/' . $staff->profile_image) }}" alt="{{ $staff->user->name ?? 'Staff' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400'>{{ $staff->getInitials() }}</div>`" />
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                    {{ $staff->getInitials() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $staff->user->name ?? 'Deleted User' }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ $staff->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                                    {{ $staff->company_name ?: '—' }}
                                </td>
                                <td class="px-4 py-4 text-slate-650 dark:text-slate-350 text-sm font-medium">
                                    @if($staff->designations->isEmpty())
                                        <span class="text-slate-400 dark:text-slate-650">—</span>
                                    @else
                                        <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                            @foreach($staff->designations as $desg)
                                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-750 dark:text-slate-300 border border-slate-200/50 dark:border-slate-700/40">
                                                    {{ $desg->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($staff->department)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                            {{ $staff->department }}
                                        </span>
                                    @else
                                        <span class="text-slate-450 dark:text-slate-550 italic text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-500 dark:text-slate-400 font-medium text-xs">
                                    @if($staff->phones->isEmpty())
                                        {{ $staff->phone ?: '—' }}
                                    @else
                                        <div class="space-y-1">
                                            @foreach($staff->phones as $phoneRec)
                                                <div><span class="font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest text-[9px] mr-1">{{ $phoneRec->label }}:</span> {{ $phoneRec->phone }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                          {{ $staff->status === 'active'
                                              ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                              : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400 dark:text-slate-555 font-semibold">
                                    {{ $staff->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('impersonate.start', $staff->user_id) }}"
                                           class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90"
                                           title="Login as this Staff Member"
                                           onclick="return confirm('Are you sure you want to login as {{ $staff->user->name ?? 'this staff member' }}?')">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                        <button type="button" wire:click="editStaff({{ $staff->id }})"
                                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </tbody>
            </table>
            </div>

            <div class="mt-4">
                {{ $Staff->links() }}
            </div>
        @endif
    </x-admin.card>

    {{-- ══════════════════════════════════════════════
         ADD STAFF MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="add-staff-modal" title="Add New Staff" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'S', 0, 2)) }}</div>`" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="$dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
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
                <input wire:model="name" id="name" type="text" required autocomplete="new-name" placeholder="e.g. John Doe"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="staff_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="staff_email" type="email" required autocomplete="new-email" placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="staff_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password') }}</label>
                <input wire:model="password" id="staff_password" type="password" required autocomplete="new-password" placeholder="Min 8 characters"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Designation & Department -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Designations') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($designations as $desg)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="designation_ids" value="{{ $desg->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $desg->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('designation_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="department" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Department') }}</label>
                    <select wire:model="department" id="department"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="" class="dark:bg-slate-900">Select Department</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}" class="dark:bg-slate-900">{{ $d }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department')" class="mt-1" />
                </div>
            </div>

            <!-- Phones Section -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
                    @if(count($phones) < 5)
                        <button type="button" wire:click="addPhoneField" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Phone
                        </button>
                    @else
                        <span class="text-[10px] font-semibold text-amber-500 dark:text-amber-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Max 5 reached
                        </span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($phones as $index => $phoneItem)
                        <div class="flex items-start gap-3" wire:key="add-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
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
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
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
                <label for="staff_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="staff_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Enter any additional details about the staff..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-staff-modal' })" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveStaff" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="saveStaff" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Staff</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    {{-- ══════════════════════════════════════════════
         EDIT STAFF MODAL
    ══════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-staff-modal" title="Edit Staff" maxWidth="max-w-3xl">
        <div class="mt-2 flex flex-col gap-3">
            <!-- Profile Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Profile Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($profile_image)
                        <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'S', 0, 2)) }}</div>`" />
                    @elseif ($existing_profile_image)
                        <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 dark:border-slate-800" onerror="this.outerHTML=`<div class='w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400 font-bold'>{{ strtoupper(substr($name ?? 'S', 0, 2)) }}</div>`" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="$dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
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
                <label for="edit_staff_email" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_staff_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_staff_password" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Password (Leave blank to keep current)') }}</label>
                <input wire:model="password" id="edit_staff_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Company Name -->
            <div>
                <label for="edit_company_name" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Company Name') }}</label>
                <input wire:model="company_name" id="edit_company_name" type="text" placeholder="e.g. Acme Corp"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>

            <!-- Designation & Department -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-450 dark:text-slate-500 uppercase tracking-widest">{{ __('Designations') }}</label>
                    <div class="mt-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 max-h-36 overflow-y-auto space-y-2">
                        @foreach($designations as $desg)
                            <label class="flex items-center gap-2 text-sm text-slate-750 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" wire:model="designation_ids" value="{{ $desg->id }}"
                                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/40 focus:ring-2 cursor-pointer transition" />
                                <span>{{ $desg->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('designation_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="edit_department" class="block text-[10px] font-extrabold text-slate-450 dark:text-slate-500 uppercase tracking-widest">{{ __('Department') }}</label>
                    <select wire:model="department" id="edit_department"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="" class="dark:bg-slate-900">Select Department</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}" class="dark:bg-slate-900">{{ $d }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department')" class="mt-1" />
                </div>
            </div>

            <!-- Phones Section (Edit) -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 pt-3.5">
                <div class="flex items-center justify-between">
                    <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">Phone Numbers</label>
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
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
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
                                       class="block w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
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
                <label for="edit_staff_status" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="edit_staff_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the staff..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-staff-modal' })" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateStaff" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="updateStaff" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Staff</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
