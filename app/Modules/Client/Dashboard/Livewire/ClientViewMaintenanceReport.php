<?php

namespace App\Modules\Client\Dashboard\Livewire;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.client-portal')]
class ClientViewMaintenanceReport extends Component
{
    public int $reportId;
    public MaintenanceReport $report;

    public function mount(int $id)
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();
        abort_if(!$client, 404);

        $this->reportId = $id;
        $this->report = MaintenanceReport::with(['client.user', 'website', 'developer', 'plugins', 'attachments'])
            ->where('client_id', $client->id)
            ->findOrFail($id);
    }

    public function render()
    {
        return view('modules.client.dashboard.client-view-maintenance-report', [
            'report' => $this->report,
        ])->layoutData(['title' => 'View Maintenance Report']);
    }
}
