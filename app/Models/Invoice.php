<?php

namespace App\Models;

use App\Enums\Currency;
use App\Models\Casts\PriceCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'bank_id',
        'payment_gateway_id',
        'number',
        'project',
        'due',
        'currency',
        'payment_type',
        'items',
        'tax',
        'subtotal',
        'total',
        'paid',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'client_id' => 'integer',
        'bank_id' => 'integer',
        'payment_gateway_id' => 'integer',
        'due' => 'date',
        'items' => 'array',
        'paid' => 'boolean',
        'currency' => Currency::class,
        'subtotal' => PriceCast::class,
        'total' => PriceCast::class,
//        'tax' => PercentCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
