<?php

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'account', 'namespace' => 'Account'], function () use ($router) {

    // Recruiter Resource
    //
    get('recruiter', ['as' => 'account.recruiter', 'uses' => 'RecruiterController@index']);
    post('recruiter', ['as' => 'account.recruiter.store', 'uses' => 'RecruiterController@store']);

});