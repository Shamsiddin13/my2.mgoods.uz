<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CustomLogin;
use App\Filament\Pages\Registration;
use App\Models\UniqueLink;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('webmaster')
            ->profile()
            ->passwordReset()
            ->emailVerification()
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+l', 'ctrl+l'])
            ->globalSearchFieldSuffix('ctrl + K')
            ->font('JetBrains Mono')
            ->userMenuItems([
                MenuItem::make()
                    ->label(function () {
                        $user = auth()->user();
                        if (!$user || !$user->username) {
                            return 'Connect To Telegram ID'; // Or handle accordingly
                        }

                        $uniqueLink = UniqueLink::where('username', $user->username)
                            ->where('is_used', true)
                            ->first();

                        return $uniqueLink ? 'Connected Telegram ID' : 'Connect To Telegram ID';
                    })
                    ->url(
                        function () {
                            $user = auth()->user();

                            // Ensure the user is authenticated and has a username
                            if (!$user || !$user->username) {
                                return ''; // Or handle accordingly
                            }

                            // Fetch the unique_parameter from the unique_links table
                            $uniqueLink = UniqueLink::where('username', $user->username)
                                ->where('is_used', false)
                                ->first();

                            if ($uniqueLink) {
                                // Construct the Telegram deep link
                                $telegram_deep_link = "https://t.me/mgoods_bot?start={$uniqueLink->unique_parameter}";
                                return $telegram_deep_link;
                            } else {
                                return 'No available link'; // Or a message indicating no available link
                            }
                        }
                    )
                    ->openUrlInNewTab()
                    ->icon(
                        function () {
                            $user = auth()->user();
                            $uniqueLink = UniqueLink::where('username', $user->username)
                                ->where('is_used', true)
                                ->first();
                            return $uniqueLink ? 'heroicon-o-check-circle' : 'heroicon-o-paper-airplane';
                        }
                    ),
            ])
            ->plugins([
                SpotlightPlugin::make()
            ])
            ->default()
            ->login(CustomLogin::class)
            ->registration(Registration::class)
            ->databaseNotifications()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Inter')
            ->favicon(asset('favicon.ico'))
            ->brandLogo(fn () => view('vendor.filament.-panels.components.logo'))
//            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
//            ->pages([
//                Pages\Dashboard::class,
//            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
//            ->widgets([
//                Widgets\AccountWidget::class,
//            ])
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
            ]);
    }


}
