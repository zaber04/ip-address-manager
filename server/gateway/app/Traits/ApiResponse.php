<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Respond with a JSON
     */
    public function jsonResponseWith(mixed $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Respond with an error JSON response.
     */
    public function errorResponse(string $errorMessage, int $statusCode): JsonResponse
    {
        return $this->jsonResponseWith(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }

    /**
     * Respond with a message JSON response.
     */
    public function messageResponse(string $message, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->jsonResponseWith(['message' => $message], $statusCode);
    }
}
