<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'auth', 'namespace' => 'Auth'], function () use ($router) {

    // Basic login
    //
    get('login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@index'
    ]);
    post('login', [
        'as' => 'auth.authenticate',
        'uses' => 'AuthController@authenticate'
    ]);

    // User activation
    get('activate/{token}', [
        'as' => 'auth.activate',
        'uses' => 'ActivateController@activate'
    ]);

    // Confirmation email
    //
    get('activate/request', [
        'as' => 'auth.activate.request',
        'uses' => 'ActivateController@requestConfirmation'
    ]);
    post('activate/resend', [
        'as' => 'auth.activate.resend',
        'uses' => 'ActivateController@resendConfirmation'
    ]);

    // User registration
    //
    resource('register', 'RegisterController', ['only' => ['index', 'store']]);

    // Social authentication
    //
    $router->group(['prefix' => 'social'], function () {
        // Social authentication redirect
        get('{authProvider}', [
            'as' => 'auth.social',
            'uses' => 'SocialAuthController@redirect'
        ]);
        // Callback from OAuth provider
        get('authorize/{authProvider}', 'SocialAuthController@callback');
    });

    // Logout
    //
    get('logout', [
        'as'   => 'auth.logout',
        'uses' => 'AuthController@logout'
    ]);

});