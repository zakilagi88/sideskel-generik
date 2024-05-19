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
            $textEntry->extraAttributes(['class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100']);
        });

        FilamentColor::register(fn (GeneralSettings $settings) => $settings->site_theme);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
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
            }
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn (): string =>
            new HtmlString('
                <script>
                    document.addEventListener("scroll-to-top", function () {
                        window.scrollTo({ top: 0, behavior: "smooth" });
                    });
                </script>
            ')
        );
    }
}
