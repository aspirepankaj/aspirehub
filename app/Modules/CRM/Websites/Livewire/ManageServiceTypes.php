<?php

namespace App\Modules\CRM\Websites\Livewire;

use App\Modules\CRM\Websites\Models\ServiceType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageServiceTypes extends Component
{
    use WithPagination;

    // Form attributes
    public string $name = '';
    public string $color = 'indigo';

    // Edit tracking
    public ?int $editingTypeId = null;

    protected function rules(): array
    {
        $uniqueRule = 'required|string|max:100|unique:adspv_service_types,name';
        if ($this->editingTypeId) {
            $uniqueRule .= ',' . $this->editingTypeId;
        }
        return [
            'name'  => $uniqueRule,
            'color' => 'required|string|in:indigo,emerald,pink,amber,slate,red,sky,violet,rose',
        ];
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'color', 'editingTypeId']);
        $this->color = 'indigo';
        $this->resetValidation();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-service-type-modal');
    }

    public function saveType(): void
    {
        $this->validate();

        ServiceType::create([
            'name'  => $this->name,
            'color' => $this->color,
        ]);

        $this->dispatch('close-modal', name: 'add-service-type-modal');
        $this->resetForm();
        session()->flash('success', 'Service type created successfully!');
    }

    public function editType(int $id): void
    {
        $type = ServiceType::findOrFail($id);
        $this->editingTypeId = $type->id;
        $this->name          = $type->name;
        $this->color         = $type->color;

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-service-type-modal');
    }

    public function updateType(): void
    {
        $this->validate();

        $type = ServiceType::findOrFail($this->editingTypeId);
        $type->update([
            'name'  => $this->name,
            'color' => $this->color,
        ]);

        $this->dispatch('close-modal', name: 'edit-service-type-modal');
        $this->resetForm();
        session()->flash('success', 'Service type updated successfully!');
    }

    public function deleteType(int $id): void
    {
        $type = ServiceType::findOrFail($id);

        if ($type->websites()->exists()) {
            session()->flash('error', 'Cannot delete service type because it is associated with existing websites.');
            return;
        }

        $type->delete();
        session()->flash('success', 'Service type deleted successfully!');
    }

    public function render()
    {
        $types = ServiceType::latest()->paginate(10);

        return view('modules.crm.websites.manage-service-types', [
            'types' => $types,
        ])->layoutData(['title' => 'Manage Service Types - Aspire Hub']);
    }
}
