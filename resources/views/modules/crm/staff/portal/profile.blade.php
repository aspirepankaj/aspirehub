@section('page_title', 'My Profile')

<div class="space-y-6">
    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="['Profile' => null]" />

    <!-- Header Card -->
    <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl relative overflow-hidden bg-white/60 dark:bg-slate-900/40 backdrop-blur-md">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-pink-500/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex items-center space-x-4">
            @if(auth()->user()->staff?->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->staff->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800 shadow-lg shadow-pink-500/10" />
            @else
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-pink-500 to-indigo-500 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-pink-500/20">
                    {{ auth()->user()->staff ? auth()->user()->staff->getInitials() : strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Staff Account Details & Preferences</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 max-w-4xl">
        <!-- Profile Info Form -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl bg-white/65 dark:bg-slate-900/45 backdrop-blur-sm">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <!-- Password Reset Form -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl bg-white/65 dark:bg-slate-900/45 backdrop-blur-sm">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>
    </div>
</div>
