<?php

declare(strict_types=1);

namespace IpHandler\Listeners;

use Authentication\Events\UserLoggedIn;
use Gateway\Enums\ActionEnum;
use IpHandler\Models\AuditTrail;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;


class BeginAuditTrail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserLoggedIn $event)
    {
        // @TODO: keep this id in cache and use it for this session
        $sessionId = Str::uuid();

        // Create a new AuditTrail instance with user id and user ip
        $auditTrail = new AuditTrail([
            'action'        => ActionEnum::LOGIN,
            'property_name' => AuditTrail::PROPERTY_NAME,
            'old_data'      => '',
            'new_data'      => '',
            'user_id'       => $event->userId,
            'session_id'    => $sessionId
        ]);

        $auditTrail->save();
    }
}
