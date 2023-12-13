<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'ip-addresses'], function () use ($router) {
    $router->get('/', 'IpHandlerController@index');
    $router->post('/', 'IpHandlerController@store');
    $router->get('/{id}', 'IpHandlerController@show');
    $router->put('/{id}', 'IpHandlerController@update');
    // $router->delete('/{id}', 'IpHandlerController@archive');
});
