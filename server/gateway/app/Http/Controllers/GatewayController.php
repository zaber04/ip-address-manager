<?php

declare(strict_types = 1);

namespace Gateway\Http\Controllers;

use Zaber04\LumenApiResources\Traits\ApiResponseTrait;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Routing\Controller as BaseController;


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
        $message     = "Welcome to 'GATEWAY' microservice. App version: " . $appVersion;

        $data = [
            'message' => $message,
            'success' => true,
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
        // $authServiceBaseUrl = config('services.auth.base_url'); // for local
        $authServiceBaseUrl = config('services.auth.base_url_name'); // for docker

        // request endpoint --> /api/auth/login remains /api/auth/login in Lumen (no prefix removal)
        $authServiceEndpoint = $request->path();
        $fullUrl = rtrim($authServiceBaseUrl, '/') . '/' . ltrim($authServiceEndpoint, '/');


        try {
            // Forward the request to the Authentication microservice
            // $request->method() --> get the HTTP method of the incoming request
            // $request->all()    --> get array of all input data
            $response = Http::baseUrl(rtrim($authServiceBaseUrl, '/'))
                ->withHeaders($request->headers->all())
                ->{$request->method()}(ltrim($authServiceEndpoint, '/'), $request->all());

            // $response = Http::withHeaders($request->headers->all())
                // ->{$request->method()}($fullUrl, $request->all());

            // Directly return the response and status code as is
            return response($response->json(), $response->status());
        } catch (\Exception $e) {
            return $this->jsonResponseWith(['error' => $e->getMessage(), 'message' => 'failed to forward request to authentication microservice', 'url' => $fullUrl, 'param' =>  $request->all()], 200);
        }
    }

    /**
     * Forward the request to the IP Handler microservice.
     *
     * @param Request $request
     * @return mixed
     */
    public function forwardToIpHandlerService(Request $request)
    {
        // $ipHandlerBaseUrl = config('services.ip_handler.base_url'); // local
        $ipHandlerBaseUrl = rtrim(config('services.ip_handler.base_url_name'), '/'); // docker
        $ipHandlerEndpoint = ltrim($request->path(), '/');
        $fullUrl = $ipHandlerBaseUrl . '/' . $ipHandlerEndpoint;

        try {
            $response = Http::baseUrl($ipHandlerBaseUrl)
                ->withHeaders($request->headers->all())
                ->{$request->method()}($ipHandlerEndpoint, $request->all());

            // Directly return the response and status code as is
            return response($response->json(), $response->status());
        } catch (\Exception $e) {
            return $this->jsonResponseWith(['error' => $e->getMessage(), 'message' => 'Failed to forward request to ip-handler microservice', 'url' => $fullUrl, 'param' =>  $request->all()], 200);
        }
    }
}
