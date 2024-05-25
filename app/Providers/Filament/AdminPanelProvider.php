<?php

namespace App\Providers\Filament;

use App\Exports\TemplateImport;
use App\Exports\UserWilayahExport;
use App\Facades\Deskel;
use App\Filament\Clusters\{HalamanArsip, HalamanBerita, HalamanKesehatan, HalamanStatistik, HalamanWilayah};
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource;
use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource;
use App\Filament\Pages\{DeskelProfile, Dashboard, Auth\AuthLogin, Auth\AuthProfile};
use App\Filament\Clusters\HalamanKependudukan\Resources\{KartuKeluargaResource, PendudukResource, DinamikaResource, BantuanResource, TambahanResource};
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages\ListKartukeluargas;
use App\Filament\Clusters\HalamanPengaturan\Resources\{RoleResource, UserResource};
use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages\ListWilayahs;
use App\Filament\Pages\Settings\PengaturanUmum;
use App\Filament\Resources\Shield\RoleResource as ShieldRoleResource;
use App\Filament\Resources\Shield\UserResource as ShieldUserResource;
use App\Models\Penduduk;
use App\Models\User;
use App\Models\Wilayah;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\{AuthenticateSession, StartSession};
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Maatwebsite\Excel\Facades\Excel;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

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
            ->routes(
                fn () => FacadesRoute::get('/downloads', function () {
                    return response()->download(storage_path('app/private/deskel/exports/' . 'akun_pengguna.xlsx'));
                })->name('downloads'),
            )
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

                $deskel = Deskel::getFacadeRoot();
                $settings = app(GeneralSettings::class)->toArray();
                $cek = $settings['site_active'];
                /** @var \App\Models\User */
                $auth = Filament::auth()->user();

                $beranda = NavigationGroup::make()
                    ->items([
                        NavigationItem::make('Beranda')
                            ->icon('fas-house')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                    ]);

                $persiapan = null;
                if ($cek == false) {
                    $pdd = Penduduk::exists();
                    $wilayah = Wilayah::exists();
                    $step = $settings['site_init'];

                    $persiapan =
                        NavigationGroup::make('Persiapan Sistem')
                        ->items([
                            NavigationItem::make('Pengaturan Aplikasi')
                                ->icon('fas-cogs')
                                ->visible(fn (): bool => $auth->can('page_PengaturanUmum'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.pengaturan-umum'))
                                ->badge(fn (): string => $step[0] ? '✓' : '✕', $step[0] ? 'success' : 'danger')
                                ->url(fn (): string => PengaturanUmum::getUrl()),
                            NavigationItem::make(fn (): string => 'Profil ' . $settings['sebutan_deskel'])
                                ->icon('fas-city')
                                ->visible(fn (): bool => $auth->can('view_deskel::profile'))
                                ->badge(fn (): string => $step[1] && $deskel->deskel_id != null ? '✓' : '✕', $step[1] && $deskel->deskel_id != null ? 'success' : 'danger')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.profil.edit', ['record' => $deskel->id]))
                                ->url(fn (): string => DeskelProfileResource::getUrl('edit', ['record' => $deskel->id])),
                            NavigationItem::make(fn (): string => 'Wilayah ' . $settings['sebutan_deskel'])
                                ->icon('fas-map-marked-alt')
                                ->badge(fn (): string => $step[2] && $wilayah ? '✓' : '✕', $step[2] && $wilayah ? 'success' : 'danger')
                                ->visible(fn (): bool => $auth->can('page_HalamanWilayah'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.wilayah.resources.wilayahs.index'))
                                ->url(fn (): string => WilayahResource::getUrl()),
                            NavigationItem::make('Kependudukan')
                                ->icon('fas-people-roof')
                                ->badge(fn (): string => $step[3] && $pdd ? '✓' : '✕', $step[3] && $pdd ? 'success' : 'danger')
                                ->visible(fn (): bool => $auth->can('page_HalamanKependudukan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kependudukan.resources.keluarga.index'))
                                ->url(fn (): string => KartukeluargaResource::getUrl()),
                            NavigationItem::make('Peran dan Pengguna')
                                ->icon('fas-users-gear')
                                ->badge(fn (): string => $step[4] && $step[3] ? '✓' : '✕', $step[4] && $step[3] ? 'success' : 'danger')
                                ->visible(fn (): bool => $auth->can('view_shield::role'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pengaturan.resources.peran.index'))
                                ->url(fn (): string => ShieldRoleResource::getUrl()),
                        ]);

                    return $builder->groups([
                        $beranda,
                        $persiapan
                    ]);
                } else {

                    $dataPokok = NavigationGroup::make('DataPokok')
                        ->label('Data Pokok ' . $settings['sebutan_deskel'])
                        ->icon('fas-layer-group')
                        ->collapsible()
                        ->items([
                            NavigationItem::make(fn (): string => 'Data Umum ' . $settings['sebutan_deskel'])
                                ->label('Data Umum ' . $settings['sebutan_deskel'])
                                ->icon('fas-city')
                                ->visible(fn (): bool => $auth->can('view_deskel::profile'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.profil.index'))
                                ->url(fn (): string => DeskelProfileResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Aparat Pemerintah ' . $settings['sebutan_deskel'])
                                ->label('Aparat Pemerintah ' . $settings['sebutan_deskel'])
                                ->icon('fas-user-tie')
                                ->visible(fn (): bool => $auth->can('view_aparatur'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.aparatur.index'))
                                ->url(fn (): string => AparaturResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Sarana Prasarana ' . $settings['sebutan_deskel'])
                                ->label('Sarana Prasarana ' . $settings['sebutan_deskel'])
                                ->icon('fas-list-check')
                                ->visible(fn (): bool => $auth->can('view_sarana::prasarana'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.sarana-prasarana.index'))
                                ->url(fn (): string => SaranaPrasaranaResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Kelembagaan ' . $settings['sebutan_deskel'])
                                ->label('Kelembagaan ' . $settings['sebutan_deskel'])
                                ->icon('fas-users-line')
                                ->visible(fn (): bool => $auth->can('view_lembaga'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.lembaga.index'))
                                ->url(fn (): string => LembagaResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Keamanan dan Ketertiban ' . $settings['sebutan_deskel'])
                                ->label('Keamanan dan Lingkungan ' . $settings['sebutan_deskel'])
                                ->icon('fas-shield-halved')
                                ->visible(fn (): bool => $auth->can('view_keamanan::dan::lingkungan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.deskel.resources.keamanan-dan-lingkungan.index'))
                                ->url(fn (): string => KeamananDanLingkunganResource::getUrl()),
                            NavigationItem::make(fn (): string => 'Wilayah ' . $settings['sebutan_deskel'])
                                ->label('Wilayah ' . $settings['sebutan_deskel'])
                                ->icon('fas-map-marked-alt')
                                ->visible(fn (): bool => $auth->can('page_HalamanWilayah'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.index.resources.wilayah.index'))
                                ->url(fn (): string => HalamanWilayah::getUrl()),

                        ]);

                    $potensi = NavigationGroup::make('Potensi')
                        ->label('Potensi ' . $settings['sebutan_deskel'])
                        ->icon('fas-chart-line')
                        ->items([
                            NavigationItem::make('Potensi Sumber Daya Alam')
                                ->icon('fas-chart-column')
                                ->visible(fn (): bool => $auth->can('page_HalamanPotensi'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.potensi.resources.sda.index'))
                                ->url(fn (): string => PotensiSDAResource::getUrl()),
                        ]);

                    $statistik = NavigationGroup::make('Statistik')
                        ->label('Statistik ' . $settings['sebutan_deskel'])
                        ->icon('fas-chart-bar')
                        ->items([
                            NavigationItem::make('Statistik Kependudukan')
                                ->icon('fas-chart-column')
                                ->visible(fn (): bool => $auth->can('page_HalamanStatistik'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.statistik.resources.kependudukan.index'))
                                ->url(fn (): string => HalamanStatistik::getUrl()),
                        ]);

                    $informasi = NavigationGroup::make('Informasi')
                        ->label('Informasi Publik Lainnya')
                        ->icon('fas-info-circle')
                        ->items([
                            NavigationItem::make('Jadwal Kegiatan')
                                ->icon('fas-calendar-days')
                                ->visible(fn (): bool =>  $auth->can('view_jadwal::kegiatan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.jadwal-kegiatan.index'))
                                ->url(fn (): string => JadwalKegiatanResource::getUrl()),
                            NavigationItem::make('Berita')
                                ->icon('fas-newspaper')
                                ->visible(fn (): bool => $auth->can('page_HalamanBerita'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.berita.resources.berita.index'))
                                ->url(fn (): string => HalamanBerita::getUrl()),
                            NavigationItem::make(fn (): string => 'Arsip ' . $settings['sebutan_deskel'])
                                ->label('Arsip ' . $settings['sebutan_deskel'])
                                ->icon('fas-box-archive')
                                ->visible(fn (): bool => $auth->can('page_HalamanArsip'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.arsip.resources.keputusan.index'))
                                ->url(fn (): string => HalamanArsip::getUrl()),

                        ]);

                    $pengaturan = NavigationGroup::make('Pengaturan')
                        ->label('Pengaturan')
                        ->icon('fas-cogs')
                        ->items([
                            NavigationItem::make('Pengaturan Peran dan Pengguna')
                                ->icon('fas-user-gear')
                                ->visible(fn (): bool => $auth->can('view_shield::role'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pengaturan.resources.peran.index'))
                                ->url(fn (): string => ShieldRoleResource::getUrl()),
                            NavigationItem::make('Pengaturan Aplikasi')
                                ->icon('fas-gear')
                                ->visible(fn (): bool => $auth->can('page_PengaturanUmum'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.pengaturan-umum'))
                                ->url(fn (): string => PengaturanUmum::getUrl()),
                        ]);

                    $dataDasar = NavigationGroup::make('DataDasar')
                        ->label('Data Dasar Keluarga')
                        ->icon('fas-house-user')
                        ->collapsible()
                        ->items([
                            NavigationItem::make('Keluarga')
                                ->icon('fas-people-roof')
                                ->visible(fn (): bool => $auth->can('view_kartu::keluarga'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kependudukan.resources.keluarga.index'))
                                ->url(fn (): string => KartukeluargaResource::getUrl()),
                            NavigationItem::make('Penduduk')
                                ->icon('fas-people-group')
                                ->visible(fn (): bool => $auth->can('view_penduduk'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kependudukan.resources.penduduk.index'))
                                ->url(fn (): string => PendudukResource::getUrl()),
                            NavigationItem::make('Dinamika Kependudukan')
                                ->icon('fas-elevator')
                                ->visible(fn (): bool => $auth->can('view_dinamika'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.dinamika.index'))
                                ->url(fn (): string => DinamikaResource::getUrl()),
                            NavigationItem::make('Kesehatan Anak')
                                ->icon('fas-baby')
                                ->visible(fn (): bool => $auth->can('page_HalamanKesehatan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.kesehatan.resources.anak.index'))
                                ->url(fn (): string => HalamanKesehatan::getUrl()),
                            NavigationItem::make('Program Bantuan')
                                ->icon('fas-hand-holding-hand')
                                ->visible(fn (): bool => $auth->can('view_bantuan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.bantuan.index'))
                                ->url(fn (): string => BantuanResource::getUrl()),
                            NavigationItem::make('Data Tambahan')
                                ->icon('fas-folder-plus')
                                ->visible(fn (): bool => $auth->can('view_tambahan'))
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.tambahan.index'))
                                ->url(fn (): string => TambahanResource::getUrl()),
                        ]);

                    return $builder->groups([
                        $beranda,
                        $dataPokok,
                        $dataDasar,
                        $potensi,
                        $statistik,
                        $informasi,
                        $pengaturan
                    ]);
                }
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
            ]);
    }
}
