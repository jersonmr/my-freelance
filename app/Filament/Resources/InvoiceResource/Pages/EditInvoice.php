<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
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
                ->label(__('filament/resources/invoice.actions.download'))
                ->icon('tabler-pdf')
                ->url(fn () => route('invoices.download', ['invoice' => $this->record]), shouldOpenInNewTab: true),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
