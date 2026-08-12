<?php

namespace App\Modules\CRM\Clients\Livewire;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageClients extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $company_name = '';
    public $profile_image;
    public ?string $existing_profile_image = null;
    public array $phones = []; // array of ['phone' => '', 'label' => 'Work']
    public string $status = 'active';
    public string $notes = '';
    public array $plan_ids = [];
    public array $assigned_staff_ids = [];
    public string $address = '';
    public string $landmark = '';
    public string $state = '';
    public string $country = '';
    public string $region = '';
    public string $zip_code = '';

    // Search & Filter
    public string $search = '';
    public string $statusFilter = '';
    public string $planFilter = '';

    // Bulk selection
    public array $selectedClients = [];
    public bool $selectAll = false;

    // Edit state tracking
    public ?int $editingClientId = null;
    public ?int $editingUserId = null;

    // Detail view state
    public ?int $selectedClientDetailId = null;
    public string $activeTab = 'overview';

    public function mount($id = null): void
    {
        if ($id) {
            $this->selectedClientDetailId = (int) $id;
        }
    }

    public function viewClientDetail(int $id)
    {
        $currentPage = $this->paginators['page'] ?? 1;
        session()->put('clients_list_page', $currentPage);
        
        return $this->redirect(route('admin.clients.detail', ['id' => $id]), navigate: true);
    }

    public function closeClientDetail()
    {
        $page = session()->get('clients_list_page', 1);
        session()->forget('clients_list_page');
        
        return $this->redirect(route('admin.clients', ['page' => $page]), navigate: true);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updatingSearch(): void
    {
        $this->selectedClients = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->selectedClients = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingPlanFilter(): void
    {
        $this->selectedClients = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->planFilter = '';
        $this->selectedClients = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedClients = $pageIds;
        } else {
            $this->selectedClients = [];
        }
    }

    public function clearSelection(): void
    {
        $this->selectedClients = [];
        $this->selectAll = false;
    }

    public function toggleClientStatus(int $id, string $status): void
    {
        $client = Client::findOrFail($id);
        $client->update(['status' => $status]);
        session()->flash('success', 'Client status updated to ' . $status . ' successfully.');
    }

    public function bulkActivate(): void
    {
        if (empty($this->selectedClients)) return;

        Client::whereIn('id', $this->selectedClients)->update(['status' => 'active']);
        $count = count($this->selectedClients);
        $this->selectedClients = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} client(s) activated successfully.");
    }

    public function bulkDeactivate(): void
    {
        if (empty($this->selectedClients)) return;

        Client::whereIn('id', $this->selectedClients)->update(['status' => 'inactive']);
        $count = count($this->selectedClients);
        $this->selectedClients = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} client(s) deactivated successfully.");
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'company_name', 'profile_image', 'existing_profile_image', 'phones', 'status', 'notes', 'editingClientId', 'editingUserId', 'plan_ids', 'assigned_staff_ids', 'address', 'landmark', 'state', 'country', 'region', 'zip_code']);
        $this->phones = [['phone' => '', 'label' => 'Work']];
        $this->plan_ids = [];
        $this->assigned_staff_ids = [];
        $this->address = '';
        $this->landmark = '';
        $this->state = '';
        $this->country = '';
        $this->region = '';
        $this->zip_code = '';
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
            'profile_image' => 'nullable|image|max:1024',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:adspv_plans,id',
            'assigned_staff_ids' => 'nullable|array',
            'assigned_staff_ids.*' => 'exists:adspv_staff,id',
            'address' => 'nullable|string|max:1000',
            'landmark' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
        ]);

        $profileImagePath = null;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_images', 'public');
        }

        DB::transaction(function () use ($profileImagePath) {
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
                'profile_image' => $profileImagePath,
                'status' => $this->status,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
                'address' => $this->address,
                'landmark' => $this->landmark,
                'state' => $this->state,
                'country' => $this->country,
                'region' => $this->region,
                'zip_code' => $this->zip_code,
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

            // 4. Sync plans
            $client->plans()->sync($this->plan_ids);

            // 5. Sync assigned staff
            $client->assignedStaff()->sync($this->assigned_staff_ids);
        });

        $this->dispatch('close-modal', name: 'add-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client created successfully!');
    }

    public function editClient($id)
    {
        $client = Client::with(['user', 'phones', 'plans', 'assignedStaff'])->findOrFail($id);

        $this->editingClientId = $client->id;
        $this->editingUserId = $client->user_id;
        $this->plan_ids = $client->plans->pluck('id')->toArray();
        $this->assigned_staff_ids = $client->assignedStaff->pluck('id')->toArray();

        $this->name = $client->user->name;
        $this->email = $client->user->email;
        $this->password = ''; // Leave password blank on edit unless updating
        $this->company_name = $client->company_name ?? '';
        $this->existing_profile_image = $client->profile_image;
        
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
        $this->address = $client->address ?? '';
        $this->landmark = $client->landmark ?? '';
        $this->state = $client->state ?? '';
        $this->country = $client->country ?? '';
        $this->region = $client->region ?? '';
        $this->zip_code = $client->zip_code ?? '';

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
            'profile_image' => 'nullable|image|max:1024',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:adspv_plans,id',
            'assigned_staff_ids' => 'nullable|array',
            'assigned_staff_ids.*' => 'exists:adspv_staff,id',
            'address' => 'nullable|string|max:1000',
            'landmark' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ], [], [
            'phones.*.phone' => 'phone number',
            'phones.*.label' => 'phone label',
        ]);

        $profileImagePath = $this->existing_profile_image;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_images', 'public');
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

            // 2. Update associated Client details
            $client = Client::findOrFail($this->editingClientId);
            $client->update([
                'company_name' => $this->company_name,
                'profile_image' => $profileImagePath,
                'status' => $this->status,
                'notes' => $this->notes,
                'edited_by' => auth()->id(),
                'address' => $this->address,
                'landmark' => $this->landmark,
                'state' => $this->state,
                'country' => $this->country,
                'region' => $this->region,
                'zip_code' => $this->zip_code,
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

            // 4. Sync plans
            $client->plans()->sync($this->plan_ids);

            // 5. Sync assigned staff
            $client->assignedStaff()->sync($this->assigned_staff_ids);
        });

        $this->dispatch('close-modal', name: 'edit-client-modal');
        $this->resetForm();
        session()->flash('success', 'Client updated successfully!');
    }

    public function getQueryString()
    {
        if ($this->selectedClientDetailId) {
            return [
                'activeTab' => ['as' => 'tab', 'history' => true],
            ];
        }
        return [];
    }

    public function queryStringHandlesPagination()
    {
        if ($this->selectedClientDetailId) {
            return collect($this->paginators)
                ->only(['activitypage', 'maintenancepage', 'documentspage'])
                ->mapWithKeys(function ($page, $pageName) {
                    return ['paginators.'.$pageName => ['history' => true, 'as' => $pageName, 'keep' => false]];
                })->toArray();
        }
        return [];
    }

    public function render()
    {
        $clientDetails = null;
        $clientWebsites = collect();
        $clientMaintenanceReports = collect();
        $clientDocuments = collect();
        $clientActivityLogs = collect();

        if ($this->selectedClientDetailId) {
            $clients = collect();
            $hasActiveFilters = false;
            $pageIds = [];

            $clientDetails = Client::with(['user', 'phones', 'plans', 'assignedStaff.user'])->findOrFail($this->selectedClientDetailId);
            
            $clientWebsites = \App\Modules\CRM\Websites\Models\Website::with('latestMaintenanceReport')
                ->where('client_id', $this->selectedClientDetailId)
                ->latest()
                ->get();
                
            $clientMaintenanceReports = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['developer', 'website'])
                ->where('client_id', $this->selectedClientDetailId)
                ->latest()
                ->paginate(10, ['*'], 'maintenancepage')
                ->onEachSide(1);
                
            $clientDocuments = \App\Modules\CRM\Documents\Models\Document::with('addedBy')
                ->where('client_id', $this->selectedClientDetailId)
                ->latest()
                ->paginate(10, ['*'], 'documentspage')
                ->onEachSide(1);

            $websiteIds = $clientWebsites->pluck('id')->toArray();
            $reportIds = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::where('client_id', $this->selectedClientDetailId)
                ->pluck('id')
                ->toArray();
            $docIds = \App\Modules\CRM\Documents\Models\Document::where('client_id', $this->selectedClientDetailId)
                ->pluck('id')
                ->toArray();

            $clientActivityLogs = \App\Modules\Core\Activity\Models\ActivityLog::with('user')
                ->where(function ($query) use ($websiteIds, $reportIds, $docIds) {
                    $query->where(function ($q) {
                        $q->where('loggable_type', \App\Modules\CRM\Clients\Models\Client::class)
                          ->where('loggable_id', $this->selectedClientDetailId);
                    })
                    ->orWhere(function ($q) {
                        $q->where('user_id', $this->selectedClientDetailId);
                    })
                    ->when(!empty($websiteIds), function ($q) use ($websiteIds) {
                        $q->orWhere(function ($sq) use ($websiteIds) {
                            $sq->where('loggable_type', \App\Modules\CRM\Websites\Models\Website::class)
                              ->whereIn('loggable_id', $websiteIds);
                        });
                    })
                    ->when(!empty($reportIds), function ($q) use ($reportIds) {
                        $q->orWhere(function ($sq) use ($reportIds) {
                            $sq->where('loggable_type', \App\Modules\CRM\Maintenance\Models\MaintenanceReport::class)
                              ->whereIn('loggable_id', $reportIds);
                        });
                    })
                    ->when(!empty($docIds), function ($q) use ($docIds) {
                        $q->orWhere(function ($sq) use ($docIds) {
                            $sq->where('loggable_type', \App\Modules\CRM\Documents\Models\Document::class)
                              ->whereIn('loggable_id', $docIds);
                        });
                    });
                })
                ->latest()
                ->paginate(10, ['*'], 'activitypage')
                ->onEachSide(1);
        } else {
            $clients = Client::with(['user', 'phones', 'plans', 'assignedStaff.user'])
                ->withCount('websites')
                ->where(function ($query) {
                    $query->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($uQuery) {
                            $uQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
                ->when($this->planFilter, fn($q) => $q->whereHas('plans', fn($pq) => $pq->where('plan_id', $this->planFilter)))
                ->latest('id')
                ->paginate(10)
                ->onEachSide(1);

            $hasActiveFilters = $this->search || $this->statusFilter || $this->planFilter;
            $pageIds = $clients->pluck('id')->toArray();
        }

        $plans = \App\Modules\CRM\Clients\Models\Plan::orderBy('name')->get();
        $staffMembers = \App\Modules\CRM\Staff\Models\Staff::with(['user', 'designations'])
            ->where('status', 'active')
            ->get()
            ->sortBy(fn($s) => $s->user?->name)
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->user?->name ?? 'Unknown Staff',
                'role' => $s->designations->pluck('name')->implode(', ')
            ])
            ->values();

        return view('modules.crm.clients.manage-clients', [
            'clients'                  => $clients,
            'plans'                    => $plans,
            'staffMembers'             => $staffMembers,
            'hasActiveFilters'         => $hasActiveFilters,
            'pageIds'                  => $pageIds,
            'clientDetails'            => $clientDetails,
            'clientWebsites'           => $clientWebsites,
            'clientMaintenanceReports' => $clientMaintenanceReports,
            'clientDocuments'          => $clientDocuments,
            'clientActivityLogs'       => $clientActivityLogs,
        ])->layoutData(['title' => 'Clients Management - Aspire Hub']);
    }
}
