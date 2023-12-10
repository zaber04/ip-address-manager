<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use App\Exceptions\CustomThrottleRequestsException;

use Closure;

use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Unlimited;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response;


class RateLimitMiddleware
{
    use ApiResponse;        // Maintain response format
    use InteractsWithTime;  // Time based algorithm -->  tocken bucket
    use Macroable;          // For extendibility

    protected RateLimiter $limiter;
    protected CustomThrottleRequestsException $throttleException;

    public function __construct(RateLimiter $limiter, CustomThrottleRequestsException $throttleException)
    {
        $this->limiter = $limiter;
        $this->throttleException = $throttleException;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        if (is_string($maxAttempts)
            && func_num_args() === 3
            && !is_null($limiter = $this->limiter->limiter($maxAttempts))
        ) {
            return $this->handleRequestUsingNamedLimiter($request, $next, $maxAttempts, $limiter);
        }

        return $this->handleRequest(
            $request,
            $next,
            [
                (object) [
                    'key' => $prefix . $this->resolveRequestSignature($request),
                    'maxAttempts' => $this->resolveMaxAttempts($request, $maxAttempts),
                    'decayMinutes' => $decayMinutes,
                    'responseCallback' => null,
                ],
            ]
        );
    }

    protected function handleRequestUsingNamedLimiter(Request $request, Closure $next, string $limiterName, Closure $limiter)
    {
        $limiterResponse = call_user_func($limiter, $request);

        if ($limiterResponse instanceof Response) {
            return $limiterResponse;
        } elseif ($limiterResponse instanceof Unlimited) {
            return $next($request);
        }

        return $this->handleRequest(
            $request,
            $next,
            collect(Arr::wrap($limiterResponse))->map(function ($limit) use ($limiterName) {
                return (object) [
                    'key'              => md5($limiterName . $limit->key),
                    'maxAttempts'      => $limit->maxAttempts,
                    'decayMinutes'     => $limit->decayMinutes,
                    'responseCallback' => $limit->responseCallback
                ];
            })->all()
        );
    }

    protected function handleRequest(Request $request, Closure $next, array $limits)
    {
        foreach ($limits as $limit) {
            if ($this->limiter->tooManyAttempts($limit->key, $limit->maxAttempts)) {
                return $this->buildException($request, $limit->key, $limit->maxAttempts, $limit->responseCallback);
            }

            $this->limiter->hit($limit->key, $limit->decayMinutes * 60);
        }

        $response = $next($request);

        foreach ($limits as $limit) {
            $response = $this->addHeaders(
                $response,
                $limit->maxAttempts,
                $this->calculateRemainingAttempts($limit->key, $limit->maxAttempts)
            );
        }

        return $response;
    }

    protected function resolveMaxAttempts(Request $request, $maxAttempts): int
    {
        if (Str::contains($maxAttempts, '|')) {
            $maxAttempts = explode('|', $maxAttempts, 2)[0];
        }

        return (int) $maxAttempts;
    }

    protected function resolveRequestSignature(Request $request): string
    {
        return sha1($request->ip());
    }

    protected function buildException(Request $request, string $key, int $maxAttempts, $responseCallback = null): ThrottleRequestsException
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        $exception = new CustomThrottleRequestsException(
            'Too Many Attempts. Please try again later.',
            $headers
        );

        throw new HttpResponseException($exception->getResponse());
    }
    

    protected function getTimeUntilNextRetry(string $key): int
    {
        return $this->limiter->availableIn($key);
    }

    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, ?int $retryAfter = null): Response
    {
        $response->headers->add(
            $this->getHeaders($maxAttempts, $remainingAttempts, $retryAfter, $response)
        );

        return $response;
    }

    protected function getHeaders(int $maxAttempts, int $remainingAttempts, ?int $retryAfter = null, ?Response $response = null): array
    {
        if ($response &&
            !is_null($response->headers->get('X-RateLimit-Remaining')) &&
            (int) $response->headers->get('X-RateLimit-Remaining') <= $remainingAttempts
        ) {
            return [];
        }

        $headers = [
            'X-RateLimit-Limit'     => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts
        ];

        if (!is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = $this->availableAt($retryAfter);
        }

        return $headers;
    }

    protected function calculateRemainingAttempts(string $key, int $maxAttempts, ?int $retryAfter = null): int
    {
        return is_null($retryAfter) ? $this->limiter->retriesLeft($key, $maxAttempts) : 0;
    }
}
