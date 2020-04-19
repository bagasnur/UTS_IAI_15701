<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('daftar', 'UserController@store');
$router->post('masuk', 'AuthController@authenticate');

$router->group(
    ['middleware' => 'jwt.auth'],
    function () use ($router) {
        //User
        $router->get('listuser', 'UserController@index');
        $router->get('user', 'UserController@getUserLogin');
        $router->put('user', 'UserController@update');
        //Social Media
        $router->post('sosmed', 'MediaSocialController@store');
        $router->get('listsosmed', 'MediaSocialController@shows');
        $router->get('sosmed/{id}', 'MediaSocialController@show');
        $router->put('sosmed/{id}', 'MediaSocialController@update');
        $router->delete('sosmed/{id}', 'MediaSocialController@destroy');
    }
);
