<?php

declare(strict_types = 1);

namespace Gateway\Http\Controllers;

use Gateway\Traits\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use Faker\Factory as FakerFactory;

class GatewayController extends BaseController
{
    use ApiResponse;

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
}
