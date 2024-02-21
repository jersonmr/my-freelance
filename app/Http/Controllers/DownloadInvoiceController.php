<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\InvoiceData;
use App\Models\Invoice;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class DownloadInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $data = InvoiceData::from($invoice);

        return Pdf::view('pdf.invoice', compact('data'))
            ->disk('public')
            ->format('a4')
            ->save("invoices/{$invoice->number}.pdf");
    }
}
