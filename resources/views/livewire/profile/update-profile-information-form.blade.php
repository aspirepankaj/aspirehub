<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

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
            'profile_image' => ['nullable', 'image', 'max:1024'],
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
            if ($this->profile_image) {
                $path = $this->profile_image->store('profile_images', 'public');
                $adminData['profile_image'] = $path;
                $this->existing_profile_image = $path;
                $this->reset('profile_image');
            }
            $user->admin->update($adminData);
        } elseif ($user->staff) {
            $staffData = [];
            if ($this->profile_image) {
                $path = $this->profile_image->store('profile_images', 'public');
                $staffData['profile_image'] = $path;
                $this->existing_profile_image = $path;
                $this->reset('profile_image');
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
                    <img src="{{ $profile_image->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-md" />
                @else
                    @if ($existing_profile_image && file_exists(public_path('storage/' . $existing_profile_image)))
                        <img src="{{ asset('storage/' . $existing_profile_image) }}" class="w-16 h-16 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-md" />
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-500/20">
                            {{ strtoupper(substr($name, 0, 2)) }}
                        </div>
                    @endif
                @endif
                <input type="file" wire:model="profile_image" class="text-xs text-slate-550 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/30 dark:file:text-indigo-400 cursor-pointer" />
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
