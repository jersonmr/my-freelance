<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class InvoiceItemData extends Data
{
    public function __construct(
        public readonly string $description,
        public readonly int $hours,
        public readonly int $rate,
        public readonly int $price,
    ) {
    }
}
