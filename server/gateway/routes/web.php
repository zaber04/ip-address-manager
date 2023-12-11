<?php

declare(strict_types = 1);

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



// How will I design the URLs? maybe these?
// $router->get('/api/v1/gateway', 'GatewayController@index');
// $router->get('/authenticate', 'AuthenticationController@authenticate');
// $router->get('/retrieve-data', 'IpHandlerController@retrieveData');

// TODO: use api versioning --> v1, v2, ...
// TODO: add routes, load balancing, rate limiting, ...

$router->get('/', ['uses' => GatewayController::class . '@welcome']);

$router->group(['prefix' => 'api'], function () use ($router) {
    // $router->group(['prefix' => 'v1'], function () use ($router) {
    //     $router->group(['prefix' => 'auth'], function () use ($router) {
    //     });

    //     $router->group(['prefix' => 'ip-handler'], function () use ($router) {
    //     });
    // });
});




