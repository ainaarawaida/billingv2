<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Pages\Tenancy\RegisterTeam;
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

    public function getTableQueryForExport(): Builder
    {
        return Team::query()->whereHas('members', function($q) {
            $q->where('users.id', auth()->user()->id);
        }); // Or customize the query as needed
    }
    
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
                ImageColumn::make('photo')
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
            ])
            ->recordUrl(
                fn (Model $record): string => url('/app/'.$record->slug)
            );
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('Create Company')
            ->visible(RegisterTeam::canview())
            ->url(function() {
                    return url('/app/new');
                }
            ),
          
        ];
    }
   
}
