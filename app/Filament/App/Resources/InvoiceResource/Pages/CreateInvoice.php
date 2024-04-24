<?php

namespace App\Filament\App\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\InvoiceResource;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $tenant_id = Filament::getTenant()->id ;
        $lastid = Invoice::where('team_id', $tenant_id)->count('id') + 1 ;
        $data['numbering'] = str_pad($lastid, 6, "0", STR_PAD_LEFT) ;
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }
}
