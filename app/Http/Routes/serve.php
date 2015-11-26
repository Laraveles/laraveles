<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'serve', 'namespace' => 'Serve'], function () use ($router) {

    // Markdown Preview
    //
    post('/markdown/transform', ['as' => 'markdown.transform', 'uses' => 'MarkdownController@transform']);

});