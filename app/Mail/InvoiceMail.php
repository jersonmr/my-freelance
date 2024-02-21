<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceMail extends Mailable
{
    use SerializesModels;

    public function __construct(public User $user, public string $subjectContent, public string $bodyContent, public Invoice $invoice)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk(config('filesystems.default'), "invoices/{$this->invoice->number}.pdf")
                ->as("{$this->invoice->number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
