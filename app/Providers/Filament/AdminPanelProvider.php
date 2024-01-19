<?php

namespace App\Providers\Filament;


use App\Filament\AvatarProviders\BoringAvatarsProvider;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\{CustomLogin, Generator, PendudukStats};
use App\Filament\Resources\{BeritaResource, KartuKeluargaResource, KategoriBeritaResource, PendudukResource, StatistikResource, WilayahResource, UserResource};
use App\Http\Middleware\FilamentSettings;
use App\Livewire\SettingsComponent;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Filament\Pages;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Navigation\{NavigationBuilder, NavigationItem, NavigationGroup};
use Filament\{Panel, PanelProvider, Widgets};
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Tapp\FilamentAuthenticationLog\Resources\AuthenticationLogResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('8xl')
            ->brandLogo(asset('images/cek1.png'))
            ->favicon(asset('images/cek1.png'))
            ->brandLogoHeight('5rem')
            ->login(CustomLogin::class)
            ->authGuard('web')
            ->globalSearchKeyBindings([
                'command+f', 'ctrl+f'
            ])
            // ->spa()
            ->emailVerification()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Indigo,
                'primary' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
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
                DispatchServingFilamentEvent::class,
                FilamentSettings::class

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
                            NavigationItem::make('Statistik Penduduk')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.penduduk-stats'))
                                ->url(fn (): string => PendudukStats::getUrl()),
                        ]),
                    NavigationGroup::make('Wilayah')
                        ->items([
                            ...WilayahResource::getNavigationItems(),
                            NavigationItem::make('Generator')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.generator'))
                                ->url(fn (): string => Generator::getUrl()),
                        ]),
                    NavigationGroup::make('Website')
                        ->items([
                            ...StatistikResource::getNavigationItems(),
                            ...KategoriBeritaResource::getNavigationItems(),
                            ...BeritaResource::getNavigationItems()
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
                FilamentShieldPlugin::make(),
                FilamentAuthenticationLogPlugin::make(),
                BreezyCore::make()
                    ->avatarUploadComponent(fn () => FileUpload::make('avatar_url')->directory('profile-photos'))
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                    )
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: true, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')

                    )
                    ->myProfileComponents([
                        'personal_info' => SettingsComponent::class
                    ]),
            ]);
    }
}