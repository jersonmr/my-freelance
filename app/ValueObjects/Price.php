<?php

namespace App\ValueObjects;

use App\Enums\Currency;

class Price
{
    public readonly int $cent;

    public readonly float $amount;

    public readonly string $formatted;

    public function __construct(int $cent, Currency $currency)
    {
        $this->cent = $cent;
        $this->amount = $cent / 100;
        $this->formatted = Currency::symbol($currency->value).number_format($this->cent, 2);
    }

    public static function from(int $cent, Currency $currency): self
    {
        return new self($cent, $currency);
    }
}
