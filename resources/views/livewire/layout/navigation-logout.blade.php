<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center justify-between p-2 rounded-xl bg-slate-100/50 dark:bg-slate-900/50 border border-slate-200/30 dark:border-slate-800/30">
    <div class="flex items-center space-x-2.5 min-w-0">
        @php
            $profileImage = null;
            if (auth()->user()->admin?->profile_image) {
                $profileImage = auth()->user()->admin->profile_image;
            } elseif (auth()->user()->staff?->profile_image) {
                $profileImage = auth()->user()->staff->profile_image;
            } elseif (auth()->user()->client?->profile_image) {
                $profileImage = auth()->user()->client->profile_image;
            }
        @endphp
        @if($profileImage)
            <img src="{{ Str::startsWith($profileImage, 'http') ? $profileImage : asset('storage/' . $profileImage) }}" alt="{{ auth()->user()->name }}" class="flex-shrink-0 w-9 h-9 rounded-xl object-cover border border-slate-200/50 dark:border-slate-800/50 shadow-sm" onerror="this.outerHTML=`<div class='flex-shrink-0 w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm'>{{ auth()->user()->getInitials() }}</div>`" />
        @else
            <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                {{ auth()->user()->getInitials() }}
            </div>
        @endif
        <div class="min-w-0">
            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ auth()->user()->name }}</p>
            <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500 truncate">{{ auth()->user()->email }}</p>
        </div>
    </div>
    
    <button wire:click="logout" class="p-1.5 text-slate-400 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 rounded-lg hover:bg-red-500/10 transition-colors" title="Logout">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
    </button>
</div>
