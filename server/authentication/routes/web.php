<?php

declare(strict_types=1);

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

$router->group(['prefix' => 'api/auth'], function () use ($router) {
    // register a user and get token
    $router->post('/register', 'AuthController@register');

    // log in user and get token
    $router->post('/login', 'AuthController@login');

    // log out user
    $router->post('/logout', 'AuthController@logout');

    // refresh jwt
    $router->post('/refresh', 'AuthController@refresh');

    // get user details
    $router->post('/user-profile', 'AuthController@me');
});
