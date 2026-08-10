<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.client-portal')]
class ClientDocuments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedCategory = 'All';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
        $this->resetPage();
    }

    public function downloadDocument(int $id)
    {
        $doc = DB::table('adspv_documents')->where('id', $id)->first();
        if ($doc && $doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            return Storage::disk('public')->download($doc->file_path, $doc->file_name);
        }
        session()->flash('error', 'File not found on server.');
    }

    public function getCategory($doc)
    {
        $name = strtolower($doc->file_name . ' ' . $doc->title);
        
        if (str_contains($name, 'report') || str_contains($name, 'maintenance') || str_contains($name, 'marketing') || str_contains($name, 'analytics')) {
            return 'Reports';
        }
        if (str_contains($name, 'invoice') || str_contains($name, 'bill')) {
            return 'Invoices';
        }
        if (str_contains($name, 'contract') || str_contains($name, 'agreement') || str_contains($name, 'terms')) {
            return 'Contracts';
        }
        if (str_contains($name, 'guide') || str_contains($name, 'manual') || str_contains($name, 'tutorial') || str_contains($name, 'sheet') || str_contains($name, 'url')) {
            return 'Guides';
        }
        if (str_contains($name, 'training') || str_contains($name, 'onboarding') || str_contains($name, 'course')) {
            return 'Training';
        }
        return 'Downloads';
    }

    public function render()
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();

        abort_if(!$client, 404, 'Client not found.');

        // Get all client documents linked to a website
        $allDocs = DB::table('adspv_documents as d')
            ->join('adspv_websites as w', 'w.id', '=', 'd.website_id')
            ->where('d.client_id', $client->id)
            ->whereNotNull('d.website_id')
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('d.title', 'like', '%' . $this->search . '%')
                        ->orWhere('d.file_name', 'like', '%' . $this->search . '%')
                        ->orWhere('w.site_name', 'like', '%' . $this->search . '%');
                });
            })
            ->select('d.*', 'w.site_name as website_name')
            ->orderByDesc('d.created_at')
            ->get();

        // Classify and filter by category in PHP collection
        $filteredDocs = $allDocs->map(function ($doc) {
            $doc->category = $this->getCategory($doc);
            return $doc;
        });

        if ($this->selectedCategory !== 'All') {
            $filteredDocs = $filteredDocs->filter(function ($doc) {
                return $doc->category === $this->selectedCategory;
            });
        }

        return view('modules.client.dashboard.client-documents', [
            'documents' => $filteredDocs,
        ]);
    }
}
