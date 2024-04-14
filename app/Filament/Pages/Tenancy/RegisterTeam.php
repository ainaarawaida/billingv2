<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    ->required()
                    ->live(onBlur:true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                    ->required()
                    ->unique(Team::class, 'slug'),
                  ]);
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