<?php

namespace App\Mail;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class MaintenanceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public MaintenanceReport $report;

    public function __construct(MaintenanceReport $report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Website Maintenance Report - ' . $this->report->maintenance_month . ' - ' . $this->report->website->site_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-report',
        );
    }

    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.crm.maintenance.pdf-maintenance-report', ['report' => $this->report]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), "Maintenance-Report-{$this->report->id}-" . str_replace(' ', '-', $this->report->maintenance_month) . ".pdf")
                ->withMime('application/pdf'),
        ];
    }
}
