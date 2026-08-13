<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component
{

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $profile_image;
    public ?string $existing_profile_image = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        if ($user->admin) {
            $this->phone = $user->admin->phone ?? '';
            $this->existing_profile_image = $user->admin->profile_image;
        } elseif ($user->staff) {
            $this->phone = $user->staff->phones()->first()?->phone ?? '';
            $this->existing_profile_image = $user->staff->profile_image;
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'profile_image' => ['nullable', 'string'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->admin) {
            $adminData = ['phone' => $validated['phone']];
            if ($this->profile_image !== null && $this->profile_image !== $this->existing_profile_image) {
                $adminData['profile_image'] = $this->profile_image;
                $this->existing_profile_image = $this->profile_image;
                $this->profile_image = null;
            }
            $user->admin->update($adminData);
        } elseif ($user->staff) {
            $staffData = [];
            if ($this->profile_image !== null && $this->profile_image !== $this->existing_profile_image) {
                $staffData['profile_image'] = $this->profile_image;
                $this->existing_profile_image = $this->profile_image;
                $this->profile_image = null;
            }
            $user->staff->update($staffData);

            if (!empty($validated['phone'])) {
                $phoneRecord = $user->staff->phones()->first();
                if ($phoneRecord) {
                    $phoneRecord->update(['phone' => $validated['phone']]);
                } else {
                    $user->staff->phones()->create([
                        'phone' => $validated['phone'],
                        'label' => 'Work',
                    ]);
                }
            }
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Remove the current user's profile image.
     */
    public function removeProfileImage(): void
    {
        $user = Auth::user();
        if ($user->admin && $user->admin->profile_image) {
            $user->admin->update(['profile_image' => null]);
        } elseif ($user->staff && $user->staff->profile_image) {
            $user->staff->update(['profile_image' => null]);
        }

        $this->profile_image = null;
        $this->existing_profile_image = null;

        $this->dispatch('profile-updated', name: $user->name);
    }

    #[On('media-selected')]
    public function setMedia($path, $field): void
    {
        if (property_exists($this, $field)) {
            $this->$field = $path;
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information, email address and profile picture.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Profile Image Field -->
        <div>
            <x-input-label :value="__('Profile Picture')" />
            <div class="mt-2 flex items-center gap-4">
                @if ($profile_image)
                    <img src="{{ Str::startsWith($profile_image, 'http') ? $profile_image : asset('storage/' . $profile_image) }}" class="w-16 h-16 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-md" onerror="this.outerHTML=`<div class='w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-500/20'>{{ auth()->user()->getInitials() }}</div>`" />
                @else
                    @if ($existing_profile_image)
                        <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-16 h-16 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-md" onerror="this.outerHTML=`<div class='w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-500/20'>{{ auth()->user()->getInitials() }}</div>`" />
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-500/20">
                            {{ auth()->user()->getInitials() }}
                        </div>
                    @endif
                @endif
                <div class="flex items-center gap-2">
                    <button type="button" @click="$dispatch('open-media-picker', { field: 'profile_image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>
                    @if ($profile_image || $existing_profile_image)
                        <button type="button" wire:click="removeProfileImage" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-800/50 text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                            {{ __('Remove') }}
                        </button>
                    @endif
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full" placeholder="e.g. +1 (555) 000-0000" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
