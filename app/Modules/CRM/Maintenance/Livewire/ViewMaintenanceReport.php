<?php

namespace App\Modules\CRM\Maintenance\Livewire;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ViewMaintenanceReport extends Component
{
    public int $reportId;
    public MaintenanceReport $report;

    public function mount(int $id)
    {
        $this->reportId = $id;
        $this->report = MaintenanceReport::with(['client.user', 'website', 'developer', 'plugins', 'attachments'])->findOrFail($id);
    }

    public function render()
    {
        return view('modules.crm.maintenance.view-maintenance-report', [
            'report' => $this->report,
        ])->layoutData(['title' => 'View Maintenance Report #' . $this->reportId . ' - Aspire Hub']);
    }
}
