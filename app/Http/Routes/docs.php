<?php

/*
|--------------------------------------------------------------------------
| Docs Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'docs'], function () use ($router) {

    // Docs section
    //
    get('/', ['as' => 'docs', 'uses' => 'DocsController@index']);
    get('{version}/{section?}', ['as' => 'docs.show', 'uses' => 'DocsController@show']);

});