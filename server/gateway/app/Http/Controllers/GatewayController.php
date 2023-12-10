<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;



class GatewayController extends BaseController
{
    use ApiResponse;

    public function index(Request $request)
    {
        $appVersion = app()->version();
        $serviceName = "API Gateway";
        $message = "Welcome to '" . $serviceName . "' microservice.<br>" . $appVersion;

        return response($message, 200);
    }
}
