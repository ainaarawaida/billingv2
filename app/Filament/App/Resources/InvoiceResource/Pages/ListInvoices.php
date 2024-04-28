<?php

namespace App\Filament\App\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\InvoiceResource;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
            ->badge(Invoice::query()->whereBelongsTo(Filament::getTenant(), 'teams')->count()),
            'Draft' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'draft')),
            'New' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'new')),
            'Process' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'process')),
            'Done' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'done')),
            'Expired' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'expired')),
            'Cancelled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('invoice_status', 'cancelled')),
               
        ];
    }
}