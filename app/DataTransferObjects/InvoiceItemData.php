<?php

namespace App\DataTransferObjects;

use App\ValueObjects\Price;
use Spatie\LaravelData\Data;

class InvoiceItemData extends Data
{
    public function __construct(
        public readonly string $description,
        public readonly int $hours,
        public readonly int $rate,
        public readonly int $price,
    ) {}
}
