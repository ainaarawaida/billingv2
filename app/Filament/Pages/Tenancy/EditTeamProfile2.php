<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Forms\Concerns\InteractsWithForms;

class EditTeamProfile2 extends EditTenantProfile
{
      public static function getLabel(): string
      {
          return 'Team profile';
      }
   
      public function form(Form $form): Form
      {
          return $form
              ->schema([
                  TextInput::make('name'),
                  TextInput::make('slug')
                  ->required()
                  ->unique(Team::class, ignoreRecord: true),
                  // ...
              ]);
      }
  }
