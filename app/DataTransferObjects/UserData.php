<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly ?ProfileData $profile,
    ) {

    }
}
