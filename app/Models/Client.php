<?php

namespace App\Models;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'contact',
        'email',
        'phone',
        'address',
        'country',
        'city',
        'state',
        'zip',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Company Name')
                ->required(),
            TextInput::make('contact')
                ->label('Contact Name')
                ->required(),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),
            TextInput::make('phone')
                ->label('Phone')
                ->required()
                ->tel(),
            LocalizedCountrySelect::make('country')
                ->label('Country')
                ->required()
                ->searchable(),
            TextInput::make('address')
                ->label('Address')
                ->required()
                ->columnSpanFull(),
            Group::make()
                ->columns(3)
                ->schema([
                             TextInput::make('city')
                                 ->label('City'),
                             TextInput::make('state')
                                 ->label('State'),
                             TextInput::make('zip')
                                 ->label('Zip'),
                         ]),
        ];
    }
}
