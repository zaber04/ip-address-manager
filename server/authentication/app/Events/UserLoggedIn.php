<?php

declare(strict_types=1);

namespace Authentication\Events;

// use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Carbon;

class UserLoggedIn extends Event
{
    // use Dispatchable, InteractsWithSockets;

    public string $userId;
    public string $sessionId;
    public string $ip;
    public int $loggedTimestamp;

    public function __construct(string $userId, string $sessionId, string $ip)
    {
        $this->userId          = $userId;
        $this->sessionId       = $sessionId;
        $this->ip              = $ip;
        $this->loggedTimestamp = Carbon::now()->timestamp;
    }
}
