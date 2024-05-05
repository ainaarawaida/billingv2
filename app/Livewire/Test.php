<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;



class Test extends Component  
{
   
    public $table ;

    public function mount(Table $table)
    {
      
       $this->table = $table
        ->query(Payment::query())
        ->columns([
            TextColumn::make('notes'),
        ])
        ->paginated(false);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Payment::query())
            ->columns([
                TextColumn::make('notes'),
            ])
            ->paginated(false);
    }


    public function render()
    {
        return view('livewire.test');
    }
}
