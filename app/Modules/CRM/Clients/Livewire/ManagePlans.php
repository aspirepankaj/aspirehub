<?php

namespace App\Modules\CRM\Clients\Livewire;

use App\Modules\CRM\Clients\Models\Plan;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManagePlans extends Component
{
    public string $name = '';
    public string $price = '0.00';
    public string $color = 'indigo';
    public string $duration = 'monthly';
    public ?int $editingPlanId = null;

    public function rules(): array
    {
        $uniqueRule = 'required|string|max:100|unique:adspv_plans,name';
        if ($this->editingPlanId) {
            $uniqueRule .= ',' . $this->editingPlanId;
        }
        return [
            'name'     => $uniqueRule,
            'price'    => 'required|numeric|min:0',
            'color'    => 'required|string|in:indigo,emerald,pink,amber,slate,red,sky,violet,rose',
            'duration' => 'required|string|in:monthly,quarterly,yearly',
        ];
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'price', 'color', 'duration', 'editingPlanId']);
        $this->resetValidation();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-plan-modal');
    }

    public function savePlan(): void
    {
        $this->validate();

        Plan::create([
            'name'     => $this->name,
            'price'    => $this->price,
            'color'    => $this->color,
            'duration' => $this->duration,
        ]);

        $this->dispatch('close-modal', name: 'add-plan-modal');
        $this->resetForm();
        session()->flash('success', 'Plan created successfully!');
    }

    public function editPlan(int $id): void
    {
        $plan = Plan::findOrFail($id);
        $this->editingPlanId = $plan->id;
        $this->name          = $plan->name;
        $this->price         = $plan->price;
        $this->color         = $plan->color;
        $this->duration      = $plan->duration ?? 'monthly';

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-plan-modal');
    }

    public function updatePlan(): void
    {
        $this->validate();

        $plan = Plan::findOrFail($this->editingPlanId);
        $plan->update([
            'name'     => $this->name,
            'price'    => $this->price,
            'color'    => $this->color,
            'duration' => $this->duration,
        ]);

        $this->dispatch('close-modal', name: 'edit-plan-modal');
        $this->resetForm();
        session()->flash('success', 'Plan updated successfully!');
    }

    public function deletePlan(int $id): void
    {
        $plan = Plan::findOrFail($id);

        if ($plan->clients()->exists()) {
            session()->flash('error', 'Cannot delete plan because it is assigned to one or more clients.');
            return;
        }

        $plan->delete();
        session()->flash('success', 'Plan deleted successfully!');
    }

    public function render()
    {
        $plans = Plan::orderBy('name')->get();

        return view('modules.crm.clients.manage-plans', [
            'plans' => $plans,
        ])->layoutData(['title' => 'Plans Management - Aspire Hub']);
    }
}
