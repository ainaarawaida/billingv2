<?php

namespace App\Filament\Home\Pages;

use App\Models\Team;
use Livewire\Component;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;




class Companys extends Page implements HasForms, HasTable  
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    protected static ?string $navigationLabel = 'Companies';
    protected static ?string $title = 'Companies';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.home.pages.companys';

    
  
    public function getTableQueryForExport(): Builder
    {
        return $this->getModel()->query(); // Or customize the query as needed
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Team::query())
            ->columns([
                TextColumn::make('name')
                ->searchable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
                Action::make('goto')
                    ->label('View')
                    ->button()
                    ->action(fn(array $data,Component $livewire, Model $record ) => $livewire->redirect(url('web', ['company' => $record->slug]), navigate:false))
            ])
            ->bulkActions([
                // ...
            ])
            ->recordUrl(function(Component $livewire,Model $record){
                return url('web', ['company' => $record->slug]);
            });
    }

}
