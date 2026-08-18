<div>
    <x-admin.breadcrumbs
        :items="[
            'My Profile' => null,
        ]"
    />

    <div class="mb-8">
        <div class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400">
            My Profile
        </div>
        <h1 class="mt-2 text-5xl font-black tracking-tight text-slate-900 dark:text-white">
            Your account.
        </h1>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left Side: Profile Details & Password Form (Col span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Profile Details Card -->
            <form wire:submit.prevent="updateProfile" class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-6">
                
                <!-- Avatar & Header info -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="relative shrink-0">
                        @if ($profile_image)
                            <img src="{{ $profile_image->temporaryUrl() }}" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-500/30 shadow-md" />
                        @elseif ($existing_profile_image && file_exists(public_path('storage/' . $existing_profile_image)))
                            <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-500/30 shadow-md" />
                        @else
                            <div class="w-20 h-20 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center font-bold text-2xl text-indigo-600 dark:text-indigo-400">
                                @php
                                    $words = explode(' ', $name);
                                    $initials = '';
                                    foreach ($words as $w) {
                                        $initials .= strtoupper(substr($w, 0, 1));
                                    }
                                    echo substr($initials, 0, 2);
                                @endphp
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center sm:text-left flex-1">
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-semibold">{{ $company_name ?: 'Meridian Wellness Group' }}</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                Change photo
                                <input type="file" wire:model="profile_image" class="hidden" />
                            </label>
                            @if ($profile_image || ($existing_profile_image && file_exists(public_path('storage/' . $existing_profile_image))))
                                <button type="button" wire:click="removeProfileImage" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 dark:border-red-800/50 text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                                    Remove photo
                                </button>
                            @endif
                        </div>
                        @error('profile_image')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Personal Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                        <input type="text" wire:model="phone" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('phone') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Company</label>
                        <input type="text" wire:model="company_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('company_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:bg-slate-800 dark:hover:bg-slate-100 transition shadow-sm">
                        Save changes
                    </button>
                </div>
            </form>

            <!-- Change Password Card -->
            <form wire:submit.prevent="updatePassword" class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Change password</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Current</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('current_password') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">New</label>
                        <input type="password" wire:model="new_password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                        @error('new_password') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Confirm</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
                        Update password
                    </button>
                </div>
            </form>

        </div>

        <!-- Right Side: Account Manager & Plan Info -->
        <div class="space-y-6">
            
            <!-- Account Manager Card -->
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
                <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    Your Account Manager
                </div>
                
                @if ($accountManagers->isNotEmpty())
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/60 -mx-6 px-6">
                        @foreach ($accountManagers as $manager)
                            <div class="py-4 first:pt-2 last:pb-2">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0">
                                        @if ($manager->profile_image)
                                            <img src="{{ Str::startsWith($manager->profile_image, 'http') ? $manager->profile_image : asset('storage/' . $manager->profile_image) }}" class="w-10 h-10 rounded-full object-cover shadow-sm" onerror="this.outerHTML=`<div class='w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center font-bold text-sm text-indigo-600 dark:text-indigo-400 shadow-sm'>@php $words = explode(' ', $manager->name); $initials = ''; foreach ($words as $w) { if (!empty($w)) { $initials .= strtoupper(substr($w, 0, 1)); } } echo substr($initials, 0, 2); @endphp</div>`" />
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center font-bold text-sm text-indigo-600 dark:text-indigo-400 shadow-sm">
                                                @php
                                                    $words = explode(' ', $manager->name);
                                                    $initials = '';
                                                    foreach ($words as $w) {
                                                        if (!empty($w)) {
                                                            $initials .= strtoupper(substr($w, 0, 1));
                                                        }
                                                    }
                                                    echo substr($initials, 0, 2);
                                                @endphp
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $manager->name }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ $manager->department ?: 'Account Manager' }}</p>
                                    </div>
                                </div>
                                

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm font-semibold text-slate-450 dark:text-slate-550 italic">
                        No account manager assigned yet.
                    </div>
                @endif
            </div>

            <!-- Subscribed Plan Card -->
            <div class="bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
                <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    Subscribed Plans
                </div>
                <div class="space-y-4 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($plans as $p)
                        <div class="pt-3 first:pt-0">
                            <h3 class="text-xl font-black text-slate-900 dark:text-white">
                                {{ $p->name }}
                            </h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">
                                Member since {{ \Carbon\Carbon::parse($p->created_at)->format('F Y') }}
                            </p>
                            <div class="pt-2">
                                <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm font-semibold text-slate-450 dark:text-slate-550 italic">
                            No active plans.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
