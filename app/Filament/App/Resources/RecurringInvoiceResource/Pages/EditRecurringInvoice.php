<?php

namespace App\Filament\App\Resources\RecurringInvoiceResource\Pages;

use App\Filament\App\Resources\RecurringInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecurringInvoice extends EditRecord
{
    protected static string $resource = RecurringInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
