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

// TODO: use api versioning --> v1, v2, ...
// TODO: add routes, load balancing, rate limiting, ...


$router->group(['prefix' => 'api'], function () use ($router) {
    // will contain versioning
    $router->group(['prefix' => 'v1'], function () use ($router) {
        // will contain authentication microservice routes
        $router->group(['prefix' => 'auth'], function () use ($router) {
        });

        // will contain ip-handler microservice routes
        // must be authenticated user to access these
        $router->group(['prefix' => 'ip-handler'], function () use ($router) {
        });
    });
});


// Just a welcome route without much restriction
$router->get('/', 'GatewayController@welcome');

// Dynamic Loading with strict host
/*
$router->group(['prefix' => 'api'], function () use ($router) {
    // will contain versioning
    $router->group(['prefix' => 'v1'], function () use ($router) {
        // will contain authentication microservice routes
        $router->group(['prefix' => 'auth'], function () use ($router) {
            $router->getRoutes()['namespace'] = 'Authentication\Controllers';
            $router->getRoutes()['as'] = 'auth.';
            $router->getRoutes()['prefix'] = 'auth';
            $router->group(['namespace' => 'Authentication\Controllers'], function () use ($router) {
                // @TODO: test this
                require base_path('server/authentication/routes/web.php');
            });
        });

        // will contain ip-handler microservice routes
        // must be authenticated user to access these
        $router->group(['prefix' => 'ip-handler'], function () use ($router) {
            $router->getRoutes()['namespace'] = 'IpHandler\Controllers';
            $router->getRoutes()['as'] = 'ip-handler.';
            $router->getRoutes()['prefix'] = 'ip-handler';
            $router->group(['namespace' => 'IpHandler\Controllers'], function () use ($router) {
                // @TODO: test this
                require base_path('server/ip-handler/routes/web.php');
            });
        });
    });
});
*/

// Dynamic Loading with flexible host
$router->group(['prefix' => 'api'], function () use ($router) {
    // will contain versioning
    $router->group(['prefix' => 'v1'], function () use ($router) {
        // Authentication microservice routes
        $router->group(['prefix' => 'auth'], function () use ($router) {
            // Forward requests to the Authentication microservice
            // neither "$router->any" nor "$router->fallback" is available in Lumen
            $router->post('/{route:.*}', 'GatewayController@forwardToAuthService');
        });

        // IP Handler microservice routes
        $router->group(['prefix' => 'ip-handler'], function () use ($router) {
            // Forward requests to the IP Handler microservice
            $router->get('/{route:.*}', 'GatewayController@forwardToIpHandlerService');
            $router->post('/{route:.*}', 'GatewayController@forwardToIpHandlerService');
            $router->put('/{route:.*}', 'GatewayController@forwardToIpHandlerService');
        });
    });
});
