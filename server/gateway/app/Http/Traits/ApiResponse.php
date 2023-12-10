<?php

declare(strict_types = 1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


trait ApiResponse
{
    /**
     * Respond with a success JSON response.
     *
     * @param mixed $data
     * @param int   $statusCode
     *
     * @return JsonResponse
     */
    public function successResponse($data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Respond with an error JSON response.
     *
     * @param string $errorMessage
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    public function errorResponse(string $errorMessage, int $statusCode): JsonResponse
    {
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }

    /**
     * Respond with an error message.
     *
     * @param string $errorMessage
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    public function errorMessage(string $errorMessage, int $statusCode): JsonResponse
    {
        return response()->json(['message' => $errorMessage], $statusCode);
    }
}
