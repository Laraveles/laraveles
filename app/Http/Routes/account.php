<?php

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
*/

$router->group([
    'prefix'     => 'account',
    'namespace'  => 'Account',
    'middleware' => 'auth'
], function () use ($router) {

    // Base Account
    //
    get('', 'ProfileController@index');

    // Profile Routes
    //
    resource('profile', 'ProfileController', ['only' => ['index', 'store']]);

    // Location Routes
    //
    resource('location', 'LocationController', ['only' => ['index', 'store']]);

    // Recruiter Routes
    //
    resource('recruiter', 'RecruiterController', ['only' => ['index', 'store']]);

});
