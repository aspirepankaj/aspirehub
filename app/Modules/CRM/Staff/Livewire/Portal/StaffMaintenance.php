<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffMaintenance extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $clientFilter = '';
    public string $websiteFilter = '';
    public string $statusFilter = '';
    public string $monthFilter = '';
    public string $sendFilter = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingClientFilter(): void
    {
        $this->websiteFilter = '';
        $this->resetPage();
    }
    public function updatingWebsiteFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingMonthFilter(): void { $this->resetPage(); }
    public function updatingSendFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset([
            'search', 'clientFilter', 'websiteFilter', 'statusFilter', 'monthFilter', 'sendFilter'
        ]);
        $this->resetPage();
    }

    public static function downloadPdf($id)
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $report = MaintenanceReport::with(['client.user', 'website', 'developer', 'plugins', 'attachments'])
            ->whereIn('client_id', $assignedClientIds)
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.crm.maintenance.pdf-maintenance-report', compact('report'));
        return $pdf->download("Maintenance-Report-{$report->id}-" . str_replace(' ', '-', $report->maintenance_month) . ".pdf");
    }

    public function emailReport(int $id): void
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $report = MaintenanceReport::with(['client.user', 'website'])
            ->whereIn('client_id', $assignedClientIds)
            ->findOrFail($id);

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

    public function render()
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $reports = MaintenanceReport::with(['client.user', 'website', 'developer'])
            ->whereIn('client_id', $assignedClientIds)
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
            ->when($this->monthFilter, fn($q) => $q->where('maintenance_month', $this->monthFilter))
            ->when($this->sendFilter === 'sent', fn($q) => $q->whereNotNull('last_sent_at'))
            ->when($this->sendFilter === 'unsent', fn($q) => $q->whereNull('last_sent_at'))
            ->latest()
            ->paginate(12);

        $clients = Client::with('user')
            ->whereHas('assignedStaff', function ($q) use ($staffId) {
                $q->where('staff_id', $staffId);
            })
            ->orderBy('company_name')
            ->get();
        
        $websites = Website::whereIn('client_id', $assignedClientIds)
            ->orderBy('site_name')
            ->when($this->clientFilter, fn($q) => $q->where('client_id', $this->clientFilter))
            ->get();
        
        $availableMonths = MaintenanceReport::select('maintenance_month')
            ->whereIn('client_id', $assignedClientIds)
            ->distinct()
            ->pluck('maintenance_month');

        $totalReportsCount = MaintenanceReport::whereIn('client_id', $assignedClientIds)->count();
        $completedReportsCount = MaintenanceReport::whereIn('client_id', $assignedClientIds)->where('status', 'completed')->count();
        $pendingReportsCount = MaintenanceReport::whereIn('client_id', $assignedClientIds)->where('status', 'draft')->count();

        $hasActiveFilters = $this->search || $this->clientFilter || $this->websiteFilter || $this->statusFilter || $this->monthFilter || $this->sendFilter;

        return view('modules.crm.staff.portal.maintenance', [
            'reports' => $reports,
            'clients' => $clients,
            'websites' => $websites,
            'availableMonths' => $availableMonths,
            'totalReportsCount' => $totalReportsCount,
            'completedReportsCount' => $completedReportsCount,
            'pendingReportsCount' => $pendingReportsCount,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'Maintenance Reports - Staff Portal']);
    }
}
