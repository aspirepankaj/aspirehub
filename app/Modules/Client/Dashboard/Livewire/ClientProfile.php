<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

#[Layout('layouts.client-portal')]
class ClientProfile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $company_name = '';
    
    public $profile_image;
    public ?string $existing_profile_image = null;

    // Password Update
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->company_name = $client->company_name ?? '';
        $this->existing_profile_image = $client->profile_image ?? null;

        if ($client) {
            $phoneRecord = DB::table('adspv_client_phones')
                ->where('client_id', $client->id)
                ->first();
            $this->phone = $phoneRecord->phone ?? '';
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        // Update User
        DB::table('users')->where('id', $user->id)->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Handle profile image upload
        $imagePath = $this->existing_profile_image;
        if ($this->profile_image) {
            $imagePath = $this->profile_image->store('profile-photos', 'public');
        }

        // Update Client details
        DB::table('adspv_clients')->where('user_id', $user->id)->update([
            'company_name' => $this->company_name,
            'profile_image' => $imagePath,
            'updated_at' => now(),
        ]);

        // Update Client phone number
        if ($client) {
            $phoneRecord = DB::table('adspv_client_phones')
                ->where('client_id', $client->id)
                ->first();

            if ($this->phone) {
                if ($phoneRecord) {
                    DB::table('adspv_client_phones')
                        ->where('id', $phoneRecord->id)
                        ->update([
                            'phone' => $this->phone,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('adspv_client_phones')->insert([
                        'client_id' => $client->id,
                        'phone' => $this->phone,
                        'label' => 'Work',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                if ($phoneRecord) {
                    DB::table('adspv_client_phones')->where('id', $phoneRecord->id)->delete();
                }
            }
        }

        $this->existing_profile_image = $imagePath;
        $this->profile_image = null;

        session()->flash('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success', 'Password updated successfully!');
    }

    public function render()
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();

        // 1. Account Manager Info
        $accountManager = DB::table('adspv_client_staff as cs')
            ->join('adspv_staff as s', 's.id', '=', 'cs.staff_id')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->where('cs.client_id', $client->id)
            ->select('u.name', 'u.email', 's.profile_image')
            ->first();

        // 2. Subscribed Plan Info
        $plans = DB::table('adspv_client_plan as cp')
            ->join('adspv_plans as p', 'p.id', '=', 'cp.plan_id')
            ->where('cp.client_id', $client->id)
            ->select('p.name', 'cp.created_at')
            ->get();

        // 3. Active Services (Retrieve website service types)
        $websiteServiceTypes = DB::table('adspv_websites as w')
            ->join('adspv_website_service_type as wst', 'wst.website_id', '=', 'w.id')
            ->join('adspv_service_types as st', 'st.id', '=', 'wst.service_type_id')
            ->where('w.client_id', $client->id)
            ->pluck('st.name')
            ->unique()
            ->toArray();

        return view('modules.client.dashboard.client-profile', [
            'accountManager' => $accountManager,
            'plans' => $plans,
            'websiteServiceTypes' => $websiteServiceTypes,
            'client' => $client,
        ]);
    }
}
