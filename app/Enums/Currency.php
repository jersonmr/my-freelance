<?php

namespace App\Enums;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case JPY = 'JPY';

    public static function symbol($symbol): string
    {
        return match ($symbol) {
            self::EUR->value => '€',
            self::USD->value => '$',
            self::GBP->value => '£',
            self::JPY->value => '¥',
            default => '$',
        };
    }
}
