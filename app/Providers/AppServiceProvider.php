<?php

namespace Laraveles\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Bus\Dispatcher;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registering how command bus should map to their handlers.
        $this->app->make(Dispatcher::class)->mapUsing(function ($command) {
            return get_class($command) . 'Handler@handle';
        });

        $this->registerDevProviders();
    }

    /**
     * Will register the providers for the local environment.
     */
    protected function registerDevProviders()
    {
        if ($this->app->environment('local')) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
