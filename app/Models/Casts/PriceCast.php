<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PriceCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value / 100;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value * 100;
    }
}
