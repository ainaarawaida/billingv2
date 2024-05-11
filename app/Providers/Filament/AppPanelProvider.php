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
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
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
use App\Filament\App\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\App\Resources\InvoiceResource\Pages\CreateInvoice;

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
            // ->viteTheme('resources/css/app.css')
            
            ->renderHook(
                PanelsRenderHook::BODY_END  ,
                fn (): string => Blade::render('
                    <script>
                        document?.addEventListener("livewire:navigated", () => {
                            document?.querySelector("table").addEventListener("click", async (e) => {
                                const selectedEle = e.target.closest(".copy-public_url");
                               
                              if(selectedEle){
                                  let linkToCopy = selectedEle.getAttribute("myurl");
                                   
                                  try {
                                        await copyToClipboard(linkToCopy);
                                    } catch(error) {
                                        console.error(error);
                                    }
                                 
                                }
                            })
                            async function copyToClipboard(textToCopy) {
                                if (navigator.clipboard && window.isSecureContext) {
                                    await navigator.clipboard.writeText(textToCopy);
                                } else {
                                    const textArea = document.createElement("textarea");
                                    textArea.value = textToCopy;
                                        
                                    textArea.style.position = "absolute";
                                    textArea.style.left = "-999999px";
                                        
                                    document.body.prepend(textArea);
                                    textArea.select();
                            
                                    try {
                                        document.execCommand("copy");
                                    } catch (error) {
                                        console.error(error);
                                    } finally {
                                        textArea.remove();
                                    }
                                }
                            }
                          
                        })
                    </script>
                
                '),
                scopes: ListInvoices::class,
            )
            
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
            )
            ;
    }
}
