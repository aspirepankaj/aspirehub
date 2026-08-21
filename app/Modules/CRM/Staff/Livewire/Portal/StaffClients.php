<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffClients extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $planFilter = '';
    
    // Client Detail modal or view tracking
    public ?int $selectedClientId = null;
    public string $activeTab = 'overview';
    public string $activeViewTab = 'my_clients'; // 'my_clients' or 'all_clients'

    public function setViewTab(string $tab): void
    {
        $this->activeViewTab = $tab;
        $this->resetPage();
    }

    // Page numbers for sub-tabs
    public int $activitypage = 1;
    public int $maintenancepage = 1;
    public int $documentspage = 1;

    public function mount($id = null): void
    {
        if ($id) {
            $this->selectedClientId = (int) $id;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPlanFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->planFilter = '';
        $this->resetPage();
    }

    // ClickUp Tickets State in Staff Client Detail View
    public array $clientClickUpTasks = [];
    public string $clickUpTaskStatusFilter = '';
    public string $clickUpTaskFolderFilter = '';
    public string $clickUpTaskSearch = '';
    public string $clickUpTaskAssigneeFilter = 'assigned_to_me'; // 'assigned_to_me' or 'all'
    public bool $clickUpTasksLoaded = false;

    public function selectClient(?int $id)
    {
        if ($id) {
            $currentPage = $this->paginators['page'] ?? 1;
            session()->put('staff_clients_list_page', $currentPage);
            $this->clickUpTasksLoaded = false;
            $this->clientClickUpTasks = [];
            return $this->redirect(route('staff.clients.detail', ['id' => $id]), navigate: true);
        } else {
            $page = session()->get('staff_clients_list_page', 1);
            session()->forget('staff_clients_list_page');
            return $this->redirect(route('staff.clients', ['page' => $page]), navigate: true);
        }
    }

    /**
     * Async background loader for ClickUp tickets
     */
    public function loadClickUpTasks(\App\Services\ClickUpService $clickUpService): void
    {
        if (!$this->selectedClientId || $this->clickUpTasksLoaded) {
            return;
        }

        try {
            $this->clientClickUpTasks = $clickUpService->fetchClientTasks($this->selectedClientId);
            $this->clickUpTasksLoaded = true;
        } catch (\Exception $e) {
            $this->clientClickUpTasks = [];
            $this->clickUpTasksLoaded = true;
        }
    }

    /**
     * Refresh ClickUp tickets for active client
     */
    public function syncClientClickUpTasks(\App\Services\ClickUpService $clickUpService): void
    {
        if (!$this->selectedClientId) {
            return;
        }

        try {
            $this->clientClickUpTasks = $clickUpService->fetchClientTasks($this->selectedClientId);
            $this->clickUpTasksLoaded = true;
            session()->flash('success', "ClickUp tickets refreshed successfully!");
        } catch (\Exception $e) {
            session()->flash('error', "Failed to load ClickUp tickets: " . $e->getMessage());
        }
    }

    public function render(\App\Services\ClickUpService $clickUpService)
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $staffUser = auth()->user();
        $staffEmail = strtolower(trim($staffUser->email ?? ''));
        $staffName = strtolower(trim($staffUser->name ?? ''));
        
        $clientDetails = null;
        $clientWebsites = collect();
        $clientMaintenanceReports = collect();
        $clientDocuments = collect();
        $clientActivityLogs = collect();
        $clientClickUpFolders = collect();
        $filteredClickUpTasks = collect();
        $isAssignedToStaff = false;

        if ($this->selectedClientId) {
            $clients = collect();
            
            $clientDetails = Client::with(['user', 'phones', 'plans', 'assignedStaff.user'])
                ->findOrFail($this->selectedClientId);

            $isAssignedToStaff = Client::where('id', $this->selectedClientId)
                ->whereHas('assignedStaff', fn($q) => $q->where('staff_id', $staffId))
                ->exists();

            if (!$isAssignedToStaff && $this->activeTab === 'clickup_tickets') {
                $this->activeTab = 'overview';
            }
            
            if ($this->activeTab === 'websites') {
                $clientWebsites = \App\Modules\CRM\Websites\Models\Website::with('latestMaintenanceReport')
                    ->where('client_id', $this->selectedClientId)
                    ->latest()
                    ->get();
            }

            if ($this->activeTab === 'clickup_tickets') {
                $clientClickUpFolders = \App\Modules\CRM\ClickUp\Models\ClickUpFolder::where('client_id', $this->selectedClientId)->get();

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
                    ->where('client_id', $this->selectedClientId)
                    ->latest()
                    ->paginate(10, ['*'], 'maintenancepage')
                    ->onEachSide(1);
            }
                
            if ($this->activeTab === 'documents') {
                $clientDocuments = \App\Modules\CRM\Documents\Models\Document::with('addedBy')
                    ->where('client_id', $this->selectedClientId)
                    ->latest()
                    ->paginate(10, ['*'], 'documentspage')
                    ->onEachSide(1);
            }

            if ($this->activeTab === 'activity log' || $this->activeTab === 'activity') {
                $websiteIds = \App\Modules\CRM\Websites\Models\Website::where('client_id', $this->selectedClientId)->pluck('id')->toArray();
                $reportIds  = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::where('client_id', $this->selectedClientId)->pluck('id')->toArray();
                $docIds     = \App\Modules\CRM\Documents\Models\Document::where('client_id', $this->selectedClientId)->pluck('id')->toArray();

                $clientActivityLogs = \App\Modules\Core\Activity\Models\ActivityLog::with('user')
                    ->where(function ($query) use ($websiteIds, $reportIds, $docIds) {
                        $query->where(function ($q) {
                            $q->where('loggable_type', Client::class)
                              ->where('loggable_id', $this->selectedClientId);
                        })
                        ->orWhere(function ($q) {
                            $q->where('user_id', $this->selectedClientId);
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
                ->when($this->activeViewTab === 'my_clients', function ($query) use ($staffId) {
                    $query->whereHas('assignedStaff', function ($q) use ($staffId) {
                        $q->where('staff_id', $staffId);
                    });
                })
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
        }

        $plans = \App\Modules\CRM\Clients\Models\Plan::orderBy('name')->get();
        $hasActiveFilters = $this->search || $this->statusFilter || $this->planFilter;

        $assignedToMeCountInClient = collect($this->clientClickUpTasks)->filter(function ($task) use ($staffEmail, $staffName) {
            if (empty($task['assignees'])) return false;
            foreach ($task['assignees'] as $assignee) {
                $aEmail = strtolower(trim($assignee['email'] ?? ''));
                $aName = strtolower(trim($assignee['username'] ?? ''));
                if ($staffEmail && $aEmail === $staffEmail) return true;
                if ($staffName && (str_contains($aName, $staffName) || str_contains($staffName, $aName))) return true;
            }
            return false;
        })->count();

        return view('modules.crm.staff.portal.clients', [
            'clients' => $clients,
            'clientDetails' => $clientDetails,
            'clientWebsites' => $clientWebsites,
            'clientMaintenanceReports' => $clientMaintenanceReports,
            'clientDocuments' => $clientDocuments,
            'clientActivityLogs' => $clientActivityLogs,
            'clientClickUpFolders' => $clientClickUpFolders,
            'filteredClickUpTasks' => $filteredClickUpTasks->values(),
            'clickUpStatuses' => $clickUpStatuses ?? collect(),
            'assignedToMeCountInClient' => $assignedToMeCountInClient,
            'isAssignedToStaff' => $isAssignedToStaff,
            'plans' => $plans,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'My Clients - Staff Portal']);
    }
}
