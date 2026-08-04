<x-admin-layout>
    @section('page_title', 'My Profile')

    <div class="space-y-6">
        <!-- Breadcrumbs -->
        <x-admin.breadcrumbs :items="['Profile' => null]" />

        <!-- Header Card -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200/50 dark:border-slate-800/50 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Admin Account Details & Preferences</p>
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

            <!-- Account Deletion Form -->
            <div class="glass-card p-6 rounded-3xl border border-red-200/50 dark:border-red-950/20 shadow-xl bg-red-500/[0.01]">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
