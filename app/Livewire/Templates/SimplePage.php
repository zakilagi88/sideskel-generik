<?php

namespace App\Livewire\Templates;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Filament\Resources\Pages\Concerns\HasRelationManagers;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SimplePage extends Component implements HasInfolists, HasForms
{
    use HasRelationManagers;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Model | int | string | null $record;

    protected static $routes;

    protected static string $resource;

    protected static string $heading;

    protected static string $parameter = '';


    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    #[Computed()]
    public function extraResources(): array
    {
        return [];
    }

    public function getRecord(): Model
    {
        return $this->record;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist;
    }

    protected function resolveRecord(int | string $key, $extra = null): Model
    {
        if (is_null($extra)) {
            return static::getResource()::resolveRecordRouteBinding($key);
        } else {
            return $this->getExtraResourceByKey($extra)::resolveRecordRouteBinding($key);
        }
    }

    public static function getResource(): string
    {
        return static::$resource;
    }

    public function getExtraResourceByKey($key)
    {
        return $this->extraResources()[$key];
    }

    protected function getPageHeading(): string
    {
        return $this->record->title ?? static::$heading;
    }

    protected function getPageSlug(): string
    {
        return $this->record->slug ?? $this->record->{$this->parameter};
    }

    public function render()
    {
        return view('livewire.templates.simple-page');
    }

    public static function getPageBreadcrumb(): array
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

    public function getShiftPageBreadcrumb(): array
    {
        return  [
            'routeName' => static::getRouteName() . '.show',
            'routeParameter' => $this->getPageSlug(),
        ];
    }


    protected static function getRouteName(): string
    {
        return 'index.' . (static::$parameter ?: static::getResource()::getSlug());
    }

    protected static function getRouteParameter($routeName): ?string
    {
        $route = static::$routes->getByName($routeName);

        return $route ? $route->uri() : null;
    }

    public static function getRouteParameterLabel(Model $model, ?string $modelLabel): string
    {
        $label = null;

        if (method_exists($model, 'getLinkKey')) {
            $label = $model->getLinkKey();
        } elseif (property_exists($model, 'linkKey')) {
            $label = $model->{$model->linkKey};
        } else {
            $label = $modelLabel;
        }

        if (is_null($label)) {
            $modelClass = $model::class;
            throw new \Exception("Could not automatically determine a label for the model [{$modelClass}]. Please implement the HasLinkPickerOptions interface on your model or provide a custom parameterOptions array on the route itself.");
        }

        return $model->$modelLabel;
    }
}
