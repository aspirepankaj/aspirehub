<?php

namespace App\Modules\CRM\Maintenance\Livewire;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageMaintenanceReports extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $clientFilter = '';
    public string $websiteFilter = '';
    public string $statusFilter = '';
    public string $developerFilter = '';
    public string $monthFilter = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingClientFilter(): void
    {
        $this->websiteFilter = '';
        $this->resetPage();
    }
    public function updatingWebsiteFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingDeveloperFilter(): void { $this->resetPage(); }
    public function updatingMonthFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset([
            'search', 'clientFilter', 'websiteFilter', 'statusFilter', 'developerFilter', 'monthFilter'
        ]);
        $this->resetPage();
    }

    public function deleteReport(int $id): void
    {
        $report = MaintenanceReport::findOrFail($id);
        $report->delete();
        session()->flash('success', 'Maintenance report deleted successfully!');
    }

    public function emailReport(int $id): void
    {
        $report = MaintenanceReport::with(['client.user', 'website'])->findOrFail($id);

        if (!$report->client || !$report->client->user || !$report->client->user->email) {
            session()->flash('error', 'Client email not found. Cannot send report.');
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::to($report->client->user->email)
                ->send(new \App\Mail\MaintenanceReportMail($report));

            $report->update(['last_sent_at' => now()]);

            \App\Modules\Core\Activity\Models\EmailLog::create([
                'sender_id' => auth()->id(),
                'recipient_email' => $report->client->user->email,
                'subject' => 'Website Maintenance Report - ' . $report->maintenance_month . ' - ' . $report->website->site_name,
                'status' => 'sent',
                'report_id' => $report->id,
            ]);

            \App\Modules\Core\Activity\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'email_maintenance_report',
                'description' => 'Emailed Maintenance Report #' . $report->id . ' to ' . $report->client->user->email,
                'meta' => [
                    'report_id' => $report->id,
                    'client_id' => $report->client_id,
                ],
            ]);

            session()->flash('success', 'Maintenance report emailed to client successfully!');
        } catch (\Exception $e) {
            \App\Modules\Core\Activity\Models\EmailLog::create([
                'sender_id' => auth()->id(),
                'recipient_email' => $report->client->user->email,
                'subject' => 'Website Maintenance Report - ' . $report->maintenance_month . ' - ' . $report->website->site_name,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'report_id' => $report->id,
            ]);

            session()->flash('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public static function downloadPdf($id)
    {
        $report = MaintenanceReport::with(['client.user', 'website', 'developer', 'plugins', 'attachments'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.crm.maintenance.pdf-maintenance-report', compact('report'));
        return $pdf->download("Maintenance-Report-{$report->id}-" . str_replace(' ', '-', $report->maintenance_month) . ".pdf");
    }

    public function render()
    {
        $reports = MaintenanceReport::with(['client.user', 'website', 'developer'])
            ->when($this->search, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('client.user', fn($ssq) => $ssq->where('name', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('client', fn($ssq) => $ssq->where('company_name', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('website', fn($ssq) => $ssq->where('site_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->clientFilter, fn($q) => $q->where('client_id', $this->clientFilter))
            ->when($this->websiteFilter, fn($q) => $q->where('website_id', $this->websiteFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->developerFilter, fn($q) => $q->where('developer_id', $this->developerFilter))
            ->when($this->monthFilter, fn($q) => $q->where('maintenance_month', $this->monthFilter))
            ->latest()
            ->paginate(12)
            ->onEachSide(1);

        $clients = Client::with('user')->orderBy('company_name')->get();
        $websites = Website::orderBy('site_name')
            ->when($this->clientFilter, fn($q) => $q->where('client_id', $this->clientFilter))
            ->get();
        $developers = User::whereHas('admin')->orderBy('name')->get(); // Only developers/admins
        
        // Dynamic list of months from db to filter
        $availableMonths = MaintenanceReport::select('maintenance_month')
            ->distinct()
            ->pluck('maintenance_month');

        $totalReportsCount = MaintenanceReport::count();
        $reportsThisMonthCount = MaintenanceReport::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $completedReportsCount = MaintenanceReport::where('status', 'completed')->count();
        $pendingReportsCount = MaintenanceReport::where('status', 'draft')->count();
        $averageDesktopScore = round(MaintenanceReport::where('performance_desktop', '>', 0)->avg('performance_desktop') ?? 0);

        // Average scores comparison for the subtext
        $avgThisMonth = round(MaintenanceReport::where('performance_desktop', '>', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->avg('performance_desktop') ?? 0);

        $avgLastMonth = round(MaintenanceReport::where('performance_desktop', '>', 0)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->avg('performance_desktop') ?? 0);

        $scoreChange = ($avgLastMonth > 0) ? ($avgThisMonth - $avgLastMonth) : 0;

        $hasActiveFilters = $this->search || $this->clientFilter || $this->websiteFilter || $this->statusFilter || $this->developerFilter || $this->monthFilter;

        return view('modules.crm.maintenance.manage-maintenance-reports', [
            'reports' => $reports,
            'clients' => $clients,
            'websites' => $websites,
            'developers' => $developers,
            'availableMonths' => $availableMonths,
            'totalReportsCount' => $totalReportsCount,
            'reportsThisMonthCount' => $reportsThisMonthCount,
            'completedReportsCount' => $completedReportsCount,
            'pendingReportsCount' => $pendingReportsCount,
            'averageDesktopScore' => $averageDesktopScore,
            'scoreChange' => $scoreChange,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'Maintenance Reports - Aspire Hub']);
    }
}
