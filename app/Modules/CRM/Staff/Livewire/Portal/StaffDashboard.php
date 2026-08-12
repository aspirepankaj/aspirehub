<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use App\Modules\Core\Activity\Models\ActivityLog;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffDashboard extends Component
{
    public function render()
    {
        $staffId = auth()->user()->staff->id ?? 0;
        
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();
        
        $clientsCount = count($assignedClientIds);
        $websitesCount = Website::whereIn('client_id', $assignedClientIds)->count();
        $reportsCount = MaintenanceReport::whereIn('client_id', $assignedClientIds)->count();
        
        $recentReports = MaintenanceReport::with(['client', 'website'])
            ->whereIn('client_id', $assignedClientIds)
            ->latest()
            ->limit(5)
            ->get();
            
        $recentActivities = ActivityLog::with('user')
            ->where(function($query) use ($assignedClientIds) {
                $query->where(function($q) use ($assignedClientIds) {
                    $q->where('loggable_type', Client::class)
                      ->whereIn('loggable_id', $assignedClientIds);
                })
                ->orWhere(function($q) use ($assignedClientIds) {
                    $q->where('loggable_type', Website::class)
                      ->whereIn('loggable_id', function($sq) use ($assignedClientIds) {
                          $sq->select('id')->from('adspv_websites')->whereIn('client_id', $assignedClientIds);
                      });
                })
                ->orWhere('user_id', auth()->id());
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('modules.crm.staff.portal.dashboard', [
            'clientsCount' => $clientsCount,
            'websitesCount' => $websitesCount,
            'reportsCount' => $reportsCount,
            'recentReports' => $recentReports,
            'recentActivities' => $recentActivities,
        ])->layoutData(['title' => 'Staff Dashboard - Aspire Hub']);
    }
}
