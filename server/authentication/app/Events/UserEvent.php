<?php

declare(strict_types=1);

namespace Authentication\Events;

use Gateway\Enums\ActionEnum;
use Illuminate\Support\Carbon;

class UserEvent extends Event
{
    public string $userId;
    public string $sessionId;
    public string $ip;
    public int $timestamp;
    public string $eventType;

    public function __construct(string $userId, string $sessionId, ActionEnum $eventType, string $ip)
    {
        $this->userId    = $userId;
        $this->sessionId = $sessionId;
        $this->ip        = $ip;
        $this->eventType = $eventType;
        $this->timestamp = Carbon::now()->timestamp;
    }
}
