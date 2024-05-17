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

    protected static $hasExtraResources = false;

    public string $currentResource = '';

    protected static $routes;

    protected static string $resource;

    protected static string $heading;

    protected static bool $isCluster = false; // apakah halaman ini adalah cluster

    protected static string $parameter = ''; // parameter untuk menentukan slug

    protected static string $parentSlug = ''; // slug parent cluster jika bukan satu cluster


    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    #[Computed]
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
        $this->currentResource = $this->extraResources()[$key];
        return $this->currentResource;
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

    public function getPageBreadcrumb(): array
    {
        if (!static::$routes) {
            static::$routes = Route::getRoutes();
        }

        $routeName = $this->getRouteName();

        // dd($routeName, static::getRouteParameter($routeName));

        $routeParameter = static::getRouteParameter($routeName);

        return [
            'routeName' => $routeName,
            'routeParameter' => $routeParameter,
            'routeLabel' => static::getRouteParameterLabel($this->record, null) ?? null,
        ];
    }

    public function getShiftPageBreadcrumb(): array
    {
        return  [
            'routeName' => $this->getRouteName() . '.show',
            'routeParameter' => $this->getPageSlug(),
            'routeLabel' => static::getRouteParameterLabel($this->record, null) ?? null,
        ];
    }

    public function getSlug()
    {
        return;
    }

    protected function getRouteName(): string
    {
        $req = app(Request::class);

        $slug = $this->getSlug() ?: static::$resource::getSlug();

        if ($req->routeIs('index.stat.show')) {
            return 'index.' . $slug;
        } else {
            if (static::$isCluster) {
                if (static::$parentSlug) {
                    return 'index.'  . static::$parentSlug . '.' . $this->currentResource::getSlug();
                } else {
                    return 'index.' . $this->currentResource::getCluster()::getSlug() . '.' . $this->currentResource::getSlug();
                }
            }
        }

        return 'index.' . $slug;
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
            throw new \Exception("Tidak bisa menemukan Key [{$modelClass}]. Silahkan tambahkan property \$linkKey atau method getLinkLabel() pada model.");
        }

        return $label;
    }
}
