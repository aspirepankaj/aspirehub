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

    public function selectClient(?int $id)
    {
        if ($id) {
            $currentPage = $this->paginators['page'] ?? 1;
            session()->put('staff_clients_list_page', $currentPage);
            return $this->redirect(route('staff.clients.detail', ['id' => $id]), navigate: true);
        } else {
            $page = session()->get('staff_clients_list_page', 1);
            session()->forget('staff_clients_list_page');
            return $this->redirect(route('staff.clients', ['page' => $page]), navigate: true);
        }
    }

    public function getQueryString()
    {
        if ($this->selectedClientId) {
            return [
                'activeTab' => ['as' => 'tab', 'history' => true],
            ];
        }
        return [];
    }

    public function queryStringHandlesPagination()
    {
        if ($this->selectedClientId) {
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
        $staffId = auth()->user()->staff->id ?? 0;
        
        $clientDetails = null;
        $clientWebsites = collect();
        $clientMaintenanceReports = collect();
        $clientDocuments = collect();
        $clientActivityLogs = collect();

        if ($this->selectedClientId) {
            $clients = collect();
            
            $clientDetails = Client::with(['user', 'phones', 'plans', 'assignedStaff.user'])
                ->findOrFail($this->selectedClientId);
            
            $clientWebsites = \App\Modules\CRM\Websites\Models\Website::with('latestMaintenanceReport')
                ->where('client_id', $this->selectedClientId)
                ->latest()
                ->get();
                
            $clientMaintenanceReports = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['developer', 'website'])
                ->where('client_id', $this->selectedClientId)
                ->latest()
                ->paginate(10, ['*'], 'maintenancepage')
                ->onEachSide(1);
                
            $clientDocuments = \App\Modules\CRM\Documents\Models\Document::with('addedBy')
                ->where('client_id', $this->selectedClientId)
                ->latest()
                ->paginate(10, ['*'], 'documentspage')
                ->onEachSide(1);

            $websiteIds = $clientWebsites->pluck('id')->toArray();
            $reportIds = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::where('client_id', $this->selectedClientId)
                ->pluck('id')
                ->toArray();
            $docIds = \App\Modules\CRM\Documents\Models\Document::where('client_id', $this->selectedClientId)
                ->pluck('id')
                ->toArray();

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
                ->latest()
                ->paginate(10)
                ->onEachSide(1);
        }

        $plans = \App\Modules\CRM\Clients\Models\Plan::orderBy('name')->get();
        $hasActiveFilters = $this->search || $this->statusFilter || $this->planFilter;

        return view('modules.crm.staff.portal.clients', [
            'clients' => $clients,
            'clientDetails' => $clientDetails,
            'clientWebsites' => $clientWebsites,
            'clientMaintenanceReports' => $clientMaintenanceReports,
            'clientDocuments' => $clientDocuments,
            'clientActivityLogs' => $clientActivityLogs,
            'plans' => $plans,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'My Clients - Staff Portal']);
    }
}
