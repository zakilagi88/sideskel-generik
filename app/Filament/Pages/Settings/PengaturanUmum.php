<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Pages\Dashboard;
use App\Services\FileService;
use App\Settings\GeneralSettings;
use App\Filament\Pages\SettingsPage;
use App\Settings\WebSettings;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use Riodwanto\FilamentAceEditor\AceEditor;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

use function Filament\Support\is_app_url;

class PengaturanUmum extends SettingsPage
{
    use HasPageShield;

    protected static string $settings = GeneralSettings::class;

    protected static string $webSettings = WebSettings::class;

    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'fas-gear';
    protected static ?string $slug = 'pengaturan-umum';
    public ?array $routes = [];

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    protected ?array $settingsData = [];

    public string $themePath = '';

    public string $twConfigPath = '';

    protected function initializeExtraData(): void
    {
        $this->themePath = resource_path('css/filament/admin/theme.css');
        $this->twConfigPath = resource_path('css/filament/admin/tailwind.config.js');
        $this->routes = $this->registerRouteOptions();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        $fileService = new FileService;

        $themeEditor = $fileService->readfile($this->themePath);
        $configEditor = $fileService->readfile($this->twConfigPath);

        return array_merge($data, [
            'theme-editor' => $themeEditor,
            'tw-config-editor' => $configEditor,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Pengaturan')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Umum')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('brand_name')
                                            ->label(fn () => __('Nama Situs'))
                                            ->hint(fn () => __('Judul situs yang akan ditampilkan di bagian atas halaman'))
                                            ->hintColor('primary')
                                            ->required(),
                                        Forms\Components\Select::make('site_active')
                                            ->label(fn () => __('page.general_settings.fields.site_active'))
                                            ->options([
                                                0 => "Tidak Aktif",
                                                1 => "Aktif",
                                            ])
                                            ->disabled()
                                            ->native(false)
                                            ->hintColor('primary')
                                            ->required(),
                                    ]),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Grid::make()->schema([
                                            Group::make([
                                                Forms\Components\TextInput::make('sebutan_deskel')->label('Sebutan Desa/Kelurahan'),
                                                Forms\Components\TextInput::make('sebutan_prov')->label('Sebutan Provinsi'),
                                                Forms\Components\TextInput::make('sebutan_kabkota')->label('Sebutan Kabupaten/Kota'),
                                                Forms\Components\TextInput::make('sebutan_kec')->label('Sebutan Kecamatan'),
                                            ]),
                                            Group::make([
                                                Forms\Components\TextInput::make('sebutan_kepala')->label('Sebutan Kepala Desa/Kelurahan'),
                                                Forms\Components\TextInput::make('singkatan_prov')->label('Singkatan Provinsi'),
                                                Forms\Components\TextInput::make('singkatan_kabkota')->label('Singkatan Kabupaten/Kota'),
                                                Forms\Components\TextInput::make('singkatan_kec')->label('Singkatan Kecamatan'),
                                            ]),
                                        ])->inlineLabel()->columnSpanFull(),

                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Web')
                            ->schema([

                                Repeater::make('web_settings')
                                    ->hiddenLabel()
                                    ->extraAttributes([
                                        'class' => 'fi-repeater-no-container',
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->minItems(1)
                                    ->defaultItems(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('web_title')
                                            ->inlineLabel()
                                            ->label('Judul Website Publik'),
                                        Forms\Components\FileUpload::make('web_gambar')
                                            ->label('Gambar Untuk Beranda Website Publik')
                                            ->inlineLabel()
                                            ->image()
                                            ->imageEditor()
                                            ->preserveFilenames()
                                            ->directory('web')
                                            ->visibility('public')
                                            ->moveFiles()
                                            ->required(),
                                        Forms\Components\FileUpload::make('kepala_gambar')
                                            ->label('Gambar Untuk Kepala Website Publik')
                                            ->inlineLabel()
                                            ->preserveFilenames()
                                            ->image()
                                            ->directory('web')
                                            ->visibility('public')
                                            ->moveFiles()
                                            ->required(),
                                        Forms\Components\TextInput::make('kepala_judul')->label('Judul Sambutan Kepala Website Publik')->inlineLabel(),
                                        Forms\Components\TextInput::make('kepala_nama')->label('Nama Kepala Website Publik')->helperText('Isikan Dengan Gelar dan Nama Kepala Desa/Kelurahan')->inlineLabel(),
                                        Forms\Components\Textarea::make('kepala_deskripsi')->label('Deskripsi Kepala Website Publik')->inlineLabel(),
                                        Forms\Components\TextInput::make('berita_judul')->label('Judul Berita Website Publik')->inlineLabel(),
                                        Forms\Components\Textarea::make('berita_deskripsi')->label('Deskripsi Berita Website Publik')->inlineLabel(),
                                        Forms\Components\Textarea::make('footer_deskripsi')->label('Deskripsi Footer Website Publik')->inlineLabel(),
                                        TableRepeater::make('menus')
                                            ->label(__('Menu Navigasi Web'))
                                            ->columnSpanFull()
                                            ->headers(
                                                [
                                                    Header::make('menu1')->label('Nama Menu'),
                                                    Header::make('menu2')->label('Tipe Halaman'),
                                                    Header::make('menu3')->label('Halaman Parent'),
                                                    Header::make('menu4')->label('Submenu Halaman'),
                                                ]
                                            )
                                            ->schema(
                                                [
                                                    Forms\Components\TextInput::make('name')
                                                        ->label(__('Nama Menu'))
                                                        ->required(),
                                                    Forms\Components\Select::make('link_type')
                                                        ->label(__('Tipe Link'))
                                                        ->options([
                                                            'static' => 'Statis',
                                                            'dynamic' => 'Dinamis',
                                                        ])
                                                        ->default('static')
                                                        ->live(onBlur: true)
                                                        ->required(),
                                                    Forms\Components\Select::make('link_name')
                                                        ->label(__('Link Parent'))
                                                        ->options(fn (Get $get): array => ($this->getRouteOptions($get('link_type'))))
                                                        ->live(onBlur: true),
                                                    TableRepeater::make('submenu')
                                                        ->label(__('Submenu'))
                                                        ->columnSpanFull()
                                                        ->emptyLabel(false)
                                                        ->headers([
                                                            Header::make('submenu1')->label('name'),
                                                            Header::make('submenu2')->label('name'),
                                                            Header::make('submenu3')->label('name'),
                                                            Header::make('submenu4')->label('name'),
                                                        ])
                                                        ->renderHeader(false)
                                                        ->schema([
                                                            Forms\Components\TextInput::make('sub_name')
                                                                ->label(__('Nama Menu')),
                                                            Forms\Components\Select::make('sub_link_type')
                                                                ->label(__('Tipe Link'))
                                                                ->options([
                                                                    'static' => 'Statis',
                                                                    'dynamic' => 'Dinamis',
                                                                ])
                                                                ->live(onBlur: true),
                                                            Forms\Components\Select::make('sub_link_name')
                                                                ->label(__('Link Parent'))
                                                                ->options(fn (Get $get): array => $this->getRouteOptions($get('sub_link_type')))
                                                                ->live(onBlur: true),
                                                            Forms\Components\Select::make('sub_link_options')
                                                                ->label(__('Link Options'))
                                                                ->options(fn (Get $get): array => $this->getSelectedDynamicRouteOptions($get('sub_link_name')))
                                                                ->live(onBlur: true),
                                                        ])
                                                ]
                                            ),
                                    ]),
                            ])->columnSpanFull(),

                    ])->columnSpanFull(),
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Color Palette')
                            ->schema([
                                Forms\Components\ColorPicker::make('site_theme.primary')
                                    ->label(fn () => __('page.general_settings.fields.primary'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.secondary')
                                    ->label(fn () => __('page.general_settings.fields.secondary'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.gray')
                                    ->label(fn () => __('page.general_settings.fields.gray'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.success')
                                    ->label(fn () => __('page.general_settings.fields.success'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.danger')
                                    ->label(fn () => __('page.general_settings.fields.danger'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.info')
                                    ->label(fn () => __('page.general_settings.fields.info'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.warning')
                                    ->label(fn () => __('page.general_settings.fields.warning'))->rgb(),
                            ])
                            ->columns(3),
                        Forms\Components\Tabs\Tab::make('Code Editor')
                            ->schema([
                                Forms\Components\Grid::make()->schema([
                                    AceEditor::make('theme-editor')
                                        ->label('theme.css')
                                        ->mode('css')
                                        ->height('24rem'),
                                    AceEditor::make('tw-config-editor')
                                        ->label('tailwind.config.js')
                                        ->height('24rem')
                                ])
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->mutateFormDataBeforeSave($this->form->getState());

            $generalSettingsData = collect($data)->except('web_settings')->toArray();
            $generalSettings = app(static::getSettings());
            $generalSettings->fill($generalSettingsData);
            $generalSettings->save();

            $webData = $data['web_settings'][0];
            foreach ($webData['menus'] as &$menu) {
                $menu['submenu'] = array_filter($menu['submenu'], function ($submenu) {
                    return !empty($submenu['sub_name']) && !empty($submenu['sub_link_name']);
                });
            }
            $webSettings = app(static::getWebSettings());
            $webSettings->fill($webData);
            $webSettings->save();

            $fileService = new FileService;
            $fileService->writeFile($this->themePath, $data['theme-editor']);
            $fileService->writeFile($this->twConfigPath, $data['tw-config-editor']);

            Notification::make()
                ->title('Settings updated.')
                ->success()
                ->send();

            $this->redirect(Dashboard::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(Dashboard::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    protected function registerRouteOptions(): array
    {

        $routes = collect(Route::getRoutes())
            ->map(function (RoutingRoute $route) {
                $data = $route->getAction('linkKeyRoute');

                if ($data == null) {
                    return null;
                }

                $hasRouteBinding = str_contains($data['routeName'], 'show');

                return [
                    'routeName' => $data['routeName'],
                    'label' => $data['label'],
                    'model' => $hasRouteBinding ? $data['model'] : null,
                    'modelLabel' => $hasRouteBinding ? $data['modelLabel'] : null,
                    'parameterOptions' => $hasRouteBinding ? $this->getRouteParameterOptions($data['model'], $data['modelLabel']) : [],
                ];
            })
            ->filter();

        return $routes->toArray();
    }

    protected function getRouteOptions($linkType): array
    {
        return collect($this->routes)
            ->when($linkType === 'static', function ($collection) {
                return $collection->filter(fn ($route) => $route['parameterOptions'] == null && !str_contains($route['routeName'], 'show'));
            })
            ->when($linkType === 'dynamic', function ($collection) {
                return $collection->filter(fn ($route) => $route['parameterOptions'] != null && str_contains($route['routeName'], 'show'));
            })
            ->mapWithKeys(function ($route) {
                return [$route['routeName'] => $route['label']];
            })
            ->toArray();
    }


    protected function getStaticRouteOptions(): array
    {
        return collect($this->routes)
            ->filter(fn ($route) => $route['parameterOptions'] != null)
            ->mapWithKeys(function ($route) {
                return [$route['routeName'] => $route['label']];
            })
            ->toArray();
    }

    protected function getDynamicRouteOptions(): array
    {
        return collect($this->routes)
            ->filter(fn ($route) => $route['parameterOptions'] == null)
            ->mapWithKeys(function ($route) {
                return [$route['routeName'] => $route['label']];
            })
            ->toArray();
    }

    protected function getSelectedDynamicRouteOptions($routeName): array
    {
        return collect($this->routes)
            ->filter(fn ($route) => $route['routeName'] == $routeName)
            ->mapWithKeys(function ($route) {
                return $route['parameterOptions'];
            })
            ->toArray();
    }

    public static function resolveRecordRouteBinding(int | string $key): ?Model
    {
        return app(static::getModel())
            ->resolveRouteBindingQuery(static::getEloquentQuery(), $key, static::getRecordRouteKeyName())
            ->first();
    }

    public function getRouteParameterOptions(?string $model, ?string $modelLabel): array
    {

        if ($model) {
            $class = new ReflectionClass($model);

            return $model::query()
                ->when(method_exists($class, 'scopeLinkKeyOptions'), function (Builder $query) {
                    return $query->linkPickerOptions();
                })
                ->get()
                ->mapWithKeys(function (Model $model) use ($modelLabel) {
                    $label = $this->getRouteParameterLabel($model, $modelLabel);

                    return [$model->getRouteKey() => $label];
                })
                ->toArray();
        }
    }

    public static function getRouteParameterLabel(Model $model, ?string $modelLabel): string
    {
        $label = null;

        if (method_exists($model, 'getLinkLabel')) {
            $label = $model->getLinkLabel();
        } elseif (property_exists($model, 'linkKey')) {
            $label = $model->{$model->linkKey};
        } else {
            $label = $modelLabel;
        }

        if (is_null($label)) {
            $modelClass = $model::class;
            throw new \Exception("Could not automatically determine a label for the model [{$modelClass}]. Please implement the HasLinkPickerOptions interface on your model or provide a custom parameterOptions array on the route itself.");
        }

        return $label;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("page.general_settings.navigationLabel");
    }

    public function getTitle(): string|Htmlable
    {
        return __("Pengaturan Aplikasi");
    }

    public function getHeading(): string|Htmlable
    {
        return __("Pengaturan Aplikasi");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __("page.general_settings.subheading");
    }
}
