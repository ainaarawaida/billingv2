<?php

namespace App\Filament\App\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use App\Models\TeamSetting;
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
        $team_setting = TeamSetting::where('team_id', $tenant_id )->first();
        $invoice_current_no = $team_setting->invoice_current_no ?? '0' ;    

        $team_setting['invoice_current_no'] = $invoice_current_no + 1 ;
        $team_setting->save();

        // $lastid = Invoice::where('team_id', $tenant_id)->count('id') + 1 ;
        $data['numbering'] = str_pad(($invoice_current_no + 1), 6, "0", STR_PAD_LEFT) ;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
