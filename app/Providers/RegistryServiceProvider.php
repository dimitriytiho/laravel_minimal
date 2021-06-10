<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RegistryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('registry', function () {
            return app()->make('App\Classes\Registry');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
