<?php

namespace App\Models;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament/resources/clients.company_name'))
                ->required(),
            TextInput::make('contact')
                ->label(__('filament/resources/clients.contact_name'))
                ->required(),
            TextInput::make('email')
                ->label(__('filament/resources/clients.email'))
                ->email()
                ->required(),
            TextInput::make('phone')
                ->label(__('filament/resources/clients.phone'))
                ->required()
                ->tel(),
            LocalizedCountrySelect::make('country')
                ->label(__('filament/resources/clients.country'))
                ->required()
                ->searchable(),
            TextInput::make('address')
                ->label(__('filament/resources/clients.address'))
                ->required()
                ->columnSpanFull(),
            Group::make()
                ->columns(3)
                ->schema([
                    TextInput::make('city')
                        ->label(__('filament/resources/clients.city')),
                    TextInput::make('state')
                        ->label(__('filament/resources/clients.state')),
                    TextInput::make('zip')
                        ->label(__('filament/resources/clients.zip')),
                ]),
        ];
    }
}
