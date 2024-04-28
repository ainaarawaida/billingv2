<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use App\Filament\Pages\Auth\Login;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Pages\Tenancy\EditTeamProfile2;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\App\Resources\QuotationResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->colors([
                'primary' => Color::Slate,
            ])
            // ->renderHook(
            //     PanelsRenderHook::SIDEBAR_NAV_END ,
            //     function(): string {
            //         return '<a wire:navigate href="'.url('/app/login').'" class="fi-topbar-item-button flex items-center justify-center gap-x-2 rounded-lg px-3 py-2 outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 bg-gray-50 dark:bg-white/5">
                   
            //         <span class="fi-topbar-item-label text-sm font-medium text-primary-600 dark:text-primary-400">
            //             Login
            //         </span>

        
            // </a>' ;
            //     }
            // )
            ->sidebarCollapsibleOnDesktop()
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenant(Team::class, ownershipRelationship: 'teams', slugAttribute: 'slug')
            ->tenantMenu(isset(request()->segments()[2]) && request()->segments()[2] == 'choose-company' ? false : true)
            ->login(Login::class)
            ->registration()
            ->passwordReset()
            ->emailVerification()
            // ->profile()
            ->profile(EditProfile::class)
            ->spa()
            ->navigation(isset(request()->segments()[2]) && request()->segments()[2] == 'choose-company' ? false : true)
            ->databaseNotifications()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Billing'),
                NavigationGroup::make()
                    ->label('Resources'),
                NavigationGroup::make()
                    ->label('Setting'),
            ])
            ->navigationItems([
                NavigationItem::make('Organization')
                    ->label(__('Organization'))
                    ->url(fn () => Filament::getUrl().'/profile')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Setting')
                    ->sort(1),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                Authenticate::class,
            ])
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
            );
    }
}