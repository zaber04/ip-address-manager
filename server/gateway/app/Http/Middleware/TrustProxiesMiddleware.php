<?php

namespace Gateway\Http\Middleware;

use Illuminate\Http\Request;


class TrustProxiesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // comma-separated list of IP addresses
        $trustedProxies = env('TRUSTED_PROXIES', '');

        // Set the trusted proxies on the request
        $request->setTrustedProxies(
            $this->parseTrustedProxies($trustedProxies),
            ProxyHeadersMiddleware::all()
        );

        return $next($request);
    }

    /**
     * Parse the comma-separated list of trusted proxies.
     *
     * @param  string  $proxies
     * @return array
     */
    protected function parseTrustedProxies($proxies)
    {
        return array_map('trim', explode(',', $proxies));
    }
}
