<x-admin-layout>
    @section('page_title', 'My Profile')

    <div class="space-y-6">
        <!-- Breadcrumbs -->
        <x-admin.breadcrumbs :items="['Profile' => null]" />

        <!-- Header Card -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-center space-x-4">
                @if(auth()->user()->admin?->profile_image)
                    <img src="{{ Str::startsWith(auth()->user()->admin->profile_image, 'http') ? auth()->user()->admin->profile_image : asset('storage/' . auth()->user()->admin->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800 shadow-lg shadow-indigo-500/10" onerror="this.outerHTML=`<div class='w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-indigo-500/20'>{{ auth()->user()->getInitials() }}</div>`" />
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-indigo-500/20">
                        {{ auth()->user()->getInitials() }}
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Admin Account Details & Preferences</p>
                    @if(auth()->user()->admin?->phone)
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ auth()->user()->admin->phone }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 max-w-4xl">
            <!-- Profile Info Form -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <!-- Password Reset Form -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
