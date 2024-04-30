<?php

namespace App\Filament\Imports;

use App\Models\Quotation;
use Filament\Facades\Filament;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class QuotationImporter extends Importer
{
    protected static ?string $model = Quotation::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('customer')
                ->relationship(),
            ImportColumn::make('team_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('numbering')
                ->rules(['max:255']),
            ImportColumn::make('quotation_date')
                ->rules(['date']),
            ImportColumn::make('valid_days')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('quote_status')
                ->rules(['max:255']),
            ImportColumn::make('title')
                ->rules(['max:255']),
            ImportColumn::make('notes')
                ->rules(['max:65535']),
            ImportColumn::make('sub_total')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('taxes')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('percentage_tax')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('delivery')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('final_amount')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?Quotation
    {
        $this->data['team_id'] = Filament::getTenant()->id ;
        return Quotation::firstOrNew($this->data);
        // return Quotation::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        // return new Quotation();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your quotation import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
