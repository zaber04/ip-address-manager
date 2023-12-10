<?php

declare(strict_types = 1);

use App\Http\Middleware\RateLimitMiddleware;


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



// How will I design the URLs? maybe thse?
// $router->get('/api/v1/gateway', 'GatewayController@index');
// $router->get('/authenticate', 'AuthenticationController@authenticate');
// $router->get('/retrieve-data', 'IpHandlerController@retrieveData');

// TODO: use api versioning --> v1, v2, ...
// TODO: add routes, load balancing, rate limiting, ...

$router->group(['middleware' => RateLimitMiddleware::class . ':6,1,customPrefix'], function () use ($router) {
    // Your protected routes here
    $router->get('/', ['uses' => GatewayController::class . '@index']);
});

