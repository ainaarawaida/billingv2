<?php

namespace App\Livewire;

use Filament\Widgets\AccountWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'default';
    protected static bool $isLazy = false;

    protected function getColumns(): int
    {
        return 2;
    }
    
    protected function getStats(): array
    {
        return [
            Stat::make(__('Payment Received'), '192.1k'),
            Stat::make(__('Waiting Payment'), '21%'),
            // Stat::make('Average time on page', '3:12'),
        ];
    }


}
