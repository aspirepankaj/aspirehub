<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-5 text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Email Address') }}</label>
            <input wire:model="email" id="email" 
                   type="email" name="email" required autofocus 
                   placeholder="admin@aspirehub.com"
                   class="block mt-1.5 w-full px-4.5 py-3 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <a class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:underline" href="{{ route('admin.login') }}" wire:navigate>
                Back to Sign In
            </a>
            
            <button type="submit" class="py-3 px-5 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/15 hover:shadow-indigo-500/25 transition duration-150 cursor-pointer flex items-center justify-center space-x-2">
                <span>Send Reset Link</span>
            </button>
        </div>
    </form>
</div>
