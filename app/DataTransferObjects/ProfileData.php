<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class ProfileData extends Data
{
    public function __construct(
        public readonly ?string $username,
        public readonly ?string $about,
        public readonly ?string $avatar,
        public readonly ?string $country,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $zip,
        public readonly ?bool $notifications,
    ) {
    }

    //    public static function fromArray(array $data): self
    //    {
    //        return self::from([
    //                              ...$data,
    //                              'user' => UserData::from([
    //                                                           'name' => $data['name'],
    //                                                           'email' => $data['email'],
    //                                                       ]),
    //                          ]);
    //    }
}
