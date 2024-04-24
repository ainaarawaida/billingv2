<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
      public static function getLabel(): string
      {
            return 'Register Organization';
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

      protected function handleRegistration(array $data): Team
      {
            $team = Team::create($data);

            $team->members()->attach(auth()->user());

            return $team;
      }

      public static function canView(): bool
      {
        $checkteam = collect(DB::select('SELECT * FROM team_user WHERE user_id = ?', [auth()->user()->id]))->count();
         if($checkteam > 0){
            return false;
         }
            return true;
      }

      
}