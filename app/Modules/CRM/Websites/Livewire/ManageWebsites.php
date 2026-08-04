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
    public string $site_type = 'maintenance';
    public string $status = 'active';
    public string $admin_url = '';
    public string $admin_username = '';
    public string $admin_password = '';
    public string $hosting_provider = '';
    public string $server_ip = '';
    public string $notes = '';

    // ─── Search ─────────────────────────────────────────────────────────────────
    public string $search = '';

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
            'site_type'        => 'required|in:maintenance,design,development,speed_optimisation,other',
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
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'client_id', 'site_name', 'url', 'site_type', 'status',
            'admin_url', 'admin_username', 'admin_password',
            'hosting_provider', 'server_ip', 'notes',
            'editingWebsiteId', 'passwordIsSet',
            'revealAddPassword', 'revealEditPassword',
        ]);
        $this->site_type = 'maintenance';
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
                'site_type'        => $this->site_type,
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
        $this->site_type        = $website->site_type;
        $this->status           = $website->status;
        $this->admin_url        = $website->admin_url ?? '';
        $this->admin_username   = $website->admin_username ?? '';
        // Don't expose the decrypted password in the form; show blank
        $this->admin_password   = '';
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
                'site_type'        => $this->site_type,
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
        $websites = Website::with(['client.user'])
            ->when($this->search, function ($q) {
                $q->where('site_name', 'like', '%' . $this->search . '%')
                  ->orWhere('url', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function ($cq) {
                      $cq->where('company_name', 'like', '%' . $this->search . '%')
                         ->orWhereHas('user', fn($uq) =>
                             $uq->where('name', 'like', '%' . $this->search . '%')
                         );
                  });
            })
            ->latest()
            ->paginate(12);

        $clients = Client::with('user')
            ->orderBy('company_name')
            ->get();

        return view('modules.crm.websites.manage-websites', [
            'websites' => $websites,
            'clients'  => $clients,
        ])->layoutData(['title' => 'Websites Management - Aspire Hub']);
    }
}
