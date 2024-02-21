<?php

namespace App\Actions;

use App\DataTransferObjects\InvoiceData;
use App\Models\Invoice;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateInvoicePdf
{
    public static function execute(Invoice $invoice): void
    {
        $data = InvoiceData::from($invoice);

        Pdf::view('pdf.invoice', compact('data'))
            ->format('a4')
            ->disk(config('filesystems.default'))
            ->save("invoices/{$invoice->number}.pdf");
    }
}
