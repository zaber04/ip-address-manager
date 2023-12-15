<?php

namespace Authentication\Http\Middleware;

use Gateway\Traits\ApiResponseTrait;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        try {
            // Check if the user is authenticated using JWT
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->jsonResponseWith(['error' => 'Unauthorized Request. JWT failure'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            return $next($request);
        } catch (\Exception $e) {
            return $this->jsonResponseWith(['error' => 'Exception. JWT failure', 'message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
