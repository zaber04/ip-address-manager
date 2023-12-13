<?php

namespace Gateway\Http\Middleware;

use Illuminate\Http\Request;

class ProxyHeadersMiddleware
{
    public const X_FORWARDED_FOR = Request::HEADER_X_FORWARDED_FOR;
    public const X_FORWARDED_HOST = Request::HEADER_X_FORWARDED_HOST;
    public const X_FORWARDED_PORT = Request::HEADER_X_FORWARDED_PORT;
    public const X_FORWARDED_PROTO = Request::HEADER_X_FORWARDED_PROTO;
    public const X_FORWARDED_AWS_ELB = Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Get all proxy headers combined.
     *
     * @return int
     */
    public static function all()
    {
        return self::X_FORWARDED_FOR |
               self::X_FORWARDED_HOST |
               self::X_FORWARDED_PORT |
               self::X_FORWARDED_PROTO |
               self::X_FORWARDED_AWS_ELB;
    }
}
