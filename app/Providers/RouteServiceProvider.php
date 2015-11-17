<?php

namespace Laraveles\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Laraveles\Http\Controllers';

    /**
     * Routing patterns.
     *
     * @var array
     */
    protected $patterns = [
        'authProvider' => 'github|google',
        'token' => '[a-zA-Z0-9]{30}'
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Registering all the patterns available
        $router->patterns($this->patterns);

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        // Mapping every route file found in the Routes folder and including it
        $router->group(['namespace' => $this->namespace], function ($router) {
            foreach (\File::allFiles(app_path('Http/Routes')) as $partial) {
                require $partial;
            }
        });
    }
}
