<?php

namespace App\Livewire\Templates;

use App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource;
use App\Models\Desa\Keputusan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class TablePage extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static $routes;

    protected static string $resource;

    protected static string $heading;

    protected static bool $isClusterParent = false;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->record($this->record);
    }

    public function table(Table $table): Table
    {
        $resource = static::getResource();
        return $resource::table($table)
            ->query($resource::getModel()::query())
            ->actions([])
            ->bulkActions([]);
    }

    public function render(): View
    {
        return view('livewire.templates.table-page');
    }

    public static function getResource(): string
    {
        return static::$resource;
    }

    public static function getPageHeading(): string
    {
        return static::$heading;
    }

    protected function getPageSlug(): string
    {
        return $this->record->slug ?? null;
    }

    public function getPageBreadcrumb(): array
    {
        if (!static::$routes) {
            static::$routes = Route::getRoutes();
        }

        $routeName = static::getRouteName();
        $routeParameter = static::getRouteParameter($routeName);

        return [
            'routeName' => $routeName,
            'routeParameter' => $routeParameter,

        ];
    }

    protected static function getRouteName(): string
    {
        if (static::$isClusterParent) {
            return 'index.' . static::getResource()::getCluster()::getSlug();
        }
        return 'index.' . static::getResource()::getSlug();
    }

    protected static function getRouteParameter($routeName): ?string
    {
        $route = static::$routes->getByName($routeName);

        return $route ? $route->uri() : null;
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
}
