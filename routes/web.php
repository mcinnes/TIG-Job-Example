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
    // return $router->app->version();
    return view('upload');

});

$router->get('upload', [
    'as' => 'profile', 'uses' => 'UploadController@view'
]);

$router->post('upload/saved', [
    'as' => 'process', 'uses' => 'UploadController@store'
]);


$router->get('/view/{id}', [
    'as' => 'profile', 'uses' => 'ViewController@view'
]);
