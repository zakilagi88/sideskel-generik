<?php

namespace App\Providers;

use App\Facades\Deskel;
use App\Models\DesaKelurahanProfile;
use Illuminate\Support\ServiceProvider;

class DeskelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('deskel', function () {
            return DesaKelurahanProfile::first() ?? new DesaKelurahanProfile();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
