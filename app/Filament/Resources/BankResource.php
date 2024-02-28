<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankResource\Pages;
use App\Models\Bank;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'tabler-building-bank';

    protected static ?int $navigationSort = 20;

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/banks.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/banks.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Bank::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->label(__('filament/resources/banks.location'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament/resources/banks.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('iban')
                    ->label(__('filament/resources/banks.iban'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBanks::route('/'),
            'create' => Pages\CreateBank::route('/create'),
            'edit' => Pages\EditBank::route('/{record}/edit'),
        ];
    }
}
