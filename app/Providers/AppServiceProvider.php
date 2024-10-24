<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Nette\Utils\Html;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/app_data.php',
            'app_data'
        );

        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks();
        });

        TextEntry::configureUsing(function (TextEntry $textEntry): void {
            $textEntry->extraAttributes(['class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100'])->placeholder('Belum Diketahui');
        });

        FilamentColor::register(fn(GeneralSettings $settings) => $settings->site_theme);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        if (env('APP_ENV') !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }

        Gate::define('download-backup', function ($user) {
            return dd($user->hasRole('admin'));
        });

        Gate::define('delete-backup', function ($user) {
            return $user->hasRole('admin');
        });

        Schema::defaultStringLength(191);
        Route::macro(
            'linkKey',
            function (?string $label = null, ?string $model = null, ?string $modelLabel = null) {
                /** @var Route $route */
                $route = $this;

                $route->action['linkKeyRoute'] = [
                    'routeName' => $route->getName(),
                    'label' => $label,
                    'model' => $model,
                    'modelLabel' => $modelLabel,
                ];
                if (env('APP_ENV') !== 'local') {
                    $this->app['request']->server->set('HTTPS', true);
                }

                Schema::defaultStringLength(191);
            }
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn(): string => new HtmlString(
                Blade::render('
                    <!-- PWA -->
                    <meta name="theme-color" content="#6777ef" />
                    <link rel="apple-touch-icon" href="{{ asset(\'logo.png\') }}">
                    <link rel="manifest" href="{{ asset(\'manifest.json\') }}">
                    ')
            )
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn(): string => new HtmlString(
                Blade::render('
                    <script>
                        document.addEventListener("scroll-to-top", function () {
                            window.scrollTo({ top: 0, behavior: "smooth" });
                        });
                    </script>
                    <script src="{{ asset(\'/sw.js\') }}"></script>
                    <script>
                        if ("serviceWorker" in navigator) {
                            // Register a service worker hosted at the root of the
                            // site using the default scope.
                            navigator.serviceWorker.register("/sw.js").then(
                                (registration) => {
                                    console.log("Service worker registration succeeded:", registration);
                                },
                                (error) => {
                                    console.error(`Service worker registration failed: ${error}`);
                                }
                            );
                        } else {
                            console.error("Service workers are not supported.");
                        }
                    </script>
                ')
            )
        );
    }
}
