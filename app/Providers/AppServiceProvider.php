<?php

namespace App\Providers;

use App\Services\RawMaterialService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registers raw material as singleton.
        $this->app->singleton(RawMaterialService::class, function ($app) {
            return new RawMaterialService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
