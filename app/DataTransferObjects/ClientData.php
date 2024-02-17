<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class ClientData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $contact,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $address,
    ) {}
}
