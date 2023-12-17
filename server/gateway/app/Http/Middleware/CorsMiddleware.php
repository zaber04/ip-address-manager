<?php

namespace Gateway\Http\Middleware;

use Illuminate\Http\Request; // we will extend this later and use that
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Intercept OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            // Pass the request to the next middleware
            $response = $next($request);
        }

        $this->addCorsHeaders($request, $response);

        return $response;
    }

    private function addCorsHeaders(Request $request, Response $response): void
    {
        $response->headers->set('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Expose-Headers', 'Location');
    }
}
