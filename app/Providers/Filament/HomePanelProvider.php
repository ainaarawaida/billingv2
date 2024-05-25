<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class HomePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('home')
            ->path('')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/home/theme.css')
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER ,
                function(): string {
                    return '<a wire:navigate href="'.url('/app/login').'" class="fi-topbar-item-button flex items-center justify-center gap-x-2 rounded-lg px-3 py-2 outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 bg-gray-50 dark:bg-white/5">
                   
                    <span class="fi-topbar-item-label text-sm font-medium text-primary-600 dark:text-primary-400">
                        Login
                    </span>

        
            </a>' ;
                }
            )
            ->discoverResources(in: app_path('Filament/Home/Resources'), for: 'App\\Filament\\Home\\Resources')
            ->discoverPages(in: app_path('Filament/Home/Pages'), for: 'App\\Filament\\Home\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Home/Widgets'), for: 'App\\Filament\\Home\\Widgets')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
               
            ])
            ->topNavigation();
    }
}
