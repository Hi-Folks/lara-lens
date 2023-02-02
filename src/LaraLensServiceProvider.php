<?php

namespace HiFolks\LaraLens;

use HiFolks\LaraLens\Console\LaraLensCommand;
use Illuminate\Support\ServiceProvider;
use HiFolks\LaraLens\Lens\LaraLens;
use Illuminate\Support\Facades\Route;

class LaraLensServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lara-lens');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lara-lens');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        //$this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                __DIR__ . '/../config/config.php' => config_path('lara-lens.php'),
                ],
                'config'
            );

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lara-lens'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/lara-lens'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lara-lens'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands(
                [
                LaraLensCommand::class,
                ]
            );
        }
    }


    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * @return (\Illuminate\Config\Repository|mixed)[]
     *
     * @psalm-return array{prefix: \Illuminate\Config\Repository|mixed, middleware: \Illuminate\Config\Repository|mixed}
     */
    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('lara-lens.prefix', 'laralens-diagnostic'),
            'middleware' => config('lara-lens.middleware'),
        ];
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'lara-lens');

        // Register the main class to use with the facade
        $this->app->singleton(
            'lara-lens',
            function () {
                return new LaraLens();
            }
        );
    }
}
