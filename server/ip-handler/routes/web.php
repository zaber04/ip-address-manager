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
$router->get('/', 'IpHandlerController@welcome');


// @TODO: Put these apis behind auth
$router->group(['prefix' => 'api', 'middleware' => ['auth', 'refresh.token']], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'ip-handler'], function () use ($router) {
            $router->group(['prefix' => 'ip-addresses'], function () use ($router) {
                // updated ip and updates audit-trail
                $router->patch('/update/{id}', 'IpHandlerController@update');

                // stores ip and updates audit-trail
                $router->post('/store', 'IpHandlerController@store');

                $router->get('/', 'IpHandlerController@index');
                $router->get('/{id}', 'IpHandlerController@show');
                // no delete or archive
            });

            $router->group(['prefix' => 'audit-trails'], function () use ($router) {
                // this endpoint should have Role Based Access Control Policy
                $router->get('/', 'AuditTrailController@index');

                // this end point returns the changes current user did in this session
                $router->get('/user/{id}', 'AuditTrailController@showByUserId');

                // get a specific audit trail entry
                $router->get('/trail/{id}', 'AuditTrailController@showByAuditId');

                // no update, delete or archive
            });
        });
    });
});
