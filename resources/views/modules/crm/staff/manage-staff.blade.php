@section('page_title', 'Staff Management')

<div>
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Staff' => null]" />

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
                   class="block w-full pl-11 pr-4.5 py-2.5 rounded-xl bg-white/100 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
        </div>

        <!-- Add Staff Button -->
        <x-admin.button wire:click="openAddModal" size="md"  style="background: linear-gradient(90deg, #105166 0%, #529daa 100%);"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 w-full sm:w-auto space-x-2"> 
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Staff</span>
        </x-admin.button>
    </div>

    <!-- Success Message Alert -->
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    <!-- Staff Table Card -->
    <x-admin.card>
        @if($Staff->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Staffs Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search keyword or create a new staff profile above.</p>
            </div>
        @else
            <x-admin.table :headers="['Staff Details', 'Company Name', 'Phone Numbers', 'Status', 'Registered', 'Actions']">
                @foreach($Staff as $staff)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $staff->user->name ?? 'Deleted User' }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ $staff->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                            {{ $staff->company_name ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium text-xs">
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
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg 
                                  {{ $staff->status === 'active' 
                                      ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                      : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                {{ ucfirst($staff->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $staff->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" wire:click="editStaff({{ $staff->id }})" 
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
                {{ $Staff->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Add staff Modal -->
    <x-admin.modal name="add-staff-modal" title="Add New Staff">
        <div class="flex flex-col gap-3 mt-2">
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

            <!-- Phones Section -->
            <div class="space-y-2 border-t border-slate-100 dark:border-slate-800/50 ">
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
                        <div class="flex items-start gap-3" wire:key="add-phone-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model="phones.{{ $index }}.label" class="block w-full px-3 py-2.5 rounded-xl bg-white dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150">
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

    <!-- Edit Staff Modal -->
    <x-admin.modal name="edit-staff-modal" title="Edit Staff">
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
                <label for="edit_staff_email" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Email Address') }}</label>
                <input wire:model="email" id="edit_staff_email" type="email" required placeholder="john.doe@example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="edit_staff_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Password (Leave blank to keep current)') }}</label>
                <input wire:model="password" id="edit_staff_password" type="password" placeholder="Min 8 characters" autocomplete="new-password"
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
                <label for="edit_staff_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Account Status') }}</label>
                <select wire:model="status" id="edit_staff_status" 
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                    <option value="active" class="dark:bg-slate-900">Active</option>
                    <option value="inactive" class="dark:bg-slate-900">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Notes -->
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Notes') }}</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Enter any additional details about the staff..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-staff-modal' })" 
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
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
