<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->pattern('provider', 'github');

$router->group(['prefix' => 'auth', 'namespace' => 'Auth'], function () use ($router) {

    // Basic login
    $router->get('login', ['as' => 'auth.login', 'uses' => 'AuthController@index']);
    $router->post('login', ['as' => 'auth.authenticate', 'uses' => 'AuthController@authenticate']);

    // User registration
    $router->resource('register', 'RegisterController', ['only' => ['index', 'store']]);

    // Social authentication specific routes
    $router->group(['prefix' => 'social'], function () {
        // Social authentication redirect
        get('{authProvider}', ['as' => 'auth.social', 'uses' => 'SocialAuthController@redirect']);
        // Callback from OAuth provider
        get('authorize/{authProvider}', 'SocialAuthController@callback');
    });

    // Logout
    get('logout', [
        'as'   => 'auth.logout',
        'uses' => 'AuthController@logout'
    ]);

});