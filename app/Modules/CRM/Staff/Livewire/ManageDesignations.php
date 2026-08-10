<?php

namespace App\Modules\CRM\Staff\Livewire;

use App\Modules\CRM\Staff\Models\Designation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageDesignations extends Component
{
    use WithPagination;

    // Form inputs
    public string $name = '';
    public string $description = '';

    // Search & Filter
    public string $search = '';

    // Bulk selection
    public array $selectedDesignations = [];
    public bool $selectAll = false;

    // Edit state tracking
    public ?int $editingDesignationId = null;

    public function updatingSearch(): void
    {
        $this->selectedDesignations = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedDesignations = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedDesignations = $pageIds;
        } else {
            $this->selectedDesignations = [];
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'editingDesignationId']);
        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-designation-modal');
    }

    public function saveDesignation()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:adspv_designations,name',
            'description' => 'nullable|string',
        ]);

        Designation::create([
            'name' => $this->name,
            'description' => $this->description,
            'added_by' => auth()->id(),
        ]);

        $this->dispatch('close-modal', name: 'add-designation-modal');
        $this->resetForm();
        session()->flash('success', 'Designation created successfully!');
    }

    public function editDesignation($id)
    {
        $designation = Designation::findOrFail($id);

        $this->editingDesignationId = $designation->id;
        $this->name = $designation->name;
        $this->description = $designation->description ?? '';

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-designation-modal');
    }

    public function updateDesignation()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:adspv_designations,name,' . $this->editingDesignationId,
            'description' => 'nullable|string',
        ]);

        $designation = Designation::findOrFail($this->editingDesignationId);
        $designation->update([
            'name' => $this->name,
            'description' => $this->description,
            'edited_by' => auth()->id(),
        ]);

        $this->dispatch('close-modal', name: 'edit-designation-modal');
        $this->resetForm();
        session()->flash('success', 'Designation updated successfully!');
    }

    public function deleteDesignation($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        session()->flash('success', 'Designation deleted successfully!');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedDesignations)) return;

        Designation::whereIn('id', $this->selectedDesignations)->delete();
        $count = count($this->selectedDesignations);
        $this->selectedDesignations = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} designation(s) deleted successfully.");
    }

    public function render()
    {
        $designations = Designation::withCount('staff')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $hasActiveFilters = !empty($this->search);
        $pageIds = $designations->pluck('id')->toArray();

        return view('modules.crm.staff.manage-designations', [
            'designations'     => $designations,
            'hasActiveFilters' => $hasActiveFilters,
            'pageIds'          => $pageIds,
        ])->layoutData(['title' => 'Designations Management - Aspire Hub']);
    }
}
