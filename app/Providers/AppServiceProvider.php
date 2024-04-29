<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => '<meta name="robots" content="noindex,nofollow">'
        );

        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\Auth\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ms','en'])
                ->visible(outsidePanels: true)
                ->outsidePanelPlacement(Placement::TopRight)
                ->outsidePanelRoutes([
                    'auth.login',
                    'auth.profile',
                    'auth.register',
                    'tenant.registration',
                    'filament.app.auth.password-reset.request'
                    // Additional custom routes where the switcher should be visible outside panels
                ]); // also accepts a closure
        });

        FilamentIcon::register([
            'panels::sidebar.collapse-button' => 'heroicon-o-chevron-double-left',
            // 'panels::sidebar.group.collapse-button' => view('icons.chevron-up'),
        ]);


    }
}
