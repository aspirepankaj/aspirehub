<?php

namespace App\Modules\CRM\Websites\Livewire;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageWebsites extends Component
{
    use WithPagination;

    // ─── Form Properties ────────────────────────────────────────────────────────
    public int|string $client_id = '';
    public string $site_name = '';
    public string $url = '';
    public int|string $service_type_id = '';
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

    // ─── Bulk Selection ──────────────────────────────────────────────────────────
    public array $selectedWebsites = [];
    public bool $selectAll = false;

    // ─── Edit State ─────────────────────────────────────────────────────────────
    public ?int $editingWebsiteId = null;
    public bool $passwordIsSet = false;   // flag: existing record has a password

    // ─── Password reveal toggle ──────────────────────────────────────────────────
    public bool $revealAddPassword = false;
    public bool $revealEditPassword = false;

    // ─── Validation Rules ───────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'client_id'        => 'required|exists:adspv_clients,id',
            'site_name'        => 'required|string|max:255',
            'url'              => 'required|url|max:255',
            'service_type_id'  => 'required|exists:adspv_service_types,id',
            'status'           => 'required|in:active,inactive,suspended',
            'admin_url'        => 'nullable|url|max:255',
            'admin_username'   => 'nullable|string|max:255',
            'admin_password'   => 'nullable|string|min:4',
            'hosting_provider' => 'nullable|string|max:255',
            'server_ip'        => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
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

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->serviceTypeFilter = '';
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

    public function bulkActivate(): void
    {
        if (empty($this->selectedWebsites)) return;

        Website::whereIn('id', $this->selectedWebsites)->update(['status' => 'active']);
        $count = count($this->selectedWebsites);
        $this->selectedWebsites = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} website(s) activated successfully.");
    }

    public function bulkDeactivate(): void
    {
        if (empty($this->selectedWebsites)) return;

        Website::whereIn('id', $this->selectedWebsites)->update(['status' => 'inactive']);
        $count = count($this->selectedWebsites);
        $this->selectedWebsites = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} website(s) deactivated successfully.");
    }

    public function resetForm(): void
    {
        $this->reset([
            'client_id', 'site_name', 'url', 'service_type_id', 'status',
            'admin_url', 'admin_username', 'admin_password',
            'hosting_provider', 'server_ip', 'notes',
            'editingWebsiteId', 'passwordIsSet',
            'revealAddPassword', 'revealEditPassword',
        ]);
        $this->status    = 'active';
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
            Website::create([
                'client_id'        => $this->client_id,
                'site_name'        => $this->site_name,
                'url'              => $this->url,
                'service_type_id'  => $this->service_type_id,
                'status'           => $this->status,
                'admin_url'        => $this->admin_url ?: null,
                'admin_username'   => $this->admin_username ?: null,
                'admin_password'   => $this->admin_password ?: null,
                'hosting_provider' => $this->hosting_provider ?: null,
                'server_ip'        => $this->server_ip ?: null,
                'notes'            => $this->notes ?: null,
                'added_by'         => auth()->id(),
            ]);
        });

        $this->dispatch('close-modal', name: 'add-website-modal');
        $this->resetForm();
        session()->flash('success', 'Website added successfully!');
    }

    // ─── Load for Edit ──────────────────────────────────────────────────────────
    public function editWebsite(int $id): void
    {
        $website = Website::findOrFail($id);

        $this->editingWebsiteId = $website->id;
        $this->client_id        = $website->client_id;
        $this->site_name        = $website->site_name;
        $this->url              = $website->url;
        $this->service_type_id  = $website->service_type_id;
        $this->status           = $website->status;
        $this->admin_url        = $website->admin_url ?? '';
        $this->admin_username   = $website->admin_username ?? '';
        $this->admin_password   = $website->admin_password ?? ''; // Load the decrypted password so the admin can see and edit it
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
        // Password optional on edit
        $rules['admin_password'] = 'nullable|string|min:4';
        $this->validate($rules);

        DB::transaction(function () {
            $website = Website::findOrFail($this->editingWebsiteId);

            $data = [
                'client_id'        => $this->client_id,
                'site_name'        => $this->site_name,
                'url'              => $this->url,
                'service_type_id'  => $this->service_type_id,
                'status'           => $this->status,
                'admin_url'        => $this->admin_url ?: null,
                'admin_username'   => $this->admin_username ?: null,
                'hosting_provider' => $this->hosting_provider ?: null,
                'server_ip'        => $this->server_ip ?: null,
                'notes'            => $this->notes ?: null,
                'edited_by'        => auth()->id(),
            ];

            // Only update password if a new one was provided
            if (!empty($this->admin_password)) {
                $data['admin_password'] = $this->admin_password;
            }

            $website->update($data);
        });

        $this->dispatch('close-modal', name: 'edit-website-modal');
        $this->resetForm();
        session()->flash('success', 'Website updated successfully!');
    }

    // ─── Render ─────────────────────────────────────────────────────────────────
    public function render()
    {
        $websites = Website::with(['client.user', 'serviceType'])
            ->when($this->search, function ($q) {
                $q->where(function($sq) {
                    $sq->where('site_name', 'like', '%' . $this->search . '%')
                      ->orWhere('url', 'like', '%' . $this->search . '%')
                      ->orWhereHas('client', function ($cq) {
                          $cq->where('company_name', 'like', '%' . $this->search . '%')
                             ->orWhereHas('user', fn($uq) =>
                                 $uq->where('name', 'like', '%' . $this->search . '%')
                             );
                      });
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->serviceTypeFilter, fn($q) => $q->where('service_type_id', $this->serviceTypeFilter))
            ->latest()
            ->paginate(12);

        $hasActiveFilters = $this->search || $this->statusFilter || $this->serviceTypeFilter;
        $pageIds = $websites->pluck('id')->toArray();

        $clients = Client::with('user')
            ->orderBy('company_name')
            ->get();

        $serviceTypes = \App\Modules\CRM\Websites\Models\ServiceType::orderBy('name')->get();

        return view('modules.crm.websites.manage-websites', [
            'websites'         => $websites,
            'clients'          => $clients,
            'serviceTypes'     => $serviceTypes,
            'hasActiveFilters' => $hasActiveFilters,
            'pageIds'          => $pageIds,
        ])->layoutData(['title' => 'Websites Management - Aspire Hub']);
    }
}
