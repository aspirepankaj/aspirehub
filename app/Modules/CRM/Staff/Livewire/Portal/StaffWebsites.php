<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.staff')]
class StaffWebsites extends Component
{
    use WithPagination;

    // ─── Form Properties ────────────────────────────────────────────────────────
    public int|string $client_id = '';
    public string $site_name = '';
    public string $url = '';
    public $image;
    public ?string $existing_image = null;
    public array $service_type_ids = [];
    public array $plan_ids = [];
    public string $status = 'active';
    public string $admin_url = '';
    public string $admin_username = '';
    public string $admin_password = '';
    public string $hosting_provider = '';
    public string $server_ip = '';
    public string $notes = '';

    // ─── Search & Filters ────────────────────────────────────────────────────────
    public string $search = '';
    public string $statusFilter = '';
    public string $serviceTypeFilter = '';
    public string $planFilter = '';

    // ─── Bulk Selection ──────────────────────────────────────────────────────────
    public array $selectedWebsites = [];
    public bool $selectAll = false;

    // ─── Edit State ─────────────────────────────────────────────────────────────
    public ?int $editingWebsiteId = null;
    public bool $passwordIsSet = false;   // flag: existing record has a password

    // ─── Password reveal toggle ──────────────────────────────────────────────────
    public bool $revealAddPassword = false;
    public bool $revealEditPassword = false;
    public bool $revealDetailPassword = false;

    // ─── Detail View State ──────────────────────────────────────────────────────
    public ?int $selectedWebsiteDetailId = null;
    public string $activeTab = 'overview';

    public function mount($id = null): void
    {
        if ($id) {
            $this->selectedWebsiteDetailId = (int) $id;
        }
    }

    public function viewWebsiteDetail(int $id): void
    {
        $currentPage = $this->paginators['page'] ?? 1;
        session()->put('staff_websites_list_page', $currentPage);

        $this->redirect(route('staff.websites.detail', ['id' => $id]), navigate: true);
    }

    public function closeWebsiteDetail(): void
    {
        $page = session()->get('staff_websites_list_page', 1);
        session()->forget('staff_websites_list_page');

        $this->redirect(route('staff.websites', ['page' => $page]), navigate: true);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function toggleDetailPassword(): void
    {
        $this->revealDetailPassword = !$this->revealDetailPassword;
    }

    // ─── Validation Rules ───────────────────────────────────────────────────────
    protected function rules(): array
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        return [
            'client_id'          => 'required|exists:adspv_clients,id|in:' . implode(',', $assignedClientIds),
            'site_name'          => 'required|string|max:255',
            'url'                => 'required|url|max:255',
            'image'              => 'nullable|string',
            'service_type_ids'   => 'required|array|min:1',
            'service_type_ids.*' => 'exists:adspv_service_types,id',
            'plan_ids'           => 'nullable|array',
            'plan_ids.*'         => 'exists:adspv_plans,id',
            'status'             => 'required|in:active,inactive,suspended',
            'admin_url'          => 'nullable|url|max:255',
            'admin_username'     => 'nullable|string|max:255',
            'admin_password'     => 'nullable|string|min:4',
            'hosting_provider'   => 'nullable|string|max:255',
            'server_ip'          => 'nullable|string|max:100',
            'notes'              => 'nullable|string',
        ];
    }

    public function updatingSearch(): void
    {
        $this->selectedWebsites = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->selectedWebsites = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingServiceTypeFilter(): void
    {
        $this->selectedWebsites = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingPlanFilter(): void
    {
        $this->selectedWebsites = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->serviceTypeFilter = '';
        $this->planFilter = '';
        $this->selectedWebsites = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedWebsites = $pageIds;
        } else {
            $this->selectedWebsites = [];
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'client_id', 'site_name', 'url', 'image', 'existing_image', 'service_type_ids', 'plan_ids', 'status',
            'admin_url', 'admin_username', 'admin_password',
            'hosting_provider', 'server_ip', 'notes',
            'editingWebsiteId', 'passwordIsSet',
            'revealAddPassword', 'revealEditPassword',
        ]);
        $this->status    = 'active';
        $this->service_type_ids = [];
        $this->plan_ids = [];
        $this->resetValidation();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-website-modal');
    }

    // ─── Create ─────────────────────────────────────────────────────────────────
    public function saveWebsite(): void
    {
        $this->validate();

        DB::transaction(function () {
            $imagePath = $this->image ?: null;

            $website = Website::create([
                'client_id'        => $this->client_id,
                'site_name'        => $this->site_name,
                'url'              => $this->url,
                'image'            => $imagePath,
                'status'           => $this->status,
                'admin_url'        => $this->admin_url ?: null,
                'admin_username'   => $this->admin_username ?: null,
                'admin_password'   => $this->admin_password ?: null,
                'hosting_provider' => $this->hosting_provider ?: null,
                'server_ip'        => $this->server_ip ?: null,
                'notes'            => $this->notes ?: null,
                'added_by'         => auth()->id(),
            ]);

            $website->serviceTypes()->sync($this->service_type_ids);
            $website->plans()->sync($this->plan_ids);
        });

        $this->dispatch('close-modal', name: 'add-website-modal');
        $this->resetForm();
        session()->flash('success', 'Website added successfully!');
    }

    // ─── Load for Edit ──────────────────────────────────────────────────────────
    public function editWebsite(int $id): void
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $website = Website::with(['serviceTypes', 'plans'])
            ->whereIn('client_id', $assignedClientIds)
            ->findOrFail($id);

        $this->editingWebsiteId = $website->id;
        $this->client_id        = $website->client_id;
        $this->site_name        = $website->site_name;
        $this->url              = $website->url;
        $this->existing_image   = $website->image;
        $this->image            = null;
        $this->service_type_ids = $website->serviceTypes->pluck('id')->toArray();
        $this->plan_ids         = $website->plans->pluck('id')->toArray();
        $this->status           = $website->status;
        $this->admin_url        = $website->admin_url ?? '';
        $this->admin_username   = $website->admin_username ?? '';
        $this->admin_password   = $website->admin_password ?? '';
        $this->passwordIsSet    = !empty($website->getRawOriginal('admin_password'));
        $this->hosting_provider = $website->hosting_provider ?? '';
        $this->server_ip        = $website->server_ip ?? '';
        $this->notes            = $website->notes ?? '';
        $this->revealEditPassword = false;

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-website-modal');
    }

    // ─── Update ─────────────────────────────────────────────────────────────────
    public function updateWebsite(): void
    {
        $rules = $this->rules();
        $rules['admin_password'] = 'nullable|string|min:4';
        $this->validate($rules);

        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        DB::transaction(function () use ($assignedClientIds) {
            $website = Website::whereIn('client_id', $assignedClientIds)->findOrFail($this->editingWebsiteId);

            $data = [
                'client_id'        => $this->client_id,
                'site_name'        => $this->site_name,
                'url'              => $this->url,
                'status'           => $this->status,
                'admin_url'        => $this->admin_url ?: null,
                'admin_username'   => $this->admin_username ?: null,
                'hosting_provider' => $this->hosting_provider ?: null,
                'server_ip'        => $this->server_ip ?: null,
                'notes'            => $this->notes ?: null,
                'edited_by'        => auth()->id(),
            ];

            if ($this->image && $this->image !== $website->image) {
                $data['image'] = $this->image;
            } else if (empty($this->existing_image) && empty($this->image) && $website->image) {
                $data['image'] = null;
            }

            if (!empty($this->admin_password)) {
                $data['admin_password'] = $this->admin_password;
            }

            $website->update($data);
            $website->serviceTypes()->sync($this->service_type_ids);
            $website->plans()->sync($this->plan_ids);
        });

        $this->dispatch('close-modal', name: 'edit-website-modal');
        $this->resetForm();
        session()->flash('success', 'Website updated successfully!');
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->existing_image = null;
    }

    #[On('media-selected')]
    public function setMedia($path, $field): void
    {
        if (property_exists($this, $field)) {
            $this->$field = $path;
            if ($field === 'image') {
                $this->existing_image = $path;
            }
        }
    }

    public function render()
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $clients = collect();
        $serviceTypes = collect();
        $plans = collect();

        // ── Detail View Mode ──────────────────────────────────────────────────
        if ($this->selectedWebsiteDetailId) {
            $websiteDetails = Website::with(['client.user', 'serviceTypes', 'plans', 'addedBy', 'editedBy'])
                ->whereIn('client_id', $assignedClientIds)
                ->findOrFail($this->selectedWebsiteDetailId);

            if ($this->editingWebsiteId) {
                $clients = Client::with('user')
                    ->whereHas('assignedStaff', function ($q) use ($staffId) {
                        $q->where('staff_id', $staffId);
                    })
                    ->orderBy('company_name')
                    ->get();
                $serviceTypes = \App\Modules\CRM\Websites\Models\ServiceType::orderBy('name')->get();
                $plans = \App\Modules\CRM\Clients\Models\Plan::orderBy('name')->get();
            }

            $websiteMaintenanceReports = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['developer', 'client.user'])
                ->where('website_id', $this->selectedWebsiteDetailId)
                ->latest()
                ->paginate(10, ['*'], 'maintenancepage')
                ->onEachSide(1);

            $websiteActivityLogs = \App\Modules\Core\Activity\Models\ActivityLog::with('user')
                ->where('loggable_type', Website::class)
                ->where('loggable_id', $this->selectedWebsiteDetailId)
                ->latest()
                ->paginate(10, ['*'], 'activitypage')
                ->onEachSide(1);

            return view('modules.crm.staff.portal.websites', [
                'websites'                  => collect(),
                'clients'                   => $clients,
                'serviceTypes'              => $serviceTypes,
                'plans'                     => $plans,
                'hasActiveFilters'          => false,
                'pageIds'                   => [],
                'websiteDetails'            => $websiteDetails,
                'websiteMaintenanceReports' => $websiteMaintenanceReports,
                'websiteActivityLogs'       => $websiteActivityLogs,
            ])->layoutData(['title' => $websiteDetails->site_name . ' — Website Detail']);
        }

        // ── List View Mode ────────────────────────────────────────────────────
        $clients = Client::with('user')
            ->whereHas('assignedStaff', function ($q) use ($staffId) {
                $q->where('staff_id', $staffId);
            })
            ->orderBy('company_name')
            ->get();
        $serviceTypes = \App\Modules\CRM\Websites\Models\ServiceType::orderBy('name')->get();
        $plans = \App\Modules\CRM\Clients\Models\Plan::orderBy('name')->get();

        $websites = Website::with(['client', 'latestMaintenanceReport', 'serviceTypes'])
            ->whereIn('client_id', $assignedClientIds)
            ->where(function ($query) {
                $query->where('site_name', 'like', '%' . $this->search . '%')
                    ->orWhere('url', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->serviceTypeFilter, fn($q) => $q->whereHas('serviceTypes', fn($sq) => $sq->where('service_type_id', $this->serviceTypeFilter)))
            ->when($this->planFilter, fn($q) => $q->whereHas('plans', fn($pq) => $pq->where('adspv_plans.id', $this->planFilter)))
            ->latest()
            ->paginate(12);

        $hasActiveFilters = $this->search || $this->statusFilter || $this->serviceTypeFilter || $this->planFilter;
        $pageIds = $websites->pluck('id')->toArray();

        return view('modules.crm.staff.portal.websites', [
            'websites'                  => $websites,
            'clients'                   => $clients,
            'serviceTypes'              => $serviceTypes,
            'plans'                     => $plans,
            'hasActiveFilters'          => $hasActiveFilters,
            'pageIds'                   => $pageIds,
            'websiteDetails'            => null,
            'websiteMaintenanceReports' => collect(),
            'websiteActivityLogs'       => collect(),
        ])->layoutData(['title' => 'Monitored Websites - Staff Portal']);
    }
}
