<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\ApiResponse;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CustomThrottleRequestsException extends HttpResponseException
{
    use ApiResponse;

    protected int $statusCode;
    protected string $hostIp;

    public function __construct($message = null, $headers = [], $statusCode = JsonResponse::HTTP_TOO_MANY_REQUESTS)
    {
        parent::__construct($this->jsonResponseWith(['error' => $message, 'error_code' => $statusCode, 'host_ip' => request()->ip()], $statusCode, $headers));
        $this->statusCode = $statusCode;
    }


    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
