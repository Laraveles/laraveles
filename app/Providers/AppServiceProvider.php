<?php

namespace Laraveles\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Bus\Dispatcher;

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
            return get_class($command).'Handler@handle';
        });
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
