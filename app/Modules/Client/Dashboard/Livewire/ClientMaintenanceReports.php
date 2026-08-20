<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

#[Layout('layouts.client-portal')]
class ClientMaintenanceReports extends Component
{
    use WithPagination;

    public $compareCurrent = null;
    public $comparePrevious = null;
    public bool $showCompareModal = false;
    public string $viewMode = 'recent';
    public string $filterMonth = '';

    public function updatingFilterMonth()
    {
        $this->resetPage();
    }

    public function updatingViewMode()
    {
        $this->resetPage();
        $this->filterMonth = ''; // Reset filter when changing tabs
    }

    public function render()
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();

        abort_if(!$client, 404, 'Client not found.');

        // Get all completed maintenance reports for client's websites
        $query = DB::table('adspv_maintenance_reports as r')
            ->join('adspv_websites as w', 'w.id', '=', 'r.website_id')
            ->where('r.client_id', $client->id)
            ->where('r.status', 'completed');

        $reports = $query->select('r.*', 'w.site_name', 'w.url')
            ->orderByDesc('r.maintenance_date')
            ->get();

        // Dynamically add plugin counts, issues found, etc.
        $reports = $reports->map(function ($report) {
            $report->updates_count = DB::table('adspv_maintenance_report_plugins')
                ->where('report_id', $report->id)
                ->count();
            $report->issues_found = ($report->health_critical_issues ?? 0) + ($report->health_warnings ?? 0);
            $report->issues_fixed = $report->issues_found; 
            $report->backups_count = $report->backup_completed ? 30 : 0;
            return $report;
        });

        $archivedReports = clone $reports;

        // Recent shows only the latest 4 reports overall
        $recentReports = $reports->take(4)->values();

        $activeReports = $this->viewMode === 'recent' ? $recentReports : $archivedReports;

        // Get unique months for the current tab
        $availableMonths = $activeReports->pluck('maintenance_date')->map(function ($date) {
            $d = Carbon::parse($date);
            return ['value' => $d->format('Y-m'), 'label' => $d->format('F Y')];
        })->unique('value')->values();

        // Apply Month Filter locally
        if (!empty($this->filterMonth)) {
            $activeReports = $activeReports->filter(function ($report) {
                return Carbon::parse($report->maintenance_date)->format('Y-m') === $this->filterMonth;
            })->values();
        }

        // Manual Pagination for the collection
        $page = $this->getPage();
        $perPage = 6;
        $paginatedReports = new LengthAwarePaginator(
            $activeReports->forPage($page, $perPage),
            $activeReports->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        return view('modules.client.dashboard.client-maintenance-reports', [
            'paginatedReports' => $paginatedReports,
            'availableMonths' => $availableMonths,
            'recentCount' => $recentReports->count(),
            'archiveCount' => $archivedReports->count(),
        ])->layoutData(['title' => 'Maintenance Reports']);
    }

    public function downloadPdf($id)
    {
        $user = Auth::user();
        $client = DB::table('adspv_clients')->where('user_id', $user->id)->first();
        abort_if(!$client, 404);

        $report = \App\Modules\CRM\Maintenance\Models\MaintenanceReport::with(['client.user', 'website', 'developer', 'plugins', 'attachments'])
            ->where('client_id', $client->id)
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.crm.maintenance.pdf-maintenance-report', compact('report'));
        return $pdf->download("Maintenance-Report-{$report->id}-" . str_replace(' ', '-', $report->maintenance_month) . ".pdf");
    }

    public function compare($reportId)
    {
        $current = DB::table('adspv_maintenance_reports as r')
            ->join('adspv_websites as w', 'w.id', '=', 'r.website_id')
            ->where('r.id', $reportId)
            ->select('r.*', 'w.site_name')
            ->first();

        if ($current) {
            $current->updates_count = DB::table('adspv_maintenance_report_plugins')->where('report_id', $current->id)->count();
            $current->issues_found = ($current->health_critical_issues ?? 0) + ($current->health_warnings ?? 0);
            $current->issues_fixed = $current->issues_found;
            $current->backups_count = $current->backup_completed ? 30 : 0;

            $previous = DB::table('adspv_maintenance_reports as r')
                ->join('adspv_websites as w', 'w.id', '=', 'r.website_id')
                ->where('r.website_id', $current->website_id)
                ->where('r.maintenance_date', '<', $current->maintenance_date)
                ->where('r.status', 'completed')
                ->select('r.*', 'w.site_name')
                ->orderByDesc('r.maintenance_date')
                ->first();

            if ($previous) {
                $previous->updates_count = DB::table('adspv_maintenance_report_plugins')->where('report_id', $previous->id)->count();
                $previous->issues_found = ($previous->health_critical_issues ?? 0) + ($previous->health_warnings ?? 0);
                $previous->issues_fixed = $previous->issues_found;
                $previous->backups_count = $previous->backup_completed ? 30 : 0;
            }

            $this->compareCurrent = (array)$current;
            $this->comparePrevious = $previous ? (array)$previous : null;
            $this->showCompareModal = true;
        }
    }

    public function closeCompare()
    {
        $this->showCompareModal = false;
        $this->reset(['compareCurrent', 'comparePrevious']);
    }
}
