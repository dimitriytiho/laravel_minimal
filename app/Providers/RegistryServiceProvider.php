<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Registry;

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
            return new Registry();
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
