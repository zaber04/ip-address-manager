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
        // $baseUrl = config('services.auth.base_url'); // for local
        $baseUrl = rtrim(config('services.auth.base_url_name'), '/'); // for docker
        $endPoint = ltrim($request->path(), '/');
        $fullUrl =  $baseUrl . '/' . $endPoint;


        try {
            // Forward the request to the Authentication microservice
            // $request->method() --> get the HTTP method of the incoming request
            // $request->all()    --> get array of all input data
            $response = Http::baseUrl(rtrim($baseUrl, '/'))
                ->withHeaders($request->headers->all())
                ->{$request->method()}(ltrim($endPoint, '/'), $request->all());


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
        // $baseUrl = config('services.ip_handler.base_url'); // local
        $baseUrl = rtrim(config('services.ip_handler.base_url_name'), '/'); // for docker
        $endPoint = ltrim($request->path(), '/');
        $fullUrl =  $baseUrl . '/' . $endPoint;
        $headers = $request->headers->all();
        $requestMethod = strtolower($request->method());

        try {
            $response = Http::baseUrl($baseUrl)
                ->withHeaders($headers)
                ->{$requestMethod}($endPoint, $request->all());

            // Directly return the response and status code as is
            $info = [
                'G_method' => $requestMethod,
                'G_all' => $requestMethod,
                'G_url' => $fullUrl,
            ];
            return response($response->json(), $response->status(), $info);
        } catch (\Exception $e) {
            return $this->jsonResponseWith(['error' => $e->getMessage(), 'message' => 'Failed to forward request to ip-handler microservice', 'url' => $fullUrl, 'param' =>  $request->all()], 200);
        }
    }

    public function forwardToIpHandler(Request $request, string $requestMethod)
    {
        $baseUrl = rtrim(config('services.ip_handler.base_url'), '/'); // for local
        // $baseUrl = rtrim(config('services.ip_handler.base_url_name'), '/'); // for docker
        $endPoint = ltrim($request->path(), '/');
        $fullUrl =  $baseUrl . '/' . $endPoint;
        $headers = $request->headers->all();

        try {
            $response = Http::baseUrl($baseUrl)
                ->withHeaders($headers)
                ->{$requestMethod}($endPoint, $request->all());

            // Directly return the response and status code as is
            return response($response->json(), $response->status());
        } catch (\Exception $e) {
            return $this->jsonResponseWith(['error' => $e->getMessage(), 'message' => 'Failed to forward request to ip-handler microservice', 'url' => $fullUrl, 'param' =>  $request->all()], 200);
        }
    }
}
