<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Laravel\Lumen\Routing\Controller as BaseController;


class GatewayController extends BaseController
{
    use ApiResponse;
}
