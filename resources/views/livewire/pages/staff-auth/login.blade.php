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

        Session::regenerate();

        $this->redirectIntended(default: '/staffadspnl', navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Staff Email Address</label>
            <input wire:model="form.email" id="email" 
                   type="email" name="email" required autofocus autocomplete="username" 
                   placeholder="staff@aspirehub.com"
                   class="block mt-1.5 w-full px-4.5 py-3 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Password</label>
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
                <span class="ms-2 text-xs font-semibold text-slate-600 dark:text-slate-400">Remember me</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full mt-2 py-3.5 px-4 bg-gradient-to-r from-pink-500 to-indigo-600 hover:from-pink-600 hover:to-indigo-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/15 hover:shadow-indigo-500/25 transition duration-150 cursor-pointer flex items-center justify-center space-x-2">
                <span>Staff Sign In</span>
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
