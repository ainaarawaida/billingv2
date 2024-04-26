<?php

namespace App\Filament\App\Resources\QuotationResource\Pages;

use Filament\Actions;
use App\Models\Quotation;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\QuotationResource;

class ListQuotations extends ListRecords
{
    protected static string $resource = QuotationResource::class;



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
            ->badge(Quotation::query()->whereBelongsTo(Filament::getTenant(), 'teams')->count()),
            'Draft' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'draft')),
            'New' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'new')),
            'Process' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'process')),
            'Done' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'done')),
            'Expired' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'expired')),
            'Cancelled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quote_status', 'cancelled')),
               
        ];
    }
}
