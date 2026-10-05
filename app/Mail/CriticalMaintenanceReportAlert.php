<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CriticalMaintenanceReportAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Modules\CRM\Maintenance\Models\MaintenanceReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CRITICAL ALERT: Maintenance Report for ' . ($this->report->website->url ?? 'Website'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance.critical-alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
