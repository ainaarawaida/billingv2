<?php

namespace App\Filament\Home\Pages;

use Filament\Pages\Page;

class Web extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $slug = 'web/{company?}';
    protected static bool $shouldRegisterNavigation = false;
    public $company = null;

    protected static string $view = 'filament.home.pages.web';
}
