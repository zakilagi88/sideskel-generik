<?php

namespace App\Livewire\Templates;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;
use App\Models\KategoriBerita;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListPage extends Component
{

    use WithPagination;

    #[Url()]
    public $sort = 'desc';

    #[Url()]
    public $search = '';

    #[Url()]
    public $kategori = '';

    protected static $routes;

    protected static string $resource;

    protected static string $heading;


    protected static string $view = 'livewire.templates.list-page';

    public function render(): View
    {
        return view(static::$view, $this->getViewData());
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    #[On('search')]
    public function updateSearch($search)
    {
        $this->search = $search;
    }

    public function searching()
    {
        $this->dispatch('search', $this->search);
    }

    #[Computed()]
    public function records()
    {
        return static::getPageModel()::published()
            ->orderBy('published_at', $this->sort)
            ->whereAny([
                'title', 'body'
            ], 'LIKE', '%' . $this->search . '%')
            ->when(KategoriBerita::where('slug', $this->kategori)->first(), function ($query) {
                return $query->kategoriBerita($this->kategori);
            })
            ->paginate(5);
    }
    public function getViewData()
    {
        return [
            'heading' => static::getPageHeading(),
            'breadcrumb' => static::getPageBreadcrumb(),
        ];
    }

    public function limitwords($value, $limit = 100, $end = '...')
    {
        if (Str::length($value) <= $limit) {
            return $value;
        }

        return Str::limit($value, $limit, $end);
    }

    public static function getResource(): string
    {
        return static::$resource;
    }

    public static function getPageModel(): string
    {
        return static::getResource()::getModel();
    }

    public static function getPageHeading(): string
    {
        return static::$heading;
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

    protected static function getRouteName(): string
    {
        return 'index.' . static::getResource()::getSlug();
    }

    protected static function getRouteParameter($routeName): ?string
    {
        $route = static::$routes->getByName($routeName);

        return $route ? $route->uri() : null;
    }
}