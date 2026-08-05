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
    public array $phones = []; // array of ['phone' => '', 'label' => 'Work']
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
        $this->reset(['name', 'email', 'password', 'company_name', 'phones', 'status', 'notes', 'editingClientId', 'editingUserId']);
        $this->phones = [['phone' => '', 'label' => 'Work']];
        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-client-modal');
    }

    public function addPhoneField()
    {
        if (count($this->phones) < 5) {
            $this->phones[] = ['phone' => '', 'label' => 'Work'];
        } else {
            session()->flash('error', 'You can add a maximum of 5 phone numbers.');
        }
    }

    public function removePhoneField($index)
    {
        unset($this->phones[$index]);
        $this->phones = array_values($this->phones);
        if (empty($this->phones)) {
            $this->addPhoneField();
        }
    }

    public function saveClient()
    {
        logger('saveClient reached! Name: ' . $this->name . ', Email: ' . $this->email);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
        ]);

        DB::transaction(function () {
            // 1. Create standard User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // 2. Create associated Client details
            $client = Client::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'status' => $this->status,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);

            // 3. Create multiple client phones
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $client->phones()->create([
                        'phone' => $phoneData['phone'],
                        'label' => $phoneData['label'],
                    ]);
                }
            }
        });

        $this->dispatch('close-modal', name: 'add-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client created successfully!');
    }

    public function editClient($id)
    {
        $client = Client::with(['user', 'phones'])->findOrFail($id);

        $this->editingClientId = $client->id;
        $this->editingUserId = $client->user_id;

        $this->name = $client->user->name;
        $this->email = $client->user->email;
        $this->password = ''; // Leave password blank on edit unless updating
        $this->company_name = $client->company_name ?? '';
        
        $this->phones = [];
        foreach ($client->phones as $phoneRecord) {
            $this->phones[] = [
                'phone' => $phoneRecord->phone,
                'label' => $phoneRecord->label,
            ];
        }
        if (empty($this->phones)) {
            $this->phones = [['phone' => '', 'label' => 'Work']];
        }

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
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
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
                'status' => $this->status,
                'notes' => $this->notes,
                'edited_by' => auth()->id(),
            ]);

            // 3. Sync client phones
            $client->phones()->delete();
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $client->phones()->create([
                        'phone' => $phoneData['phone'],
                        'label' => $phoneData['label'],
                    ]);
                }
            }
        });

        $this->dispatch('close-modal', name: 'edit-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client updated successfully!');
    }

    public function render()
    {
        $clients = Client::with(['user', 'phones'])
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
