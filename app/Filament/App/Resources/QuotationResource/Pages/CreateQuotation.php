<?php

namespace App\Filament\App\Resources\QuotationResource\Pages;

use App\Models\Note;
use Filament\Actions;
use App\Models\Quotation;
use App\Models\TeamSetting;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\QuotationResource;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        $team_setting = TeamSetting::where('team_id', $tenant_id )->first();
        $quotation_current_no = $team_setting->quotation_current_no ?? '0' ;

        $team_setting['quotation_current_no'] = $quotation_current_no + 1 ;
        $team_setting->save();

      
        
        // $lastid = Quotation::where('team_id', $tenant_id)->count('id') + 1 ;
        $data['numbering'] = str_pad(($quotation_current_no + 1), 6, "0", STR_PAD_LEFT) ;
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

    protected function associateRecordWithTenant(Model $record, Model $tenant): Model
    {
        $relationship = static::getResource()::getTenantRelationship($tenant);

        if ($relationship instanceof HasManyThrough) {
            $record->save();
            return $record;
        }
        $record = $relationship->save($record);
        
        //save note
        if($this->form->getState()['content'] != ''){
            Note::create([
                'user_id' => auth()->user()->id,
                'team_id' => Filament::getTenant()->id,
                'type' => 'quotation',
                'type_id' => $record->id,
                'content' =>  $this->form->getState()['content'],
                
            ]);

        }

        return $record ;
    }


  
}
