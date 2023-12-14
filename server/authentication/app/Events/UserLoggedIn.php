<?php

declare(strict_types=1);

namespace Authentication\Events;


use Illuminate\Support\Carbon;

class UserLoggedIn extends Event
{
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
