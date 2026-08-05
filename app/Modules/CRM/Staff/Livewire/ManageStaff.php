<?php

namespace App\Modules\CRM\Staff\Livewire;

use App\Models\User;
use App\Modules\CRM\Staff\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageStaff extends Component
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
    public ?int $editingStaffId = null;
    public ?int $editingUserId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'company_name',
            'phones',
            'notes',
            'editingStaffId',
            'editingUserId',
        ]);

        $this->status = 'active';

        $this->phones = [
            [
                'phone' => '',
                'label' => 'Work',
            ]
        ];

        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-staff-modal');
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

    public function saveStaff()
    {
        logger('saveStaff reached! Name: ' . $this->name . ', Email: ' . $this->email);

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

            // 2. Create associated Staff details
            $Staff = Staff::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'status' => $this->status,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);

            // 3. Create multiple Staff phones
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $Staff->phones()->create([
                        'phone' => $phoneData['phone'],
                        'label' => $phoneData['label'],
                    ]);
                }
            }
        });

        $this->dispatch('close-modal', name: 'add-staff-modal');
        $this->resetForm();
        session()->flash('success', 'Staff created successfully!');
    }

    public function editStaff($id)
    {
        $Staff = Staff::with(['user', 'phones'])->findOrFail($id);

        $this->editingStaffId = $Staff->id;
        $this->editingUserId = $Staff->user_id;

        $this->name = $Staff->user->name;
        $this->email = $Staff->user->email;
        $this->password = ''; // Leave password blank on edit unless updating
        $this->company_name = $Staff->company_name ?? '';
        
        $this->phones = [];
        foreach ($Staff->phones as $phoneRecord) {
            $this->phones[] = [
                'phone' => $phoneRecord->phone,
                'label' => $phoneRecord->label,
            ];
        }
        if (empty($this->phones)) {
            $this->phones = [['phone' => '', 'label' => 'Work']];
        }

        $this->status = $Staff->status;
        $this->notes = $Staff->notes ?? '';

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-staff-modal');
    }

    public function updateStaff()
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

            // 2. Update associated Staff details
            $Staff = Staff::findOrFail($this->editingStaffId);
            $Staff->update([
                'company_name' => $this->company_name,
                'status' => $this->status,
                'notes' => $this->notes,
                'edited_by' => auth()->id(),
            ]);

            // 3. Sync Staff phones
            $Staff->phones()->delete();
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $Staff->phones()->create([
                        'phone' => $phoneData['phone'],
                        'label' => $phoneData['label'],
                    ]);
                }
            }
        });

        $this->dispatch('close-modal', name: 'edit-staff-modal');
        $this->resetForm();
        session()->flash('success', 'Staff updated successfully!');
    }

    public function render()
    {
        $Staff = Staff::with(['user', 'phones'])
            ->where(function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($uQuery) {
                        $uQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('modules.crm.staff.manage-staff', [
            'Staff' => $Staff,
        ])->layoutData(['title' => 'Staff Management - Aspire Hub']);
    }
}
