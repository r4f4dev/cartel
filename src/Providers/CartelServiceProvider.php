<?php

namespace r4f4dev\Cartel\Providers;

use Illuminate\Support\ServiceProvider;
use r4f4dev\Cartel\Cart;

class CartelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfiguration();
        $this->publishMigrations();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__.'/../../config/cart.php';
        $this->mergeConfigFrom($config, 'cart');
        $this->app->singleton('Cart', Cart::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Cart'];
    }

    public function publishConfiguration()
    {
        $path = realpath(__DIR__.'/../../config/cart.php');
        $this->publishes([$path => config_path('cart.php')], 'config');
    }

    public function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('/migrations'),
        ], 'migrations');
    }
}
