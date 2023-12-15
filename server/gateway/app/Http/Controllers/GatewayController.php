<?php

declare(strict_types = 1);

namespace Gateway\Http\Controllers;

use Gateway\Traits\ApiResponseTrait;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Routing\Controller as BaseController;

// use Faker\Factory as FakerFactory;

class GatewayController extends BaseController
{
    use ApiResponseTrait;

    /**
     * Welcome route. Used for initial development
     *
     * @param Request $request
     * @return void
     */
    public function welcome(Request $request)
    {
        $appVersion  = app()->version();
        $serviceName = "API Gateway";
        $message     = "Welcome to '" . $serviceName . "' microservice. App version: " . $appVersion;

        $data = [
            'message' => $message,
            'success' => true,
            'status'  => JsonResponse::HTTP_OK,
            'host_ip' => $request->getClientIp()
        ];

        return $this->jsonResponseWith($data, JsonResponse::HTTP_OK);
    }

    /**
     * Forward the request to the Authentication microservice.
     *
     * @param Request $request
     * @return mixed
     */
    public function forwardToAuthService(Request $request)
    {
        // retrieve authentication service base url
        $authServiceBaseUrl = config('services.auth.base_url');

        // update request endpoint --> /api/auth/login becomes /login
        $authServiceEndpoint = $request->path();

        // Forward the request to the Authentication microservice
        // $request->method() --> get the HTTP method of the incoming request
        // $request->all()    --> get array of all input data
        $response = Http::baseUrl($authServiceBaseUrl)
            ->withHeaders($request->headers->all())
            ->{$request->method()}($authServiceEndpoint, $request->all());

        return $response;
    }

    /**
     * Forward the request to the IP Handler microservice.
     *
     * @param Request $request
     * @return mixed
     */
    public function forwardToIpHandlerService(Request $request)
    {
        $ipHandlerBaseUrl = config('services.ip_handler.base_url');
        $ipHandlerEndpoint = $request->path();

        // Forward the request to the IP Handler microservice
        $response = Http::baseUrl($ipHandlerBaseUrl)
            ->withHeaders($request->headers->all())
            ->{$request->method()}($ipHandlerEndpoint, $request->all());

        return $response;
    }
}
