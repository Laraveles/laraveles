<?php

/*
|--------------------------------------------------------------------------
| Basic Routes
|--------------------------------------------------------------------------
*/

get('/', ['as' => 'home', function () {
    return 'hola!';
}]);

get('/testing', function () {

    $data = [
        'username' => 'Israel Ortuño',
        'email' => 'ai.ortuno@gmail.com'
    ];

    dd(array_only($data, ['username', 'email', 'password']));
});