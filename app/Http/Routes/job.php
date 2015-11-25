<?php

/*
|--------------------------------------------------------------------------
| Job Routes
|--------------------------------------------------------------------------
*/

// Job Tasks
//
get('job/approve/{id}', ['as' => 'job.approve', 'uses' => 'JobController@approve']);

// Job Resource
//
resource('job', 'JobController');