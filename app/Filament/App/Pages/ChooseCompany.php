<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ChooseCompany extends Page implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $layout = 'filament-panels::components.layout.index';
    protected static string $view = 'filament.app.pages.choose-company';
    protected static bool $shouldRegisterNavigation = false;

    public function table(Table $table): Table
    {
      
        return $table
            ->query(Team::query()->whereHas('members', function($q) {
                $q->where('users.id', auth()->user()->id);
            }))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('phone'),
            ])
            ->filters([
              
            ])
            ->actions([
                Action::make('select')
                ->label('Select')
                ->url(function($record) {
                        return url('/app/'.$record->slug);
                    }
                ),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('Create Company')
            ->url(function() {
                    return url('/app/new');
                }
            ),
          
        ];
    }
   
}
