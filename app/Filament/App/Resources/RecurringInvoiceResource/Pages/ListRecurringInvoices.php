<?php

namespace App\Filament\App\Resources\RecurringInvoiceResource\Pages;

use App\Filament\App\Resources\RecurringInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecurringInvoices extends ListRecords
{
    protected static string $resource = RecurringInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
