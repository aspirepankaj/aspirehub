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
                            <img src="{{ $profile_image->temporaryUrl() }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500/30 shadow-md" />
                        @elseif ($existing_profile_image)
                            <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500/30 shadow-md" />
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-black text-3xl shadow-lg">
                                {{ strtoupper(substr($name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center sm:text-left flex-1">
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-semibold">{{ $company_name ?: 'Meridian Wellness Group' }}</p>
                    </div>

                    <div>
                        <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            Change photo
                            <input type="file" wire:model="profile_image" class="hidden" />
                        </label>
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
                
                @if ($accountManager)
                    <div class="flex items-center gap-3">
                        <div class="shrink-0">
                            @if ($accountManager->profile_image)
                                <img src="{{ asset('storage/' . $accountManager->profile_image) }}" class="w-10 h-10 rounded-full object-cover" />
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($accountManager->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $accountManager->name }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ $accountManager->department ?: 'Senior Account Manager' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs font-medium text-slate-650 dark:text-slate-350">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $accountManager->email }}" class="hover:text-indigo-500 transition">{{ $accountManager->email }}</a>
                        </div>
                        @if ($accountManager->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $accountManager->phone }}</span>
                            </div>
                        @endif
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
                    Plan
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ $plan->name ?? 'Standard Plan' }}
                    </h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">
                        Member since {{ $plan->created_at ? \Carbon\Carbon::parse($plan->created_at)->format('F Y') : 'April 2023' }}
                    </p>
                </div>
                <div class="pt-2">
                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        {{ ucfirst($client->status) }}
                    </span>
                </div>
            </div>

        </div>

    </div>

    <!-- Active Services Card (Col span full at bottom) -->
    <div class="mt-6 bg-white dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800/60 p-6 shadow-sm space-y-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Active services</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 font-semibold">Included with your {{ $plan->name ?? 'subscribed' }} plan.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pt-2">
            @php
                $allServices = [
                    'WordPress Maintenance',
                    'Google Ads Management',
                    'Website Hosting',
                    'SEO & Content',
                    'Meta Ads Management',
                    'Priority Support'
                ];
            @endphp
            @foreach ($allServices as $service)
                @php
                    // Check if this service name is in websiteServiceTypes or check dynamically
                    $isActive = in_array($service, $websiteServiceTypes) || (isset($plan) && str_contains(strtolower($plan->name), 'growth'));
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800/40 bg-slate-50/50 dark:bg-slate-900/30">
                    <span class="w-5 h-5 shrink-0 flex items-center justify-center rounded-full {{ $isActive ? 'bg-emerald-500/15 text-emerald-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-350' }}">
                        @if ($isActive)
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @endif
                    </span>
                    <span class="text-sm font-bold {{ $isActive ? 'text-slate-850 dark:text-slate-205' : 'text-slate-400 line-through' }}">
                        {{ $service }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
