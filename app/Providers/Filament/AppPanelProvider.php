<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use App\Models\Invoice;
use App\Models\Payment;

use App\Models\Quotation;
use Filament\PanelProvider;

use App\Livewire\ListPayment;
use Filament\Facades\Filament;
use App\Livewire\AccountWidget;
use App\Livewire\StatsOverview;
use App\Filament\Pages\Auth\Login;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use App\Filament\App\Pages\Dashboard;
use App\Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\App\Resources\InvoiceResource;
use App\Filament\App\Resources\PaymentResource;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Pages\Tenancy\EditTeamProfile2;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\App\Resources\QuotationResource;
use App\Filament\Pages\Auth\RequestPasswordReset;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\App\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\App\Resources\PaymentResource\Pages\ListPayments;
use App\Filament\App\Resources\InvoiceResource\Pages\CreateInvoice;
use App\Filament\App\Resources\PaymentResource\Widgets\PaymentChart;
use App\Filament\App\Resources\QuotationResource\Pages\ListQuotations;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\App\Resources\RecurringInvoiceResource\Pages\EditRecurringInvoice;
use App\Filament\App\Resources\RecurringInvoiceResource\Pages\ListRecurringInvoices;
use App\Filament\App\Resources\RecurringInvoiceResource\Pages\CreateRecurringInvoice;
use Althinect\FilamentSpatieRolesPermissions\Middleware\SyncSpatiePermissionsWithFilamentTenants;
 
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
                            document?.querySelector("table")?.addEventListener("click", async (e) => {
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
                scopes: [
                    ListQuotations::class,
                    ListInvoices::class,
                    ListPayments::class,
                    ListRecurringInvoices::class,
                ]
            )
            ->sidebarCollapsibleOnDesktop()
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenant(Team::class, ownershipRelationship: 'teams', slugAttribute: 'slug')
            ->tenantMenu(isset(request()->segments()[2]) && request()->segments()[2] == 'choose-company' ? false : true)
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(RequestPasswordReset::class)
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
                NavigationItem::make('Quotations')
                    ->label(__('Quotations'))
                    ->url(fn () => QuotationResource::getUrl('index',['activeTab' => 'new']))
                    ->isActiveWhen(fn (): bool => strpos(url()->current(), QuotationResource::getUrl('index')) !== false)
                    ->icon('heroicon-o-clipboard')
                    ->group('Billing')
                    ->sort(1)
                    ->badge(fn () => Quotation::whereBelongsTo(Filament::getTenant(), 'teams')->where('quote_status', 'new')->count()),
                NavigationItem::make('Invoices')
                    ->label(__('Invoices'))
                    ->url(fn () => InvoiceResource::getUrl('index',['activeTab' => 'new']))
                    ->isActiveWhen(fn (): bool => strpos(url()->current(), InvoiceResource::getUrl('index')) !== false)
                    ->icon('heroicon-o-newspaper')
                    ->group('Billing')
                    ->sort(2)
                    ->badge(fn () => Invoice::whereBelongsTo(Filament::getTenant(), 'teams')->where('invoice_status', 'new')->count()),
                NavigationItem::make('Payments')
                    ->label(__('Payments'))
                    ->url(fn () => PaymentResource::getUrl('index',['activeTab' => 'pending_payment']))
                    ->isActiveWhen(fn (): bool => strpos(url()->current(), PaymentResource::getUrl('index')) !== false)
                    ->icon('heroicon-o-credit-card')
                    ->group('Billing')
                    ->sort(7)
                    ->badge(fn () => Payment::whereBelongsTo(Filament::getTenant(), 'teams')->where('status', 'pending_payment')->count()),
                NavigationItem::make('Organization')
                    ->label(__('Organization'))
                    ->isActiveWhen(fn (): bool => url()->current() == Filament::getUrl().'/profile')
                    ->url(fn () => Filament::getUrl().'/profile')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Setting')
                    ->sort(1),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                // AccountWidget::class,
                StatsOverview::class,
                PaymentChart::class,
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
                // SyncSpatiePermissionsWithFilamentTenants::class,
            ])
            ->plugins([
                \Hasnayeen\Themes\ThemesPlugin::make(),
                FilamentSpatieRolesPermissionsPlugin::make()
            ]);
    }
}
