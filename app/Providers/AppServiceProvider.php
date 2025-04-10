<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Paginator::useBootstrap();


        Cashier::ignoreMigrations();

        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

    }
}
