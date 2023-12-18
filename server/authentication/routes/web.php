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

// Just a welcome route without much restriction
$router->get('/', 'AuthController@welcome');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'auth'], function () use ($router) {
            // register a user and get token
            $router->post('/register[/{trailingSlash}]', 'AuthController@register');

            // log in user and get token
            $router->post('/login[/{trailingSlash}]', 'AuthController@login');

            // log out user
            $router->post('/logout[/{trailingSlash}]', 'AuthController@logout');

            // refresh jwt
            $router->post('/refresh[/{trailingSlash}]', 'AuthController@refresh');

            // get user details
            $router->post('/user-profile[/{trailingSlash}]', 'AuthController@me');
        });
    });
});
