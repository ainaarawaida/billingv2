<?php

namespace App\Filament\App\Resources\QuotationResource\Pages;

use Filament\Actions;
use App\Models\Quotation;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\QuotationResource;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;


    // public function getHeading(): string 
    // {
    //     // return "ddd";
    //     // $data = $this->form->getRawState();
    //     // return json_encode($data);
    //     return $this->heading ?? $this->getTitle()." ".;
    // }

    // protected function getHeaderActions(): array
    // {
    //     return [
            
    //     Actions\CreateAction::make(),
    //     ];
    // }

    public function mount(): void
    {

        // $tenant = Filament::getTenant();
        
        // dd($tenant->id);

        $this->form->fill();
    }

    protected function handleRecordCreation(array $data): Model
    {
        $tenant_id = Filament::getTenant()->id ;
        $lastid = Quotation::where('team_id', $tenant_id)->count('id') + 1 ;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


  
}
