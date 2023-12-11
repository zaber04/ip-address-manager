<?php

namespace App\Helpers;

class RouteHelpers
{
    public function getCustomRateLimitConfig($maxAttempts = 60, $decayMinutes = 1, $prefix = 'userLimit'): string
    {
        $throttleSetup = [
            env('RATE_LIMIT_MAX_ATTEMPTS', $maxAttempts),
            env('RATE_LIMIT_DECAY_MINUTES', $decayMinutes),
            env('RATE_LIMIT_PREFIX', $prefix),
        ];

        return ":" . implode(",", $throttleSetup);
    }
}
