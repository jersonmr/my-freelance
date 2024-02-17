<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class BankData extends Data
{
    public function __construct(
        public readonly string $location,
        public readonly string $name,
        public readonly ?string $address,
        public readonly string $swift,
        public readonly string $iban,
        public readonly string $beneficiary_name,
        public readonly string $beneficiary_address,
        public readonly string $beneficiary_email,
    ) {}
}
