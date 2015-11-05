<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->pattern('provider', 'github');

$router->group(['prefix' => 'auth', 'namespace' => 'Auth'], function () use ($router) {
    // Logout
    get('logout', [
        'as'   => 'auth.logout',
        'uses' => 'AuthController@logout'
    ]);

    // Social authentication specific routes
    $router->group(['prefix' => 'social'], function () {
        // Social authentication redirect
        get('{authProvider}', ['as' => 'auth.social', 'uses' => 'SocialAuthController@redirect']);
        // Callback from OAuth provider
        get('authorize/{authProvider}', 'SocialAuthController@callback');
    });

});