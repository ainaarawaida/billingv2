<?php
 
namespace App\Filament\App\Pages;
 
class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    public function getColumns(): int | string | array
    {
        return 2;
    }
}