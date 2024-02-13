<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                         Forms\Components\TextInput::make('name')
                             ->label('Company Name')
                             ->required(),
                         Forms\Components\TextInput::make('contact')
                             ->label('Contact Name')
                             ->required(),
                         Forms\Components\TextInput::make('email')
                             ->label('Email')
                             ->email()
                             ->required(),
                         Forms\Components\TextInput::make('phone')
                             ->label('Phone')
                             ->required()
                            ->tel(),
                         LocalizedCountrySelect::make('country')
                             ->label('Country')
                             ->required()
                             ->searchable(),
                         Forms\Components\TextInput::make('address')
                             ->label('Address')
                             ->required()
                             ->columnSpanFull(),
                         Forms\Components\Group::make()
                             ->columns(3)
                             ->schema([
                                          Forms\Components\TextInput::make('city')
                                              ->label('City')
                                              ->required(),
                                          Forms\Components\TextInput::make('state')
                                              ->label('State')
                                              ->required(),
                                          Forms\Components\TextInput::make('zip')
                                              ->label('Zip')
                                              ->required(),
                                      ]),
                     ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('name')
                              ->searchable()
                              ->sortable(),
                            Tables\Columns\TextColumn::make('contact')
                              ->searchable(),
                            Tables\Columns\TextColumn::make('email')
                              ->searchable(),
                            Tables\Columns\TextColumn::make('phone')
                                ->searchable(),
                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Tables\Actions\EditAction::make()
                            ->iconButton(),
                          Tables\Actions\DeleteAction::make()
                            ->iconButton(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
