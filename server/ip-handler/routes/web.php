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

// @TODO: Put these apis behind auth
$router->group(['prefix' => '', 'middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'ip-addresses'], function () use ($router) {
        $router->get('/', 'IpHandlerController@index');

        // stores ip and updates audit-trail
        $router->post('/', 'IpHandlerController@store');

        $router->get('/{id}', 'IpHandlerController@show');

        // updated ip and updates audit-trail
        $router->put('/{id}', 'IpHandlerController@update');

        // no delete or archive
    });

    $router->group(['prefix' => 'audit-trails'], function () use ($router) {
        // this endpoint should have Role Based Access Control Policy
        $router->get('/', 'AuditTrailController@index');

        // this end point returns the changes current user did in this session
        $router->get('/{id}', 'AuditTrailController@show');

        // no update, delete or archive
    });
});
