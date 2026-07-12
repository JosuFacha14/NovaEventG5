<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//registrar service provider
use App\Services\PersonasService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PersonasService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}