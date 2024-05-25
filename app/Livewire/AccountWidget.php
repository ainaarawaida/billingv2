<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class AccountWidget extends Widget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = '2';
    protected function getColumns(): int
    {
        return 1;
    }

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::widgets.account-widget';
}
