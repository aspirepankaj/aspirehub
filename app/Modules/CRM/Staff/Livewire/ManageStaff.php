<?php

namespace App\Modules\CRM\Staff\Livewire;

use App\Models\User;
use App\Modules\CRM\Staff\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

#[Layout('layouts.admin')]
class ManageStaff extends Component
{
    use WithPagination;

    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $company_name = '';
    public array $designation_ids = [];
    public array $departments_list = [];
    public string $department = '';
    public $profile_image;
    public ?string $existing_profile_image = null;
    public array $phones = []; // array of ['phone' => '', 'label' => 'Work']
    public string $status = 'active';
    public string $notes = '';

    // Search & Filter
    public string $search = '';
    public string $statusFilter = '';
    public string $designationFilter = '';
    public string $departmentFilter = '';

    // Bulk selection
    public array $selectedStaff = [];
    public bool $selectAll = false;

    // Edit state tracking
    public ?int $editingStaffId = null;
    public ?int $editingUserId = null;

    // Detail view state
    public ?int $selectedStaffDetailId = null;

    #[Url(as: 'tab')]
    public string $activeTab = 'overview';

    // Dropdown options
    public array $departments = [
        'Client Success', 'Marketing', 'Engineering', 'Design',
        'Support', 'Operations', 'Sales', 'HR', 'Finance',
    ];

    public function mount($id = null): void
    {
        if ($id) {
            $this->selectedStaffDetailId = (int) $id;
        }
        if (request()->has('tab')) {
            $this->activeTab = (string) request()->get('tab');
        }
    }

    public function viewStaffDetail(int $id): void
    {
        $currentPage = $this->paginators['page'] ?? 1;
        session()->put('staff_list_page', $currentPage);

        $this->redirect(route('admin.staff.detail', ['id' => $id]), navigate: true);
    }

    public function closeStaffDetail(): void
    {
        $page = session()->get('staff_list_page', 1);
        session()->forget('staff_list_page');

        $this->redirect(route('admin.staff', ['page' => $page]), navigate: true);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updatingSearch(): void
    {
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingDesignationFilter(): void
    {
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingDepartmentFilter(): void
    {
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->designationFilter = '';
        $this->departmentFilter = '';
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedStaff = $pageIds;
        } else {
            $this->selectedStaff = [];
        }
    }

    public function bulkActivate(): void
    {
        if (empty($this->selectedStaff)) return;

        Staff::whereIn('id', $this->selectedStaff)->update(['status' => 'active']);
        $count = count($this->selectedStaff);
        $this->selectedStaff = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} staff member(s) activated successfully.");
    }

    public function bulkDeactivate(): void
    {
        if (empty($this->selectedStaff)) return;

        Staff::whereIn('id', $this->selectedStaff)->update(['status' => 'inactive']);
        $count = count($this->selectedStaff);
        $this->selectedStaff = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} staff member(s) deactivated successfully.");
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'company_name',
            'designation_ids',
            'departments_list',
            'department',
            'profile_image',
            'existing_profile_image',
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
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'designation_ids' => 'array',
            'designation_ids.*' => 'exists:adspv_designations,id',
            'departments_list' => 'nullable|array',
            'profile_image' => 'nullable|image|max:1024',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
        ]);

        $profileImagePath = $this->profile_image ?: null;

        DB::transaction(function () use ($profileImagePath) {
            // 1. Create standard User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $depts = array_values(array_filter($this->departments_list));
            $primaryDept = !empty($depts) ? $depts[0] : null;

            // 2. Create associated Staff details
            $staffRecord = Staff::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'department' => $primaryDept,
                'departments' => $depts,
                'profile_image' => $profileImagePath,
                'status' => $this->status,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);

            // Sync multiple designations
            $staffRecord->designations()->sync($this->designation_ids);

            // 3. Create multiple Staff phones
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $staffRecord->phones()->create([
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
        $staffRecord = Staff::with(['user', 'phones', 'designations'])->findOrFail($id);

        $this->editingStaffId = $staffRecord->id;
        $this->editingUserId = $staffRecord->user_id;

        $this->name = $staffRecord->user->name ?? 'Deleted User';
        $this->email = $staffRecord->user->email ?? '';
        $this->password = ''; // Leave password blank on edit unless updating
        $this->company_name = $staffRecord->company_name ?? '';
        $this->designation_ids = $staffRecord->designations->pluck('id')->toArray();
        $this->departments_list = $staffRecord->departments ?? ($staffRecord->department ? [$staffRecord->department] : []);
        $this->existing_profile_image = $staffRecord->profile_image;

        $this->phones = [];
        foreach ($staffRecord->phones as $phoneRecord) {
            $this->phones[] = [
                'phone' => $phoneRecord->phone,
                'label' => $phoneRecord->label,
            ];
        }
        if (empty($this->phones)) {
            $this->phones = [['phone' => '', 'label' => 'Work']];
        }

        $this->status = $staffRecord->status;
        $this->notes = $staffRecord->notes ?? '';

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
            'designation_ids' => 'array',
            'designation_ids.*' => 'exists:adspv_designations,id',
            'departments_list' => 'nullable|array',
            'profile_image' => 'nullable|image|max:1024',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
        ]);

        $profileImagePath = $this->existing_profile_image;
        if ($this->profile_image && $this->profile_image !== $this->existing_profile_image) {
            $profileImagePath = $this->profile_image;
        } elseif (empty($this->profile_image) && empty($this->existing_profile_image)) {
            $profileImagePath = null;
        }

        DB::transaction(function () use ($profileImagePath) {
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

            $depts = array_values(array_filter($this->departments_list));
            $primaryDept = !empty($depts) ? $depts[0] : null;

            // 2. Update associated Staff details
            $staffRecord = Staff::findOrFail($this->editingStaffId);
            $staffRecord->update([
                'company_name' => $this->company_name,
                'department' => $primaryDept,
                'departments' => $depts,
                'profile_image' => $profileImagePath,
                'status' => $this->status,
                'notes' => $this->notes,
                'edited_by' => auth()->id(),
            ]);

            // Sync multiple designations
            $staffRecord->designations()->sync($this->designation_ids);

            // 3. Sync Staff phones
            $staffRecord->phones()->delete();
            foreach ($this->phones as $phoneData) {
                if (!empty($phoneData['phone'])) {
                    $staffRecord->phones()->create([
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

    public function removeProfileImage(): void
    {
        $this->profile_image = null;
        $this->existing_profile_image = null;
    }

    #[On('media-selected')]
    public function setMedia($path, $field, $name = null, $mime_type = null, $size = null): void
    {
        if (property_exists($this, $field)) {
            $this->$field = $path;
        }
    }

    public function render()
    {
        // — Detail view mode —
        if ($this->selectedStaffDetailId) {
            $staffDetails = Staff::with([
                'user',
                'phones',
                'designations',
                'clients.user',
                'clients.plans',
                'clients.phones',
                'addedBy',
                'editedBy',
            ])->findOrFail($this->selectedStaffDetailId);

            // Websites of assigned clients
            $clientIds = $staffDetails->clients->pluck('id')->toArray();

            $staffWebsites = \App\Modules\CRM\Websites\Models\Website::with(['client.user', 'latestMaintenanceReport'])
                ->whereIn('client_id', $clientIds)
                ->latest()
                ->get();

            // Maintenance reports where this staff was developer
            $staffMaintenanceReports = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['client.user', 'website'])
                ->where('developer_id', $staffDetails->user_id)
                ->latest()
                ->paginate(10, ['*'], 'maintenancepage')
                ->onEachSide(1);

            // Activity logs for this staff user
            $staffActivityLogs = \App\Modules\Core\Activity\Models\ActivityLog::with('user')
                ->where(function ($query) use ($staffDetails) {
                    $query->where(function ($q) use ($staffDetails) {
                        $q->where('loggable_type', Staff::class)
                          ->where('loggable_id', $staffDetails->id);
                    })->orWhere(function ($q) use ($staffDetails) {
                        $q->where('user_id', $staffDetails->user_id);
                    });
                })
                ->latest()
                ->paginate(10, ['*'], 'activitypage')
                ->onEachSide(1);

            $designations = \App\Modules\CRM\Staff\Models\Designation::orderBy('name')->get();

            return view('modules.crm.staff.manage-staff', [
                'Staff'                   => collect(),
                'designations'            => $designations,
                'hasActiveFilters'        => false,
                'pageIds'                 => [],
                'staffDetails'            => $staffDetails,
                'staffWebsites'           => $staffWebsites,
                'staffMaintenanceReports' => $staffMaintenanceReports,
                'staffActivityLogs'       => $staffActivityLogs,
            ])->layoutData(['title' => ($staffDetails->user->name ?? 'Staff') . ' — Staff Detail']);
        }

        // — List view mode —
        $searchTerm = trim($this->search);

        $Staff = Staff::with(['user', 'phones', 'designations'])
            ->where(function ($query) use ($searchTerm) {
                $query->where('company_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('department', 'like', '%' . $searchTerm . '%')
                    ->orWhere('departments', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('designations', function ($dq) use ($searchTerm) {
                        $dq->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('user', function ($uQuery) use ($searchTerm) {
                        $uQuery->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->designationFilter, fn($q) => $q->whereHas('designations', fn($dq) => $dq->where('adspv_designations.id', $this->designationFilter)))
            ->when($this->departmentFilter, function ($q) {
                $dept = $this->departmentFilter;
                $q->where(function ($sq) use ($dept) {
                    $sq->where('departments', 'like', '%' . $dept . '%')
                       ->orWhere('department', $dept);
                });
            })
            ->latest()
            ->paginate(10)
            ->onEachSide(1);

        $hasActiveFilters = $this->search || $this->statusFilter || $this->designationFilter || $this->departmentFilter;
        $pageIds = $Staff->pluck('id')->toArray();

        $designations = \App\Modules\CRM\Staff\Models\Designation::orderBy('name')->get();


        $hasActiveFilters = $this->search || $this->statusFilter;
        $pageIds = $Staff->pluck('id')->toArray();

        $hasActiveFilters = $this->search || $this->statusFilter;
        $pageIds = $Staff->pluck('id')->toArray();

        $hasActiveFilters = $this->search || $this->statusFilter;
        $pageIds = $Staff->pluck('id')->toArray();

        return view('modules.crm.staff.manage-staff', [
            'Staff'                   => $Staff,
            'designations'            => $designations,
            'hasActiveFilters'        => $hasActiveFilters,
            'pageIds'                 => $pageIds,
            'staffDetails'            => null,
            'staffWebsites'           => collect(),
            'staffMaintenanceReports' => collect(),
            'staffActivityLogs'       => collect(),
        ])->layoutData(['title' => 'Staff Management - Aspire Hub']);
    }

}
