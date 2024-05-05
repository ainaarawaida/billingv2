<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Payment;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class ListPayment extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
        ->query(Payment::query())
        ->columns([
           
        ]);
    }
}
