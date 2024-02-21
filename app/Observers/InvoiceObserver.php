<?php

namespace App\Observers;

use App\Actions\GenerateInvoicePdf;
use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        GenerateInvoicePdf::execute($invoice);
    }

    public function updated(Invoice $invoice): void
    {
        GenerateInvoicePdf::execute($invoice);
    }
}
