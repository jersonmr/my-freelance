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
                ->label(__('filament/resources/banks.location'))
                ->required(),
            TextInput::make('name')
                ->label(__('filament/resources/banks.name'))
                ->required(),
            TextInput::make('swift')
                ->label(__('filament/resources/banks.swift'))
                ->required(),
            TextInput::make('iban')
                ->label(__('filament/resources/banks.iban'))
                ->required(),
            TextInput::make('address')
                ->label(__('filament/resources/banks.address'))
                ->required(),
            TextInput::make('beneficiary_name')
                ->label(__('filament/resources/banks.beneficiary_name'))
                ->required(),
            TextInput::make('beneficiary_address')
                ->label(__('filament/resources/banks.beneficiary_address'))
                ->required(),
            TextInput::make('beneficiary_email')
                ->label(__('filament/resources/banks.beneficiary_email'))
                ->required(),
        ];
    }
}
