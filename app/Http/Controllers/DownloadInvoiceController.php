<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\InvoiceData;
use App\Models\Invoice;
use function Spatie\LaravelPdf\Support\pdf;

class DownloadInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
//        dd($invoice);
        $data = InvoiceData::from($invoice);

        return pdf('pdf.invoice', compact('data'));
        return Pdf::view('pdf.invoice', compact('invoice'))
            ->format('a4')
            ->save("invoices/{$invoice->number}.pdf");
    }
}
