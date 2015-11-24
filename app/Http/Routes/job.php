<?php

/*
|--------------------------------------------------------------------------
| Job Routes
|--------------------------------------------------------------------------
*/

$router->group(['namespace' => 'Job'], function () use ($router) {

    // Job Tasks
    //
    get('job/approve/{id}', ['as' => 'job.approve', 'uses' => 'JobController@approve']);

    // Job Resource
    //
    resource('job', 'JobController');

});