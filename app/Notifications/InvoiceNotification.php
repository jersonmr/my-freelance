<?php

namespace App\Notifications;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification
{
    public function __construct(public string $subject, public string $message, public Invoice $invoice)
    {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): InvoiceMail
    {
        return (new InvoiceMail($notifiable, $this->subject, $this->message, $this->invoice))
            ->subject($this->subject)
            ->to($this->invoice->client->email);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
