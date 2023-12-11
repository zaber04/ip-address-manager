<?php

declare(strict_types = 1);

namespace Gateway\Http\Middleware;

use Illuminate\Http\Request; // we will extend this later and use that
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware {
	public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $this->addCorsHeaders($response);

        return $response;
    }

    private function addCorsHeaders(Response $response): void
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
