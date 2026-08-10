<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Documents\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffDocuments extends Component
{
    use WithPagination, WithFileUploads;

    // Tabs: 'client' or 'website'
    public string $activeTab = 'client';

    // Form fields
    public ?int $editingDocId = null;
    public string $title = '';
    public ?int $client_id = null;
    public ?int $website_id = null;
    public $file; // temporary uploaded file

    // Search and filters
    public string $search = '';
    public string $clientFilter = '';
    public string $websiteFilter = '';

    // Track active page select
    public bool $selectAll = false;
    public array $selectedDocs = [];

    // Preview state
    public ?int $previewingDocId = null;
    public ?string $previewUrl = null;
    public ?string $previewType = null;
    public ?string $previewTitle = null;
    public ?string $previewTextContent = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingClientFilter(): void
    {
        $this->websiteFilter = '';
        $this->resetPage();
    }

    public function updatingWebsiteFilter(): void
    {
        $this->resetPage();
    }

    public function updatingActiveTab(): void
    {
        $this->clearFilters();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->clientFilter = '';
        $this->websiteFilter = '';
        $this->selectedDocs = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset(['editingDocId', 'title', 'client_id', 'website_id', 'file']);
        $this->resetValidation();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-doc-modal');
    }

    public function updatedClientId($value): void
    {
        $this->website_id = null;
    }

    public function saveDocument()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:adspv_clients,id',
            'file' => 'required|file|max:10240', // 10MB max
        ];

        if ($this->activeTab === 'website') {
            $rules['website_id'] = 'required|exists:adspv_websites,id';
        } else {
            $this->website_id = null;
        }

        $this->validate($rules);

        $path = $this->file->store('documents', 'public');
        $fileName = $this->file->getClientOriginalName();
        $fileType = strtolower($this->file->getClientOriginalExtension());
        $fileSize = $this->file->getSize();

        Document::create([
            'client_id' => $this->client_id,
            'website_id' => $this->website_id,
            'title' => $this->title,
            'file_path' => $path,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'added_by' => auth()->id(),
        ]);

        $this->dispatch('close-modal', name: 'add-doc-modal');
        $this->resetForm();
        session()->flash('success', 'Document uploaded successfully!');
    }

    public function editDocument(int $id): void
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $doc = Document::whereIn('client_id', $assignedClientIds)->findOrFail($id);
        $this->editingDocId = $doc->id;
        $this->title = $doc->title;
        $this->client_id = $doc->client_id;
        $this->website_id = $doc->website_id;
        $this->file = null; 

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-doc-modal');
    }

    public function updateDocument()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:adspv_clients,id',
            'file' => 'nullable|file|max:10240',
        ];

        if ($this->activeTab === 'website') {
            $rules['website_id'] = 'required|exists:adspv_websites,id';
        } else {
            $this->website_id = null;
        }

        $this->validate($rules);

        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $doc = Document::whereIn('client_id', $assignedClientIds)->findOrFail($this->editingDocId);
        $updateData = [
            'title' => $this->title,
            'client_id' => $this->client_id,
            'website_id' => $this->website_id,
        ];

        if ($this->file) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            $path = $this->file->store('documents', 'public');
            $updateData['file_path'] = $path;
            $updateData['file_name'] = $this->file->getClientOriginalName();
            $updateData['file_type'] = strtolower($this->file->getClientOriginalExtension());
            $updateData['file_size'] = $this->file->getSize();
        }

        $doc->update($updateData);

        $this->dispatch('close-modal', name: 'edit-doc-modal');
        $this->resetForm();
        session()->flash('success', 'Document updated successfully!');
    }

    public function deleteDocument(int $id): void
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $doc = Document::whereIn('client_id', $assignedClientIds)->findOrFail($id);

        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();
        session()->flash('success', 'Document deleted successfully.');
    }

    public function downloadDocument(int $id)
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $doc = Document::whereIn('client_id', $assignedClientIds)->findOrFail($id);
        
        if (!Storage::disk('public')->exists($doc->file_path)) {
            session()->flash('error', 'File not found on storage.');
            return null;
        }

        return Storage::disk('public')->download($doc->file_path, $doc->file_name);
    }

    public function previewDocument(int $id): void
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $doc = Document::whereIn('client_id', $assignedClientIds)->findOrFail($id);
        $this->previewingDocId = $doc->id;
        $this->previewTitle = $doc->title;
        $this->previewType = strtolower($doc->file_type);
        $this->previewUrl = asset('storage/' . $doc->file_path);

        if (in_array($this->previewType, ['txt', 'csv', 'log'])) {
            if (Storage::disk('public')->exists($doc->file_path)) {
                $content = Storage::disk('public')->get($doc->file_path);
                $this->previewTextContent = mb_strimwidth($content, 0, 50000, "\n... [Truncated for preview] ...");
            } else {
                $this->previewTextContent = 'File not found on server.';
            }
        } else {
            $this->previewTextContent = null;
        }

        $this->dispatch('open-modal', name: 'preview-doc-modal');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedDocs)) return;

        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $docs = Document::whereIn('client_id', $assignedClientIds)->whereIn('id', $this->selectedDocs)->get();
        foreach ($docs as $doc) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            $doc->delete();
        }

        $count = count($docs);
        $this->selectedDocs = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} document(s) deleted permanently.");
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedDocs = $pageIds;
        } else {
            $this->selectedDocs = [];
        }
    }

    public function render()
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::where('assigned_staff_id', $staffId)->pluck('id')->toArray();

        $query = Document::with(['client.user', 'website', 'addedBy'])->whereIn('client_id', $assignedClientIds);

        if ($this->activeTab === 'website') {
            $query->whereNotNull('website_id');
        } else {
            $query->whereNull('website_id');
        }

        $query->where(function ($q) {
            $q->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('file_name', 'like', '%' . $this->search . '%')
                ->orWhereHas('client.user', function ($u) {
                    $u->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('website', function ($w) {
                    $w->where('site_name', 'like', '%' . $this->search . '%')
                      ->orWhere('url', 'like', '%' . $this->search . '%');
                });
        });

        if ($this->clientFilter) {
            $query->where('client_id', $this->clientFilter);
        }

        if ($this->websiteFilter) {
            $query->where('website_id', $this->websiteFilter);
        }

        $documents = $query->latest()->paginate(10);
        $pageIds = $documents->pluck('id')->toArray();

        $clients = Client::with('user')->where('assigned_staff_id', $staffId)->orderBy('company_name')->get();

        $websites = collect();
        if ($this->client_id) {
            $websites = Website::where('client_id', $this->client_id)->orderBy('site_name')->get();
        }

        $filterWebsites = collect();
        if ($this->clientFilter) {
            $filterWebsites = Website::where('client_id', $this->clientFilter)->orderBy('site_name')->get();
        }

        $allWebsites = Website::whereIn('client_id', $assignedClientIds)->orderBy('site_name')->get()->map(fn($w) => [
            'id' => $w->id,
            'name' => $w->site_name,
            'client_id' => $w->client_id,
        ]);

        $hasActiveFilters = $this->search || $this->clientFilter || $this->websiteFilter;

        return view('modules.crm.staff.portal.documents', [
            'documents' => $documents,
            'clients' => $clients,
            'websites' => $websites,
            'filterWebsites' => $filterWebsites,
            'allWebsites' => $allWebsites,
            'pageIds' => $pageIds,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'Documents Library - Staff Portal']);
    }
}
