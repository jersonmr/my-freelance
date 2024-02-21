<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
//        dd($this->record);
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('print')
                ->label('Generar PDF')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('invoices.download', ['invoice' => $this->record]), shouldOpenInNewTab: true)
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
