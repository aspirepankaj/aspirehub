<x-admin-layout>
    @section('page_title', $title)

    <!-- Breadcrumbs -->
    <x-admin.breadcrumbs :items="[$title => null]" />

    <x-admin.card class="text-center py-16 relative overflow-hidden">
        <!-- Floating details -->
        <div class="absolute -top-16 -left-16 w-36 h-36 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-16 -right-16 w-36 h-36 bg-pink-500/10 rounded-full blur-2xl"></div>

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400 font-bold mb-6">
            <!-- Animated spinning gear icon -->
            <svg class="w-10 h-10 animate-spin" style="animation-duration: 6s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-2">{{ $title }}</h2>
        <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 font-medium text-sm leading-relaxed">
            This module is prepared as part of the Phase 1 Folder Architecture. The underlying business logic and database tables will be built in Phase 2.
        </p>

        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150" wire:navigate>
                Back to Dashboard
            </a>
        </div>
    </x-admin.card>
</x-admin-layout>
