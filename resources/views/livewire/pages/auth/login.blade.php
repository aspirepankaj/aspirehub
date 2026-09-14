<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->client) {
            \Illuminate\Support\Facades\Auth::logout();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'form.email' => 'Access denied. You do not have client privileges.',
            ]);
        }

        Session::regenerate();

        $user->client->update(['last_login_at' => now()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Email Address') }}</label>
            <input wire:model="form.email" id="email" 
                   type="email" name="email" required autofocus autocomplete="username" 
                   placeholder="admin@aspiredigitalsolutions.com"
                   class="block mt-1.5 w-full px-4.5 py-3 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" 
                   type="password" name="password" required autocomplete="current-password" 
                   placeholder="••••••••"
                   class="block mt-1.5 w-full px-4.5 py-3 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" 
                       class="rounded border-slate-200/60 dark:border-slate-800/50 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-slate-900/50" name="remember">
                <span class="ms-2 text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" wire:loading.attr="disabled" class="disabled:opacity-75 disabled:cursor-not-allowed w-full mt-2 py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-pink-500 hover:from-indigo-700 hover:to-pink-600 active:scale-95 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/35 transition duration-150 cursor-pointer flex items-center justify-center space-x-2 border border-white/10">
                <svg wire:loading wire:target="login" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="login">Sign In</span>
                <span wire:loading wire:target="login">Signing In...</span>
                <svg wire:loading.remove wire:target="login" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
