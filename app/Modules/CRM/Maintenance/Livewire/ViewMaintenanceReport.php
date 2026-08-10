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

    public function render()
    {
        return view('modules.crm.maintenance.view-maintenance-report', [
            'report' => $this->report,
        ])->layoutData(['title' => 'View Maintenance Report #' . $this->reportId . ' - Aspire Hub']);
    }
}
