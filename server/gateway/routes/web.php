<?php

use App\Http\Controllers\GatewayController;
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

$router->get('/', function () use ($router) {
    $appVersion = $router->app->version();
    $serviceName = "API Gateway";
    $message = "Welcome to '" . $serviceName . "' microservice.<br>" . $appVersion;

    return $message;
});


// How will I design the URLs? maybe thse?
// $router->get('/api/v1/gateway', 'GatewayController@index');
// $router->get('/authenticate', 'AuthenticationController@authenticate');
// $router->get('/retrieve-data', 'IpHandlerController@retrieveData');

// TODO: use api versioning --> v1, v2, ...
// TODO: add routes, load balancing, rate limiting, ...
