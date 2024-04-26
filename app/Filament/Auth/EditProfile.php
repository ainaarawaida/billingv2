<?php

namespace App\Filament\Auth;
 
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getFormActions(): array
    {
        // dd(Filament::getTenant());
        $panel = Filament::getCurrentPanel()->getId();
        return [
            Action::make('Back')
            // ->url('/'.$panel.'/')
            ->url(url()->previous())
                        
            ->extraAttributes(['style' => 'width:30%;','class' => 'bg-gray-400']),   
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }



}