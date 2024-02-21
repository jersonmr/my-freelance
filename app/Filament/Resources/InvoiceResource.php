<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\{Get, Set};

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'tabler-file-invoice';

    protected static ?string $modelLabel = 'Facturas';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                         Forms\Components\Select::make('client_id')
                             ->label('Client')
                             ->options(function () {
                                 return \App\Models\Client::whereBelongsTo(auth()->user())->pluck('name', 'id');
                             })
                             ->relationship('client', 'name')
                             ->createOptionForm(Client::getForm())
                             ->createOptionUsing(function ($data) {
                                 $client = new Client();
                                 $client->fill($data);
                                 $client->user_id = auth()->id();
                                 $client->save();

                                 return $client->getKey();
                             })
                             ->required()
                             ->live(onBlur: true),
                         Forms\Components\TextInput::make('number')
                             ->default('INV-' . random_int(100000, 999999))
                             ->disabled()
                             ->label('Number')
                             ->dehydrated()
                             ->required()
                             ->maxLength(32)
                             ->unique(Invoice::class, 'number', ignoreRecord: true),
                         Forms\Components\Grid::make()
                             ->columns([
                                           'md' => 2,
                                           'lg' => 2,
                                           'xl' => 3,
                                       ])
                             ->schema([
                                          Forms\Components\TextInput::make('project')
                                              ->columnSpan(2)
                                              ->label('Project')
                                              ->required()
                                              ->columnSpan(2),
                                          Forms\Components\DatePicker::make('due')
                                              ->columnSpan(1)
                                              ->label('Date')
                                              ->default(now()->format('Y-m-d')),
                                      ]),
                         Forms\Components\Grid::make()
                             ->columns([
                                           'md' => 2,
                                           'lg' => 3,
                                           'xl' => 4,
                                       ])
                             ->schema([
                                          Forms\Components\Select::make('currency')
                                              ->columnSpan(1)
                                              ->label('Currency')
                                              ->options(Currency::class)
                                              ->live(),
                                          Forms\Components\Select::make('payment_type')
                                              ->columnSpan(1)
                                              ->label('Payment Type')
                                              ->options([
                                                            'bank_transfer' => 'Bank Transfer',
                                                            'paypal' => 'PayPal',
                                                            'binance' => 'Binance',
                                                            'cash' => 'Cash',
                                                        ])
                                              ->live()
                                              ->required(),
                                          Forms\Components\Select::make('bank_id')
                                              ->columnSpan(1)
                                              ->label('Bank Account')
                                              ->options(function () {
                                                  return Bank::all()->pluck('name', 'id');
                                              })
                                              ->createOptionForm(Bank::getForm())
                                              ->createOptionUsing(function ($data) {
                                                  $bank = new Bank();
                                                  $bank->fill($data);
                                                  $bank->save();

                                                  return $bank->getKey();
                                              })
                                              ->required(fn(Get $get): bool => $get('payment_type') === 'bank_transfer')
                                              ->visible(fn($get) => $get('payment_type') === 'bank_transfer'),
                                          Forms\Components\Select::make('payment_gateway_id')
                                              ->columnSpan(1)
                                              ->label('Payment Gateway')
                                              ->options(function (Get $get) {
                                                  return PaymentGateway::where('type', '=', $get('payment_type'))->pluck('name', 'id');
                                              })
                                              ->createOptionForm(PaymentGateway::getForm())
                                              ->createOptionUsing(function ($data) {
                                                  $paymentGateway = new PaymentGateway();
                                                  $paymentGateway->fill($data);
                                                  $paymentGateway->save();

                                                  return $paymentGateway->getKey();
                                              })
                                              ->required(fn(Get $get): bool => $get('payment_type') === 'paypal' || $get('payment_type') === 'binance')
                                              ->visible(fn($get) => $get('payment_type') === 'paypal' || $get('payment_type') === 'binance'),
                                      ]),
                         Forms\Components\Repeater::make('items')
                             ->columnSpanFull()
                             ->columns(5)
                             ->schema([
                                          Forms\Components\TextInput::make('description')
                                              ->label('Description')
                                              ->required()
                                              ->columnSpan(2),
                                          Forms\Components\TextInput::make('hours')
                                              ->label('Hours')
                                              ->requiredWithout('price')
                                              ->numeric()
                                              ->mask(RawJs::make('$money($input)'))
                                              ->stripCharacters(',')
                                              ->live(debounce: 500)
                                              ->afterStateUpdated(function (Get $get, Set $set) {
                                                  self::updatePrice($get, $set);
                                              }),
                                          Forms\Components\TextInput::make('rate')
                                              ->label('Rate')
                                              ->requiredWithout('price')
                                              ->numeric()
                                              ->prefix(fn(Get $get): string => Currency::symbol($get('../../currency')))
                                              ->mask('999')
                                              ->live(debounce: 500)
                                              ->afterStateUpdated(function (Get $get, Set $set) {
                                                  self::updatePrice($get, $set);
                                              }),
                                          Forms\Components\TextInput::make('price')
                                              ->label('Price')
                                              ->required()
                                              ->prefix(fn(Get $get): string => Currency::symbol($get('../../currency')))
//                                              ->mask('99999')
                                              ->readOnly()
                                              ->numeric(),
                                      ])
                             ->live(debounce: 500)
                             ->afterStateUpdated(function (Get $get, Set $set) {
                                 self::updateTotals($get, $set);
                             })
                             ->reorderableWithButtons(),
                         Forms\Components\Grid::make()
                             ->columns(6)
                             ->schema([
                                          Forms\Components\TextInput::make('subtotal')
                                              ->label('Subtotal')
                                              ->readOnly()
                                              ->required()
                                              ->columnStart(6)
                                              ->prefix(fn(Get $get): string => Currency::symbol($get('currency'))),
                                          Forms\Components\TextInput::make('tax')
                                              ->label('Tax')
                                              ->suffix('%')
                                              ->columnStart(6)
                                              ->live(debounce: 500),
                                          Forms\Components\TextInput::make('total')
                                              ->label('Total')
                                              ->readOnly()
                                              ->required()
                                              ->columnStart(6)
                                              ->prefix(fn(Get $get): string => Currency::symbol($get('currency'))),
                                      ])
                             ->afterStateUpdated(function (Get $get, Set $set) {
                                 self::updateTotals($get, $set);
                             }),
                     ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('number')
                              ->searchable()
                              ->label('Number'),
                          Tables\Columns\TextColumn::make('project')
                              ->searchable()
                              ->label('Project'),
                          Tables\Columns\IconColumn::make('paid')
                              ->boolean()
                              ->label('Paid'),
                          Tables\Columns\TextColumn::make('total')
                              ->searchable()
                              ->money(fn(Invoice $record) => $record->currency->value)
                              ->label('Total'),
                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Tables\Actions\EditAction::make()
                              ->iconButton(),
                          Tables\Actions\Action::make('Download')
                              ->icon('heroicon-o-arrow-down-tray')
                              ->iconButton()
                              ->url(fn(Invoice $record) => route('invoices.download', $record))
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function updatePrice(Get $get, Set $set): void
    {
        if (!is_numeric($get('hours')) || !is_numeric($get('rate'))) {
            return;
        }

        $price = $get('hours') * $get('rate');

        $set('price', number_format($price, 2, '.', ''));
    }

    public static function updateTotals(Get $get, Set $set): void
    {

        // Retrieve all selected items and remove empty rows
        $selectedItems = collect($get('items'))->filter(fn($item) => (!empty($item['rate']) && !empty($item['hours'])) || !empty($item['price']));

        // Calculate subtotal based on the selected products and quantities
        $subtotal = $selectedItems->reduce(function ($subtotal, $item) {
            if (!empty($item['hours']) && !empty($item['rate']))
                return $subtotal + ($item['rate'] * $item['hours']);

            if (!empty($item['price']))
                return $subtotal + $item['price'];

            return $subtotal + ($item['rate'] * $item['hours']);
        }, 0);


        // Update the state with the new values
        $set('subtotal', number_format($subtotal, 2, '.', ''));

        if ($get('tax')) {
            $set('total', number_format($subtotal + ($subtotal * ($get('tax') / 100)), 2, '.', ''));

        } else {
            $set('total', number_format($subtotal, 2, '.', ''));
        }

//        $set('subtotal', number_format($subtotal, 2, '.', ''));
//        $set('total', number_format($subtotal + ($subtotal * ($get('taxes') / 100)), 2, '.', ''));
    }

}
