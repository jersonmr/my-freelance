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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'tabler-file-invoice';

    public static function getModelLabel(): string
    {
        return __('filament/resources/invoice.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label(__('filament/resources/invoice.client'))
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
                    ->default('INV-'.random_int(100000, 999999))
                    ->disabled()
                    ->label(__('filament/resources/invoice.number'))
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
                            ->label(__('filament/resources/invoice.project'))
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\DatePicker::make('due')
                            ->columnSpan(1)
                            ->label(__('filament/resources/invoice.date'))
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
                            ->label(__('filament/resources/invoice.currency'))
                            ->options(Currency::class)
                            ->live(),
                        Forms\Components\Select::make('payment_type')
                            ->columnSpan(1)
                            ->label(__('filament/resources/invoice.payment_type.label'))
                            ->options([
                                'bank_transfer' => __('filament/resources/invoice.payment_type.bank'),
                                'cash' => __('filament/resources/invoice.payment_type.cash'),
                                'paypal' => __('filament/resources/invoice.payment_type.paypal'),
                                'binance' => __('filament/resources/invoice.payment_type.binance'),
                            ])
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('bank_id')
                            ->columnSpan(1)
                            ->label(__('filament/resources/invoice.bank_account'))
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
                            ->required(fn (Get $get): bool => $get('payment_type') === 'bank_transfer')
                            ->visible(fn ($get) => $get('payment_type') === 'bank_transfer'),
                        Forms\Components\Select::make('payment_gateway_id')
                            ->columnSpan(1)
                            ->label(__('filament/resources/invoice.payment_gateway'))
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
                            ->required(fn (Get $get): bool => $get('payment_type') === 'paypal' || $get('payment_type') === 'binance')
                            ->visible(fn ($get) => $get('payment_type') === 'paypal' || $get('payment_type') === 'binance'),
                    ]),
                Forms\Components\Repeater::make('items')
                    ->label(__('filament/resources/invoice.tasks.label'))
                    ->columnSpanFull()
                    ->columns(5)
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label(__('filament/resources/invoice.tasks.description'))
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('hours')
                            ->label(__('filament/resources/invoice.tasks.hours'))
                            ->requiredWithout('price')
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updatePrice($get, $set);
                            }),
                        Forms\Components\TextInput::make('rate')
                            ->label(__('filament/resources/invoice.tasks.rate'))
                            ->requiredWithout('price')
                            ->numeric()
                            ->prefix(fn (Get $get): string => Currency::symbol($get('../../currency')))
                            ->mask('999')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updatePrice($get, $set);
                            }),
                        Forms\Components\TextInput::make('price')
                            ->label(__('filament/resources/invoice.tasks.price'))
                            ->required()
                            ->prefix(fn (Get $get): string => Currency::symbol($get('../../currency')))
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
                            ->label(__('filament/resources/invoice.subtotal'))
                            ->readOnly()
                            ->required()
                            ->columnStart(6)
                            ->prefix(fn (Get $get): string => Currency::symbol($get('currency'))),
                        Forms\Components\TextInput::make('tax')
                            ->label(__('filament/resources/invoice.tax'))
                            ->suffix('%')
                            ->columnStart(6)
                            ->live(debounce: 500),
                        Forms\Components\TextInput::make('total')
                            ->label(__('filament/resources/invoice.total'))
                            ->readOnly()
                            ->required()
                            ->columnStart(6)
                            ->prefix(fn (Get $get): string => Currency::symbol($get('currency'))),
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
                    ->label(__('filament/resources/invoice.number')),
                Tables\Columns\TextColumn::make('project')
                    ->searchable()
                    ->label(__('filament/resources/invoice.project')),
                Tables\Columns\IconColumn::make('paid')
                    ->boolean()
                    ->label(__('filament/resources/invoice.paid')),
                Tables\Columns\TextColumn::make('total')
                    ->searchable()
                    ->money(fn (Invoice $record) => $record->currency->value)
                    ->label(__('filament/resources/invoice.total')),
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
                    ->url(fn (Invoice $record) => route('invoices.download', $record)),
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
        if (! is_numeric($get('hours')) || ! is_numeric($get('rate'))) {
            return;
        }

        $price = $get('hours') * $get('rate');

        $set('price', number_format($price, 2, '.', ''));
    }

    public static function updateTotals(Get $get, Set $set): void
    {

        // Retrieve all selected items and remove empty rows
        $selectedItems = collect($get('items'))->filter(fn ($item) => (! empty($item['rate']) && ! empty($item['hours'])) || ! empty($item['price']));

        // Calculate subtotal based on the selected products and quantities
        $subtotal = $selectedItems->reduce(function ($subtotal, $item) {
            if (! empty($item['hours']) && ! empty($item['rate'])) {
                return $subtotal + ($item['rate'] * $item['hours']);
            }

            if (! empty($item['price'])) {
                return $subtotal + $item['price'];
            }

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
