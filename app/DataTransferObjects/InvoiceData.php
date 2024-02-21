<?php

namespace App\DataTransferObjects;

use App\Enums\Currency;
use App\Models\Bank;
use App\Models\Invoice;
use App\ValueObjects\Percent;
use App\ValueObjects\Price;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class InvoiceData extends Data
{
    public function __construct(
        public readonly string $number,
        public readonly string $project,
        public readonly Currency $currency,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly Carbon $due,
        public readonly Collection $items,
        public readonly Price $subtotal,
        public readonly null|Percent $tax,
        public readonly Price $total,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly bool $paid,
        public readonly ClientData $client,
        public readonly ?BankData $bank,
    ) {}

    public static function fromModel(Invoice $invoice): self {
        return new self(
            $invoice->number,
            $invoice->project,
            $invoice->currency,
            $invoice->due,
            collect($invoice->items)->map(fn($item) => InvoiceItemData::from($item)),
            Price::from($invoice->subtotal, $invoice->currency),
            Percent::from($invoice->tax),
            Price::from($invoice->total, $invoice->currency),
            $invoice->paid,
            ClientData::from($invoice->client),
            $invoice->bank_id ? BankData::from(Bank::find($invoice->bank_id)) : null,
        );
    }
}
