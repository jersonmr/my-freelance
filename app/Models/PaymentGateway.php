<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament/resources/payment_gateways.client_name'))
                ->required(),
            TextInput::make('email')
                ->label(__('filament/resources/payment_gateways.client_email'))
                ->email()
                ->required(),
            Select::make('type')
                ->label(__('filament/resources/payment_gateways.type'))
                ->options([
                    'paypal' => 'PayPal',
                    'binance' => 'Binance',
                ])
                ->required(),
        ];
    }
}
