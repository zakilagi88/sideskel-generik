<?php

namespace App\Providers\Filament;

use App\Facades\Deskel;
use App\Filament\Clusters\{HalamanArsip, HalamanBerita, HalamanKesehatan, HalamanStatistik, HalamanWilayah};
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource;
use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource;
use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource;
use App\Filament\Pages\{DeskelProfile, Dashboard, Auth\AuthLogin, Auth\AuthProfile};
use App\Filament\Clusters\HalamanKependudukan\Resources\{KartuKeluargaResource, PendudukResource, DinamikaResource, BantuanResource, TambahanResource};
use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Filament\Pages\Settings\PengaturanUmum;
use App\Filament\Resources\Shield\{RoleResource, UserResource};
use App\Models\Desa\Peraturan;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Navigation\{NavigationBuilder, NavigationItem, NavigationGroup};
use Filament\{Panel, PanelProvider};
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\{AuthenticateSession, StartSession};
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use phpDocumentor\Reflection\PseudoTypes\False_;
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
            ->favicon(fn (GeneralSettings $settings) => Storage::url($settings->site_favicon))
            ->brandName(fn (GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn (GeneralSettings $settings) => Storage::url($settings->brand_logo))
            ->brandLogoHeight(fn (GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn (GeneralSettings $settings) => $settings->site_theme)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->login(AuthLogin::class)
            ->authGuard('web')
            ->passwordReset()
            ->loginRouteSlug('login')
            ->profile(AuthProfile::class, isSimple: false)
            ->globalSearchKeyBindings([
                'command+f', 'ctrl+f'
            ])
            ->spa()
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {

                $settings = app(GeneralSettings::class)->toArray();
                $cek = $settings['site_active'];
                /** @var \App\Models\User */
                $auth = Filament::auth()->user();

                $persiapan =
                    NavigationGroup::make('Persiapan Sistem')
                    ->items([
                        NavigationItem::make('Pengaturan Aplikasi')
                            ->icon('fas-cogs')
                            ->visible(fn (): bool => $auth->can('page_PengaturanUmum') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.pengaturan-umum'))
                            ->url(fn (): string => PengaturanUmum::getUrl()),
                        NavigationItem::make(fn (): string => 'Profil ' . $settings['sebutan_deskel'])
                            ->icon('fas-city')
                            ->visible(fn (): bool => $auth->can('page_DeskelProfile') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.profil.edit', ['record' => Deskel::getFacadeRoot()->first()]))
                            ->url(fn (): string => DeskelProfileResource::getUrl()),
                        NavigationItem::make(fn (): string => 'Wilayah ' . $settings['sebutan_deskel'])
                            ->icon('fas-map-marked-alt')
                            ->visible(fn (): bool => $auth->can('page_HalamanWilayah') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.wilayah.resources.wilayahs.index'))
                            ->url(fn (): string => WilayahResource::getUrl()),
                        NavigationItem::make('Kependudukan')
                            ->icon('fas-people-roof')
                            ->visible(fn (): bool => $auth->can('page_HalamanKependudukan') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kependudukan'))
                            ->url(fn (): string => KartukeluargaResource::getUrl()),
                        NavigationItem::make('Peran')
                            ->icon('fas-user-tag')
                            ->visible(fn (): bool => $auth->can('view_shield::role') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.roles.index'))
                            ->url(fn (): string => RoleResource::getUrl()),
                        NavigationItem::make('Pengguna')
                            ->icon('fas-users')
                            ->visible(fn (): bool => $auth->can('view_shield::user') && $cek == false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.users.index'))
                            ->url(fn (): string => UserResource::getUrl()),
                    ]);
                return $builder->groups([

                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Beranda')
                                ->icon('fas-house')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    $persiapan,
                    NavigationGroup::make('DataPokok')
                        ->label('Data Pokok ' . $settings['sebutan_deskel'])
                        ->icon('fas-layer-group')
                        ->collapsible()
                        ->items([
                            NavigationItem::make(fn (): string => 'Data Umum ' . $settings['sebutan_deskel'])
                                ->label('Data Umum ' . $settings['sebutan_deskel'])
                                ->icon('fas-city')
                                ->visible(fn (): bool => $auth->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.profil.index'))
                                ->url(fn (): string => DeskelProfileResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Aparat Pemerintah ' . $settings['sebutan_deskel'])
                                ->label('Aparat Pemerintah ' . $settings['sebutan_deskel'])
                                ->icon('fas-user-tie')
                                ->visible(fn (): bool => $auth->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.aparatur.index'))
                                ->url(fn (): string => AparaturResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Sarana Prasarana ' . $settings['sebutan_deskel'])
                                ->label('Sarana Prasarana ' . $settings['sebutan_deskel'])
                                ->icon('fas-list-check')
                                ->visible(fn (): bool => $auth->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.desa.resource.sarana-prasarana.index'))
                                ->url(fn (): string => SaranaPrasaranaResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Kelembagaan ' . $settings['sebutan_deskel'])
                                ->label('Kelembagaan ' . $settings['sebutan_deskel'])
                                ->icon('fas-users-line')
                                ->visible(fn (): bool => $auth->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.lembaga.index'))
                                ->url(fn (): string => LembagaResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Keamanan dan Ketertiban ' . $settings['sebutan_deskel'])
                                ->label('Keamanan dan Lingkungan ' . $settings['sebutan_deskel'])
                                ->icon('fas-shield-halved')
                                ->visible(fn (): bool => $auth->can('page_HalamanDesa') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.keamanan-dan-lingkungan.index'))
                                ->url(fn (): string => KeamananDanLingkunganResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Wilayah ' . $settings['sebutan_deskel'])
                                ->label('Wilayah ' . $settings['sebutan_deskel'])
                                ->icon('fas-map-marked-alt')
                                ->visible(fn (): bool => $auth->can('page_HalamanWilayah') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.index.resources.wilayah.index'))
                                ->url(fn (): string => HalamanWilayah::getUrl()),

                        ]),
                    NavigationGroup::make('DataDasar')
                        ->label('Data Dasar Keluarga')
                        ->icon('fas-house-user')
                        ->collapsible()
                        ->items([
                            NavigationItem::make('Keluarga')
                                ->icon('fas-people-roof')
                                ->visible(fn (): bool => $auth->can('view_kartu::keluarga') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.keluarga.index'))
                                ->url(fn (): string => KartukeluargaResource::getUrl()),
                            NavigationItem::make('Penduduk')
                                ->icon('fas-people-group')
                                ->visible(fn (): bool => $auth->can('view_penduduk') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.penduduk.index'))
                                ->url(fn (): string => PendudukResource::getUrl()),
                            NavigationItem::make('Dinamika Kependudukan')
                                ->icon('fas-elevator')
                                ->visible(fn (): bool => $auth->can('view_dinamika') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.dinamika.index'))
                                ->url(fn (): string => DinamikaResource::getUrl()),
                            NavigationItem::make('Kesehatan Anak')
                                ->icon('fas-baby')
                                ->visible(fn (): bool => $auth->can('page_HalamanKesehatan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kesehatan'))
                                ->url(fn (): string => HalamanKesehatan::getUrl()),
                            NavigationItem::make('Program Bantuan')
                                ->icon('fas-hand-holding-hand')
                                ->visible(fn (): bool => $auth->can('view_bantuan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.bantuan.index'))
                                ->url(fn (): string => BantuanResource::getUrl()),
                            NavigationItem::make('Data Tambahan')
                                ->icon('fas-folder-plus')
                                ->visible(fn (): bool => $auth->can('view_tambahan') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.tambahan.index'))
                                ->url(fn (): string => TambahanResource::getUrl()),
                        ]),
                    NavigationGroup::make('Potensi')
                        ->label('Potensi ' . $settings['sebutan_deskel'])
                        ->icon('fas-chart-line')
                        ->items([
                            NavigationItem::make('Potensi Sumber Daya Alam')
                                ->icon('fas-chart-column')
                                ->visible(fn (): bool => $auth->can('page_HalamanPotensi') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.potensi.resources.sda.index'))
                                ->url(fn (): string => PotensiSDAResource::getUrl()),
                        ]),
                    NavigationGroup::make('Statistik')
                        ->label('Statistik ' . $settings['sebutan_deskel'])
                        ->icon('fas-chart-bar')
                        ->items([
                            NavigationItem::make('Statistik Kependudukan')
                                ->icon('fas-chart-column')
                                ->visible(fn (): bool => $auth->can('page_HalamanStatistik') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.statistik'))
                                ->url(fn (): string => HalamanStatistik::getUrl()),
                        ]),
                    NavigationGroup::make('Informasi')
                        ->label('Informasi Publik Lainnya')
                        ->icon('fas-info-circle')
                        ->items([
                            NavigationItem::make('Jadwal Kegiatan')
                                ->icon('fas-calendar-days')
                                ->visible(fn (): bool =>  $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.web.web-jadwal-kegiatan.index'))
                                ->url(fn (): string => JadwalKegiatanResource::getUrl()),
                            NavigationItem::make('Berita')
                                ->icon('fas-newspaper')
                                ->visible(fn (): bool => $auth->can('page_HalamanBerita') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.berita'))
                                ->url(fn (): string => HalamanBerita::getUrl()),
                            NavigationItem::make(fn (): string => 'Arsip ' . $settings['sebutan_deskel'])
                                ->label('Arsip ' . $settings['sebutan_deskel'])
                                ->icon('fas-box-archive')
                                ->visible(fn (): bool => $auth->can('page_HalamanArsip') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resource.peraturan.index'))
                                ->url(fn (): string => HalamanArsip::getUrl()),

                        ]),
                    NavigationGroup::make('Pengaturan')
                        ->label('Pengaturan')
                        ->icon('fas-cogs')
                        ->items([
                            NavigationItem::make('Pengaturan Peran')
                                ->icon('fas-user-shield')
                                ->visible(fn (): bool => $auth->can('view_shield::role') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.roles.index'))
                                ->url(fn (): string => RoleResource::getUrl()),
                            NavigationItem::make('Pengaturan Pengguna')
                                ->icon('fas-user-gear')
                                ->visible(fn (): bool => $auth->can('view_shield::user') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.shield.users.index'))
                                ->url(fn (): string => UserResource::getUrl()),
                            NavigationItem::make('Pengaturan Aplikasi')
                                ->icon('fas-gear')
                                ->visible(fn (): bool => $auth->can('page_PengaturanUmum') && $cek == true)
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.pengaturan-umum'))
                                ->url(fn (): string => PengaturanUmum::getUrl()),
                        ])
                ]);
            })
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): string => Blade::render('@livewire(\'components.user-info-card\')'),
            )
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    // ->selectable()
                    // ->editable()
                    ->plugins(['dayGrid', 'timeGrid'])
                    ->locale('id')
                    ->config([]),
                FilamentApexChartsPlugin::make(),
                FilamentShieldPlugin::make(),
                // FilamentAuthenticationLogPlugin::make(),
            ]);
    }
}
