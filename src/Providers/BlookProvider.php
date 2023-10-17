<?php

namespace Raitone\Blook\Providers;

use Illuminate\Support\ServiceProvider;

class BlookProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'blook');
        $this->publishes([__DIR__.'/../config/blook.php' => config_path('blook.php'),]);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/blook.php', 'blook'
        );
    }

}