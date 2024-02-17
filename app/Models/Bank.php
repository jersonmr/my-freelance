<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'name',
        'swift',
        'iban',
        'address',
        'beneficiary_name',
        'beneficiary_address',
        'beneficiary_email',
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
            TextInput::make('location')
                ->required(),
            TextInput::make('name')
                ->required(),
            TextInput::make('swift')
                ->label('Bank Code / SWIFT')
                ->required(),
            TextInput::make('iban')
                ->label('Account Number / IBAN')
                ->required(),
            TextInput::make('address')
                ->label('Bank Address')
                ->required(),
            TextInput::make('beneficiary_name')
                ->required(),
            TextInput::make('beneficiary_address')
                ->required(),
            TextInput::make('beneficiary_email')
                ->required(),
        ];
    }
}
