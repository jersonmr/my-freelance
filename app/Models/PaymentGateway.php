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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getForm(): array {
        return [
            TextInput::make('name')
                ->label('Client Name')
                ->required(),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),
            Select::make('type')
                ->label('Type')
                ->options([
                    'paypal' => 'PayPal',
                    'binance' => 'Binance',
                ])
                ->required(),
        ];
    }
}
