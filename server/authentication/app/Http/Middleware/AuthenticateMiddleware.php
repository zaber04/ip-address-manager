<?php

declare(strict_types=1);

namespace Authentication\Http\Middleware;

use Gateway\Traits\ApiResponseTrait;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticateMiddleware
{
    use ApiResponseTrait;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        // Using the 'api' guard from 'config/auth.php' for authentication
        if ($this->auth->guard($guard)->guest()) {
            return $this->jsonResponseWith(['error' => 'Unauthorized Request'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
