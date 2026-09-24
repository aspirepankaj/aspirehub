<?php

namespace App\Mail;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public Client $client;
    public Website $website;
    public array $reportData;
    public string $mailSubject;
    public ?string $personalMessage;
    public string $pdfContent;
    public string $pdfFilename;

    public function __construct(
        Client $client,
        Website $website,
        array $reportData,
        string $subject,
        ?string $personalMessage,
        string $pdfContent,
        string $pdfFilename
    ) {
        $this->client = $client;
        $this->website = $website;
        $this->reportData = $reportData;
        $this->mailSubject = $subject;
        $this->personalMessage = $personalMessage;
        $this->pdfContent = $pdfContent;
        $this->pdfFilename = $pdfFilename;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing-report',
            with: [
                'client' => $this->client,
                'website' => $this->website,
                'reportData' => $this->reportData,
                'personalMessage' => $this->personalMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->pdfFilename)
                ->withMime('application/pdf'),
        ];
    }
}
