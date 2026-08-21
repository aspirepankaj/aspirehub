<?php

namespace App\Modules\CRM\Clients\Livewire;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\ClickUp\Models\ClickUpFolder;
use App\Modules\CRM\ClickUp\Models\ClickUpSpace;
use App\Services\ClickUpService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class ManageClients extends Component
{
    use WithPagination;

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
    public ?int $assigned_staff_id = null;
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

    // ClickUp Mapping State
    public ?int $mappingClientId = null;
    public string $clickUpSpaceId = '';
    public string $clickUpFolderSearch = '';
    public array $selectedClickUpFolderIds = [];

    // ClickUp Tickets State in Client Detail View
    public array $clientClickUpTasks = [];
    public string $clickUpTaskStatusFilter = '';
    public string $clickUpTaskFolderFilter = '';
    public string $clickUpTaskSearch = '';
    public bool $clickUpTasksLoaded = false;

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
        $this->reset(['name', 'email', 'password', 'company_name', 'profile_image', 'existing_profile_image', 'phones', 'status', 'notes', 'editingClientId', 'editingUserId', 'plan_ids', 'assigned_staff_id', 'address', 'landmark', 'state', 'country', 'region', 'zip_code']);
        $this->phones = [['phone' => '', 'label' => 'Work']];
        $this->plan_ids = [];
        $this->assigned_staff_id = null;
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
            'profile_image' => 'nullable|string',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:adspv_plans,id',
            'assigned_staff_id' => 'nullable|exists:adspv_staff,id',
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

        $profileImagePath = $this->profile_image ?: null;

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
            $client->assignedStaff()->sync($this->assigned_staff_id ? [$this->assigned_staff_id] : []);
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
        $this->assigned_staff_id = $client->assignedStaff->first()?->id;

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
            'profile_image' => 'nullable|string',
            'phones' => 'array|min:1',
            'phones.*.phone' => 'required|string|max:30',
            'phones.*.label' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:adspv_plans,id',
            'assigned_staff_id' => 'nullable|exists:adspv_staff,id',
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
            $client->assignedStaff()->sync($this->assigned_staff_id ? [$this->assigned_staff_id] : []);
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

    /**
     * Open ClickUp Mapping Modal for a specific Client
     */
    public function openClickUpMappingModal(int $clientId): void
    {
        $client = Client::with('user')->findOrFail($clientId);
        $this->mappingClientId = $client->id;
        $this->clickUpSpaceId = ''; // No space selected by default
        $this->clickUpFolderSearch = '';
        $this->selectedClickUpFolderIds = ClickUpFolder::where('client_id', $client->id)->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $this->dispatch('open-modal', name: 'clickup-client-mapping-modal');
    }

    /**
     * Select active ClickUp Space in modal
     */
    public function selectClickUpSpace(string $spaceId): void
    {
        $this->clickUpSpaceId = $spaceId;
    }

    /**
     * Toggle individual folder selection in modal
     */
    public function toggleFolderSelection(string $folderId): void
    {
        $folderId = (string) $folderId;
        if (in_array($folderId, $this->selectedClickUpFolderIds, true)) {
            $this->selectedClickUpFolderIds = array_values(array_diff($this->selectedClickUpFolderIds, [$folderId]));
        } else {
            $this->selectedClickUpFolderIds[] = $folderId;
        }
    }

    /**
     * Sync ClickUp API in real-time
     */
    public function syncClickUpApi(ClickUpService $clickUpService): void
    {
        try {
            $res = $clickUpService->syncAll();
            if ($this->mappingClientId) {
                $this->selectedClickUpFolderIds = ClickUpFolder::where('client_id', $this->mappingClientId)->pluck('id')->map(fn($id) => (string)$id)->toArray();
            }
            session()->flash('success', "ClickUp API synced successfully! Updated {$res['synced_folders']} folders.");
        } catch (\Exception $e) {
            session()->flash('error', "ClickUp API Error: " . $e->getMessage());
        }
    }

    /**
     * Save folder mappings for active client
     */
    public function saveClickUpMapping(): void
    {
        if (!$this->mappingClientId) {
            return;
        }

        $client = Client::findOrFail($this->mappingClientId);
        $selectedIds = array_map('strval', array_values(array_filter($this->selectedClickUpFolderIds)));

        // 1. Unmap folders previously assigned to this client that are no longer selected
        ClickUpFolder::where('client_id', $client->id)
            ->whereNotIn('id', $selectedIds)
            ->update(['client_id' => null]);

        // 2. Map selected folders to this client
        if (!empty($selectedIds)) {
            ClickUpFolder::whereIn('id', $selectedIds)
                ->update(['client_id' => $client->id]);
        }

        $this->dispatch('close-modal', name: 'clickup-client-mapping-modal');
        $clientName = $client->company_name ?: ($client->user->name ?? 'Client');
        session()->flash('success', "ClickUp folder mappings saved for '{$clientName}' successfully!");
    }

    /**
     * Async background loader for ClickUp tickets
     */
    public function loadClickUpTasks(ClickUpService $clickUpService): void
    {
        if (!$this->selectedClientDetailId || $this->clickUpTasksLoaded) {
            return;
        }

        try {
            $this->clientClickUpTasks = $clickUpService->fetchClientTasks($this->selectedClientDetailId);
            $this->clickUpTasksLoaded = true;
        } catch (\Exception $e) {
            $this->clientClickUpTasks = [];
            $this->clickUpTasksLoaded = true;
        }
    }

    /**
     * Refresh ClickUp tickets for active client
     */
    public function syncClientClickUpTasks(ClickUpService $clickUpService): void
    {
        if (!$this->selectedClientDetailId) {
            return;
        }

        try {
            $this->clientClickUpTasks = $clickUpService->fetchClientTasks($this->selectedClientDetailId);
            $this->clickUpTasksLoaded = true;
            session()->flash('success', "ClickUp tickets refreshed successfully!");
        } catch (\Exception $e) {
            session()->flash('error', "Failed to load ClickUp tickets: " . $e->getMessage());
        }
    }

    public function render(ClickUpService $clickUpService)
    {
        $clientDetails = null;
        $clientWebsites = collect();
        $clientMaintenanceReports = collect();
        $clientDocuments = collect();
        $clientActivityLogs = collect();
        $clientClickUpFolders = collect();
        $filteredClickUpTasks = collect();

        if ($this->selectedClientDetailId) {
            $clients = collect();
            $hasActiveFilters = false;
            $pageIds = [];

            // 1. Basic Client Details (header & overview)
            $clientDetails = Client::with(['user', 'phones', 'plans', 'assignedStaff.user', 'assignedStaff.designations'])->findOrFail($this->selectedClientDetailId);
            
            // 2. Strict Tab-Based Data Loading (Only load what the active tab needs!)
            if ($this->activeTab === 'websites') {
                $clientWebsites = \App\Modules\CRM\Websites\Models\Website::with('latestMaintenanceReport')
                    ->where('client_id', $this->selectedClientDetailId)
                    ->latest()
                    ->get();
            }

            if ($this->activeTab === 'clickup_tickets') {
                $clientClickUpFolders = ClickUpFolder::where('client_id', $this->selectedClientDetailId)->get();

                $clickUpStatuses = collect($this->clientClickUpTasks)
                    ->pluck('status')
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();

                $filteredClickUpTasks = collect($this->clientClickUpTasks);

                if (!empty($this->clickUpTaskStatusFilter)) {
                    $sf = strtolower(trim($this->clickUpTaskStatusFilter));
                    $filteredClickUpTasks = $filteredClickUpTasks->filter(fn($t) => strtolower(trim($t['status'])) === $sf);
                }

                if (!empty($this->clickUpTaskFolderFilter)) {
                    $ff = (string) $this->clickUpTaskFolderFilter;
                    $filteredClickUpTasks = $filteredClickUpTasks->filter(fn($t) => (string) $t['folder_id'] === $ff);
                }
            }

            if ($this->activeTab === 'maintenance') {
                $clientMaintenanceReports = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['developer', 'website'])
                    ->where('client_id', $this->selectedClientDetailId)
                    ->latest()
                    ->paginate(10, ['*'], 'maintenancepage')
                    ->onEachSide(1);
            }

            if ($this->activeTab === 'documents') {
                $clientDocuments = \App\Modules\CRM\Documents\Models\Document::with('addedBy')
                    ->where('client_id', $this->selectedClientDetailId)
                    ->latest()
                    ->paginate(10, ['*'], 'documentspage')
                    ->onEachSide(1);
            }

            if ($this->activeTab === 'activity log' || $this->activeTab === 'activity') {
                $websiteIds = \App\Modules\CRM\Websites\Models\Website::where('client_id', $this->selectedClientDetailId)->pluck('id')->toArray();
                $reportIds  = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::where('client_id', $this->selectedClientDetailId)->pluck('id')->toArray();
                $docIds     = \App\Modules\CRM\Documents\Models\Document::where('client_id', $this->selectedClientDetailId)->pluck('id')->toArray();

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
            }
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

        // ClickUp Data for Mapping Modal
        $clickUpSpaces = ClickUpSpace::withCount('folders')->orderBy('name')->get();

        if (!empty($this->clickUpSpaceId)) {
            $clickUpFoldersQuery = ClickUpFolder::with('client.user')
                ->where('clickup_space_id', $this->clickUpSpaceId);

            if (!empty($this->clickUpFolderSearch)) {
                $searchTerm = '%' . trim($this->clickUpFolderSearch) . '%';
                $clickUpFoldersQuery->where('name', 'like', $searchTerm);
            }

            $clickUpFolders = $clickUpFoldersQuery->get()->sortBy(function ($folder) {
                $isAssigned = ($folder->client_id === $this->mappingClientId) 
                    || in_array((string) $folder->id, $this->selectedClickUpFolderIds, true);
                return [$isAssigned ? 0 : 1, strtolower($folder->name)];
            })->values();
        } else {
            $clickUpFolders = collect();
        }

        $mappingClient = $this->mappingClientId ? Client::with('user')->find($this->mappingClientId) : null;

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
            'clientClickUpFolders'     => $clientClickUpFolders,
            'clientClickUpTasks'       => $filteredClickUpTasks,
            'filteredClickUpTasks'     => $filteredClickUpTasks->values(),
            'clickUpStatuses'          => $clickUpStatuses ?? collect(),
            'clickUpSpaces'            => $clickUpSpaces,
            'clickUpFolders'           => $clickUpFolders,
            'mappingClient'            => $mappingClient,
        ])->layoutData(['title' => 'Clients Management - Aspire Hub']);
    }
}
