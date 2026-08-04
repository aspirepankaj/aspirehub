<?php

namespace App\Modules\CRM\Clients\Livewire;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageClients extends Component
{
    use WithPagination;

    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $company_name = '';
    public string $phone = '';
    public string $status = 'active';
    public string $notes = '';

    // Search query
    public string $search = '';

    // Edit state tracking
    public ?int $editingClientId = null;
    public ?int $editingUserId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'company_name', 'phone', 'status', 'notes', 'editingClientId', 'editingUserId']);
        $this->resetValidation();
    }

    public function saveClient()
    {
        logger('saveClient reached! Name: ' . $this->name . ', Email: ' . $this->email);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () {
            // 1. Create standard User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // 2. Create associated Client details
            Client::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'phone' => $this->phone,
                'status' => $this->status,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);
        });

        $this->dispatch('close-modal', name: 'add-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client created successfully!');
    }

    public function editClient($id)
    {
        $client = Client::with('user')->findOrFail($id);

        $this->editingClientId = $client->id;
        $this->editingUserId = $client->user_id;

        $this->name = $client->user->name;
        $this->email = $client->user->email;
        $this->password = ''; // Leave password blank on edit unless updating
        $this->company_name = $client->company_name ?? '';
        $this->phone = $client->phone ?? '';
        $this->status = $client->status;
        $this->notes = $client->notes ?? '';

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-client-modal');
    }

    public function updateClient()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'password' => 'nullable|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () {
            // 1. Update standard User
            $user = User::findOrFail($this->editingUserId);
            $userUpdateData = [
                'name' => $this->name,
                'email' => $this->email,
            ];
            if (!empty($this->password)) {
                $userUpdateData['password'] = Hash::make($this->password);
            }
            $user->update($userUpdateData);

            // 2. Update associated Client details
            $client = Client::findOrFail($this->editingClientId);
            $client->update([
                'company_name' => $this->company_name,
                'phone' => $this->phone,
                'status' => $this->status,
                'notes' => $this->notes,
                'edited_by' => auth()->id(),
            ]);
        });

        $this->dispatch('close-modal', name: 'edit-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client updated successfully!');
    }

    public function render()
    {
        $clients = Client::with('user')
            ->where(function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($uQuery) {
                        $uQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('modules.crm.clients.manage-clients', [
            'clients' => $clients,
        ])->layoutData(['title' => 'Clients Management - Aspire Hub']);
    }
}
