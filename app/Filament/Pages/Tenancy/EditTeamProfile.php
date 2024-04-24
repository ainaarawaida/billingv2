<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
      public static function getLabel(): string
      {
            return 'Organization';
      }

      public function form(Form $form): Form
      {
            return $form
            ->schema([
                  TextInput::make('name')
                        ->label('Name / Company Name')
                        ->required()
                        ->live(onBlur:true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                  TextInput::make('slug')
                        ->required()
                        ->unique(Team::class, 'slug'),
                  TextInput::make('email')
                        ->email()
                        // ->required()
                        ->maxLength(255),
                  TextInput::make('phone')
                        ->tel()
                        // ->required()
                        ->maxLength(255),
                
                    TextInput::make('ssm')
                        ->label('SSM No.')
                        ->maxLength(255),
                        TextInput::make('address')
                        ->maxLength(255)
                        ->columnSpan(2),
                    TextInput::make('poscode')
                        ->maxLength(255),
                    TextInput::make('city')
                        ->maxLength(255),
                    Select::make('state')
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

         

            ])->columns(2);
      }

      public function getRedirectUrl(): string
      {
            // return Filament::getUrl('index');
            // $newSlug = $this->record->slug;
            // $tenant = Filament::getTenant();
            // dd($tenant);
            // Replace '/desired-route' with the actual path you want to redirect to
            // dd(route('filament.admin.tenant'));
            // dd(route('filament.admin.tenant'));
            // $link = route('filament.admin.tenant') ;
            return route('filament.admin.tenant');
            // return redirect()->to($link);
            // return static::getResource()::getUrl('index');
            // return route('filament.admin.tenant');
      }
}
