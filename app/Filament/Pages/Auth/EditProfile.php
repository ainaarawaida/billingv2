<?php

namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Forms\Components\Fieldset;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Tabs::make('Tabs')
                    ->tabs([
                        Components\Tabs\Tab::make(__('General'))
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                              
                            ]),
                        Components\Tabs\Tab::make(__('Address'))
                            ->schema([
                                Components\Group::make()
                                    ->relationship('user_detail')
                                    ->schema([
                                        Components\TextInput::make('address')
                                                ->maxLength(255)
                                                ->columnSpan(2),
                                        Components\TextInput::make('poscode')
                                                ->maxLength(255),
                                        Components\TextInput::make('city')
                                                ->maxLength(255),
                                        Components\Select::make('state')
                                                        ->options([
                                                            'JHR' => 'Johor',
                                                            'KDH' => 'Kedah',
                                                            'KTN' => 'Kelantan',
                                                            'MLK' => 'Melaka',
                                                            'NSN' => 'Negeri Sembilan',
                                                            'PHG' => 'Pahang',
                                                            'PRK' => 'Perak',
                                                            'PLS' => 'Perlis',
                                                            'PNG' => 'Pulau Pinang',
                                                            'SBH' => 'Sabah',
                                                            'SWK' => 'Sarawak',
                                                            'SGR' => 'Selangor',
                                                            'TRG' => 'Terengganu',
                                                            'KUL' => 'W.P. Kuala Lumpur',
                                                            'LBN' => 'W.P. Labuan',
                                                            'PJY' => 'W.P. Putrajaya'
                                                        ])
                                                        ->searchable()
                                                        ->preload()
                        
                                
                                    ])->columns(2),
                            ]),

                        Components\Tabs\Tab::make(__('Photo'))
                        ->schema([
                            Components\Group::make()
                                ->relationship('user_detail')
                                ->schema([
                                    Components\FileUpload::make('photo')
                                            ->image()
                                            ->directory('photo')
                                            ->avatar()
                                            ->imageEditor()
                                            ->circleCropper()
                                            ->columnSpan(2),
                                ])->columns(2),
                        ]),
                    ])
                ]);

               


         
    
    
    }

    protected function getFormActions(): array
    {
        // dd(Filament::getTenant());
        $panel = Filament::getCurrentPanel()->getId();
        return [
            // Action::make('Back')
            // // ->url('/'.$panel.'/')
            // ->url(url()->previous())
            $this->getCancelFormAction()
            ->label('Back')
            ->extraAttributes(['style' => 'width:30%;','class' => 'bg-gray-400']),   
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }



}