<?php

namespace edgewizz\tnf;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class TnfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Edgewizz\Tnf\Controllers\TnfController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // dd($this);
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__ . '/components', 'tnf');
        Blade::component('tnf::tnf.open', 'tnf.open');
        Blade::component('tnf::tnf.index', 'tnf.index');
        Blade::component('tnf::tnf.edit', 'tnf.edit');
    }
}
