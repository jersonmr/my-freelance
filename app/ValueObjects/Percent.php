<?php

namespace App\ValueObjects;

class Percent
{
    public readonly ?float $value;

    public readonly string $formatted;

    public function __construct(?int $value)
    {
        $this->value = $value;

        if ($this->value === null) {
            $this->formatted = '';
        } else {
            $this->formatted = number_format($value / 100, 2).'%';
        }
    }

    public static function from(?int $value): self
    {
        return new self($value);
    }
}
