<?php

namespace App\DataTransferObjects;

use App\Casts\MoneyCast;
use App\Models\Bank;
use App\Models\Invoice;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class InvoiceData extends Data
{
    public function __construct(
        public readonly string $number,
        public readonly string $subject,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly Carbon $due,
        public readonly array $items,
        public readonly float $subtotal,
        public readonly null|int $tax,
        public readonly float $total,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $paid,
        public readonly ClientData $client,
        public readonly BankData $bank,
    ) {}

    public static function fromModel(Invoice $invoice): self {
        return new self(
            $invoice->number,
            $invoice->subject,
            $invoice->due,
            $invoice->items,
            $invoice->subtotal,
            $invoice->tax,
            $invoice->total,
            $invoice->paid,
            ClientData::from($invoice->client),
            BankData::from(Bank::find($invoice->bank_id)),
        );
    }
}
