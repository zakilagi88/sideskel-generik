<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\BoringAvatarsProvider;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\KartukeluargaResource;
use App\Filament\Resources\PendudukResource;
use App\Filament\Resources\Shield\RoleResource;
use App\Filament\Resources\SLSResource;
use App\Filament\Resources\TagResource;
use App\Filament\Resources\UserResource;
use Awcodes\FilamentGravatar\GravatarPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Awcodes\FilamentGravatar\GravatarProvider;
use Filament\Forms\Components\Hidden;
use Filament\Navigation\MenuItem;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Tapp\FilamentAuthenticationLog\Resources\AuthenticationLogResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('8xl')
            // ->topNavigation()
            ->id('admin')
            ->path('admin')
            ->login()

            ->userMenuItems(
                [
                    'profile' => MenuItem::make()->label('Edit Profile'),
                ]
            )
            ->globalSearchKeyBindings([
                'command+f', 'ctrl+f'
            ])
            ->spa()
            ->emailVerification()
            // ->defaultAvatarProvider(GravatarProvider::class)
            ->profile(EditProfile::class)
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                // 'navy' => Color::Slate,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class, 
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
                DispatchServingFilamentEvent::class

            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Beranda')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Manajemen Data')
                        ->items([
                            ...KartukeluargaResource::getNavigationItems(),
                            ...PendudukResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Wilayah')
                        ->items([
                            // ...RWResource::getNavigationItems(),
                            // ...RtResource::getNavigationItems(),
                            ...SLSResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Website')
                        ->items([
                            ...CategoryResource::getNavigationItems(),
                             ...ArticleResource::getNavigationItems()
                        ]),
                    NavigationGroup::make('Pengaturan')
                        ->items([
                            ...RoleResource::getNavigationItems(),
                            ...UserResource::getNavigationItems(),
                            ...AuthenticationLogResource::getNavigationItems(),

                        ])
                ]);
            })
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                'panels::topbar.start',
                fn () => view('filament.custom.topbar-start'),
            )
            ->plugins([
                FilamentAuthenticationLogPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                GravatarPlugin::make()
                    ->default('robohash')
                    ->size(400)
                    ->rating('pg'),

            ]);
    }
}
