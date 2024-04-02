<?php

namespace App\Providers\Filament;

use App\Facades\Deskel;
use App\Filament\Clusters\{HalamanBerita, HalamanKesehatan, HalamanStatistik, HalamanWilayah};
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Pages\{DeskelProfile, Dashboard, Auth\AuthLogin, Auth\AuthProfile};
use App\Filament\Clusters\HalamanKependudukan\Resources\{KartuKeluargaResource, PendudukResource, DinamikaResource, BantuanResource, TambahanResource};
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Filament\Resources\Web\{StatistikResource};
use App\Filament\Resources\Shield\{AutentikasiLogResource, AutentikasiPengguna, RoleResource, UserResource};
use App\Http\Middleware\FilamentSettings;
use App\Livewire\Components\UserInfo;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Navigation\{NavigationBuilder, NavigationItem, NavigationGroup};
use Filament\{Panel, PanelProvider};
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\{AuthenticateSession, StartSession};
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Tapp\FilamentAuthenticationLog\{FilamentAuthenticationLogPlugin, Resources\AuthenticationLogResource};

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('panel')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->favicon(asset('images/logo.png'))
            ->brandLogoHeight('10rem')
            ->login(AuthLogin::class)
            ->authGuard('web')
            ->passwordReset()
            ->loginRouteSlug('login')
            ->profile(AuthProfile::class, isSimple: false)
            ->globalSearchKeyBindings([
                'command+f', 'ctrl+f'
            ])
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Indigo,
                'primary' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'fuchsia' => Color::Fuchsia,
            ])
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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

                $deskel = Deskel::getFacadeRoot();
                $cek = $deskel->deskel_status;
                $persiapan =
                    NavigationGroup::make('Persiapan Sistem')
                    ->items([
                        NavigationItem::make(fn (): string => 'Profil ' . $deskel->deskel_sebutan)
                            ->icon('fas-city')
                            ->visible(fn (): bool => auth()->user()->can('page_DeskelProfile') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.deskel-profile'))
                            ->url(fn (): string => DeskelProfile::getUrl()),
                        NavigationItem::make(fn (): string => 'Wilayah ' . $deskel->deskel_sebutan)
                            ->icon('fas-map-marked-alt')
                            ->visible(fn (): bool => auth()->user()->can('page_HalamanWilayah') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.wilayah.resources.wilayahs.index'))
                            ->url(fn (): string => WilayahResource::getUrl()),
                        NavigationItem::make('Kependudukan')
                            ->icon('fas-people-roof')
                            ->visible(fn (): bool => auth()->user()->can('page_HalamanKependudukan') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kependudukan'))
                            ->url(fn (): string => KartukeluargaResource::getUrl()),
                        NavigationItem::make('Peran')
                            ->icon('fas-user-tag')
                            ->visible(fn (): bool => auth()->user()->can('view_shield::role') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.roles.index'))
                            ->url(fn (): string => RoleResource::getUrl()),
                        NavigationItem::make('Pengguna')
                            ->icon('fas-users')
                            ->visible(fn (): bool => auth()->user()->can('view_shield::user') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.users.index'))
                            ->url(fn (): string => UserResource::getUrl()),
                    ]);
                return $builder->groups([

                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Beranda')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    $persiapan,
                    NavigationGroup::make('Desa')
                        ->items([
                            NavigationItem::make(fn (): string => 'Profil ' . $deskel->deskel_sebutan)
                                ->icon('fas-city')
                                ->visible(fn (): bool => auth()->user()->can('page_DeskelProfile') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.deskel-profile'))
                                ->url(fn (): string => DeskelProfile::getUrl()),
                            NavigationItem::make(fn (): string => 'Pemerintah ' . $deskel->deskel_sebutan)
                                ->icon('fas-user-tie')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.aparatur.index'))
                                ->url(fn (): string => AparaturResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Wilayah ' . $deskel->deskel_sebutan)
                                ->icon('fas-map-marked-alt')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanWilayah') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.wilayah.pages.index'))
                                ->url(fn (): string => HalamanWilayah::getUrl()),
                            NavigationItem::make(fn (): string => 'Arsip ' . $deskel->deskel_sebutan)
                                ->icon('fas-box-archive')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.desa'))
                                ->url(fn (): string => HalamanDesa::getUrl()),

                        ]),
                    NavigationGroup::make('Kependudukan')
                        ->items([
                            NavigationItem::make('Keluarga')
                                ->icon('fas-people-roof')
                                ->visible(fn (): bool => auth()->user()->can('view_kartu::keluarga') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.keluarga.index'))
                                ->url(fn (): string => KartukeluargaResource::getUrl()),
                            NavigationItem::make('Penduduk')
                                ->icon('fas-people-group')
                                ->visible(fn (): bool => auth()->user()->can('view_penduduk') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.penduduk.index'))
                                ->url(fn (): string => PendudukResource::getUrl()),
                            NavigationItem::make('Dinamika Kependudukan')
                                ->icon('fas-elevator')
                                ->visible(fn (): bool => auth()->user()->can('view_dinamika') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.dinamika.index'))
                                ->url(fn (): string => DinamikaResource::getUrl()),
                            NavigationItem::make('Data Tambahan')
                                ->icon('fas-folder-plus')
                                ->visible(fn (): bool => auth()->user()->can('view_tambahan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.tambahan.index'))
                                ->url(fn (): string => TambahanResource::getUrl()),
                        ]),
                    NavigationGroup::make('Kesehatan')
                        ->items([
                            NavigationItem::make('Kesehatan Anak')
                                ->icon('fas-baby')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanKesehatan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kesehatan'))
                                ->url(fn (): string => HalamanKesehatan::getUrl()),
                        ]),
                    NavigationGroup::make('Keuangan')
                        ->items([
                            NavigationItem::make('APBDes')
                                ->icon('fas-money-bill-transfer')
                                ->visible(fn (): bool => auth()->user()->hasRole('Admin') && $cek == true)
                                // ->visible(fn (): bool => auth()->user()->can('view_bantuan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.bantuan.index'))
                        ]),
                    NavigationGroup::make('Bantuan')
                        ->items([
                            NavigationItem::make('Bantuan')
                                ->icon('fas-hand-holding-hand')
                                ->visible(fn (): bool => auth()->user()->can('view_bantuan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.bantuan.index'))
                                ->url(fn (): string => BantuanResource::getUrl()),
                        ]),
                    NavigationGroup::make('Statistik')
                        ->items([
                            NavigationItem::make('Data Statistik')
                                ->icon('fas-chart-column')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanStatistik') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.statistik'))
                                ->url(fn (): string => HalamanStatistik::getUrl()),
                        ]),
                    NavigationGroup::make('Jadwal Kegiatan')
                        ->items([
                            NavigationItem::make('Jadwal Kegiatan')
                                ->icon('fas-calendar-days')
                                ->visible(fn (): bool =>  $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.web.web-jadwal-kegiatan.index'))
                            // ->url(fn (): string => StatistikResource::getUrl()),
                        ]),
                    NavigationGroup::make('Website')
                        ->items([
                            NavigationItem::make('Berita')
                                ->icon('fas-newspaper')
                                ->visible(fn (): bool => auth()->user()->can('page_HalamanBerita') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.berita'))
                                ->url(fn (): string => HalamanBerita::getUrl()),
                            NavigationItem::make('Statistik')
                                ->icon('fas-chart-line')
                                ->visible(fn (): bool => auth()->user()->can('view_web::statistik') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.web.web-statistik.index'))
                                ->url(fn (): string => StatistikResource::getUrl()),

                        ]),
                    NavigationGroup::make('Pengaturan')
                        ->items([
                            NavigationItem::make('Peran')
                                ->icon('fas-user-tag')
                                ->visible(fn (): bool => auth()->user()->can('view_shield::role') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.roles.index'))
                                ->url(fn (): string => RoleResource::getUrl()),
                            NavigationItem::make('Pengguna')
                                ->icon('fas-users')
                                ->visible(fn (): bool => auth()->user()->can('view_shield::user') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.users.index'))
                                ->url(fn (): string => UserResource::getUrl()),
                            // NavigationItem::make('Autentikasi Log')
                            //     ->icon('fas-user-lock')
                            //     ->visible(fn (): bool => auth()->user()->can('view_shield::autentikasi::log') && $cek == true)
                            //     ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.authentication-logs.index'))
                            //     ->url(fn (): string => AutentikasiLogResource::getUrl()),

                        ])
                ]);
            })
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                'panels::topbar.start',
                fn () => view('filament.custom.topbar-start'),
            )
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->plugins(['dayGrid', 'timeGrid'])
                    ->config([]),
                FilamentApexChartsPlugin::make(),
                FilamentShieldPlugin::make(),
                // FilamentAuthenticationLogPlugin::make(),
            ]);
    }
}
